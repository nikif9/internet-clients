<?php
namespace App\Controller;

use App\Core\Request;
use App\Service\TreeService;
use App\Core\Database;

/**
 * Контроллер публичной части сайта.
 */
class SiteController {
    /**
     * @var \PDO Объект подключения к БД.
     */
    protected $db;
    
    /**
     * Конструктор.
     */
    public function __construct() {
        $config = require __DIR__ . '/../../config/config.php';
        $this->db = \App\Core\Database::getInstance($config['db'])->getConnection();
    }
    
    /**
     * Отображает публичную страницу с деревом элементов.
     *
     * @param Request $request
     */
    public function index(Request $request) {
        $tree = TreeService::getTree($this->db);
        require __DIR__ . '/../View/site.php';
    }
}
