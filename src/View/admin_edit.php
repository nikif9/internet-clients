<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Редактировать элемент</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Редактировать элемент</h2>
    <p><a href="/admin" class="btn btn-secondary">Назад</a></p>
    <?php
    if (isset($_SESSION['errors'])) {
        foreach ($_SESSION['errors'] as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        unset($_SESSION['errors']);
    }
    ?>
    <form method="post" action="/admin">
        <input type="hidden" name="operation" value="edit">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($element['id']); ?>">
        
        <div class="form-group">
            <label>Название:</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($element['title']); ?>">
        </div>
        <div class="form-group">
            <label>Описание:</label>
            <textarea name="description" class="form-control"><?php echo htmlspecialchars($element['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label>Родитель:</label>
            <select name="parent_id" class="form-control">
                <option value="">-- Корневой элемент --</option>
                <?php
                /**
                 * Функция для генерации опций селектора с учётом выбранного значения.
                 *
                 * @param array $tree Дерево элементов.
                 * @param mixed $currentParent Текущий выбранный родитель.
                 * @param int $level Уровень вложенности.
                 * @param mixed $currentId ID редактируемого элемента (чтобы не выводить его как вариант).
                 * @return string
                 */
                function renderParentOptionsWithSelected($tree, $currentParent, $level = 0, $currentId) {
                    $html = '';
                    $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
                    foreach ($tree as $item) {
                        if ($item['id'] == $currentId) {
                            continue;
                        }
                        $selected = ($item['id'] == $currentParent) ? 'selected' : '';
                        $html .= '<option value="' . $item['id'] . '" ' . $selected . '>' . $indent . htmlspecialchars($item['title']) . '</option>';
                        if (!empty($item['children'])) {
                            $html .= renderParentOptionsWithSelected($item['children'], $currentParent, $level + 1, $currentId);
                        }
                    }
                    return $html;
                }
                echo renderParentOptionsWithSelected($tree, $element['parent_id'], 0, $element['id']);
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
    </form>
</div>
<script src="/assets/js/main.js"></script>
</body>
</html>
