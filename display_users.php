<?php
/**
 * Created by PhpStorm.
 * Author: popstarfreas (https://dark-gaming.com/profile/popstarfreas)
 * Date: 26/12/14
 * Time: 20:19
 */
if (!defined('index')) exit;

// construct player list
$list = '<div id="players">';
foreach ($player['list'] as $player) {
    if (is_array($player)) {
        foreach ($player as $p) {
            if (!empty($p) && $p['nickname'] != "") $list .= '<a href="?player=' . str_replace('#', '%23', $p['nickname']) . '">' . $p['nickname'] . '</a><br />';
        }
    }
}
$list .= '</div>';

?>
<!DOCTYPE html>
<html>
<head profile="http://www.w3.org/2005/10/profile">
    <link rel="icon"
          type="image/png"
          href="favicon.png">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic' rel='stylesheet' type='text/css'/>
    <link rel="stylesheet" href="styles.css" type="text/css"/>
    <title>T-Inv</title>
</head>
<body id="users">
<form action="" method="GET">
    <input type="text" name="player"/>
    <input type="submit" value="Read"/>
</form>
<?php echo $list ?>
</body>
</html>
