<?php

declare(strict_types=1);

namespace App\Models;

use App\Http\Kernel;
use App\Db;

abstract class Model
{
    protected Db $db;
    public function __construct()
    {
        $this->db = Kernel::db();
    }

}