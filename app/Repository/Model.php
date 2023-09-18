<?php

declare(strict_types=1);

namespace App\Repository;

use App\db\Db;
use App\Http\Kernel;

abstract class Model
{
    protected Db $db;
    public function __construct()
    {
        $this->db = Kernel::db();
    }

}
