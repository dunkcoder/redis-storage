--TEST--
phpiredis command binary safe using image
--SKIPIF--
<?php include 'skipif.inc'; if (!function_exists('imagecreate') || !function_exists('imagepng')) die('Need GD library to run this test'); ?>
--FILE--
<?php
require_once('connect.inc');
$test = '';

$host = '127.0.0.1';
if (!$link = my_phpiredis_connect($host))
        printf("[001] Cannot connect to the server using host=%s\n",
                $host);

ob_start();
$im = imagecreate(200,200);
imagecolorallocate($im,23,123,51);
imagepng($im);
$data = ob_get_contents();
ob_end_clean();

phpiredis_command_bs($link, array('DEL','test'));
phpiredis_command_bs($link, array('SET','test', $data));
$response = phpiredis_command_bs($link, array('GET','test'));
var_dump($response == $data);
?>
--EXPECTF--
bool(true)
