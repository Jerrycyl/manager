<?php
namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
/**
 * 数据管理
 */
class Scheduling extends Base
{
  /**
   * [index cron 列表]
   * @Author   Jerry                    (wx621201)
   * @DateTime 2019-05-24T11:25:06+0800
   * @Example  eg:
   * @param    Request                  $request   [description]
   * @return   [type]                              [description]
   */
  public function index(Request $request)
  {
           //1. 加载Console内核
      app()->make(\Illuminate\Contracts\Console\Kernel::class);
       
      //2.  获取计划任务列表
      $scheduleList = app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events();
      // dump($scheduleList);

  }
  

}
