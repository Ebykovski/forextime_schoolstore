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
        $reflect = new \ReflectionClass($this);
//        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
        $props = $reflect->getProperties();

        $outArray = [];

        foreach ($props as $prop) {
            $prop_name = $prop->getName();

            $method_name = 'get' . $prop_name;
            if (method_exists($this, $method_name)) {
                $outArray[$prop_name] = $this->{$method_name}();
            }
        }

        return $outArray;
    }

}
