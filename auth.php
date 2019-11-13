<?php
$dir = sys_get_temp_dir();
session_save_path($dir);
session_start();
if(!isset($_SESSION["username"])){
header("Location: login.php");
exit(); }
?>