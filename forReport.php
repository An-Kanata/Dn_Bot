<script src="jquery-3.6.0.min.js"></script>
<script src="scrip.js"></script>
<style>
    input[type="date"]::-webkit-calendar-picker-indicator {
        background: transparent;
        bottom: 0;
        color: transparent;
        cursor: pointer;
        height: auto;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        width: auto;
    }
    .ul input[type="date"]{
        box-sizing: border-box;
        outline: 0;
        padding: .75rem;
        position: relative;
    }
    .ul {
        margin-left: 5%; /* Отступ слева */
    }
    body {
        background-image: url("body.png"); /* Путь к фоновому изображению */
        background-color: #202020; /* Цвет фона */
        color: #FFFFFF;
        font-family: TeX Gyre Adventor, URW Gothic L;
    }

    .th {
        border-bottom: 1px solid; /* Параметры границы */
        margin-left: -2px;
    }
    th{
        padding: 10px;
    }
    form {
        height: 25px;
        display: flex;
    }

    input {
        background: none;
        border: 1px solid white;
        color: white;
        line-height: 100%;
        height: 100%;
        margin-left: 5px;
        margin-right: 5px;
        border-radius: 5px;
    }
    /*input::-webkit-calendar-picker-indicator {*/
    /*    background-color: #FFFFFF;*/
    /*    margin-right: 1px;*/
    /*    border-radius: 5px;*/
    /*}*/

    .div1 {
        vertical-align: middle;
        display: flex;
        justify-content: space-around;
    }

    .div1 > div:first-child{
        width: 50%;

    }
    .div2 {
        vertical-align: middle;
        display: flex;
    }
    table {
        width: 100%;
    }
    a {
        color: black;
    }
</style>
<head>
    <link type="Image/x-icon" href="/fvc.jpg" rel="icon">
    <title>Отчёт бот-ВК</title>
</head>
<form method="post" action="" class="ul">
    <label for="">От</label>
    <input type="date" name="from">
    <label for="">До</label>
    <input type="date" name="to">
    <input type="submit" name="submit">
</form>
    </div>
<?php
require "db.php";
/**@var $connection*/
$all = 0;
if ($_POST['from']!='') {
    $dateFrom = date('Y-m-d', strtotime($_POST['from']));
} else {
    $dateFrom = date('Y-m-d', 0);
}
if ($_POST['to']!='') {
    $dateTo = date('Y-m-d', strtotime($_POST['to']));
} else {
    $dateTo = date('Y-m-d', time()-86400);
}
$command = "select res.sum_,res.type,types.name from(SELECT sum(count_) as sum_,type
  FROM logPerDay 
 WHERE date_ >= '{$dateFrom}' 
   AND date_ <= '{$dateTo}' group by type) as res
   right join types on res.type=types.id";
$equips = mysqli_query($connection, $command);
//echo '<pre>';print_r($connection); echo '</pre>';
?>
<div class="div1">
    <div>
        <table>
            <tr>
                <th class="brd">Тип запроса</th>
                <th class="brd">Количество</th>
            </tr>
            <?php
            $arr = array();
            while ($result = mysqli_fetch_assoc($equips)):
//echo '<pre>';print_r($result); echo '</pre>';
                $arr[$result['name']] = $result['sum_'];
                $all = $all + $result['sum_'] ?>
                <tr>
                    <th class="th"><?= $result['name'] ?></th>
                    <th class="th"><?= $result['sum_'] == '' ? '0' : $result['sum_'] ?></th>
                </tr>
            <?php endwhile;
            arsort($arr) ?>
            <tr>
                <th class="brd">Всего:</th>
                <th class="brd"><?= $all ?></th>
            </tr>
        </table>
        <br>
        * Сейчас показана информация за время: <?= $dateFrom ?> -- <?= $dateTo ?>
        <?php
        if (strtotime($dateTo) < strtotime($dateFrom)):?>
            (дата начала позже даты окончания).
        <?php endif?>
        <br>
        ** Информация обновляется в 00:00 каждый день.
    </div>
    <div>
        Категории по популярности, в выбранном временном промежутке:
        <br>
        <br>
        <table class="tb">
            <tr>
                <?php
                if($all == 0){$all = 1;}
                foreach ($arr
                as $key => $value):
                ?>
            <tr>
                <th><?= $key ?></th>
                <th><?= round(($value/$all)*100, 2) ?>%</th>
            </tr>
            <?php
            endforeach ?>
        </table>
    </div>
</div>
<br>
<div>
    <a href="http://91.240.87.245/emias.php" color="#000000">Здась кто-нибудь есть?</a>
</div>





