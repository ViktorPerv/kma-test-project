<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Model;

class Content extends Model
{
    public function getAllContent(): false|array
    {
        $stmt = $this->db->query('SELECT * FROM content');

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}