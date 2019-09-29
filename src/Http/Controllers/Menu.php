<?php

namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
// use cylcode\manager\Models\Menu;
use Illuminate\Support\Facades\Cookie;
use cylcode\manager\Models\Task as t;
use Illuminate\Support\Facades\Auth as a;
use cylcode\tools\arr\Arr as arr;
##菜单
class Menu extends Base
{
    protected $table    = 'admin_menu';

    // private   $password = 'a123456!@#$%^';

    public function index(Request $request)
    {

      $data = arr::objToArray(Db::table($this->table)->orderBy('order','desc')->get());
      $data = arr::tree($data,'html','id','parent_id');
      // dump($data);

      return Bear::Table()
              ->addColumn('id','id')
              ->addColumn('title','标题',function($data){
                $str = '';
                if($data['parent_id']==0) $str.='<i class="fa '.$data['icon'].'" style="margin-right:10px;"></i>';
                return $str.$data['_html'].$data['title'];
              })
              // ->addColumn('method','类型')
              ->addColumn('uri','uri')
              // ->addColumn('route','路由地址',function($data){
                // return "<a class='btn btn-default btn-xs' style='margin-right:10px;'>{$data['method']}</a>".$data['route'];
              // })
              ->setData($data)
              ->addTopBtn('edit','创建菜单',['href'=>route('manager.menu.edit'),'class'=>'btn-xls'])
              ->addRightBtn('edit','编辑菜单',['href'=>route('manager.menu.edit').'?id={id}','class'=>'btn-xs'])
              ->fetch();
     
     
    }
    /**
     * [edit 新建]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-06-19T15:43:23+0800
     * @Example  eg:
     */
    public function edit(Request $request){
      if(isPost()){
        $post = $request->all();
        $post['do_table'] = $this->table;
        $post['where'] = ['id'=>$post['id']];
        if(false!==$this->save($post)){
          return returnJson(0,'添加成功',['href'=>route('manager.menu.index')]);
        }else{
          return returnJson(-1,'添加失败');
        }
       
      }
      $id = (int)$request->input('id');
      if($id){
         extract((array)DB::table($this->table)->where(['id'=>$id])->first());
      }
      $data = arr::objToArray(Db::table($this->table)->select('id','title','parent_id')->orderBy('order','desc')->get());
      $data = arr::tree($data,'html','id','parent_id');
      array_unshift($data,['id'=>0,'title'=>'顶级']);
      foreach ($data as &$v) {
        $v['title'] = $v['_html'].$v['title'];
      }
      // dump($data);
      return Bear::form()
            // ->addAlert('','路由设置不能少于3级','danger')
            ->addText('title','标题',$title)
            ->addSelect('parent_id','上级',arr::forMat($data,'id','title'),$parent_id)
            ->addRadio('is_show','是否显示',['不显示','显示'],$is_show)
            ->addIcon('icon','icon',$icon)
            ->addText('uri','uri',$uri)
            // ->addSelect('method','类型',['PUT'=>'PUT','GET'=>'GET','POST'=>'POST','DELETE'=>'DELETE','ANY'=>'ANY'],isset($method)?$method:'ANY')
            // ->addText('route','路由地址',$route,['tips'=>'eg:/manager/article/*,  /manager/article/s'])
            ->addHidden('id',$id)
            ->fetch(); 


    }
}
