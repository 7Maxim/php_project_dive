<?php
session_start();
require_once 'main/function.php';

unset($_SESSION['email']);
redirect_to('page_login.php');