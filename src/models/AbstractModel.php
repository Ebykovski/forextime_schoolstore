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
        $reflection = new \ReflectionClass($this);

        $aProperties = $reflection->getProperties(/* \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED */);

        $aOutArray = [];

        foreach ($aProperties as $property) {
            $sPropertyName = $property->getName();

            $sMethodName = 'get' . $sPropertyName;

            if (method_exists($this, $sMethodName)) {
                $outArray[$sPropertyName] = $this->{$sMethodName}();
            }
        }

        return $aOutArray;
    }

}
