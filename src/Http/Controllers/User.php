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
use cylcode\manager\Http\data\Data;
class User extends Base
{
    protected $table    = 'admin_users';

    private   $password = 'a123456!@#$%^';

    public function index(Request $request)
    {
      return Bear::Table()
              ->addColumn('id','id')
              ->addColumn('username','用户名')
              ->addColumn('usergroup','用户组',function($data){
               return DB::table('admin_roles')->where(['id'=>$data['role_id']])->value('name');
              })
              ->addColumn('avatar','图相','img')
              ->addColumn('created_at','创建时间')
              ->setData(arr::objToArray(Db::table($this->table)->get()))
              ->addTopBtn('create','创建用户',['href'=>route('manager.user.create'),'class'=>'btn-xls'])
              ->addRightBtn('changepwd','修改密码',['class'=>'btn-xs','href'=> route('manager.user.changepwd',['id'=>'']).'{id}'])
              ->addRightBtn('edit','修改',['class'=>'btn-xs','href'=> route('manager.user.create',['id'=>'']).'{id}'])
              ->fetch();
     
     
    }
    /**
     * [changepwd description]
     * @Author   Jerry
     * @DateTime 2019-07-03T21:56:12+0800
     * @Example  eg:
     * @return   [type]                   [description]
     */
    public function changepwd(Request $request){
      $id = (int)$request->input('id');
      if(isPost()){
        $password = trim(htmlspecialchars($request->input('password')));
        if(mb_strlen($password)<8){
          return returnJson(-1,'密码不能小于8位');
        }
        // dump($password);
        // dump($id);
        if(false!==DB::table($this->table)->where(['id'=>$id])->update(['password'=>bcrypt($password)])){
          return returnJson(0,'修改成功');
        }else{
          return returnJson(-1,'修改失败');
        }
      }
      return Bear::form()
              ->addPassword('password','密码')
              // ->addHide('id',$id)
              ->fetch();

    }
    /**
     * [create 新建]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-06-19T15:43:23+0800
     * @Example  eg:
     */
    public function create(Request $request){
      if(isPost()){
        $post = $request->all();
        if(!$post['username']) return returnJson(-1,'登陆名不能为空');
        if(!$post['role_id']) return returnJson(-1,'需要选择用户组');
        if($post['id']){
          ##修改用户
          $data =  [
                'username'  => $post['username'],
                // 'password'  => bcrypt($this->password),
                'email'     => $post['email'],
                'name'      => $post['username'],
                'created_at'=> date('Y-m-d H:i:s'),
                'role_id'   => $post['role_id'],
                'id'        => $post['id'],
                'where'     =>['id'=>$post['id']]
              ];
        }else{
          ##创建用户
           if(DB::table($this->table)->where(['username'=>$post['username']])->count()) returnJson(-1,'用户已存在');
           $data = [
                'username'  => $post['username'],
                'password'  => bcrypt($this->password),
                'email'     => $post['email'],
                'name'      => $post['username'],
                'created_at'=> date('Y-m-d H:i:s'),
                'role_id'   => $post['role_id']
              ];
        }
        $data['do_table'] = $this->table;
        if(false!== $this->save($data)){
          return returnJson(0,'操作成功');
        }else{
          return returnJson(-1,'操作失败');
        }
       

      }
      $id = $request->input('id');
      if($id){
        $info = Data::getOne($this->table,['id'=>$id]);
        if($info)extract($info);
      }
      return Bear::form()
            ->addAlert('','默认密码 a123456!@#$%^','danger')
            ->addText('username','用户名',$username)
            ->addText('email','邮箱',$email)
            ->addImg('avatar','用户图相',$avatar)
            ->addHidden('id',$id)
            ->addSelect('role_id','用户组',Arr::format(Arr::objToArray(DB::table('admin_roles')->select('id','name')->get()),'id','name'))
            ->fetch();
    }
}
