<?php

namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use cylcode\manager\Models\Menu;
use Illuminate\Support\Facades\Cookie;
use cylcode\manager\Models\Task as t;
use Illuminate\Support\Facades\Auth as a;
use cylcode\tools\arr\Arr as arr;
##用户授权
class UserRole extends Base
{

     protected $table    = 'admin_menu';
    /**
     * [auth 开始授权]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-06-20T08:41:49+0800
     * @Example  eg:
     * @param    Request                  $request   [description]
     * @return   [type]                              [description]
     */
    public function auth(Request $request)
    {
        $role_id = (int)$request->input('role_id');
        if(!$role_id) return Bear::layout()->error('请选择需要授权的角色');
        if(isPost()){
          $ids = $request->input('ids');
          if(!$ids) return returnJson(-1,'请选择需要授权的菜单');
          ##清掉之前的数据
          DB::table('admin_role_menu')->where(['role_id'=>$role_id])->delete();
          foreach ($ids as $v) {
            DB::table('admin_role_menu')->insert(['role_id'=>$role_id,'menu_id'=>$v]);
          }
          return returnJson(0,'授权成功');
        }
        ##当前组的授权
        $role_Menu = arr::objToArray(Db::table('admin_role_menu')->select('menu_id')->where(['role_id'=>$role_id])->get());
        $roleIds = [];
        if($role_Menu){
          foreach ($role_Menu as $v) {
            $roleIds[] = $v['menu_id'];
          }

        }
        $data = arr::objToArray(Db::table($this->table)->select('id','parent_id as pId','title as name','is_show')->orderBy('order','desc')->get());
         foreach ($data as &$v) {
           $v['open'] = true;
           $v['checked'] = in_array($v['id'], $roleIds)?true:false;
           $v['name'] = $v['is_show']<1?'<i class="" aria-hidden="true"></i>-- '.$v['name']:$v['name'];
         }
      return Bear::make('layout')->extend(__DIR__.'/../../Views/role/auth',['data'=>$data,'json'=>json_encode($data),'role_id'=>$role_id]);
      // return Bear::Table()
      //         ->addColumn('id','id')
      //         ->addColumn('username','用户名')
      //         ->addColumn('avatar','图相','img')
      //         ->addColumn('created_at','创建时间')
      //         ->setData(arr::objToArray(Db::table($this->table)->get()))
      //         ->addTopBtn('create','创建用户',['href'=>route('manager.user.create'),'class'=>'btn-xls'])
      //         ->fetch();
     
     
    }

}
