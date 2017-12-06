<?php

namespace App\Model;

/**
 * BaseMapper
 *
 * @author ebykovski
 */
abstract class BaseMapper
{

    /**
     * @var \PDO
     */
    protected $db;

    /**
     * Limit items for pagination
     *
     * @var integer
     */
    protected $limit = 10;

    /**
     * Page number for pagination
     *
     * @var integer
     */
    protected $page = 0;

    /**
     *
     * @param \PDO $db
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Get liimt items for pagination
     *
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set limit items for pagination
     *
     * @param integer $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = (int) $limit;

        return $this;
    }

    /**
     * Get page number items for pagination
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set page number items for pagination
     *
     * @param integer $page
     * @return $this
     */
    public function setPage($page)
    {
        $this->page = (int) $page;

        return $this;
    }

    /**
     * Fetch all items from database
     *
     * @return array \App\Model\ModelName
     */
    public function fetchAll()
    {
        $sQuery = 'SELECT SQL_CALC_FOUND_ROWS * FROM ' . $this->tableName;

        if ($this->getPage() > 0) {
            $sQuery .= ' LIMIT ' . $this->getLimit() . ' OFFSET ' . (($this->getPage() - 1) * $this->getLimit());
        }

        $stmt = $this->db->prepare($sQuery);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_CLASS, $this->modelName, [$this->db]);
    }

    /**
     * Fetch Item by id
     *
     * @param integer $id
     * @return \App\Model\ModelName
     */
    public function fetchById($id)
    {
        $sQuery = 'SELECT * FROM ' . $this->tableName . ' WHERE id = :id';

        $stmt = $this->db->prepare($sQuery);

        $stmt->execute([
            'id' => (int) $id
        ]);

        return $stmt->fetchObject($this->modelName, [$this->db]);
    }

    /**
     * Get count total founded rows in last select
     *
     * @return integer
     */
    public function foundRows()
    {
        $stmt = $this->db->prepare('SELECT FOUND_ROWS()');
        $stmt->execute();

        return $stmt->fetchColumn();
    }

}
