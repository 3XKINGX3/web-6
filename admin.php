<?php

/**
 * Задача 6. Реализовать вход администратора с использованием
 * HTTP-авторизации для просмотра и удаления результатов.
 **/

// 1. Фикс для работы HTTP-авторизации на сервере (обязательно для кубсу)
if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
    list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 6)));
}

// 2. Настройки подключения к БД
$db_user = 'u82373';
$db_pass = '4362231';
$pdo = new PDO('mysql:host=localhost;dbname=u82373;charset=utf8', $db_user, $db_pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

// 3. Проверка авторизации (используем хеш из таблицы admins)
$auth_success = false;
if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW'])) {
    $stmt = $pdo->prepare("SELECT password_hash FROM admins WHERE login = ?");
    $stmt->execute([$_SERVER['PHP_AUTH_USER']]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($_SERVER['PHP_AUTH_PW'], $admin['password_hash'])) {
        $auth_success = true;
    }
}

// Если авторизация не прошла — выводим окно входа (как в примере)
if (!$auth_success) {
  header('HTTP/1.1 401 Unauthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}

// --- КОД ДЛЯ АВТОРИЗОВАННОГО АДМИНА ---

// 4. Обработка удаления пользователя
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM application_languages WHERE application_id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM applications WHERE id = ?")->execute([$id]);
    header('Location: admin.php');
    exit();
}

// 5. Получение статистики по языкам (Задание 6)
$stats = $pdo->query("SELECT l.name, COUNT(al.application_id) as count 
                      FROM languages l 
                      LEFT JOIN application_languages al ON l.id = al.language_id 
                      GROUP BY l.id")->fetchAll();

// 6. Получение всех данных пользователей для таблицы
$users = $pdo->query("SELECT * FROM applications")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background-color: #f4f4f9; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        .stats { margin-bottom: 20px; padding: 15px; border: 1px solid #007bff; border-radius: 4px; }
        .btn-del { color: #dc3545; text-decoration: none; font-weight: bold; }
        .btn-edit { color: #007bff; text-decoration: none; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Панель администратора</h1>
        
        <div class="stats">
            <h3>Статистика по языкам программирования:</h3>
            <?php foreach ($stats as $s): ?>
                <div><?= htmlspecialchars($s['name']) ?>: <strong><?= $s['count'] ?></strong></div>
            <?php endforeach; ?>
        </div>

        <h3>Список пользователей:</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Email</th>
                    <th>Логин</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['fio']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['login'] ?? '—') ?></td>
                    <td>
                        <a href="index.php?edit_id=<?= $u['id'] ?>" class="btn-edit">Редактировать</a>
                        <a href="?delete=<?= $u['id'] ?>" class="btn-del" onclick="return confirm('Удалить?')">Удалить</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <a href="index.php">Назад к форме</a>
    </div>
</body>
</html>
