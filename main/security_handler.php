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
        set_flash_message('info', 'Можно редактировать только свой профиль');
        redirect_to('../users.php');
    }
}

$edit_user = get_user_by_id($edit_user_id);

if (empty($edit_user['id'])) {
    set_flash_message('info', 'Такого пользователя не существует');
    redirect_to('../users.php');
}


$new_email = $_POST['email'];
$new_password = $_POST['password'];

$new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);



$users = select_all_users();

foreach ($users as $user) {
    if ($user['email'] === $new_email) {

        if ($user['email'] === $edit_user['email']) {
            break;
        }

        set_flash_message('danger', "Пользователь с почтой $new_email уже сущестует");
        redirect_to('../security.php?id=' . $edit_user['id']);

    }
}

update_email($edit_user_id, $new_email);

if (!empty($new_password)) {
    update_password($edit_user_id, $new_hashed_password);
}



//edit_credentials($edit_user['id'], $new_email, $new_hashed_password);

set_flash_message('success', 'Профиль успешно обновлен');


if (is_author($logged_user['id'], $edit_user_id)) {
    $_SESSION['email'] = $new_email;
}


redirect_to('../page_profile.php?id=' . $edit_user['id']);

