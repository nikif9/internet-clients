<?php
namespace App\Model;

/**
 * Модель элемента дерева.
 */
class TreeItem {
    /**
     * @var int Идентификатор элемента.
     */
    public $id;

    /**
     * @var string Название элемента.
     */
    public $title;

    /**
     * @var string Описание элемента.
     */
    public $description;

    /**
     * @var int|null Идентификатор родительского элемента.
     */
    public $parent_id;
    
    /**
     * Возвращает все элементы.
     *
     * @param \PDO $pdo
     * @return array
     */
    public static function getAll($pdo) {
        $stmt = $pdo->prepare("SELECT * FROM tree_items ORDER BY parent_id ASC, id ASC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Формирует дерево элементов.
     *
     * @param \PDO $pdo
     * @return array
     */
    public static function getTree($pdo) {
        $items = self::getAll($pdo);
        $tree = [];
        $itemsById = [];
        foreach ($items as $item) {
            $item['children'] = [];
            $itemsById[$item['id']] = $item;
        }
        foreach ($itemsById as $id => &$item) {
            if ($item['parent_id']) {
                if (isset($itemsById[$item['parent_id']])) {
                    $itemsById[$item['parent_id']]['children'][] = &$item;
                }
            } else {
                $tree[] = &$item;
            }
        }
        return $tree;
    }
    
    /**
     * Возвращает элемент по идентификатору.
     *
     * @param int $id
     * @param \PDO $pdo
     * @return array|false
     */
    public static function getById($id, $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM tree_items WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Создает новый элемент.
     *
     * @param string $title
     * @param string $description
     * @param int|null $parent_id
     * @param \PDO $pdo
     * @return int Последний вставленный ID.
     */
    public static function create($title, $description, $parent_id, $pdo) {
        $stmt = $pdo->prepare("INSERT INTO tree_items (title, description, parent_id) VALUES (:title, :description, :parent_id)");
        $stmt->execute([
            ':title'       => $title,
            ':description' => $description,
            ':parent_id'   => $parent_id ? $parent_id : null
        ]);
        return $pdo->lastInsertId();
    }
    
    /**
     * Обновляет элемент.
     *
     * @param int $id
     * @param string $title
     * @param string $description
     * @param int|null $parent_id
     * @param \PDO $pdo
     * @return bool
     */
    public static function update($id, $title, $description, $parent_id, $pdo) {
        $stmt = $pdo->prepare("UPDATE tree_items SET title = :title, description = :description, parent_id = :parent_id WHERE id = :id");
        return $stmt->execute([
            ':title'       => $title,
            ':description' => $description,
            ':parent_id'   => $parent_id ? $parent_id : null,
            ':id'          => $id
        ]);
    }
    
    /**
     * Удаляет элемент вместе с его потомками.
     *
     * @param int $id
     * @param \PDO $pdo
     * @return bool
     */
    public static function delete($id, $pdo) {
        $children = self::getChildren($id, $pdo);
        foreach ($children as $child) {
            self::delete($child['id'], $pdo);
        }
        $stmt = $pdo->prepare("DELETE FROM tree_items WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Возвращает потомков элемента.
     *
     * @param int $parent_id
     * @param \PDO $pdo
     * @return array
     */
    public static function getChildren($parent_id, $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM tree_items WHERE parent_id = :parent_id");
        $stmt->execute([':parent_id' => $parent_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
