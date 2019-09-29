<?php
namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use cylcode\manager\Models\Task as t;
/**
 * 任务管理
 */
class Task extends Base
{
  protected $table = 'manager_task';
  public function index()
  {
    $data = t::get()->toArray();
    return Bear::make('layout')->extend(__DIR__.'/../../Views/task/index',['data'=>$data]);
  }
  /**
   * [edit 编辑]
   * @Author   Jerry                    (wx621201)
   * @DateTime 2019-05-16T15:01:48+0800
   * @Example  eg:
   * @return   [type]                   [description]
   */
  public function edit(Request $request){
  	if(isPost()){
  		$post = $_POST;
  		$post['do_table']   = $this->table;
  		$post['where']	 = ['id'=>$post['id']];
  		if($this->save($post)){
  			return returnJson(0,'操作成功',['gourl'=>'/tools/task/index']);
  		}else{
  			return returnJson(-1,'操作失败');
  		}
  	}
  	$id = (int)$request->route('id');
  	if($id) $data = t::where(['id'=>$id])->first()->toArray();
  	if($data) extract($data);
  	return 	Bear::make('form')
  				->addHidden('id',(int)$id)
  				->addText('name','任务名',$name)
  				->addRadio('status','状态',['未开始','进行中','已完成'],(int)$status)
  				->addText('progress','进度',$progress)
  				->fetch();
  }

}
