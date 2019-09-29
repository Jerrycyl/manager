<?php

// namespace cylcode\manager\Commands;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Console\Command;

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
        parent::__construct();
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
        // $appId = $this->target_app->app_id;
        // $db = app::get($appId)->database();
        $schema = new \Doctrine\DBAL\Schema\Schema();
        $table = $schema->createTable($this->loadDbschema());
die;
        $define = $this->realLoad();
        // 建立字段
        foreach($define['columns'] as $columnName => $columnDefine)
        {
            list($type, $options) = $columnDefine['doctrineType'];
            $table->addColumn($columnName, $type, $options);
        }

        // 建立主键
        //if ($define['pkeys']) $table->setPrimaryKey($define['pkeys']);
        if ($define['primary']) $table->setPrimaryKey($define['primary']);
        // 建立索引
        if ($define['index'])
        {
            foreach((array)$define['index'] as $indexName => $indexDefine)
            {
                if (strtolower($indexDefine['prefix'])=='unique')
                {
                    $table->addUniqueIndex($indexDefine['columns'], $indexName);
                }
                else
                {
                    $table->addIndex($indexDefine['columns'], $indexName);
                }
            }
        }
        return $schema;
    }
   public function loadDbschema()
    {
        $list = scandir(__DIR__."/../dbschema");
        dump($list);　
    
    }
    public function getCreateTableSql()
    {
        $appId = $this->target_app->app_id;
        $schema = $this->createTableSchema();
        $sql = current($schema->toSql(app::get($appId)->database()->getDatabasePlatform()));
        return $sql;
    }


    public function createDoctrineType($columnDefine)
    {
        $options = [];
        $options['notnull'] = ($columnDefine['required']) ? true : false;
        $convertKeys = ['autoincrement', 'comment', 'default', 'fixed', 'precision', 'scale', 'length', 'unsigned', 'customSchemaOptions'];
        array_walk($convertKeys, function($key) use ($columnDefine, &$options) {
            if (isset($columnDefine[$key])) $options[$key] = $columnDefine[$key];
        });

        $type = $columnDefine['type'];
        switch (true)
        {
            case is_array($primType =$type):
                $type = 'string';
                $options['length'] = array_reduce(array_keys($primType), function($max, $item) {
                    $itemLenth = strlen($item);
                    return $itemLenth > $max ? $itemLenth : $max;
                });
                break;
            case starts_with($type, 'table:'):
                @list(,$relatedModelString, $relatedColumnName) = explode(':', $type);
                @list($relatedModelName, $relatedModelAppId) = explode('@', $relatedModelString);
                $relatedModelAppId = $relatedModelAppId ?: $this->target_app->app_id;

                $relatedTableDefine = with(new base_application_dbtable)->detect($relatedModelAppId, $relatedModelName)->realLoad();

                if (!$relatedColumnName)
                {
                    // 如果关联表有超过1个主键, 意味着没办法进行关联
                    if (count($relatedTableDefine['primary']) !== 1)
                    {
                        throw new InvalidArgumentException(sprintf('related table: %s %s, not have one primary key! ', $relatedModelAppId, $relatedModelName));
                    }
                    $relatedColumnName = current($relatedTableDefine['primary']);
                }

                @list($type, $relatedOptions) = $relatedTableDefine['columns'][$relatedColumnName]['doctrineType'];
                $relatedOptions = array_intersect_key($relatedOptions, array_flip(['precision', 'scale', 'fixed', 'length', 'unsigned']));
                $options = array_merge($options, $relatedOptions);

                break;
            case kernel::single('base_db_datatype_manage')->isExistDefine($type):
                @list($type, $initOptions) = kernel::single('base_db_datatype_manage')->getDefineDoctrineType($type);
                $initOptions = is_array($initOptions) ? $initOptions : [];
                $options = array_merge($options, array_intersect_key($initOptions, array_flip(['precision', 'scale', 'fixed', 'length', 'unsigned'])));
                break;
        }

        return [$type, $options];

    }




   


    function install()
    {
        $appId = $this->target_app->app_id;
        $db = app::get($appId)->database();

        $schema = $this->createTableSchema();
        $sql = current($schema->toSql(app::get($appId)->database()->getDatabasePlatform()));
        $real_table_name = $this->real_table_name();

        logger::info('Creating table '.$real_table_name);

        if ($db->getSchemaManager()->tablesExist($real_table_name))
        {
            $db->getSchemaManager()->dropTable($real_table_name);
        }
        $db->exec($sql);
    }

    public function update($appId)
    {
        foreach($this->detect($appId) as $item)
        {
            $item->updateTable();
        }
        syscache::instance('tbdefine')->set_last_modify();
    }

    public function updateTable($schema, $saveMode = true)
    {
        $appId = $this->target_app->app_id;
        $db = app::get($appId)->database();
        $comparator = new \Doctrine\DBAL\Schema\Comparator();

        $real_table_name = $this->real_table_name();
        $toSchema = $this->createTableSchema();

        // 如果存在原始表, 则通过原始表建立schema对象
        if ($db->getSchemaManager()->tablesExist($real_table_name))
        {
            $fromSchema = new \Doctrine\DBAL\Schema\Schema([$db->getSchemaManager()->listTableDetails($real_table_name)], [], $db->getSchemaManager()->createSchemaConfig());
        }
        // 否则建立空schema
        else
        {
            $fromSchema = new \Doctrine\DBAL\Schema\Schema();
        }

        $comparator = new \Doctrine\DBAL\Schema\Comparator();

        $schemaDiff = $comparator->compare($fromSchema, $toSchema);

        // 非安全模式
        if (!$saveMode)
        {
            $queries = $schemaDiff->toSql($db->getDatabasePlatform());
        }
        // 安全模式, 删除drop columns的相关语句
        else
        {
            $changeTable = current($schemaDiff->changedTables);
            $changeTable->removedColumns = [];
            $queries = $schemaDiff->toSaveSql($db->getDatabasePlatform());
        }

        foreach($queries as $sql)
        {
            logger::info($sql);
            $db->exec($sql);
        }
    }

    public function last_modified($appId)
    {
        if(self::$force_update){
            return time()+999999;
        }else{
            return parent::last_modified($appId);
        }
    }

	


    public function pause_by_app($appId)
    {
        $db = app::get($appId)->database();
        $suffix = '_'.substr(md5('dbtable_'.$appId), 0, 16);

        // 包含临时表和真实表
        $tableNames = $this->getAppTableNames($appId);

        foreach($tableNames as $tableName)
        {
            if (ends_with($tableName, $suffix))
            {
                $tmpTableNames[] = $tableName;
            }
            else
            {
                $appTableNames[] = $tableName;
            }
        }
        // 删除临时表
        foreach($tmpTableNames as $tableName)
        {
            $db->getSchemaManager()->dropTable($tableName);
        }
        // 变更真实表为临时表
        foreach($appTableNames as $tableName)
        {
            $newTableName = $tableName.$suffix;
            $db->getSchemaManager()->renameTable($tableName, $newTableName);
            logger::info(sprintf('%s backup to %s', $tableName, $newTableName));
        }
    }//End Function

}
