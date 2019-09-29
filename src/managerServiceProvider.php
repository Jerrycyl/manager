<?php

namespace cylcode\manager;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use cylcode\tools\arr\Arr;
class managerServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $managerConfig = require_once __DIR__.'/../config/config.php';
        config(['manager'=>$managerConfig]);
        $setting = Arr::objToArray(DB::table('setting')->get());
        $settingConfig = [];
        if($setting){
          foreach ($setting as $v) {
            $settingConfig[$v['key']] = $v['value'];
          }
        }
        config(['setting'=>$settingConfig]);
        $this->app->booted(function () {
          require_once __DIR__.'/../routes/web.php';
            // manager::routes(__DIR__.'/../routes/web.php');
        });

        ##注册路由
        //API接口
         // Route::group(['prefix'=>'api/xcx','namespace'=>'\cylcode\manager\Api\xcx'],function(){
         //  $apiInfo = require_once __DIR__.'/../config/api.php';
         //  if(is_array($apiInfo)){
         //         foreach ($apiInfo as $key => $value) {
         //             Route::post($key,$value.'@handle');
         //        }
         //  }
         // });
        // dump($apiInfo);
    }
}
