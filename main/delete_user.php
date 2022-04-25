<?php
session_start();

require_once 'function.php';

if (is_not_logged()) {
    redirect_to('../page_login.php');
}

if (empty($_GET['id'])) {
    redirect_to('../users.php');
}

$logged_user = get_user_by_email($_SESSION['email']);
$edit_user_id = $_GET['id'];

if ($logged_user['role'] !== 'admin') {
    if (!is_author($logged_user['id'], $edit_user_id)) {
        set_flash_message('info', 'У вас нет прав на удаление пользователей');
        redirect_to('../users.php');
    }
}

$edit_user = get_user_by_id($edit_user_id);

if (empty($edit_user['id'])) {
    set_flash_message('info', 'Такого пользователя не существует');
    redirect_to('../users.php');
}


echo '<pre>';
print_r($edit_user);


/*
if ($edit_user['image'] !== 'avatar-m.png') {
    deleteImg('../img/uploads/', $edit_user['image']);
    echo "1";
}


exit();
*/


if (is_author($logged_user['id'], $edit_user_id)) {

    if ($edit_user['image'] !== 'avatar-m.png') {
        deleteImg('../img/uploads/', $edit_user['image']);
    }

    delete($edit_user_id);
    unset($_SESSION['email']);
    redirect_to('../page_register.php');

}


if ($edit_user['image'] !== 'avatar-m.png') {
    deleteImg('../img/uploads/', $edit_user['image']);
}

delete($edit_user['id']);

set_flash_message('info', 'Пользователь удален');
redirect_to('../users.php');