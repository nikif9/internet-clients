<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Структура данных</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
    <!-- Навигационный бар -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/">Структура данных</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin">Админ панель</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Выход</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Войти</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Структура данных</h2>
        <div id="tree">
            <?php
            echo '<ul class="list-unstyled">';
            foreach ($tree as $item) {
                $hasChildren = !empty($item['children']);
                echo '<li data-id="' . $item['id'] . '">';
                echo '<span class="tree-item" onclick="showDescription(' . $item['id'] . ')">' . htmlspecialchars($item['title']) . '</span>';
                if ($hasChildren) {
                    echo ' <span class="toggle" onclick="toggleChildren(event, ' . $item['id'] . ')">[+]</span>';
                    echo '<ul class="children list-unstyled" style="display:none;"></ul>';
                }
                echo '</li>';
            }
            echo '</ul>';
            ?>
        </div>
        <div id="description" class="mt-4">
            <h3>Описание</h3>
            <div id="descContent"></div>
        </div>
    </div>
    <script src="/assets/js/main.js"></script>
</body>

</html>