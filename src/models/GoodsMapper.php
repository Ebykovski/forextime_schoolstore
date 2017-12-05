<?php

namespace App\Models;

/**
 * Description of GoodsMapper
 *
 * @author ebykovski
 */
class GoodsMapper
{
    /**
     *
     * @var \PDO
     */
    private $db;

    /**
     *
     * @param \PDO $db
     */
    public function __construct(\PDO $db)
    {
        $this->db= $db;
    }

    /**
     * Fetch all goods from database
     *
     * @return array \App\Model\Goods
     */
    public function fetchAll()
    {
        $sQuery = 'SELECT * FROM goods';

        $stmt = $this->db->prepare($sQuery);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_CLASS, '\App\Model\Goods');
    }
}
