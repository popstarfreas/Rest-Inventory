<?php
/**
 * Created by PhpStorm.
 * Author: popstarfreas (https://dark-gaming.com/profile/popstarfreas)
 * Date: 26/12/14
 * Time: 20:09
 */

if(!defined('index')) exit;

//DB Connect
$db = array(
    "name" => "database_name",
    "host" => "127.0.0.1",
    "user" => "mysql_login",
    "pass" => "mysql_password"
);

// Table Name
$tableName = 'InventoryBackgrounds';

try {
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['name'], $db['user'], $db['pass'], array(PDO::ATTR_TIMEOUT => "3"));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('SET NAMES "utf8"');
} catch (PDOException $e) {
    echo 'Database Error. Try again later.';
    echo $e;
}

$background = null;
if (isset($player['info']['position'])) {
    $pos = explode(',', $player['info']['position']);
    try {
        $query = "SELECT COUNT(*), image
                  FROM $tableName;
                  WHERE :pos0 >= TL_X AND :pos1 >= TL_Y AND :pos0 <= BR_X AND :pos1 <= BR_Y";
        $result = $pdo->prepare($query);
        $result->bindValue(':pos0', $pos[0]);
        $result->bindValue(':pos1', $pos[1]);
        $result->execute();
    } catch (PDOException $e) {

    }

    foreach ($result as $row) {
        if ($row['COUNT(*)'] > 0) {
            $background = "img/".$row['image'];
        } else {
            $background = 'backgrounds/6.png';
        }
    }

}