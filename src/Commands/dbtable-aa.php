<?php

namespace cylcode\manager\Commands;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class dbtable  extends Command
{

    var $path = 'dbschema';
    // var $use_db_cache = true;
    // static $_define = null;
    // static $force_update = false;

    // static $__type_define = array();


     /**
     * 控制台命令名称
     *
     * @var string
     */
    // protected $signature = 'email:send {user}';
    protected $signature = 'table:create';

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
        // parent::__construct();
        // $this->drip = $drip;
    }
    public function detect($app,$current=null){
        // parent::detect($app, $current);
        // return $this;
    }

	/**
	 * 根据实际定义的dbschema生成实际创建表的dbal schema
	 *
	 * @return \Doctrine\DBAL\Schema\Schema
	 */
    public function schedule()
    {
        $this->_editTables($this->_loadDbschema());

        // $appId = $this->target_app->app_id;
        // $db = app::get($appId)->database();
        // $schema = new \Doctrine\DBAL\Schema\Schema();
        // $table = $schema->createTable($this->loadDbschema());
// die;
       
    }
    /**
     * [_loadDbschema 加载数据库文件]
     * @Author   Jerry
     * @DateTime 2019-06-16T11:08:36+0800
     * @Example  eg:
     * @return   [type]                   [description]
     */
   private function _loadDbschema()
    {
        $path = dirname(__FILE__).'/../dbschema/';
        //扫描文件夹
        $file = scandir($path);
        $tables = [];
        if(!$file||!is_array($file)) throw new Exception("no file in ".$path, 1);
            foreach ($file as $key => $v) {
                if($v=='.'||$v=='..') continue;
                $tables[trim($v,'.php')] = include $path.$v; 
            }
       return $tables;
    }
    /**
     * [_editTables 处理表格]
     * @Author   Jerry
     * @DateTime 2019-06-16T11:09:28+0800
     * @Example  eg:
     * @param    [type]                   $tables [description]
     * @return   [type]                           [description]
     */
    private function _editTables($tables){
        foreach ($tables as $tableName => $tableInfo) {
            if (Schema::hasTable($tableName)) {
                    //
            }else{
                $this->_createTable($tableName,$tableInfo);
            }
        }
    }

    private function _createTable($tableName,$tableInfo){
        // $table->increments('id');
        $table = new \Illuminate\Database\Schema\Blueprint($tableName);
        $table->increments('id');
        dump($table->toSql());
        die;
        Schema::create('test', function ($table) {
            
        });
        // Schema::table($tableName, function (Blueprint $table) {
            // dump($tableInfo);
            // $table->string('email');
        // });
       // dump($tableName);
    }
    private function _updateTable($tableName,$tableInfo){
        dump($tableName);
        die;
         if (Schema::hasColumn('users', 'email')) {
            //
        }
    }
    
   
}
