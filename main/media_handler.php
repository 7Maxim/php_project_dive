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


if ( !empty($_FILES['avatar']['name']) ) {
    $newFile = upload_file($_FILES['avatar']['name'], $_FILES['avatar']['tmp_name']);
    set_avatar($edit_user_id, $newFile);

    set_flash_message('success', 'Профиль обновлен. Аватар успешно загружен');
    redirect_to('../page_profile.php?id=' . $edit_user_id);

}


