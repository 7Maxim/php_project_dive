<?php
session_start();
require_once 'function.php';

$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);


$is_login = login($email, $password);

if ($is_login) {
    redirect_to('../users.php');
    exit();
}

redirect_to('../page_login.php');
