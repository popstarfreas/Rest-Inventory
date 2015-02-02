<?php
/**
 * Created by PhpStorm.
 * Author: popstarfreas (https://dark-gaming.com/profile/popstarfreas)
 * Date: 26/12/14
 * Time: 20:56
 * This file extracts the zip in items_images
 */

if (!defined('index')) exit;
// File
$file = 'items_images/items.zip';

// output for index.php
$setup = false;

// get the absolute path to $file
$path = pathinfo(realpath($file), PATHINFO_DIRNAME);

$zip = new ZipArchive;
$res = $zip->open($file);
if ($res === TRUE) {
// extract it to the path we determined above
    $zip->extractTo($path);
    $zip->close();

// Setup has completed
    $setup = true;
    
// Delete zip
    unlink('items_images/items.zip');
}