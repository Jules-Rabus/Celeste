<?php

namespace App\Model;

use PDO;

class SalleModel extends Model{

    protected string $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = "salle";
    }

}
