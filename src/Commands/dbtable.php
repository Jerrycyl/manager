<?php

namespace cylcode\manager\Commands;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\Command;
use cylcode\manager\Http\Controllers\WxBase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Log;

class dbtable  extends Command
{

    // $table->bigIncrements('id');   自增ID，类型为bigint
    // $table->bigInteger('votes');   等同于数据库中的BIGINT类型
    // $table->binary('data');    等同于数据库中的BLOB类型
    // $table->boolean('confirmed');  等同于数据库中的BOOLEAN类型
    // $table->char('name', 4);   等同于数据库中的CHAR类型
    // $table->date('created_at');    等同于数据库中的DATE类型
    // $table->dateTime('created_at');    等同于数据库中的DATETIME类型
    // $table->dateTimeTz('created_at');  等同于数据库中的DATETIME类型（带时区）
    // $table->decimal('amount', 5, 2);   等同于数据库中的DECIMAL类型，带一个精度和范围
    // $table->double('column', 15, 8);   等同于数据库中的DOUBLE类型，带精度, 总共15位数字，小数点后8位.
    // $table->enum('choices', ['foo', 'bar']);   等同于数据库中的 ENUM类型
    // $table->float('amount');   等同于数据库中的 FLOAT 类型
    // $table->increments('id');  数据库主键自增ID
    // $table->integer('votes');  等同于数据库中的 INTEGER 类型
    // $table->ipAddress('visitor');  等同于数据库中的 IP 地址
    // $table->json('options');   等同于数据库中的 JSON 类型
    // $table->jsonb('options');  等同于数据库中的 JSONB 类型
    // $table->longText('description');   等同于数据库中的 LONGTEXT 类型
    // $table->macAddress('device');  等同于数据库中的 MAC 地址
    // $table->mediumIncrements('id');    自增ID，类型为无符号的mediumint
    // $table->mediumInteger('numbers');  等同于数据库中的 MEDIUMINT类型
    // $table->mediumText('description'); 等同于数据库中的 MEDIUMTEXT类型
    // $table->morphs('taggable');    添加一个 INTEGER类型的 taggable_id 列和一个 STRING类型的 taggable_type列
    // $table->nullableTimestamps();  和 timestamps()一样但允许 NULL值.
    // $table->rememberToken();   添加一个 remember_token 列： VARCHAR(100) NULL.
    // $table->smallIncrements('id'); 自增ID，类型为无符号的smallint
    // $table->smallInteger('votes'); 等同于数据库中的 SMALLINT 类型
    // $table->softDeletes(); 新增一个 deleted_at 列 用于软删除.
    // $table->string('email');   等同于数据库中的 VARCHAR 列  .
    // $table->string('name', 100);   等同于数据库中的 VARCHAR，带一个长度
    // $table->text('description');   等同于数据库中的 TEXT 类型
    // $table->time('sunrise');   等同于数据库中的 TIME类型
    // $table->timeTz('sunrise'); 等同于数据库中的 TIME 类型（带时区）
    // $table->tinyInteger('numbers');    等同于数据库中的 TINYINT 类型
    // $table->timestamp('added_on'); 等同于数据库中的 TIMESTAMP 类型
    // $table->timestampTz('added_on');   等同于数据库中的 TIMESTAMP 类型（带时区）
    // $table->timestamps();  添加 created_at 和 updated_at列
    // $table->timestampsTz();    添加 created_at 和 updated_at列（带时区）
    // $table->unsignedBigInteger('votes');   等同于数据库中无符号的 BIGINT 类型
    // $table->unsignedInteger('votes');  等同于数据库中无符号的 INT 类型
    // $table->unsignedMediumInteger('votes');    等同于数据库中无符号的 MEDIUMINT 类型
    // $table->unsignedSmallInteger('votes'); 等同于数据库中无符号的 SMALLINT 类型
    // $table->unsignedTinyInteger('votes');  等同于数据库中无符号的 TINYINT 类型
    // $table->uuid('id');    等同于数据库的UUID

