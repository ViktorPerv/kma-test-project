<?php

declare(strict_types=1);

namespace App\Repository;

class ContentRepository extends Model
{
    public function getAllContent(): false|array
    {
        $stmt = $this->db->query('SELECT * FROM content');

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @throws \Exception
     */
    public function create(int $length, string $timestamp): void
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
