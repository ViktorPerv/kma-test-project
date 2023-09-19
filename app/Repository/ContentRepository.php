<?php

declare(strict_types=1);

namespace App\Repository;

class ContentRepository extends Model
{
    public function getAllContent(): false|array
    {
        $stmt = $this->db->query(
            'SELECT 
            MINUTE(FROM_UNIXTIME(timestamp)) as every1Min, 
            count(*) as countPer1Minute, 
            round(avg(content)) avgContent,
            min(FROM_UNIXTIME(timestamp)) minIn1Minute,
            max(FROM_UNIXTIME(timestamp)) maxIn1Minute
            FROM `content` WHERE 1 GROUP BY every1Min;
        '
        );


        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @throws \Exception
     */
    public function create(int $length, int $timestamp): void
    {
        try {
            $sql = 'INSERT INTO content (content, timestamp) VALUES (:length, :timestamp)';

            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':length', $length);
            $stmt->bindParam(':timestamp', $timestamp);

            $stmt->execute();
        } catch (\PDOException $exception) {
            throw new \Exception('DB exception: '. $exception->getMessage(), 500);
        }

    }

}
