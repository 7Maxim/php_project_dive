<?php
session_start();

require_once 'function.php';

if (is_not_logged()) {
    redirect_to('page_login.php');
}

$user_logged = get_user_by_email($_SESSION['email']);

if ($user_logged['role'] !== 'admin') {
    redirect_to('page_login.php');
}




$email = htmlspecialchars($_POST['user_email']);
$password = $_POST['user_password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT );

$username = htmlspecialchars($_POST['user_name']);
$job_title = htmlspecialchars($_POST['user_job']);
$phone = htmlspecialchars($_POST['user_phone']);
$address = htmlspecialchars($_POST['user_address']);

$status = $_POST['user_status'];

$user_social_telegram = htmlspecialchars($_POST['user_social_telegram']);
$user_social_instagram = htmlspecialchars($_POST['user_social_instagram']);
$user_social_vk = htmlspecialchars($_POST['user_social_vk']);

$users = select_all_users();


foreach ($users as $user) {
    if ($email === $user['email']) {
        set_flash_message('danger', "Пользователь  c почтой $email уже есть в базе");
        redirect_to('../create_user.php');
    }

}

$new_user_id = add_user($email, $hashed_password);

edit_info($new_user_id, $username, $job_title, $phone, $address);

set_status($new_user_id, $status);


if (!empty($_FILES['user_avatar'])) {
    $user_avatar = upload_file($_FILES['user_avatar']['name'], $_FILES['user_avatar']['tmp_name']);
    set_avatar($new_user_id, $user_avatar);
}


add_social_links($new_user_id, $user_social_telegram, $user_social_instagram, $user_social_vk);


set_flash_message('success', "Пользователь  c почтой $email успешно добавлен");
redirect_to('../users.php');
