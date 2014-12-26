<?php
/**
 * Created by PhpStorm.
 * Author: popstarfreas (https://dark-gaming.com/profile/popstarfreas)
 * Date: 26/12/14
 * Time: 21:11
 */

if (!defined('index')) exit;

function handle_form($post, $ctx)
{
    $errors = array();
    $ip = $post['ip'];
    $port = $post['port'];
    $rest_user = $post['username'];
    $rest_pass = $post['password'];

    if(empty($ip)) {
        $errors[] = "IP cannot be empty";
    }

    if(empty($port)) {
        $errors[] = "Port cannot be empty";
    }

    if(empty($rest_user)) {
        $errors[] = "Username cannot be empty";
    }

    if(!empty($errors)) return $errors;

    $location = "$ip:$port";
    // Test Connection
    $response = json_decode(file_get_contents("http://$location/token/create/$rest_user/$rest_pass", 0, $ctx));

    if (empty($response)) {
        $errors[] = 'Failed to connect to ' . $location;
    } else {
        if ($response->status == "401") {
            $errors[] = 'Invalid username/password combination';
        }

        if ($response->status == "200") {
            // Now we're going to setup ip, port and password
            $contents =
                "<?php
                 \$ip = '$ip';
                 \$port = '$port';
                 \$rest_user = '$rest_user';
                 \$rest_pass = '$rest_pass';
                 ";

            file_put_contents('settings.php', $contents);
        }
    }

    return $errors;
}

$setup = false;
$form = true;

// Check values are correct and then test connection
if (!empty($_POST['ip'])) {
    $errors = handle_form($_POST, $ctx);

    if (empty($errors)) {
        $form = false;
        $setup = true;
    }
}

if ($form) {
// Error list
    if (!empty($errors)) {
        $list = "";
        foreach ($errors as $e) {
            $list .= '<li>' . $e . '</li>';
        }
        echo "<ul>$list</ul>";
    }

    $ip = isset($_POST['ip']) ? $_POST['ip'] : '';
    $port = isset($_POST['port']) ? $_POST['port'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    echo 'Please enter the values for the REST API access
          <form action="" method="POST">
            IP: <input type="text" name="ip" placeholder="127.0.0.l" value="'.$ip.'"/> <br />
            Port: <input type="text" name="port" placeholder="7878" value="'.$port.'"/> <br />
            Username: <input type="text" name="username" placeholder="RestUser" value="'.$username.'"/> <br />
            Password: <input type="password" name="password" value="'.$password.'"/> <br />
            <input type="submit" value="Submit" />
          </form>';
    exit;
}
