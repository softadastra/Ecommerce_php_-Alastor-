<?php

namespace Softadastra\Domain\Shop\Model;

use Softadastra\Config\Database;
use PDO;

abstract class ModelRepositoryPDO
{
    public $pdo;
    protected $table;

    public function __construct()
    {
        $this->pdo = new Database(DB_NAME, DB_HOST, DB_USER, DB_PWD);
    }

    public function findAll()
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table}");
        $query->execute();
        $sizes = $query->fetchAll(PDO::FETCH_ASSOC);
        return $sizes;
    }
}