<?php

namespace App\Model;

/**
 * GoodsMapper
 *
 * @author ebykovski
 */
final class GoodsMapper extends AbstractMapper
{
    /**
     * Name of table
     *
     * @var string
     */
    protected $tableName = 'goods';

    /**
     * Name of model
     *
     * @var string
     */
    protected $modelName = '\App\Model\Goods';

    /**
     * Search Goods by options
     *
     * @var string $sQueryString
     * @var integer $iCategoryId
     * @return array \App\Model\Goods
     */
    public function search($sQueryString, $iCategoryId = false)
    {
        // save search history as tags
        $tag = new Tag($this->db);
        $tag->setName($sQueryString);

        (new TagsMapper($this->db))->save($tag);

        $sQuery = 'SELECT
                    g.*
                FROM
                    (SELECT
                        goods_id,
                        SUM(MATCH (option_value) AGAINST (:query_string IN BOOLEAN MODE)) AS score
                    FROM
                        goods_options
                    WHERE
                        MATCH (option_value) AGAINST(:query_string IN BOOLEAN MODE)

                        -- 2.1.1 Fields to search: all the fields except year(3) of book(1)
                        AND NOT (goods_id IN (SELECT id FROM goods WHERE category_id = 1) AND option_id = 3)

                        '.(!$iCategoryId ? '' : ' AND goods_id IN (SELECT id FROM goods WHERE category_id = :category_id)').'
                    GROUP BY
                        goods_id
                    ORDER BY score DESC) AS r
                LEFT JOIN
                    goods g
                ON
                    r.goods_id = g.id
        ';

        if ($this->getPage() > 0) {
            $sQuery .= ' LIMIT '.$this->getLimit().' OFFSET '.(($this->getPage() - 1) * $this->getLimit());
        }

        $stmt = $this->db->prepare($sQuery);

        // need for boolean mode
        $sQueryString = preg_replace('/\s+/', '* ', $sQueryString).'*';

        $stmt->bindValue(':query_string', $sQueryString, \PDO::PARAM_STR);

        if ($iCategoryId) {
            $stmt->bindValue(':category_id', $iCategoryId, \PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_CLASS, $this->modelName, [$this->db]);
    }

    /**
     * Save goods
     *
     * @param \App\Model\Goods $goods
     * @return GoodsMapper
     */
    public function save(Goods $goods)
    {
        if ($goods->getId() > 0) {
            $this->update($goods);
        } else {
            $this->insert($goods);
        }

        return $this;
    }

    /**
     * Insert new goods
     *
     * @param \App\Model\Goods $goods
     * @return Goods
     */
    private function insert(Goods $goods)
    {

        $this->db->beginTransaction();

        try {

            /*
             * Create goods
             */

            $sQuery = 'INSERT INTO '
                .$this->tableName.' (
                            category_id
                        )
                    VALUES (
                        :category_id
                    )';

            $stmt = $this->db->prepare($sQuery);

            $res = $stmt->execute([
                'category_id' => $goods->getCategoryId()
            ]);

            if (!$res) {
                throw new \Exception($stmt->errorInfo()[2]);
            }

            $id = $this->db->lastInsertId();


            /*
             * Insert options for goods
             */

            $aOptionIds = [];

            $sQuery = 'INSERT INTO
                goods_options (
                    goods_id,
                    option_id,
                    option_value
                )
                VALUES (
                    :goods_id,
                    :option_id,
                    :value
                )
                ON DUPLICATE KEY UPDATE
                    option_value = VALUES(option_value)';

            $stmt = $this->db->prepare($sQuery);

            foreach ($goods->getOptions() as $option) {
                $aOptionIds[] = $option->getId();

                $res = $stmt->execute([
                    'goods_id'  => $id,
                    'option_id' => $option->getId(),
                    'value'     => $option->getValue(),
                ]);

                if (!$res) {
                    throw new \Exception($stmt->errorInfo()[2]);
                }
            }

            $this->db->commit();
        } catch (\Exception $exc) {
            $this->db->rollBack();

            throw new \Exception($exc->getMessage());
        }

        return $this;
    }

    /**
     * Update existing goods
     *
     * @param \App\Model\Goods $goods
     * @return GoodsMapper
     */
    private function update(Goods $goods)
    {
        $this->db->beginTransaction();

        try {

            /*
             * Create goods
             */

            $sQuery = 'UPDATE '
                .$this->tableName.'
                    SET
                        category_id = :category_id
                    WHERE
                        id = :id';

            $stmt = $this->db->prepare($sQuery);

            $res = $stmt->execute([
                'id'          => $goods->getId(),
                'category_id' => $goods->getCategoryId()
            ]);

            if (!$res) {
                throw new \Exception($stmt->errorInfo()[2]);
            }

            /*
             * Insert options for goods
             */

            $aOptionIds = [];

            $sQuery = 'INSERT INTO
                        goods_options (
                            goods_id,
                            option_id,
                            option_value
                        )
                        VALUES (
                            :goods_id,
                            :option_id,
                            :value
                        )
                        ON DUPLICATE KEY UPDATE
                            option_value = VALUES(option_value)';

            $stmt = $this->db->prepare($sQuery);

            foreach ($goods->getOptions() as $option) {
                $aOptionIds[] = $option->getId();

                $res = $stmt->execute([
                    'goods_id'  => $goods->getId(),
                    'option_id' => $option->getId(),
                    'value'     => $option->getValue(),
                ]);

                if (!$res) {
                    throw new \Exception($stmt->errorInfo()[2]);
                }
            }

            /*
             * Delete options from previous categories
             */

            $sQuery = 'DELETE FROM
                        goods_options
                    WHERE
                        goods_id = :id AND
                        option_id NOT IN ('.implode(',', $aOptionIds).')';

            $stmt = $this->db->prepare($sQuery);
            $res  = $stmt->execute([
                'id' => $id
            ]);

            if (!$res) {
                throw new \Exception($stmt->errorInfo()[2]);
            }

            $this->db->commit();
        } catch (\Exception $exc) {
            $this->db->rollBack();

            throw new \Exception($exc->getMessage());
        }

        return $this;
    }

}
