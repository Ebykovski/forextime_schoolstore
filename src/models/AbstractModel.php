<?php

namespace App\Model;

/**
 * AbstractModel
 *
 * @author ebykovski
 */
abstract class AbstractModel implements \JsonSerializable
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

    /**
     * Method for json_encode
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId()
        ];
    }

}
