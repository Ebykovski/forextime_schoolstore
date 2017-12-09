<?php

namespace App\Model;

/**
 * Option
 *
 * @author ebykovski
 */
final class Option extends AbstractModel
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $option_value;

    /**
     * Get Id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Option
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get option value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->option_value;
    }

    /**
     * Set option value
     *
     * @param string $option_value
     * @return Option
     */
    public function setValue($option_value)
    {
        $this->option_value = $option_value;

        return $this;
    }

    /**
     * Method for json_encode
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'value' => $this->getValue()
        ];
    }

}
