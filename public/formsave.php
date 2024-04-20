<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set("display_errors", "on");

require_once "../Core/App.php";

$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';

switch (true) {
    case isset($_POST['save']):
        try {
            $list = WorkingWithFile::readFile();

            $id = WorkingWithFile::generateId($list);
            $phone = WorkingWithFile::validatePhone($phone, $list);
            $entry = new PhoneNumber($id, $name, $phone);

            TelephoneList::getInstance()->addEntry($entry);
            WorkingWithFile::writeFile(TelephoneList::getInstance());

            header("Location: /");
        } catch (DomainException | TypeError $th) {
            $info = $th->getMessage();
        }

        break;
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" href=""/>
    <title>Тестовое задание</title>
    <style>
        .welcome {
            display: block;
        }
    </style>
</head>
<body>
<section>

<div class="app">
    <form method="post" action="" class="welcome">
    <h1>Добавить запись</h1>
        <input type="text" name="name" size="30" placeholder="Имя контакта" required value="<?php echo $name ?>">
        <input type="text" name="phone" size="12" maxlength="11" pattern="[7]{1}[0-9]{10}" placeholder="7ХХХХХХХХХХ" required value="<?php echo $_POST['phone'] ?? '' ?>">
        <button type="submit" name="save">Добавить</button>
        <p><?php echo $info ?? ''; ?></p>
    </form>
    
</div>

</section>
</body>
</html>
