<?php
namespace cylcode\manager\Commands;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\Command;
use cylcode\manager\Http\Controllers\WxBase;
use Illuminate\Support\Facades\DB;
use cylcode\manager\Http\Controllers\Base;
use Log;
/**
 * 获取微信用户
 */
class Wxuser extends Command
{
    /**
     * 控制台命令名称
     *
     * @var string
     */
    // protected $signature = 'email:send {user}';
    protected $signature = 'wx:user';

    /**
     * 控制台命令描述
     *
     * @var string
     */
    protected $description = '获取微信用户';

    /**
     * The drip e-mail service.
     *
     * @var DripEmailer
     */
    protected $drip;

    /**
     * 创建新的命令实例
     *
     * @param  DripEmailer  $drip
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // $this->drip = $drip;
    }

    /**
     * 执行控制台命令
     *
     * @return mixed
     */
    public function handle()
    {
        $app = new WxBase;
        $base = new Base;
        $nextOpenId = null;
        do {
            $users  = $app->app->user->list();
            if($users['total']<1) break;
            $nextOpenId = $users['next_openid'];
            ##获得用户信息
            $openids  = $this->_formatOPenids($users['data']['openid']);
            // dump($openids);
            // die;
            foreach ($openids as $k => $v) {
                $userinfo = $app->app->user->select($v);
                 foreach ($userinfo['user_info_list'] as $vu) {
                    $vu['do_table'] = 'wx_users';
                    $vu['where'] = ['openid'=>$vu['openid']];
                    $base->save($vu);
                }
                
            }
            // dump($userinfo['next_openid']);
            // dump($userinfo);
            if(!$userinfo['next_openid']) break;
        } while (true);
        
    }

    /**
     * [_formatOPenids description]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-05-24T16:37:02+0800
     * @Example  eg:
     * @param    [type]                   $openids   [description]
     * @return   [type]                              [description]
     */
    private function _formatOPenids($openids){
        if(!$openids)return ;
        ##每次处理100条
        $baseCloumn = 100;
        if(count($openids)<=$baseCloumn) return [$openids];
        $formatOpenids = [];
        $openid = [];
        foreach ($openids as $k => $v) {
              if(count($openid)>=$baseCloumn||$k==(count($openids)-1)){
                $formatOpenids[] = $openid;
                $openid = [];
                $openid[] = $v;
              }else{
                $openid[] = $v;
              }
        }
        return $formatOpenids;

    }


}
