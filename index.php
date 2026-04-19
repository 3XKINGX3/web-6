<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
if (isset($_GET['edit_id'])) {
    $_SESSION['user_id'] = $_GET['edit_id'];
    $_SESSION['admin_mode'] = true;
}
$db_user = 'u82373';
$db_pass = '4362231';
$pdo = new PDO('mysql:host=localhost;dbname=u82373;charset=utf8', $db_user, $db_pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $messages = [];
    if (!empty($_COOKIE['save_success'])) {
        $messages[] = $_COOKIE['save_success'];
        setcookie('save_success', '', 100000);
    }
    $errors = [];
    foreach (['fio','phone','email','birth','gender','languages','bio','contract'] as $f) {
        if (!empty($_COOKIE[$f.'_error'])) {
            $errors[$f] = $_COOKIE[$f.'_error'];
            setcookie($f.'_error', '', 100000);
        }
    }
    $values = [];
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM applications WHERE id=?");
        $stmt->execute([$_SESSION['user_id']]);
        $row = $stmt->fetch();
        $values['fio'] = $row['fio'];
        $values['phone'] = $row['phone'];
        $values['email'] = $row['email'];
        $values['birth_date'] = $row['birth_date'];
        $values['gender'] = $row['gender'];
        $values['biography'] = $row['biography'];
        $stmt_l = $pdo->prepare("SELECT language_id FROM application_languages WHERE application_id=?");
        $stmt_l->execute([$row['id']]);
        $values['languages'] = $stmt_l->fetchAll(PDO::FETCH_COLUMN);
    }
    include 'form.php';
    exit();
}
$fio = $_POST['fio'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$birth = $_POST['birth_date'];
$gender = $_POST['gender'];
$languages = $_POST['languages'] ?? [];
$bio = $_POST['biography'];
if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("UPDATE applications SET fio=?, phone=?, email=?, birth_date=?, gender=?, biography=? WHERE id=?");
    $stmt->execute([$fio, $phone, $email, $birth, $gender, $bio, $id]);
    $pdo->prepare("DELETE FROM application_languages WHERE application_id=?")->execute([$id]);
    $stmt_l = $pdo->prepare("INSERT INTO application_languages (application_id, language_id) VALUES (?, ?)");
    foreach ($languages as $l_id) { $stmt_l->execute([$id, $l_id]); }
    if (isset($_SESSION['admin_mode'])) {
        unset($_SESSION['admin_mode']);
        setcookie('save_success', 'Данные пользователя обновлены!', time() + 24*3600);
        header("Location: admin.php");
    } else {
        setcookie('save_success', 'Данные успешно обновлены!', time() + 24*3600);
        header("Location: index.php");
    }
}
exit();
