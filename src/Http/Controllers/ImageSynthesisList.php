<?php
namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use cylcode\tools\arr\Arr;
/**
 * 图片合成参数
 */
class ImageSynthesisList extends Base
{
  protected $table = 'img_synthesis_list';
  public function index(Request $request)
  {
    $synth_id = (int)$request->input('synth_id');
    return Bear::table()
            ->addColumn('title','标题')
            
            ->addColumn('type','类型',function($data){
              return $data['type']==1?'文字':'图片';
            })
            ->addColumn('value','值',function($data){
              return $data['type']==1?$data['value_text']:$data['value_img'];
            })
            ->addColumn('x','x轴')
            ->addColumn('y','y轴')
            ->addTopBtn('edit','添加',['href'=>route('manager.ImageSynthesisList.edit').'?synth_id='.$synth_id])
            ->addRightBtn('edit','编辑',['class'=>' btn-xs','href'=>route('manager.ImageSynthesisList.edit').'?id={id}&synth_id='.$synth_id])
            // ->addRightBtn('lists','列表参数',['class'=>' btn-xs ','icon'=>'fa fa-gear','href'=>route('manager.ImageSynthesis.edit').'?id={id}'])
            ->setData(Arr::objToArray(DB::table($this->table)->orderBy('sort','desc')->get()))
            ->addColumn('remark','备注信息')
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
      // if(!$post['soure_img']) returnJson(-1,'图片源不能为空');
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
          ->addRadio('type','类型',['1'=>'文字','2'=>'图片'],(int)$type==2?2:1)
          ->addText('value_text','值',$value_text)
          ->addText('value_img','值',$value_img)
          ->addText('x','x轴',$x)
          ->addText('y','y轴',$y)
          ->addText('font_size','字体大小',$font_size)
          ->addText('sort','排序',$sort)
          ->setTrigger('type','1','value_text')
          ->setTrigger('type','2','value_img')
          ->addTextarea('remark','备注信息',$remark)
          ->fetch();
  }
 

}
