<?php

namespace App\Model;

/**
 * TagsMapper
 *
 * @author ebykovski
 */
final class TagsMapper extends AbstractMapper
{
    /**
     * Name of table
     *
     * @var string
     */
    protected $tableName = 'tags';

    /**
     * Name of model
     *
     * @var string
     */
    protected $modelName = '\App\Model\Tag';

    /**
     * Get top 50 tags
     *
     * @return array \App\Model\Tag
     */
    public function fetchTop()
    {
        $sQuery = "SELECT
                        *
                    FROM
                        {$this->tableName}
                    ORDER BY
                        cnt DESC
                    LIMIT 50";

        $stmt = $this->db->prepare($sQuery);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_CLASS, $this->modelName, [$this->db]);
    }

    /**
     * Save tag
     *
     * @param \App\Model\Tag $tag
     * @return $this
     */
    public function save(Tag $tag)
    {
        $sQuery = "INSERT INTO
                        {$this->tableName} (name, cnt)
                    VALUES (
                        :name,
                        1
                    )
                    ON DUPLICATE KEY UPDATE
                        cnt=cnt+1";

        $stmt = $this->db->prepare($sQuery);

        $stmt->execute([
            'name' => $tag->getName()
        ]);

        return $this;
    }

}
