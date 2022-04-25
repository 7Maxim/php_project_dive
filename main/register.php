<?php
session_start();
require_once 'function.php';


$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);
$hashed_password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);


$user = get_user_by_email($email);

if (!empty($user['email'])) {
    set_flash_message('danger', "Пользователь с почтой $email уже зарегистрирован");
    redirect_to('../page_register.php');
    exit();
}

add_user($email, $hashed_password);

set_flash_message('success', "Пользователь $email успешно зарегистрирован");

redirect_to('../users.php');
