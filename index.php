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
        $errors[$f] = !empty($_COOKIE[$f.'_error']);
        setcookie($f.'_error', '', 100000);
    }

    $values = [
        'fio' => '', 'phone' => '', 'email' => '', 'birth_date' => '', 
        'gender' => '', 'biography' => '', 'contract' => '', 'languages' => []
    ];

    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM applications WHERE id=?");
        $stmt->execute([$_SESSION['user_id']]);
        $row = $stmt->fetch();
        
        if ($row) {
            foreach (['fio', 'phone', 'email', 'birth_date', 'gender', 'biography'] as $f) {
                $values[$f] = $row[$f];
            }
            $values['contract'] = 1;
            
            $stmt_l = $pdo->prepare("SELECT language_id FROM application_languages WHERE application_id=?");
            $stmt_l->execute([$_SESSION['user_id']]);
            $values['languages'] = $stmt_l->fetchAll(PDO::FETCH_COLUMN);
        }
    }

    foreach (['fio', 'phone', 'email', 'birth_date', 'gender', 'biography'] as $f) {
        if (isset($_COOKIE[$f.'_value'])) {
            $values[$f] = $_COOKIE[$f.'_value'];
        }
    }
    if (isset($_COOKIE['languages_value'])) {
        $values['languages'] = explode(',', $_COOKIE['languages_value']);
    }

    include 'form.php';
    exit();
}

$fio = $_POST['fio'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$birth = $_POST['birth_date'] ?? '';
$gender = $_POST['gender'] ?? '';
$languages = $_POST['languages'] ?? [];
$bio = $_POST['biography'] ?? '';
$contract = isset($_POST['contract']);

$errors = false;
if (empty($fio)) { setcookie('fio_error', '1', time() + 3600); $errors = true; }
if (empty($gender)) { setcookie('gender_error', '1', time() + 3600); $errors = true; }
if (empty($languages)) { setcookie('languages_error', '1', time() + 3600); $errors = true; }

setcookie('fio_value', $fio, time() + 30*24*3600);
setcookie('phone_value', $phone, time() + 30*24*3600);
setcookie('email_value', $email, time() + 30*24*3600);
setcookie('birth_date_value', $birth, time() + 30*24*3600);
setcookie('gender_value', $gender, time() + 30*24*3600);
setcookie('languages_value', implode(',', $languages), time() + 30*24*3600);
setcookie('bio_value', $bio, time() + 30*24*3600);

if ($errors) {
    header("Location: index.php" . (isset($_SESSION['admin_mode']) ? "?edit_id=".$_SESSION['user_id'] : ""));
    exit();
}

foreach(['fio','phone','email','birth_date','gender','languages','bio','contract'] as $f) {
    setcookie($f.'_value', '', 100000);
}

if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("UPDATE applications SET fio=?, phone=?, email=?, birth_date=?, gender=?, biography=? WHERE id=?");
    $stmt->execute([$fio, $phone, $email, $birth, $gender, $bio, $id]);

    $pdo->prepare("DELETE FROM application_languages WHERE application_id=?")->execute([$id]);
    $stmt_l = $pdo->prepare("INSERT INTO application_languages (application_id, language_id) VALUES (?, ?)");
    foreach ($languages as $l_id) {
        $stmt_l->execute([$id, $l_id]);
    }
    
    setcookie('save_success', 'Данные успешно обновлены', time() + 24*3600);
    if (isset($_SESSION['admin_mode'])) {
        unset($_SESSION['admin_mode']);
        unset($_SESSION['user_id']);
        header("Location: admin.php");
    } else {
        header("Location: index.php");
    }
} else {
    $login = 'user' . rand(1000, 9999);
    $pass = bin2hex(random_bytes(4));
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO applications (fio, phone, email, birth_date, gender, biography, login, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$fio, $phone, $email, $birth, $gender, $bio, $login, $hash]);
    $id = $pdo->lastInsertId();

    $stmt_l = $pdo->prepare("INSERT INTO application_languages (application_id, language_id) VALUES (?, ?)");
    foreach ($languages as $l_id) {
        $stmt_l->execute([$id, $l_id]);
    }

    setcookie('save_success', "Регистрация успешна! Логин: $login, Пароль: $pass", time() + 24*3600);
    header("Location: index.php");
}
