<?php



function get_user_by_email($email) {
    $pdo = new PDO("mysql:host=localhost;dbname=php_tasks;charset=utf8", "root", "");

    $sql = "SELECT * FROM users WHERE email = :email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email"=>$email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    return $user;
}


function add_user($email, $password) {
    $pdo = new PDO("mysql:host=localhost;dbname=php_tasks;charset=utf8", "root", "");

    $sql = "INSERT INTO `users` (email, password) VALUES (:email, :password)";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email"=>$email,"password"=>$password]);

    $user = get_user_by_email($email);

    return $user['id'];
}

function set_flash_message($name, $message) {
    $_SESSION[$name] = $message;
}

function display_flash_message($name) {

    if (isset($_SESSION[$name])) {
        echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">
                    {$_SESSION[$name]}
               </div>";
        unset($_SESSION[$name]);
    }


}


function redirect_to($path) {
    header("Location:$path");
    exit();
}



function login($email, $password) {

    $user = get_user_by_email($email);

    if (empty($user) || !password_verify($password, $user['password'])) {
        set_flash_message('danger', "Неверный логин или пароль");
        return false;
    }

    $_SESSION['email'] = $email;
    set_flash_message('success', "Успешно авторизован");

    return true;
}


function is_not_logged() {
    if (isset($_SESSION['email'])) {
        return false;
    }
    return true;
}


function select_all_users() {
    $pdo = new PDO("mysql:host=localhost;dbname=php_tasks;charset=utf8", "root", "");
    $statement = $pdo->query("SELECT * FROM users");
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $users;
}

function edit_info($id, $username, $job_title, $phone, $address) {
    $pdo = new PDO("mysql:host=localhost;dbname=php_tasks;charset=utf8", "root", "");

    $sql = "UPDATE `users` SET `username` = :username, `company`= :company, `phone`=:phone, `address`=:address WHERE id=:id ";
    $statement = $pdo->prepare($sql);

    $arr = ["id"=>$id,"username"=>$username,"company"=>$job_title, "phone"=>$phone, "address"=>$address];
    $statement->execute($arr);

    return true;
}


function set_status($id, $status) {
    $pdo = new PDO("mysql:host=localhost;dbname=php_tasks;charset=utf8", "root", "");

    $sql = "UPDATE `users` SET `status` = :status WHERE id=:id ";
    $statement = $pdo->prepare($sql);

    $arr = ["id"=>$id,"status"=>$status];
    $statement->execute($arr);
}



function set_avatar($id, $image) {

    $pdo = new PDO("mysql:host=localhost;dbname=php_tasks;charset=utf8", "root", "");
    $sql = "UPDATE `users` SET `image` = :image WHERE id=:id ";
    $statement = $pdo->prepare($sql);

    $statement->execute(["id"=>$id, "image"=>$image]);
}


function upload_file($filename, $tmp_name) {
    $result = pathinfo($filename);
    $newFileName = uniqid() . '.' . $result['extension'];
    move_uploaded_file($tmp_name, '../img/uploads/' . $newFileName);
    return $newFileName;
}



function add_social_links($id, $telegram, $instagram, $vk) {
    $pdo = new PDO("mysql:host=localhost;dbname=php_tasks;charset=utf8", "root", "");

    $sql = "UPDATE `users` SET `user_social_vk` = :vk, `user_social_telegram`= :telegram, `user_social_instagram` = :instagram WHERE id=:id ";
    $statement = $pdo->prepare($sql);

    $arr = ["id"=>$id,"vk"=>$vk, "telegram"=>$telegram, "instagram"=>$instagram];
    $statement->execute($arr);
}


function get_user_by_id($id) {
    $pdo = new PDO("mysql:host=localhost;dbname=php_tasks;charset=utf8", "root", "");

    $sql = "SELECT * FROM users WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["id"=>$id]);

    $user = $statement->fetch(PDO::FETCH_ASSOC);
    return $user;
}



function is_author($logged_user, $edit_user_id) {
    if ($logged_user === $edit_user_id) {
        return true;
    }
    return false;
}



function edit_credentials($user_id, $email, $password) {
    $pdo = new PDO("mysql:host=localhost;dbname=php_tasks;charset=utf8", "root", "");

    $sql = "UPDATE `users` SET `email` = :email, `password`= :password WHERE id=:id ";
    $statement = $pdo->prepare($sql);

    $arr = ["id"=>$user_id,"email"=>$email,"password"=>$password];
    $statement->execute($arr);

    return true;
}



function update_email($user_id, $email) {
    $pdo = new PDO("mysql:host=localhost;dbname=php_tasks;charset=utf8", "root", "");

    $sql = "UPDATE `users` SET `email`=:email WHERE id=:id ";
    $statement = $pdo->prepare($sql);

    $arr = ["id"=>$user_id, "email"=>$email];
    $statement->execute($arr);

    return true;
}


function update_password($user_id, $password) {
    $pdo = new PDO("mysql:host=localhost;dbname=php_tasks;charset=utf8", "root", "");

    $sql = "UPDATE `users` SET `password`= :password WHERE id=:id ";
    $statement = $pdo->prepare($sql);

    $arr = ["id"=>$user_id, "password"=>$password];
    $statement->execute($arr);

    return true;
}



function has_image($user_id) {

    $user = get_user_by_id($user_id);

    if ( empty($user['image']) ) {
        return false;
    }

    return true;
}



function delete($user_id) {
    $pdo = new PDO("mysql:host=localhost;dbname=php_tasks;charset=utf8", "root", "");

    $sql = "DELETE FROM `users` WHERE id=:id ";
    $statement = $pdo->prepare($sql);

    $arr = ["id"=>$user_id];
    $statement->execute($arr);

    return true;
}




function deleteImg($path, $image) {
    $pathImage = $path . $image;
    unlink($pathImage);
}


