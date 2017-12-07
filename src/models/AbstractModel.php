<?php

namespace App\Model;

/**
 * AbstractModel
 *
 * @author ebykovski
 */
abstract class AbstractModel
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
