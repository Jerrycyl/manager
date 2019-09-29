<?php
namespace cylcode\manager\Commands;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\Command;
use cylcode\manager\Http\Controllers\WxBase;
use Illuminate\Support\Facades\DB;
use Log;
/**
 * 获得微信用户组
 */
class Wxgroup extends Command
{
    /**
     * 控制台命令名称
     *
     * @var string
     */
    // protected $signature = 'email:send {user}';
    protected $signature = 'wx:group';

    /**
     * 控制台命令描述
     *
     * @var string
     */
    protected $description = '获得微信用户组';

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
        $group  = $app->app->user_tag->list();
        if($group){
            $this->_saveGroup($group);
        }
    }
    /**
     * [_saveGroup description]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-05-24T15:47:40+0800
     * @Example  eg:
     * @return   [type]                   [description]
     */
    public function _saveGroup($group){
        if(!$group) return ;
        foreach ($group['tags'] as $v) {
            $data = [
                'name'=>$v['name'],
                'count'=>$v['count'],
                'id'=>$v['id'],

            ];
            if(DB::table('wx_users_groups')->where(['id'=>$v['id']])->count()){
                DB::table('wx_users_groups')->where(['id'=>$v['id']])->update($data);
            }else{
                DB::table('wx_users_groups')->where(['id'=>$v['id']])->insert($data);
            }
        }
        
    }

}
