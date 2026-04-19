<?php
$values = $values ?? [];
$errors = $errors ?? [];
$messages = $messages ?? [];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
    <title>Задание 6</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма регистрации</title>
<style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; display: flex; justify-content: center; padding: 40px 20px; }
        .container { background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 100%; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .auth-btn { text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 14px; font-weight: 500; }
        .login-btn { color: #007bff; border: 1px solid #007bff; }
        .logout-btn { color: #dc3545; border: 1px solid #dc3545; }
        .field { margin-bottom: 18px; }
        label { display: block; font-weight: 600; margin-bottom: 8px; color: #444; }
        input[type="text"], input[type="email"], input[type="date"], select, textarea { 
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; 
        }
        .error-field { border-color: #dc3545 !important; background: #fff8f8; }
        .error-text { color: #dc3545; font-size: 12px; margin-top: 5px; font-weight: 500; }
        .msg-box { padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; line-height: 1.5; }
        .msg-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        button[type="submit"] { 
            width: 100%; padding: 14px; background: #28a745; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s; 
        }
        button:hover { background: #218838; }
        .radio-group { display: flex; gap: 20px; }
        .error { border: 2px solid red; }
        .msg { padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; background: #f9f9f9; }
</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span style="font-size: 18px; font-weight: bold;">Анкета</span>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="?logout=1" class="auth-btn logout-btn">Выйти (ID: <?= $_SESSION['user_id'] ?>)</a>
            <?php else: ?>
                <a href="login.php" class="auth-btn login-btn">Войти</a>
            <?php endif; ?>
        </div>
        <form method="POST">
            <?php foreach ($messages as $msg): ?>
                <div class="msg-box msg-success"><?= $msg ?></div>
            <?php endforeach; ?>
            <div class="field">
                <label>ФИО</label>
                <input name="fio" value="<?= htmlspecialchars($values['fio'] ?? '') ?>" class="<?= isset($errors['fio'])?'error-field':'' ?>">
                <?php if(isset($errors['fio'])): ?><div class="error-text"><?= htmlspecialchars($_COOKIE['fio_error'] ?? '') ?></div><?php endif; ?>
            </div>
            <div class="field">
                <label>Телефон</label>
                <input name="phone" value="<?= htmlspecialchars($values['phone'] ?? '') ?>" class="<?= isset($errors['phone'])?'error-field':'' ?>">
                <?php if(isset($errors['phone'])): ?><div class="error-text"><?= htmlspecialchars($_COOKIE['phone_error'] ?? '') ?></div><?php endif; ?>
            </div>
            <div class="field">
                <label>Email</label>
                <input name="email" type="email" value="<?= htmlspecialchars($values['email'] ?? '') ?>" class="<?= isset($errors['email'])?'error-field':'' ?>">
                <?php if(isset($errors['email'])): ?><div class="error-text"><?= htmlspecialchars($_COOKIE['email_error'] ?? '') ?></div><?php endif; ?>
            </div>
            <div class="field">
                <label>Дата рождения</label>
                <input name="birth_date" type="date" value="<?= htmlspecialchars($values['birth_date'] ?? '') ?>" class="<?= isset($errors['birth'])?'error-field':'' ?>">
                <?php if(isset($errors['birth'])): ?><div class="error-text"><?= htmlspecialchars($_COOKIE['birth_error'] ?? '') ?></div><?php endif; ?>
            </div>
            <div class="field">
                <label>Пол</label>
                <div class="radio-group">
                    <label><input type="radio" name="gender" value="male" <?= ($values['gender'] ?? '') == 'male' ? 'checked' : '' ?>> М</label>
                    <label><input type="radio" name="gender" value="female" <?= ($values['gender'] ?? '') == 'female' ? 'checked' : '' ?>> Ж</label>
                </div>
                <?php if(isset($errors['gender'])): ?><div class="error-text"><?= htmlspecialchars($_COOKIE['gender_error'] ?? 'Выберите пол') ?></div><?php endif; ?>
            </div>
            <div class="field">
                <label>Языки программирования</label>
                <select name="languages[]" multiple size="6" class="<?= isset($errors['languages'])?'error-field':'' ?>">
                    <?php foreach ($langs as $id => $name): ?>
                        <option value="<?= $id ?>" <?= in_array($id, $values['languages'] ?? []) ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if(isset($errors['languages'])): ?><div class="error-text"><?= htmlspecialchars($_COOKIE['languages_error'] ?? '') ?></div><?php endif; ?>
            </div>
            <div class="field">
                <label>Биография</label>
                <textarea name="biography" rows="4" class="<?= isset($errors['bio'])?'error-field':'' ?>"><?= htmlspecialchars($values['biography'] ?? '') ?></textarea>
                <?php if(isset($errors['bio'])): ?><div class="error-text"><?= htmlspecialchars($_COOKIE['bio_error'] ?? '') ?></div><?php endif; ?>
            </div>
            <div class="field">
                <label style="font-weight: normal; font-size: 14px;">
                    <input type="checkbox" name="contract" <?= !empty($values['contract']) ? 'checked' : '' ?>> Согласен с условиями
                </label>
                <?php if(isset($errors['contract'])): ?><div class="error-text"><?= htmlspecialchars($_COOKIE['contract_error'] ?? '') ?></div><?php endif; ?>
            </div>
            <button type="submit"><?= isset($_SESSION['user_id']) ? 'Обновить данные' : 'Отправить' ?></button>
        </form>
    </div>

<?php
if (!empty($messages)) {
  print('<div id="messages">');
  foreach ($messages as $message) {
    print('<div class="msg">' . $message . '</div>');
  }
  print('</div>');
}
?>

<form action="index.php" method="POST">
    <label>ФИО:</label><br>
    <input name="fio" value="<?php print $values['fio']; ?>" <?php if (!empty($errors['fio'])) { print 'class="error"'; } ?>><br>

    <label>Телефон:</label><br>
    <input name="phone" value="<?php print $values['phone']; ?>" <?php if (!empty($errors['phone'])) { print 'class="error"'; } ?>><br>

    <label>Email:</label><br>
    <input name="email" value="<?php print $values['email']; ?>" <?php if (!empty($errors['email'])) { print 'class="error"'; } ?>><br>

    <label>Дата рождения:</label><br>
    <input type="date" name="birth_date" value="<?php print $values['birth_date']; ?>" <?php if (!empty($errors['birth'])) { print 'class="error"'; } ?>><br>

    <label>Пол:</label><br>
    <input type="radio" name="gender" value="M" <?php if ($values['gender'] == 'M') { print 'checked'; } ?>> М
    <input type="radio" name="gender" value="F" <?php if ($values['gender'] == 'F') { print 'checked'; } ?>> Ж<br>

    <label>Любимый язык программирования:</label><br>
    <select name="languages[]" multiple="multiple" <?php if (!empty($errors['languages'])) { print 'class="error"'; } ?>>
        <option value="1" <?php if(in_array('1', $values['languages'])) echo 'selected'; ?>>Pascal</option>
        <option value="2" <?php if(in_array('2', $values['languages'])) echo 'selected'; ?>>C</option>
        <option value="3" <?php if(in_array('3', $values['languages'])) echo 'selected'; ?>>C++</option>
        <option value="4" <?php if(in_array('4', $values['languages'])) echo 'selected'; ?>>JavaScript</option>
        <option value="5" <?php if(in_array('5', $values['languages'])) echo 'selected'; ?>>PHP</option>
        <option value="6" <?php if(in_array('6', $values['languages'])) echo 'selected'; ?>>Python</option>
        <option value="7" <?php if(in_array('7', $values['languages'])) echo 'selected'; ?>>Java</option>
        <option value="8" <?php if(in_array('8', $values['languages'])) echo 'selected'; ?>>Haskell</option>
        <option value="9" <?php if(in_array('9', $values['languages'])) echo 'selected'; ?>>C#</option>
    </select><br>

    <label>Биография:</label><br>
    <textarea name="biography"><?php print $values['biography']; ?></textarea><br>

    <input type="checkbox" name="contract" <?php if ($values['contract']) { print 'checked'; } ?> value="y"> С контрактом ознакомлен<br>

    <button type="submit">Отправить</button>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="index.php?logout=1">Выйти</a>
    <?php endif; ?>
</form>

</body>
</html>
