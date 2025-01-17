<?php
//start session
session_start();

//check if a user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

//check the current users role
function get_user_role() {
    return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
}

//check if user has a specific role
function has_role($role) {
    return get_user_role() === $role;
}

?>
