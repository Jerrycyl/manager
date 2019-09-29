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
class FastsqlBtn extends Base
{
  protected $table = 'manager_fastsql_btn';
  public function index(Request $request)
  {
    $table = $request->route('table');
      return Bear::make('table')
        ->setData(Arr::objToArray(DB::table($this->table)->where(['table_name'=>$table])->orderBy('sort','desc')->orderBy('id','desc')->get()))
        ->addColumn('alias_name','按钮名称')
        ->addColumn('status','状态')
        ->addColumn('postion','按钮位置')
        ->addTopBtn('delete','删除数据',['class'=>' btn-danger','href'=>route('fastsql.btndelete'),'icon'=>'fa fa-trash-o','submit'=>true,'submit-msg'=>'确认删除数据吗?','form'=>'bear-table-from'])
        ->addRightBtn('edit','修改数据',['class'=>'btn-xs','href'=>route('fastsql.btnedit',['table'=>$table,'id'=>'']).'/{id}','icon'=>'fa fa-edit'])
        ->addTopBtn('add','添加数据',['class'=>'','href'=>route('fastsql.btnadd',['table'=>$table,'id'=>'']),'icon'=>'fa fa-edit'])
        ->fetch();

  }
    /**
     * [delete description]
     * @Author   Jerry                    (c84133883)
     * @DateTime 2019-08-28T11:02:29+0800
     * @Example  eg:
     * @param    Request                  $request    [description]
     * @return   [type]                               [description]
     */
  public function delete(Request $request){
    $ids = $request->input('id');
    if(!is_array($ids)) return returnJson(-1,'数据异常');
     if(DB::table($this->table)->whereIn('id',$ids)->delete()){
      return returnJson(0,'删除成功',['href'=>'']);
     }else{
      return returnJson(-1,'删除失败');
     }

  }

  /**
   * [edit description]
   * @Author   Jerry                    (c84133883)
   * @DateTime 2019-08-27T17:57:56+0800
   * @Example  eg:
   * @return   [type]                   [description]
   */
  public function edit(Request $request){
    $id = $request->route('id');
    $table = $request->route('table');
    if($id){
      extract(Arr::objToArray(DB::table($this->table)->where(['id'=>$id])->first()));
    }
    if(isPost()){
      $post = $_POST;
      $post['do_table']   = $this->table;
      $post['where']   = ['id'=>$post['id']];

      if($this->save($post)){
        return returnJson(0,'操作成功',['gourl'=>route('fastsql.btn',['table'=>$table])]);
      }else{
        return returnJson(-1,'操作失败');
      }
    }
    
    return Bear::make('form')
        ->addText('alias_name','按钮名称',$alias_name)
        ->addText('action','请求地址(URL)',$action)
        ->addRadio('status','状态',['无效','有效'],(int)$status)
        ->addSelect('postion','位置',['top'=>'顶部','right'=>'右侧'],$postion)
        ->addSelect('method','提交方式',['get'=>'get','post'=>'post'],$method)
        ->addText('exception','抛出警语',$exception,['tips'=>'弹窗询问'])
        ->setTrigger('method','post','exception')
        
        ->addText('sort','排序',$sort)
        ->addIcon('icon','icon图标',$icon)
        ->addText('class','class样式',$class)
        ->addHidden('table_name',$table)
        ->addTextarea('remark','备注',$remark)
        ->addHidden('id',$id)
        ->fetch();
  }

}
