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

if (!($logged_user['role'] === 'admin')) {
    if (!is_author($logged_user['id'], $edit_user_id)) {
        set_flash_message('info', 'Можно редактировать только свой профиль');
        redirect_to('users.php');
    }
}


$username = htmlspecialchars($_POST['username']);
$company = htmlspecialchars($_POST['company']);
$phone = htmlspecialchars($_POST['phone']);
$address = htmlspecialchars($_POST['address']);


edit_info($edit_user_id, $username, $company, $phone, $address);

set_flash_message('success', "Профиль успешно обновлен");
redirect_to('../page_edit.php?id=' . $edit_user_id);

