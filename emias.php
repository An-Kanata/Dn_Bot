<html>
<head>
    <meta charset="UTF-8">
    <link type="Image/x-icon" href="/fvc.jpg" rel="icon">
    <title>Теперь тут ЕМИАС</title>
</head>
<style>
    body {
        background-image: url("body.png"); /* Путь к фоновому изображению */
        background-color: #202020; /* Цвет фона */
        color: #FFFFFF;
        font-family: TeX Gyre Adventor, URW Gothic L;
    }

    .key {
        width: 20%;
        display: inline-block;
    }

    .elem {
        border: 1px solid white;
        padding: 5px 15px;
    }
</style>
Теперь тут поиск в выгрузке ЕМИАС по СНИЛС.
<br>
<br>
СНИЛС вводить в формате ***-***-*** ** (рекомендую вводить хотябы первые 5-6 символов)
<br>
<br>
<form method="post">
    <label for="">СНИЛС:</label>
    <input type="text" name="snils">
    <input type="submit" name="submit">
</form>
<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
$snils = $_POST['snils'];
require 'vendor/autoload.php';
$handle = fopen('emias.csv', 'r');
if (!$handle) {
    echo '<pre>';
    print_r(123);
    echo '</pre>';
}
$row = 0;
if (($keys = fgetcsv($handle, 0, ';')) !== FALSE) {
}
echo "<br />\n";
while (($data = fgetcsv($handle, 0, ';')) !== FALSE) : ?>
<?php
$count=0;
if (strpos($data[0], $snils) !== false) : ?>
<?php $count++ ?>
    <div class='elem'> <?php
        $num = count($data);
        for ($c = 0; $c < $num; $c++) : ?>
            <span class='key'><?= $keys[$c] ?></span>  ->   <span class="data"><?= $data[$c] ?></span> <br/>
        <?php endfor; ?>
    </div>
<?php endif ?>
<?php endwhile;
fclose($handle);
if ($count==0){
    echo ('Ничего не найдено');
}
