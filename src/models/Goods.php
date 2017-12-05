<?php

namespace App\Model;

/**
 * Goods
 *
 * @author ebykovski
 */
final class Goods extends BaseModel
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $category_id;

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
     * Get category_id
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set category_id
     *
     * @param integer $category_id
     * @return Goods
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;

        return $this;
    }

    /**
     * Get category
     *
     * @return \App\Model\Category
     */
    public function getCategory()
    {
        $mapper = new CategoryMapper($this->db);

        return $mapper->fetchById($this->getCategoryId());
    }

    /**
     * Set category
     *
     * @param \App\Model\Category $category
     * @return Goods
     */
    public function setCategory(Category $category)
    {
        $this->category_id = $category->getId();

        return $this;
    }

}
