<?php

namespace App\Model;

/**
 * Tag
 *
 * @author ebykovski
 */
final class Tag extends AbstractModel
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
     * @var integer
     */
    private $cnt;

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
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get count searches
     *
     * @return string
     */
    public function getCount()
    {
        return $this->cnt;
    }

}
