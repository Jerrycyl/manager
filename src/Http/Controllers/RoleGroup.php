<?php

namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use cylcode\manager\Models\Menu;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth as a;
use cylcode\tools\arr\Arr as arr;
##用户组
class RoleGroup extends Base
{

     protected $table    = 'admin_roles';
    /**
     * [auth lists]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-06-20T08:41:49+0800
     * @Example  eg:
     * @param    Request                  $request   [description]
     * @return   [type]                              [description]
     */
    public function lists(Request $request)
    {
      
      return Bear::Table()
              ->addColumn('id','id')
              ->addColumn('name','名称')
              // ->addColumn('avatar','图相','img')
              ->addColumn('created_at','创建时间')
              ->setData(arr::objToArray(Db::table($this->table)->get()))
              ->addTopBtn('create','创建用户组',['href'=>route('manager.roleGroup.edit'),'class'=>'btn-xls'])
              ->addRightBtn('edit','编辑',['href'=>route('manager.roleGroup.edit',['id'=>'']).'{id}','class'=>'btn-xs ','icon'=>'fa fa-edit'])
              ->addRightBtn('auth','组授权',['href'=>route('manager.userRole.auth',['role_id'=>'']).'{id}','class'=>'btn-xs frAlert','icon'=>'fa fa-key'])
              ->fetch();
     
     
    }
    public function edit(Request $request){
        if(isPost()){
          $post = $request->all();
          $post['do_table']   = $this->table;
          $post['where']   = ['id'=>$post['id']];
          if($this->save($post)){
            return returnJson(0,'操作成功');
          }else{
            return returnJson(-1,'操作失败');
          }
        }
        $id = (int)$request->input('id');
        if($id) $data = arr::objToArray(DB::table($this->table)->where(['id'=>$id])->first());
        if($data) extract($data);
         return Bear::form()
              ->addText('name','组名',$name)
              ->addHidden('id',$id)
              // ->addSelect('role_id','用户组',Arr::format(Arr::objToArray(DB::table('admin_roles')->select('id','name')->get()),'id','name'))
              ->fetch();
      
    }

}
