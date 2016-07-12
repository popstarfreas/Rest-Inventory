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

// Gets new token and caches
function getNewToken() {
    $token = null;
    $response = json_decode(@file_get_contents("http://$location/v2/token/create?username=".urlencode($rest_user)."&password=".urlencode($rest_pass), 0, $ctx));
    if (isset($response->token)) {
        $token = $response->token;
        file_put_contents('token', $token);
    }

    return $token;
}

// Get cached token
$token = file_get_contents('token');
if (strlen($token) === 0) {
    $token = getNewToken();

    if ($token === null) {
        exit('Could not get token.');
    }
}

// If no player is specified, list players online
if (!isset($_GET['player'])) {
    $response = json_decode(@file_get_contents("http://$location/v2/players/list?token=" . $token), true);
    if ($response->status === "403") {
        $token = getNewToken();
        $response = json_decode(@file_get_contents("http://$location/v2/players/list?token=" . $token), true);
    }

    $player['list'] = $response;
    $status = json_decode(@file_get_contents("http://$location/v2/server/status?token=" . $token), true);
    $player['count'] = $status['playercount'];

    if (!empty($player['list']))
        include_once 'display_users.php';
    else
        echo 'Unable to display user list';
        
    exit;
}

// Remove spaces
$player['GET'] = str_replace(' ', '%20', $_GET['player']);
$player['GET'] = str_replace('#', '%23', $player['GET']);

// Grab a token
$response = json_decode(@file_get_contents("http://$location/v2/token/create?username=".urlencode($rest_user)."&password=".urlencode($rest_pass), 0, $ctx));
if (isset($response->token)) {
    // Run the command
    $response = json_decode(@file_get_contents("http://$location/v3/players/read?token=" . $response->token . '&player=' . $player['GET']), true);
    if ($response->status === "403") {
        $token = getNewToken();
        $response = json_decode(@file_get_contents("http://$location/v3/players/read?token=" . $response->token . '&player=' . $player['GET']), true);
    }

    $player['info'] = $response;

    // Check player is on server
    if(!isset($player['info']['inventory'])) {
        echo 'Player is not on the server';
    } else {
        $background = $defaultBG;
        include_once 'display_inv.php';
    }
} else {
    exit('Server failed to respond.');
}
