<?php
namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use cylcode\tools\arr\Arr;
use cylcode\manager\Http\data\Data;
/**
 * 图片合成
 */
class ImageSynthesis extends Base
{
	protected $table = 'img_synthesis';
  public function index()
  {
    return Bear::table()
            ->addColumn('title','标题')
            ->addColumn('soure_img','合成源图','attachment')
            ->addColumn('remark','备注信息')
            ->addColumn('create_time','生成时间','dateTime')
            ->addTopBtn('edit','添加',['href'=>route('manager.ImageSynthesis.edit')])
            ->addRightBtn('edit','编辑',['class'=>' btn-xs','href'=>route('manager.ImageSynthesis.edit').'?id={id}'])
            ->addRightBtn('lists','列表参数',['class'=>' btn-xs ','icon'=>'fa fa-gear','href'=>route('manager.ImageSynthesisList.index').'?synth_id={id}'])
            ->addRightBtn('debug','调试',['class'=>' btn-xs ','icon'=>'fa fa-wrench','href'=>"alert('test')"])
            ->setData(Arr::objToArray(DB::table($this->table)->get()))
            ->fetch();
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
      if(!$post['title']) return returnJson(-1,'标题不能为空');
      if(!$post['soure_img']) return returnJson(-1,'图片源不能为空');
      $post['do_table']   = $this->table;
      $post['where']   = ['id'=>$post['id']];
      if($this->save($post)){
        return returnJson(0,'操作成功',['gourl'=>'/tools/task/index']);
      }else{
        return returnJson(-1,'操作失败');
      }
    }
    $id = (int)$request->input('id');
    if($id) $data = DB::table($this->table)->where(['id'=>$id])->first();
    if($data) $data = Arr::objToArray($data);
    if($data) extract($data);
    return  Bear::make('form')
          ->addHidden('id',(int)$id)
          ->addText('title','标题',$title)
          ->addText('soure_img','合成源图',$soure_img)
          ->addTextarea('remark','备注信息',$remark)
          ->addRadio('create_do_time','是否标记生成时间',['否','是'],(int)$create_do_time)
          ->fetch();
  }

   

}
