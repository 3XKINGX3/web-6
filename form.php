<!DOCTYPE html>
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
