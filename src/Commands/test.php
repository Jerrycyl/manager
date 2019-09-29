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
class test extends Command
{
    /**
     * 控制台命令名称
     *
     * @var string
     */
    // protected $signature = 'email:send {user}';
    protected $signature = 'test';

    /**
     * 控制台命令描述
     *
     * @var string
     */
    protected $description = '测试';

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
     
        $nextOpenId = null;
        $i = 0;
        while ($i <= 100) {
            $i++;
            $this->_do();
            
        }
        // do {
        //   $this->_do();
        // } while (true);
        
    }

    /**
     * [_formatOPenids description]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-05-24T16:37:02+0800
     * @Example  eg:
     * @param    [type]                   $openids   [description]
     * @return   [type]                              [description]
     */
    private function _do(){
        ## 生成随机字符串
        $huawei_userid = "huawei_userid".time().rand(111111,888888);
        $huawei_openid = "huawei_openid".time().rand(111111,888888);
        $name          = "张name:".rand(111,888);
        $sn            = "sn-".time().rand(1111111,999999);
        $udid          = "dd-".rand(111,999).'cc-'.md5(rand(1111,9999));
        $account       = "177411455".rand(111,888);
        $device_tokens = "device_tokens".time().rand(111111,888888);
        return file_get_contents("https://qinxuan-uat.honor.cn/index.php/topapi?format=json&v=v1&method=abutment.vmall&huawei_userid={$huawei_userid}&huawei_openid={$huawei_openid}&name={$name}&sn={$sn}&key_str=90d0e93cd2af58f1cb2729c519eab2ce&udid={$udid}&account={$account}&device_tokens={$device_tokens}");
    }


}
