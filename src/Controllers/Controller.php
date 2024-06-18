<?php

namespace Softadastra\Controllers;

use Softadastra\Config\Database;

class Controller
{
    protected $db;
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function view(string $path, array $params = null)
    {
        ob_start();
        $path = str_replace('.', DIRECTORY_SEPARATOR, $path);
        require VIEWS . $path . '.php';

        $content = ob_get_clean();

        require VIEWS . 'base.php';
    }
    public function getDB()
    {
        return $this->db;
    }
}
