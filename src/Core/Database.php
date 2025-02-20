<?php
namespace App\Core;

/**
 * Класс для работы с базой данных.
 */
class Database {
    /**
     * @var Database|null Экземпляр синглтона.
     */
    private static $instance = null;

    /**
     * @var \PDO Объект подключения к базе данных.
     */
    private $pdo;

    /**
     * Конструктор.
     *
     * @param array $config Конфигурация БД.
     */
    private function __construct($config) {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $this->pdo = new \PDO($dsn, $config['user'], $config['pass']);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Возвращает экземпляр базы данных.
     *
     * @param array $config Конфигурация БД.
     * @return Database
     */
    public static function getInstance($config) {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    /**
     * Возвращает PDO-соединение.
     *
     * @return \PDO
     */
    public function getConnection() {
        return $this->pdo;
    }
}
