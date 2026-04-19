<?php
$db_user = 'u82373';
$db_pass = '4362231';
$pdo = new PDO('mysql:host=localhost;dbname=u82373;charset=utf8', $db_user, $db_pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="Admin Panel"');
    header('HTTP/1.0 401 Unauthorized');
    exit();
}

$stmt = $pdo->prepare("SELECT password_hash FROM admins WHERE login = ?");
$stmt->execute([$_SERVER['PHP_AUTH_USER']]);
$admin = $stmt->fetch();

if (!$admin || !password_verify($_SERVER['PHP_AUTH_PW'], $admin['password_hash'])) {
    header('WWW-Authenticate: Basic realm="Admin Panel"');
    header('HTTP/1.0 401 Unauthorized');
    exit();
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM application_languages WHERE application_id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM applications WHERE id = ?")->execute([$id]);
    header('Location: admin.php');
    exit();
}

$stats = $pdo->query("
    SELECT l.name, COUNT(al.application_id) as count 
    FROM languages l 
    LEFT JOIN application_languages al ON l.id = al.language_id 
    GROUP BY l.id
")->fetchAll();

$users = $pdo->query("SELECT * FROM applications")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f7f6; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #333; color: white; }
        .stats { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn-del { color: #dc3545; text-decoration: none; font-weight: bold; }
        .btn-edit { color: #007bff; text-decoration: none; margin-right: 15px; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Панель администратора</h1>
    
    <div class="stats">
        <h3>Статистика по языкам</h3>
        <?php foreach ($stats as $s): ?>
            <div><?= htmlspecialchars($s['name']) ?>: <strong><?= $s['count'] ?></strong></div>
        <?php endforeach; ?>
    </div>

    <table>
        <tr>
            <th>ID</th><th>ФИО</th><th>Email</th><th>Логин</th><th>Действия</th>
        </tr>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['fio']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['login'] ?? '—') ?></td>
            <td>
                <a href="index.php?edit_id=<?= $u['id'] ?>" class="btn-edit">Редактировать</a>
                <a href="?delete=<?= $u['id'] ?>" class="btn-del" onclick="return confirm('Удалить пользователя?')">Удалить</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
