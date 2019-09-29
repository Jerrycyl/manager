<?php
namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use cylcode\tools\arr\Arr;
/**
 * 数据表结构管理
 */
class Fastsql extends Base
{
  protected $table = 'manager_fastsql';
  private $data_func = [
    'htmlspecialchars'=>'htmlspecialchars',
    'encode'=>'encode',
    'urlencode'=>'urlencode',
    'htmlentities'=>'htmlentities',
    'inter'=>'inter',
    'abs'=>'abs',
    'htmlspecialchars_decode'=>'htmlspecialchars_decode:反解',
    'decode'=>'decode:解密',
    'floor'=>'floor:向下舍入为最接近的整数',
    'round'=>'round:四舍五入',
    'ceil'=>'ceil:向上舍入为最接近的整数',
    'urldecode'=>'urldecode',

  ];
  public function index()
  {
      $tableInfo = self::getTables();
      $tables = [];
      ##处理排序
      foreach ($tableInfo as $v) {
        $showFields = DB::table($this->table)->where(['table'=>$v['Name'],'is_show_list'=>1])->get();
          if($showFields->count()){
             $v['message']  = "<a href='".route('fastpost.message',['table'=>encode($v['Name'])])."' class='site-iframe-active' data-type='tabAdd' >管理数据</a>";
            array_unshift($tables, $v);
          }else{
            $v['message']  = "<span></span>";
            $tables[]  = $v;
          }
         
      }
      return Bear::make('table')
            ->setData($tables)
            ->addColumn('Name','表名')
            ->addColumn('Engine','引擎')
            ->addColumn('Data_length','长度')
            ->addColumn('Create_time','创建时间')
            ->addColumn('Collation','编码')
            ->addColumn('Comment','备注')
            ->addColumn('Rows','条数')
      		  ->addColumn('message','数据管理')
            ->addRightBtn('editField','编辑字段',['class'=>'btn-xs','target'=>'_blank','href'=>route('fastsql.edit',['table'=>'']).'/{Name}','icon'=>'fa fa-edit'])
            ->addRightBtn('editBtn','编辑按钮',['class'=>'btn-xs','target'=>'_blank','href'=>route('fastsql.btn',['table'=>'']).'/{Name}','icon'=>'fa fa-edit'])
            ->fetch();

  }
  /**
   * 编辑表的字段信息
   */
  public function editField(Request $request){
    $table = $request->route('table');
    $field = $request->route('field');
    $fieldInfo = [];
    ##查询字段信息
    $columns =  self::getFields($table);
    $leftMenu = [];
    $fields = [];
    foreach ($columns as &$item) {
      $fields[] = $item['Field'];
        if($field==$item['Field']) $fieldInfo = $item;
        $item['title']    = $item['Field'];
        $item['url']      = route('fastsql.editDetail',['table'=>$table,'field'=>$item['Field']]);
        $item['current']  = $item['Field'];
         ##当前字段是否在列表显示，是否可编辑
        $itemInfo = (array)DB::table($this->table)->where(['table'=>$table,'field'=>$item['Field']])->select('is_show_list','alias_name','is_write')->first();
        if($itemInfo){
          $item['title'] = $itemInfo['alias_name']?$item['title'].':'.$itemInfo['alias_name']:$item['title'];
          $item['title'] = $itemInfo['is_show_list']?$item['title'].'<i style="padding-right:20px;display:inline-block;float:right" class="fa fa-eye"></i>':$item['title'];
          $item['title'] = $itemInfo['is_write']?$item['title'].'<i style="padding-right:5px;display:inline-block;float:right;margin-right:0" class="fa fa-edit"></i> ':$item['title'];
        }
        ##调整排序插入可写，和列表显示的排前面
         if($itemInfo['is_write']||$itemInfo['is_show_list']){
          array_unshift($leftMenu, $item);
         }else{
          $leftMenu[]       = $item;
         }
        
      }
    $leftMenus['current'] = $field;
    $leftMenus['lists']   = $leftMenu;

    if(!$field) return redirect(route('fastsql.editDetail',['table'=>$table,'field'=>$columns[0]['Field']])) ;
    if(isPost()){
      $_POST['do_table'] = $this->table;
      $_POST['where'] = ['table'=>$_POST['table'],'field'=>$_POST['field']];
      ##清掉不存在字段的相关信息，避免脏数据
      $re = DB::table($this->table)->where(['table'=>$table])->whereNotIn('field',$fields)->delete();
      $this->save($_POST);
      return returnJson(0,'操作成功',['href'=>'']);
    }
    $fastsqlData = DB::table($this->table)->where(['table'=>$table,'field'=>$field])->first();
    if($fastsqlData) extract((array)$fastsqlData);
   return Bear::make('form')
        ->setTitle('快速表数据管理')
        ->setLeftMenu($leftMenus)
        // ->addStatic('<div class="ibox float-e-margins"> <div class="ibox-title"><h5>基本数据</h5> </div> </div>')
        ->addText('table','表名',$table,['readonly'=>'readonly'])
        ->addText('field','字段',$fieldInfo['Field'],['readonly'=>'readonly'])
        ->addText('alias_name','别名',$alias_name)
        

        ->addRadio('is_show_list','是否列表显示',['否','是'],(int)$is_show_list)
        ->addText('sort_list','列表显示排序',(int)$sort_list)
        ->addText('prepare_list','预处理',$prepare_list,['tips'=>'支持函数处理 eg: sql|select key,value from table (我们直接取第一个为KEY，每二个为VALUE) 当然也可以自定义函数回调如 selffunc|args <a target="_blank" href="http://doc.jerryblog.cn/docs/show/522">点击查看</a>'])

        ->addRadio('is_write','是否可编辑',['否','是'],(int)$is_write)
        ->addSelect('mold','类型',Bear::make('form')->getFormType(),$mold)
        ->addText('options','参数',$options,['tips'=>'支持函数处理 eg: sql|select key,value from table (我们直接取第一个为KEY，每二个为VALUE) 当然也可以自定义函数回调如 selffunc|args <a target="_blank" href="http://doc.jerryblog.cn/docs/show/522">点击查看</a>'])
        ->addText('sort_edit','编辑显示排序',(int)$sort_edit)
        ->addText('default_data','默认数据',$default_data,['tips'=>'支持函数处理 eg: sql|select key,value from table (我们直接取第一个为KEY，每二个为VALUE) 当然也可以自定义函数回调如 selffunc|args'])
        ->addText('role','验证规则',$role,['tips'=>'查看支持的规则列表<a target="_blank" href="http://doc.jerryblog.cn/docs/show/504">点击查看</a>多个用|分隔'])
        ->addText('insert_before','数据插入',$insert_before,['tips'=>'数据入库前的处理，支持,encode<a target="_blank" href="http://doc.jerryblog.cn/docs/show/505">点击查看</a>多个用,号隔开,默认会用htmlspecilchar,htmlentities先数据进行处理'])
        ->addText('select_after','数据查找',$select_after,['tips'=>'数据查出后的函数处理，支持htmlspecilchar_decode,decode<a target="_blank" href="http://doc.jerryblog.cn/docs/show/506">点击查看</a>多个用,号隔开'])

        ->addRadio('is_search','是否可搜索',['否','是'],(int)$is_search)

        ->addTextarea('remark','备注',$remark)
        ->setNav('基本设置',['table','field','alias_name',])
        ->setNav('列表设置',['is_show_list','sort_list','prepare_list'])
        ->setNav('编辑设置',['mold','is_write','sort_edit','role','insert_before','select_after','remark','default_data','options'])
        ->setNav('其它设置',['is_search'])

        ->setTrigger('mold','radio,checkbox,switch,select,select','options')##radio都需要参数，所有选中显示参数

        ->fetch();
  }


}
