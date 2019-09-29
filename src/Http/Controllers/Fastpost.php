<?php
namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use cylcode\tools\arr\Arr;
use cylcode\tools\str\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
/**
 * 数据管理
 */
class Fastpost extends Base
{
  // public function 
  /**
   * [message 数据管理]
   * @Author   Jerry                    (wx621201)
   * @DateTime 2019-05-15T18:01:35+0800
   * @Example  eg:
   * @return   [type]                   [description]
   */
  public function message(Request $request)
  {
     $table = decode($request->route('table'));
     ##要显示的字段
     $showFields = DB::table('manager_fastsql')->select('alias_name','field','prepare_list')->where(['table'=>$table,'is_show_list'=>1])->orderBy('sort_list','desc')->get();
     if(!$showFields->count()) return Bear::make('layout')->error('没有要显示的字段，请先配置需要显示的字段信息');
     $showFields = Arr::objToArray($showFields);
     $tableHtml = Bear::make('table');
     
     ##需要显示的字段
     foreach ($showFields as $v) {
        ##数据有预处理，优先预处理
        if($v['prepare_list']){
          $tableHtml = $tableHtml->addColumn($v['field'],$v['alias_name'],$v['prepare_list']); 
        }else{
           $v['alias_name'] = $v['alias_name']?$v['alias_name']:$v['field'];
          $tableHtml = $tableHtml->addColumn($v['field'],$v['alias_name']); ##默认显示的类型
        }
        
     }
     $tableHtml = $tableHtml;
     
     $data = $this->paginate($table);
     
     
  
      $tableHtml =  $tableHtml->setData($data['data'])
                      ->addRightBtn('editField','编辑数据',['class'=>'btn-xs','href'=>route('fastpost.edit',['table'=>encode($table),'id'=>'']).'/{id}','icon'=>'fa fa-edit'])
                      ->addTopBtn('delete','删除数据',
                        [
                        'class'=>' btn-danger',
                        'href'=>route('fastpost.delete',['table'=>encode($table)]),
                        'icon'=>'fa fa-trash-o',
                        'submit'=>true,
                        'submit-msg'=>'确认删除数据吗?',
                        'form'=>'bear-table-from'
                        ])
                      ->addTopBtn('create','新建数据',['class'=>'','href'=>route('fastpost.add',['table'=>encode($table)]),'icon'=>''])
                      ->setPages($data['total'],$data['per_page'])
                      ->setSearch($this->_getSearch($table))
                      ->setPk('pk',$pk = $this->getPk($table));
     ##自定义按钮处理
     $btns = Arr::objToArray(DB::table('manager_fastsql_btn')->where(['table_name'=>$table])->orderBy('sort','desc')->orderBy('id','desc')->get());
     if(is_array($btns)&&$btns){
      foreach ($btns as $v) {
        $pinyin = Str::pinyin($v['alias_name']);
        $param = [
            'class'   => $pinyin . ($v['postion']=='right'?' btn-xs ':' ') . $v['class'],
            'href'    => $v['action'],
            'icon'    => "fa ".$v['icon']
        ];
        if($v['method']=='post'){
          $param['submit']      = true;
          $param['submit-msg']  = $v['exception']?$v['exception']:'您确定此操作吗?';
          $param['submit-method'] = $v['method'];
        }
        if($v['postion']=='top'){
          $tableHtml = $tableHtml->addTopBtn($pinyin.$v['id'],$v['alias_name'],$param);
        }else{
          $tableHtml = $tableHtml->addRightBtn($pinyin.$v['id'],$v['alias_name'],$param);
        }
      }
     }


      return $tableHtml->fetch();
    
            // ->setData($tables)
    

  }
  public function delete(Request $request){

    $table = htmlspecialchars(decode($request->route('table')));
    $ids = $request->input('id');
    if(!is_array($ids)) return returnJson(-1,'数据异常');
    // $editInfo = $this->_getEditInfo($table);
     ##主键
     $pk = $this->getPk($table);
     if(DB::table($table)->whereIn($pk,$ids)->delete()){
      return returnJson(0,'删除成功',['href'=>route('fastpost.message',['table'=>encode($table)])]);
     }else{
      return returnJson(-1,'删除失败');
     }

  }
  /**
   * [edit description]
   * @Author   Jerry                    (wx621201)
   * @DateTime 2019-05-29T10:59:50+0800
   * @Example  eg:
   * @param    Request                  $request   [description]
   * @return   [type]                              [description]
   */
  public function edit(Request $request){
    ##可编辑的字段
    $table = htmlspecialchars(decode($request->route('table')));
    $id = (int)$request->route('id');
    $editInfo = $this->_getEditInfo($table);
     ##主键
     $pk = $this->getPk($table);
    if(isPost()){
      $post = $_POST;
      $post['where'] = [$pk=>$id];
      $post['do_table'] = $table;
       // throw new \Exception("Error Processing Request", 1);
      if(false==$this->save($post)){

        
        return returnJson(-1,'操作失败');
      }else{
        return returnJson(0,'操作成功');
      }
    }
    if($id){
      $infos = (array)DB::table($table)->where([$pk=>$id])->first();
      if($infos) extract($infos);
    }
    // dump($editInfo);
    // die;
    ##生成表单
    $forms = [];
    foreach ($editInfo as $key => $v) {
      $thumbField = $v['field'];
        $isRequire = strstr($v['role'], 'required');
        ##参数处理
        $forms[] = [
          'type'    => $v['mold'],
          'field'   => $v['field'],
          'title'   => $v['alias_name'].($isRequire?"<span style='color:red'>(必填)</span>":''),
          'default' => $this->csrfStrDecode($$thumbField),
          'options' => $this->_formatOptions($v['options']),
          // 'field'   => $v['field'],
        ];
    }
   return Bear::make('form')
      ->addHidden($pk,$id)
      ->Forms($forms)->Fetch();

  }
  /**
   * [_formatOptions 参数处理]
   * @Author   Jerry                    (c84133883)
   * @DateTime 2019-08-08T17:42:26+0800
   * @Example  eg:
   * @param    [type]                   $options    [description]
   * @return   [type]                               [description]
   */
  private function _formatOptions($options){
    $optionsArr = explode("|", $options);
    if(function_exists($optionsArr[0])){

      return call_user_func($optionsArr[0], $optionsArr[1]);

    }
    return $options;

  }
  /**
   * [_getEditInfo 可编辑字段相关信息]
   * @Author   Jerry                    (wx621201)
   * @DateTime 2019-05-29T11:07:15+0800
   * @Example  eg:
   * @param    [type]                   $table     [description]
   * @return   [type]                              [description]
   */
  private function _getEditInfo($table){
    $info = DB::table('manager_fastsql')->where(['is_write'=>1,'table'=>$table])->select('alias_name','field','role','mold','default_data','options')->orderBy('sort_edit','desc')->get();
    $info = Arr::objToArray($info);
    $returnInfo = [];
    foreach ($info as  $v) {
      $v['alias_name'] = $v['alias_name']?$v['alias_name']:$v['field'];
      $returnInfo[$v['field']] = $v;
    }
    return $returnInfo;
    
  }
  /**
   * [_getSearch 列表搜索字段]
   * @Author   Jerry                    (wx621201)
   * @DateTime 2019-05-29T10:52:02+0800
   * @Example  eg:
   * @param    [type]                   $table     [description]
   * @return   [type]                              [description]
   */
  protected function _getSearch($table){
    $search = DB::table('manager_fastsql')->select('field','alias_name')->where(['is_search'=>1])->get();
    $search = Arr::objToArray($search);
    if($search){
      $searField = [];
      foreach ($search as $key => $v) {
          $searField[$v['field']] = $v['alias_name']?$v['alias_name']:$v['field'];
      }
      // dump($searField);
      return $searField;
    }else{
      return [];
    }

  }
  /**
   * [lists 列表]
   * @Author   Jerry                    (wx621201)
   * @DateTime 2019-05-27T10:10:16+0800
   * @Example  eg:
   * @return   [type]                   [description]
   */
  public function lists(){

  }
  

}
