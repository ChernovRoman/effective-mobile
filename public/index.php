<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set("display_errors", "on");


require_once "../Core/App.php";

$read_file = WorkingWithFile::readFile();


switch (true) {
    case isset($_POST['del']):
        try {
            TelephoneList::getInstance()->deleteEntry((int)$_POST['del']);
            WorkingWithFile::writeFile(TelephoneList::getInstance());
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
    <link rel="stylesheet" href="" />
    <title>Тестовое задание</title>
    <style>

    </style>
</head>

<body>
    <section>
        <h1>Мини-проект "Телефонный справочник"</h1>
        <p><a href="formsave.php">Добавить запись</a></p>
        <p><?php echo $info ?? ''; ?></p>
        <?php foreach ($read_file->data as $key => $obj) : ?>
            <div class="app">
                <div class="welcome" style="width: 100%;">
                    <div style="width: 46%"><?php echo $obj->name; ?></div>

                    <div style="width: 36%"><?php echo $obj->phone; ?></div>

                    <div style="width: 18%">
                        <form method="post" action="">
                            <button type="submit" name="del" value="<?php echo $obj->id; ?>">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</body>

</html>