<?php

namespace App\Model;

/**
 * Goods
 *
 * @author ebykovski
 */
final class Goods extends AbstractModel
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
     * @var array \App\Model\Option
     */
    private $options = [];

    /**
     * @var \App\Model\Category
     */
    private $category;

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
    public function setCategoryId(integer $category_id)
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
        if (!$this->category) {
            $mapper = new CategoryMapper($this->db);
            $this->category = $mapper->fetchById($this->getCategoryId());
        }

        return $this->category;
    }

    /**
     * Set category
     *
     * @param \App\Model\Category $category
     * @return Goods
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
        $this->category_id = $category->getId();

        return $this;
    }

    /**
     * Get list options for goods
     *
     * @return array \App\Model\Option
     */
    public function getOptions()
    {
        if (count($this->options) == 0) {
            $this->options = (new OptionMapper($this->db))->getGoodsOptions($this);
        }

        return $this->options;
    }

    /**
     * Get list options for goods
     *
     * @param array $options
     * @return Goods
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

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
            'category' => $this->getCategory(),
            'options' => $this->getOptions()
        ];
    }

}
