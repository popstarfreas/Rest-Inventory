<?php
/**
 * Created by PhpStorm.
 * Author: popstarfreas (https://dark-gaming.com/profile/popstarfreas)
 * Date: 26/12/14
 * Time: 20:04
 */

// Define index so that files included will process
define('index', 1);

// Run setup if exists
if(file_exists('setup.php')) {
    include_once 'setup.php';
    if ($setup)
        unlink('setup.php');
    else
        exit('Setup was unable to complete');
}

// Set file_get_contents timeout
$ctx = stream_context_create(array(
        'http' => array(
            'timeout' => 0.8
        )
    )
);

// Run settings setup if settings aren't set
if(!file_exists('settings.php')) {
    include_once 'setup_settings.php';
    if($setup)
        unlink('setup_settings.php');
    else
        exit('Settings setup was unable to complete');
}

include_once 'settings.php';

// Config
if (!file_exists('config.php')) {
    $data = "
    <?php
    \$config = array(
        'display_position' => true,
        'display_group' => false,
        'display_ip' => false
    );
    ";
    file_put_contents('config.php', $data);
}

include_once 'config.php';

/* If you want a background image, you can use these variables to set one
   $rand gets a random number, and then displays the image with the name as that number
   default:
   $useBG = true;
   $rand = rand(1,3);
   $defaultBG = 'default.png';
*/
$useBG = true;
$rand = rand(1,3);
$defaultBG = "backgrounds/$rand.jpg";

// Initial variable assignments
$player['GET'] = null;
$location = "$ip:$port";

// If no player is specified, list players online
if (!isset($_GET['player'])) {
    $token = json_decode(@file_get_contents("http://$location/token/create/$rest_user/$rest_pass", 0, $ctx));

    if (isset($token->token)) {
        $player['list'] = json_decode(@file_get_contents("http://$location/v2/players/list?token=" . $token->token), true);
        $status = json_decode(@file_get_contents("http://$location/v2/server/status?token=" . $token->token), true);
        $player['count'] = $status['playercount'];
    } else {
        exit('Server failed to respond.');
    }

    if (!empty($player['list']))
        include_once 'display_users.php';
    else
        echo 'Unable to display user list';
            
    exit;
}

// Remove spaces
$player['GET'] = str_replace(' ', '%20', $_GET['player']);

// Grab a token
$token = json_decode(@file_get_contents("http://$location/token/create/$rest_user/$rest_pass", 0, $ctx));
if (isset($token->token)) {
    // Run the command
    $player['info'] = json_decode(@file_get_contents("http://$location/v3/players/read?token=" . $token->token . '&player=' . $player['GET']), true);

    // Check player is on server
    if(!isset($player['info']['inventory'])) {
        exit('Player is not on the server');
    }
} else {
    exit('Server failed to respond.');
}

$background = $defaultBG;

include_once 'display_inv.php';
