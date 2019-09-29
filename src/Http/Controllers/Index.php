<?php

namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
// use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use cylcode\manager\Models\Menu;
use Illuminate\Support\Facades\Cookie;
use cylcode\manager\Models\Task as t;
use Illuminate\Support\Facades\Auth as a;
use cylcode\tools\arr\Arr as arr;
use cylcode\manager\Middleware\Auth;
class Index extends Base
{
    // protected $prefix = '';
    public function index(Request $request)
    {
      if(a::guard('manager')->check()){
          $userInfo = current(a::guard('manager')->user());
      }
      // $leftMenu = Menu::orderBy('order','desc')->orderBy('order','desc')->get()->toArray();
      $leftMenu = Auth::getpowerMenu();
      foreach ($leftMenu as $k => $v) {
        if($v['is_show']<1) unset($leftMenu[$k]);
      }
      $data['leftMenu']   = $this->_tree($leftMenu,0);
      $data['defaultUri'] = '/manager/welcome';
      $userInfo['avatar'] = $userInfo['avatar']?$userInfo['avatar']:config('manager.default.avatar');
      $data['userInfo'] = $userInfo;
      $data['notice']['open_notic_windows'] = setting('open_notic_windows');
      $data['notice']['notice'] = setting('notice');
      // dump($data);
      return Bear::make('layout')->iframeWindows($data);
    }
    /**
     * [_tree 树处理]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-06-21T18:06:54+0800
     * @Example  eg:
     * @param    [type]                   $data      [description]
     * @param    integer                  $pid       [description]
     * @return   [type]                              [description]
     */
    private function _tree($data,$pid = 0){
      $array = [];
      foreach($data as $k => $v)
      {
         if($v['parent_id'] == $pid)
         {
          $v['child'] = self::_tree($data, $v['id']);
          $array[$v['id']] = $v;
         }
      }
      return $array;
    }
    /**
     * [welcome description]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-06-21T18:07:07+0800
     * @Example  eg:
     * @return   [type]                   [description]
     */
    public function welcome(){
      $data['task'] = t::get()->toArray();
      // $data['wxUserCount'] = DB::table('')->where()->count();
      // dump($data);
      return Bear::make('layout')->extend(__DIR__.'/../../Views/index/welcome',['data'=>$data]);
    }
    /**
     * [notice description]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-06-21T18:09:57+0800
     * @Example  eg:
     * @return   [type]                   [description]
     */
     public function notice(){
      return Bear::make('layout')->extend(__DIR__.'/../../Views/index/notice',['data'=>$this->csrfStrDecode(setting('notice'))]);
    }
}
