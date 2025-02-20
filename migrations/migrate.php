<?php
/**
 * Скрипт миграции базы данных.
 * Создает таблицы и добавляет тестового пользователя.
 */

$config = require __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/autoload.php';

use App\Core\Database;

// Получаем настройки подключения к БД
$dbConfig = $config['db'];

// Инициализируем соединение
$pdo = Database::getInstance($dbConfig)->getConnection();

// Массив с запросами миграций
$migrations = [
    // Создание таблицы пользователей
    "CREATE TABLE IF NOT EXISTS users (
        id INT(11) NOT NULL AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL,
        password VARCHAR(255) NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY username (username)
    ) ENGINE=InnoDB DEFAULT CHARSET={$dbConfig['charset']};",

    // Создание таблицы элементов дерева
    "CREATE TABLE IF NOT EXISTS tree_items (
        id INT(11) NOT NULL AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        parent_id INT(11) DEFAULT NULL,
        PRIMARY KEY (id),
        KEY parent_id (parent_id),
        CONSTRAINT tree_items_ibfk_1 FOREIGN KEY (parent_id) REFERENCES tree_items (id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET={$dbConfig['charset']};"
];

// Подготавливаем хэш пароля для тестового пользователя (пароль: test123)
$testUserPassword = password_hash("test123", PASSWORD_DEFAULT);

// Добавляем запрос на вставку тестового пользователя
$migrations[] = "INSERT IGNORE INTO users (username, password) VALUES ('test', '$testUserPassword')";

echo "Запуск миграций...\n";

foreach ($migrations as $sql) {
    try {
        $pdo->exec($sql);
        echo "Выполнена миграция: " . substr($sql, 0, 50) . "...\n";
    } catch (Exception $e) {
        echo "Ошибка миграции: " . $e->getMessage() . "\n";
    }
}

echo "Миграции завершены.\n";
