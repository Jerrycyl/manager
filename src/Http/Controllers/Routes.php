<?php
namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use cylcode\tools\arr\Arr as arr;
/**
 * 路由地址
 */
class Routes extends Base
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
    $routes = app('router')->getRoutes();
         $colors = [
                    'GET'    => 'green',
                    'HEAD'   => 'gray',
                    'POST'   => 'blue',
                    'PUT'    => 'yellow',
                    'DELETE' => 'red',
                    'PATCH'  => 'aqua',
                    'OPTIONS'=> 'light-blue',
                ];
    $routes = collect($routes)->map(function ($route) {
             $res         = $this->getRouteInformation($route);
             $res['uri']  =  preg_replace('/\{.+?\}/', '<code>$0</code>', $res['uri']);
             $res['action']  =  preg_replace('/@.+/', '<code>$0</code>', $res['action']);
             return $res;
    })->all();
    return Bear::table()
           ->addColumn('host','host')
           ->addColumn('method','method',function($data,$colors){
              $method = $data['method'];
              if(!$method) return ;
              $str = '';
              foreach ($method as $vs) {
                $str.= "<span class='label bg-{$colors[$vs]}'>$vs</span>";
              }
              return $str;
           },$colors)
           ->addColumn('name','name')
           ->addColumn('uri','uri')
           ->addColumn('action','action')
           ->setData($routes)
           ->fetch();
    // dump($routes);
  }
  protected function getRouteInformation( $route)
    {
      // dump($route);
        return [
            'host'       => $route->domain(),
            'method'     => $route->methods(),
            'uri'        => $route->uri(),
            'name'       => $route->getName(),
            'action'     => $route->getActionName(),
            // 'middleware' => $this->getRouteMiddleware($route),
        ];
    }



}