    private $path = 'dbschema';
    private $schema;
    /**
     * @method ColumnDefinition after(string $column) Place the column "after" another column (MySQL)
     * @method ColumnDefinition autoIncrement() Set INTEGER columns as auto-increment (primary key)
     * @method ColumnDefinition charset(string $charset) Specify a character set for the column (MySQL)
     * @method ColumnDefinition collation(string $collation) Specify a collation for the column (MySQL/PostgreSQL/SQL Server)
     * @method ColumnDefinition comment(string $comment) Add a comment to the column (MySQL)
     * @method ColumnDefinition default(mixed $value) Specify a "default" value for the column
     * @method ColumnDefinition first() Place the column "first" in the table (MySQL)
     * @method ColumnDefinition index(string $indexName = null) Add an index
     * @method ColumnDefinition nullable(bool $value = true) Allow NULL values to be inserted into the column
     * @method ColumnDefinition primary() Add a primary index
     * @method ColumnDefinition spatialIndex() Add a spatial index
     * @method ColumnDefinition storedAs(string $expression) Create a stored generated column (MySQL)
     * @method ColumnDefinition unique() Add a unique index
     * @method ColumnDefinition unsigned() Set the INTEGER column as UNSIGNED (MySQL)
     * @method ColumnDefinition useCurrent() Set the TIMESTAMP column to use CURRENT_TIMESTAMP as default value
     * @method ColumnDefinition virtualAs(string $expression) Create a virtual generated column (MySQL)
     * @method ColumnDefinition persisted() Mark the computed generated column as persistent (SQL Server)
     * ->after('column')   将该列置于另一个列之后 (仅适用于MySQL)
     *->comment('my comment') 添加注释信息
     *->default($value)  指定列的默认值
     *->first()   将该列置为表中第一个列 (仅适用于MySQL)
     *->nullable()    允许该列的值为NULL
     *->storedAs($expression)    创建一个存储生成列（只支持MySQL）
     *->unsigned()    设置 integer 列为 UNSIGNED
     *->virtualAs($expression)   创建一个虚拟生成列（只支持MySQL）
     */
    private $columnsOptions = [
                'after',
                'first',
                'index',
                'spatialIndex',
                'storedAs',
                'unique',
                'charset',
                'length',
                'nullable',
                'primary',
                'precision',
                'scale',
                'unsigned',
                'fixed',
                'notnull',
                'default',
                'autoIncrement',
                'comment',
                'columnDefinition',

            ];
    private $conn;
    private $tableName;
    private $tableInfo;
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
        parent::__construct();

