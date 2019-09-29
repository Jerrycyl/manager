<?php

namespace  cylcode\manager\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth as a;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use cylcode\bear\Bear;
use cylcode\tools\arr\Arr;
use Closure;
class Auth
{
    /**
     * 处理传入的请求
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
 
        if (!a::guard('manager')->check()) return redirect(route('manager.login'));
        if (true==session('manager.lock')) return redirect(route('manager.lock'));
        if(!$this->_checkPower($request)){
            if(isPost()){
                die(returnJson(-1,'您没有访问权限'));
            }else{
                die(Bear::make('layout')->error('您没有访问权限'));
            }
        }
        return $next($request);
    }
    /**
     * [_checkPower description]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-07-10T09:23:27+0800
     * @Example  eg:
     * @return   [type]                   [description]
     */
    private function _checkPower($request){
       
        $userInfo = current(a::guard('manager')->user());
        $user_id  = $userInfo['id'];
        ##创始人
        if($user_id==1) return true;

        ##当前用户的权限 
        $roleMenu  = self::getpowerMenu();
        if(!$roleMenu) return false;
        ##初始权限
        $matchUri = $this->_formatPowerMenu($roleMenu);
        
        ##快速构造URI
        $nowUri = $this->_getNowUri($request);
        // dump($nowUri);
        if(!in_array($nowUri, $matchUri)){
            return  false;
        }  
        
        
        return true;
        
    }
    private function _formatPowerMenu($roleMenu){
         $matchUri = [
            'manager',
            'manager/welcome',
            'manager/notice'
        ];
       foreach ($roleMenu as $v) {
                if(trim($v['uri'],'/')) $matchUri[] = trim($v['uri'],'/');
        } 
        return $matchUri;
    }
    /**
     * [_getNowUri description]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-07-12T14:44:20+0800
     * @Example  eg:
     * @return   [type]                   [description]
     */
    public function _getNowUri($request){
         $nowUri = trim($request->route()->uri(),'/');
         ##快速构造URI处理
         $firstBuilderUri = [
                        'manager/fastpost/message/{table}',
                        'manager/fastpost/edit/{table}',
                        'manager/fastpost/edit/{table}/{id}',
                        'manager/fastpost/delete/{table}',
                        'manager/szpp/category/{catid}'
                    ];
        $nowUri  = trim(str_replace("{id}", '', $nowUri),'/');
         if(in_array($nowUri, $firstBuilderUri)){
            preg_match_all('/(?<=\{)([^\}]*?)(?=\})/' , $nowUri , $match);
            if(is_array($match[0])){
                foreach ($match[0] as $v) {
                    $varValue = $request->route($v);##变量值
                    $nowUri= str_replace('{'.$v.'}',$varValue,$nowUri);
                }
            }  

         }
         return $nowUri;

    }
    /**
     * [getpowerMenu description]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-07-12T09:45:12+0800
     * @Example  eg:
     * @return   [type]                   [description]
     */
    public static function getpowerMenu()
    {
        $userInfo = current(a::guard('manager')->user());
        $user_id  = $userInfo['id'];
        ##当前用户所属权限
        $role_id = DB::table('admin_users')->where(['id'=>$user_id])->value('role_id');
        if(!$role_id) return [];
        ##当前用户的权限 
        if($user_id==1){
            ## 创始人
            $roleMenu = Arr::objToArray(DB::table('admin_menu')->where(['index_show'=>0])->orderBy('order','desc')->get());
        }else{
            $roleMenu  = Arr::objToArray(DB::table('admin_role_menu')->where(['admin_role_menu.role_id'=>$role_id,'index_show'=>0])->select('admin_menu.*')->leftJoin('admin_menu','admin_menu.id','admin_role_menu.menu_id')->orderBy('admin_menu.order','desc')->get()); 
        }
       
        return $roleMenu?Arr::objToArray($roleMenu):[];
    }

}