<?php

namespace App\Model;

use PDO;

class UtilisateurModel extends Model{

    protected string $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = "utilisateur";
    }


}
