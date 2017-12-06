<?php

namespace App\Model;

/**
 * GoodsMapper
 *
 * @author ebykovski
 */
final class GoodsMapper extends BaseMapper
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

        $sQuery = 'SELECT
                    g.*,
                    r.score
                FROM
                    (SELECT
                        goods_id,
                        SUM(MATCH (option_value) AGAINST (:query_string IN BOOLEAN MODE)) AS score
                    FROM
                        goods_options
                    WHERE
                        MATCH (option_value) AGAINST(:query_string IN BOOLEAN MODE)
                        '.(!$iCategoryId ? '' : ' AND goods_id IN (SELECT id FROM goods WHERE category_id = :category_id)').'
                    GROUP BY
                        goods_id
                    ORDER BY score) AS r
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
        $sQueryString = '+'.preg_replace('/\s+/', '* +', $sQueryString).'*';

        $stmt->bindValue(':query_string', $sQueryString, \PDO::PARAM_STR);

        if ($iCategoryId) {
            $stmt->bindValue(':category_id', $iCategoryId, \PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_CLASS, $this->modelName, [$this->db]);
    }

}
