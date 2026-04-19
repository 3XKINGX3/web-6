<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма регистрации</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; }
        h2 { margin-top: 0; color: #333; text-align: center; }<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма регистрации</title>
    <style>
        .error { border: 2px solid red; }
        .msg { padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; background: #f9f9f9; }
    </style>
</head>
<body>

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
        label { font-weight: 600; display: block; margin-bottom: 8px; color: #555; }
        input[type="text"], input[type="email"], input[type="date"], select, textarea { width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 14px; }
        input.error, select.error { border-color: #dc3545; background-color: #fff8f8; }
        .gender-group { margin-bottom: 20px; }
        .gender-group input { margin-right: 5px; }
        .gender-group label { display: inline; font-weight: normal; margin-right: 15px; }
        textarea { height: 80px; resize: vertical; }
        .contract-group { margin-bottom: 25px; display: flex; align-items: center; }
        .contract-group input { margin-right: 10px; }
        button { width: 100%; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background 0.3s; }
        button:hover { background-color: #218838; }
        .msg { padding: 12px; margin-bottom: 20px; border-radius: 6px; text-align: center; }
        .msg-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .logout-btn { display: block; text-align: center; margin-top: 15px; color: #dc3545; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Анкета</h2>

    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $m): ?>
            <div class="msg msg-success"><?= $m ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <form action="index.php" method="POST">
        <label>ФИО:</label>
        <input type="text" name="fio" value="<?= htmlspecialchars($values['fio'] ?? '') ?>" <?= !empty($errors['fio']) ? 'class="error"' : '' ?>>

        <label>Телефон:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($values['phone'] ?? '') ?>" <?= !empty($errors['phone']) ? 'class="error"' : '' ?>>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($values['email'] ?? '') ?>" <?= !empty($errors['email']) ? 'class="error"' : '' ?>>

        <label>Дата рождения:</label>
        <input type="date" name="birth_date" value="<?= htmlspecialchars($values['birth_date'] ?? '') ?>" <?= !empty($errors['birth']) ? 'class="error"' : '' ?>>

        <label>Пол:</label>
        <div class="gender-group">
            <input type="radio" name="gender" value="M" id="male" <?= ($values['gender'] ?? '') == 'M' ? 'checked' : '' ?>>
            <label for="male">М</label>
            <input type="radio" name="gender" value="F" id="female" <?= ($values['gender'] ?? '') == 'F' ? 'checked' : '' ?>>
            <label for="female">Ж</label>
        </div>

        <label>Языки программирования:</label>
        <select name="languages[]" multiple="multiple" <?= !empty($errors['languages']) ? 'class="error"' : '' ?>>
            <?php 
            $langs = [1=>'Pascal', 2=>'C', 3=>'C++', 4=>'JavaScript', 5=>'PHP', 6=>'Python', 7=>'Java', 8=>'Haskell', 9=>'C#'];
            foreach ($langs as $id => $name): ?>
                <option value="<?= $id ?>" <?= in_array($id, $values['languages'] ?? []) ? 'selected' : '' ?>><?= $name ?></option>
            <?php endforeach; ?>
        </select>

        <label>Биография:</label>
        <textarea name="biography"><?= htmlspecialchars($values['biography'] ?? '') ?></textarea>

        <div class="contract-group">
            <input type="checkbox" name="contract" id="contract" <?= !empty($values['contract']) ? 'checked' : '' ?> value="y">
            <label for="contract">Согласен с условиями</label>
        </div>

        <button type="submit"><?= isset($_SESSION['user_id']) ? 'Обновить данные' : 'Отправить' ?></button>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="index.php?logout=1" class="logout-btn">Выйти (ID: <?= $_SESSION['user_id'] ?>)</a>
        <?php endif; ?>
    </form>
</div>

</body>
</html>
