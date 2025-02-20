<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-4">
                <h2 class="text-center">Вход</h2>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['errors'])) {
                    foreach ($_SESSION['errors'] as $error) {
                        echo '<div class="alert alert-danger">' . $error . '</div>';
                    }
                    unset($_SESSION['errors']);
                }
                ?>
                <form method="post" action="/login">
                    <div class="form-group">
                        <label>Логин</label>
                        <input type="text" name="username" class="form-control" placeholder="Введите логин">
                    </div>
                    <div class="form-group">
                        <label>Пароль</label>
                        <input type="password" name="password" class="form-control" placeholder="Введите пароль">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Войти</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>