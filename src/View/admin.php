<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Админ панель – Управление структурой</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
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
        <h2>Админ панель – Управление структурой</h2>
        <?php
        // Вывод ошибок, если имеются
        if (isset($_SESSION['errors'])) {
            foreach ($_SESSION['errors'] as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            unset($_SESSION['errors']);
        }
        ?>
        <h3>Существующая структура:</h3>
        <div>
            <?php
            // $tree должна быть передана контроллером AdminController::index()
            echo \App\Service\TreeService::renderAdminTree($tree);
            ?>
        </div>
        <h3>Добавить новый элемент</h3>
        <form method="post" action="/admin">
            <input type="hidden" name="operation" value="add">
            <div class="form-group">
                <label>Название:</label>
                <input type="text" name="title" class="form-control" placeholder="Введите название">
            </div>
            <div class="form-group">
                <label>Описание:</label>
                <textarea name="description" class="form-control" placeholder="Введите описание"></textarea>
            </div>
            <div class="form-group">
                <label>Родитель:</label>
                <select name="parent_id" class="form-control">
                    <option value="">-- Корневой элемент --</option>
                    <?php
                    // Используем метод renderParentOptions из TreeService для формирования списка опций
                    echo \App\Service\TreeService::renderParentOptions($tree);
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Добавить</button>
        </form>
    </div>
    <script src="/assets/js/main.js"></script>
</body>

</html>