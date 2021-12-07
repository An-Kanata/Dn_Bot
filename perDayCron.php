<?php
require "db.php";
/**@var $connection*/
$mysqltime = date('Y-m-d', time());
$mysqltime2 = date('Y-m-d', time() - 24 * 60 * 60);
$command = "SELECT count(*) as cnt,type
  FROM commonLog 
 WHERE date_ >= '{$mysqltime2} 00:00:00' 
   AND date_ <= '{$mysqltime} 00:00:00' group by type";
$equips = mysqli_query($connection, $command);
//echo '<pre>';print_r($command); echo '</pre>';

while ($result = mysqli_fetch_assoc($equips)) {
    $type = $result['type'];
    $count_ = $result['cnt'];
    $command = "insert into logPerDay (date_, type, count_) values ('{$mysqltime2}', '{$type}', '{$count_}')";
    $tot = mysqli_query($connection, $command);
//    echo '<pre>';
//    print_r($connection);
//    echo '</pre>';
}