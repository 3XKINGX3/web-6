<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма регистрации</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; }
        h2 { margin-top: 0; color: #333; text-align: center; }
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
