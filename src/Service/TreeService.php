<?php
namespace App\Service;

use App\Model\TreeItem;

/**
 * Сервис для работы с деревом элементов.
 */
class TreeService {
    /**
     * Получает дерево элементов.
     *
     * @param \PDO $pdo
     * @return array
     */
    public static function getTree($pdo) {
        return TreeItem::getTree($pdo);
    }
    
    /**
     * Формирует HTML для отображения потомков с отступами и, при необходимости, кнопками редактирования/удаления.
     *
     * @param array $children Массив потомков.
     * @param \PDO $pdo Объект подключения к базе данных.
     * @return string HTML-разметка.
     */
    public static function renderChildrenHtml($children, $pdo) {
        $html = '<ul class="list-unstyled">';
        foreach ($children as $child) {
            $hasChildren = count(TreeItem::getChildren($child['id'], $pdo)) > 0;
            $html .= '<li data-id="' . $child['id'] . '">';
            $html .= '<span class="tree-item" onclick="showDescription(' . $child['id'] . ')">' 
                  . htmlspecialchars($child['title']) 
                  . '</span>';

            if ($hasChildren) {
                $html .= ' <span class="toggle" onclick="toggleChildren(event, ' . $child['id'] . ')">[+]</span>';
                $html .= '<ul class="children list-unstyled" style="display:none;"></ul>';
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * Рекурсивная функция для рендеринга дерева элементов с отступами для иерархии.
     *
     * @param array $tree Массив с данными дерева.
     * @param int   $level Текущий уровень вложенности (используется для отступов).
     * @return string HTML-разметка дерева.
     */
    public static function renderAdminTree(array $tree, $level = 0) {
        $html = '<ul class="list-group">';
        foreach ($tree as $item) {
            // Генерируем отступы для текущего уровня вложенности
            $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
            $html .= '<li class="list-group-item d-flex justify-content-between align-items-center">';
            $html .= '<span>' . $indent . htmlspecialchars($item['title']) . '</span>';
            $html .= '<div>';
            // Кнопка для редактирования элемента
            $html .= '<a href="/admin/edit?id=' . $item['id'] . '" class="btn btn-sm btn-secondary mr-2">Редактировать</a>';
            // Кнопка для удаления элемента с подтверждением
            $html .= '<a href="/admin/delete?id=' . $item['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Удалить элемент?\')">Удалить</a>';
            $html .= '</div>';
            $html .= '</li>';
            // Если у элемента есть потомки, рекурсивно выводим их с увеличением уровня вложенности
            if (!empty($item['children'])) {
                $html .= self::renderAdminTree($item['children'], $level + 1);
            }
        }
        $html .= '</ul>';
        return $html;
    }
    
    /**
     * Рекурсивно формирует HTML-опции для выбора родительского элемента.
     *
     * @param array $tree Дерево элементов.
     * @param int $level Уровень вложенности.
     * @return string
     */
    public static function renderParentOptions($tree, $level = 0) {
        $html = '';
        $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
        foreach ($tree as $item) {
            $html .= '<option value="' . $item['id'] . '">' . $indent . htmlspecialchars($item['title']) . '</option>';
            if (!empty($item['children'])) {
                $html .= self::renderParentOptions($item['children'], $level + 1);
            }
        }
        return $html;
    }
}
