<?php

namespace zikwall\cellconstructor;

class ConstructorConfiguration
{
    /**
     * @var \PDO
     */
    public $db;

    /**
     * @var ConstructorConfiguration
     */
    public static $instance;

    private $dbconfig = [
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'dbname' => 'cellconstructor',
        'pref' => ''
    ];

    const TABLES = [
        'templates'           => 'templates',
        'reports'             => 'reports',
        'descriptionStorange' => 'descriptionStorange',
        'description'         => 'description'
    ];

    const FIELDS = [
        'primaryIdentity'  => 'id',
        'typeIdentity'     => 'field_type',
        'relationIdentity' => 'internal_key',
        'sortOrder'        => 'sort_order',
        'physicalName'     => 'field_name',
        'visibleName'      => 'name',
        'lenghtOptions'    => 'lenght',
        'statusOptions'    => 'status',
        'headingOptions'   => 'heading',
        'pathOptions'      => 'path',
        'levelOptions'     => 'level',
        'childsOptions'    => 'childs'
    ];

    public function __construct()
    {
        ConstructorComponent::$component = $this;
        $this->init();
    }

    public function init()
    {
        $this->initDatabase();
    }

    public function initDatabase()
    {
        $config = [
            'driver'    => 'mysql', // Db driver
            'host'      => $this->dbconfig['host'],
            'database'  => $this->dbconfig['dbname'],
            'username'  => $this->dbconfig['user'],
            'password'  => $this->dbconfig['password'],
            'charset'   => 'utf8', // Optional
            'collation' => 'utf8_unicode_ci', // Optional
            'prefix'    => '', // Table prefix, optional
        ];

        $conn = new \Pixie\Connection('mysql', $config);
        $this->db = new \Pixie\QueryBuilder\QueryBuilderHandler($conn);
    }

    /**
     * @return \PDO
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @return ConstructorConfiguration
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}