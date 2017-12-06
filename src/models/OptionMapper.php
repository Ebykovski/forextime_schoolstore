<?php

namespace App\Model;

/**
 * OptionMapper
 *
 * @author ebykovski
 */
final class OptionMapper extends BaseMapper
{

    /**
     * Name of table
     *
     * @var string
     */
    protected $tableName = 'options';

    /**
     * Name of model
     *
     * @var string
     */
    protected $modelName = '\App\Model\Option';

    /**
     * Get list options for goods
     *
     * @param \App\Model\Goods $category
     * @return array \App\Model\Option
     */
    public function getGoodsOptions(Goods $goods)
    {
        $sQuery = 'SELECT
                        o.id,
                        o.name,
                        go.option_value
                    FROM
                        options o
                    LEFT JOIN
                        (SELECT
                            option_id,
                            option_value
                        FROM
                            goods_options
                        WHERE
                            goods_id = :goods_id
                        ) AS go
                    ON
                        o.id = go.option_id
                    WHERE
                        o.id IN (SELECT
                                    option_id
                                FROM
                                    categories_options
                                WHERE
                                    category_id = :category_id
                                )
        ';

        $stmt = $this->db->prepare($sQuery);

        $stmt->execute([
            'goods_id' => $goods->getId(),
            'category_id' => $goods->getCategoryId()
        ]);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, $this->modelName, [$this->db]);
    }

    /**
     * Get list options for category
     *
     * @param \App\Model\Category $category
     * @return array \App\Model\Option
     */
    public function getCategoryOptions(Category $category)
    {
        $sQuery = 'SELECT
                        o.id,
                        o.name,
                        co.option_value
                    FROM
                        categories_options co
                    LEFT JOIN
                        options o
                    ON
                        co.option_id = o.id
                    WHERE
                        co.category_id = :id';

        $stmt = $this->db->prepare($sQuery);

        $stmt->execute([
            'id' => $category->getId()
        ]);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, $this->modelName, [$this->db]);
    }

}