        // $this->drip = $drip;
    }
    public function detect($app,$current=null){
        // parent::detect($app, $current);
        // return $this;
    }
    public function handle()
    {
        
        $this->_editTables($this->_loadDbschema());
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
                $tables[trim($v,'.php')]['mtime'] = filemtime($path.$v);
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
        foreach ($tables as $k => $v) {
            ##获取缓存
            $cacheV = $this->_cacheGet($k);
            ##文件修改时间一样，没有改动不处理
            if($cacheV['mtime']==$v['mtime']) continue;
            if($cacheV){
                $this->tableInfo = $this->_comparison($cacheV,$v);##更改过的相关字段信息
            }else{
                $this->tableInfo = $v;
            } 
            $this->tableName = $k;
           if(Schema::hasTable($k)){
                ##更新
                Schema::table($k, function ($table) {
                       $this->_doField($table);
                 });
           }else{
                ##新加
                Schema::create($k, function ($table) {
                       $this->_doField($table);
                 });
           }
           ##处理完成写入缓存
           $this->_cachePut($k,$v);
        }
        
    }
    /**
     * [_comparison description]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-08-01T15:25:23+0800
     * @Example  eg:
     * @param    [type]                   $oldData   [description]
     * @param    [type]                   $newData   [description]
     * @return   [type]                              [description]
     */
    private function _comparison($oldData,$newData){
        ##删除的字段保留处理。如果不需要可以手动删除 
        ##比对字段
        $columns = [];
        foreach ($newData['columns'] as $k => $v) {
            if($oldData['columns'][$k]){
                if($data = $this->_comparisonArray($oldData['columns'][$k],$v)) $columns[$k] =  $data;

            }
        }
        return $columns;
    }
    /**
     * [_comparisonArray 数组比对，后面的比对前面的，如果有差异就保留]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-08-01T15:45:56+0800
     * @Example  eg:
     * @param    [type]                   $array1    [description]
     * @param    [type]                   $array2    [description]
     * @return   [type]                              [description]
     */
    private function _comparisonArray($array1,$array2){
        $newarr = [];
        foreach ($array2 as $k => $v) {
            if($v!=$array1[$k]) $newarr[$k] = $v;
        }
        return $newarr;
    }
    /**
     * [_cachePut description]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-08-01T14:49:18+0800
     * @Example  eg:
     * @param    [type]                   $key       [description]
     * @param    [type]                   $value     [description]
     * @return   [type]                              [description]
     */
    private function _cachePut($key,$value){
        return Cache::store('file')->forever($key,$value);

    }
    /**
     * [_cacheGet description]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-08-01T14:49:15+0800
     * @Example  eg:
     * @param    [type]                   $key       [description]
     * @return   [type]                              [description]
     */
    private function _cacheGet($key){
        if(!Schema::hasTable($key)) return null;##表不存在，直接新建
        return Cache::store('file')->get($key);

    }
    /**
     * [_doField description]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-08-01T09:14:16+0800
     * @Example  eg:
     * @param    [type]                   $tableObj  [description]
     * @return   [type]                              [description]
     */
    private function _doField(Blueprint $tableObj){
         // ##字段
            $columns  = $this->tableInfo['columns'];
            if($columns&&is_array($columns)){
                ##字段
                foreach ($columns as $k => $v) {
                    $fileName   = trim(strtolower($k));
                    $options    = $v;
                    $tableName  = $this->tableName;
                    
                   if(Schema::hasColumn($tableName,$fileName)){
                         foreach ($options as $key => $value) {
                         if($key=='type'||$key=='length') continue ; ##长度上面已处理，不单独处理
                                 // dump($key);
                                 // dump($value);
                                 if(method_exists($tableObj, $key)) $fieldObj = $fieldObj->$key($value);
                            }
                            unset($fieldObj);

                        // dump($tableObj->addColumn($type,$fileName,$options)->change());
                        die;
                   }else{
                    unset($options['type']);
                        $tableObj->addColumn($v['type'],$fileName,$options);
                   }
                    
                    
                }
                ##普通索引，不支持主键索引
                // $table->primary('id'); 添加主键索引
                // $table->primary(['first', 'last']);    添加混合索引
                // $table->unique('email');   添加唯一索引
                // $table->unique('state', 'my_index_name');  指定自定义索引名称
                // $table->index('state');    添加普通索引
                // if(isset($this->tableInfo['primary'])&&$this->tableInfo['primary']){
                //     $primary = $this->tableInfo['primary'];
                //     $primaryArr = is_array($primary)?$primary:[$primary];
                //     foreach ($primaryArr as $value) {
                //          $tableObj->increments($value);
                //     }
                // }
                // ##索引
                // if(isset($this->tableInfo['index'])&&$this->tableInfo['index']){
                //     $index = $this->tableInfo['index'];

                //     // $tableObj->index('cat_path','index_cat_path');
                //     foreach ($index as $k => $v) {
                //         $tableObj->index($v['columns'],$k);
                //     }
                // }
            }
            $this->tableInfo = [];
    }
    /**
     * [mergeOption 组合column options]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-07-31T17:42:18+0800
     * @Example  eg:
     * @param    [type]                   $info      [description]
     * @return   [type]                              [description]
     */
    private function _mergeColumnOption($tableInfo){
        if(!$tableInfo) return ;
        $columnsOptions = $this->columnsOptions;
        $options = [];
        foreach ($tableInfo as $k => $v) {
            if(in_array($k, $columnsOptions)&&$v){
                $options[$k] = $v;
            }
        }
        return $options;
    }
 
   
}
