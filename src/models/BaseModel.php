<?php

namespace App\Model;

/**
 * BaseModel
 *
 * @author ebykovski
 */
abstract class BaseModel
{

    /**
     * @var \PDO
     */
    protected $db;

    /**
     *
     * @param \PDO $db
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

}
