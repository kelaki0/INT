//Role-based access control ensures that only users with specific roles can access certain parts of the applcation

<?php
//check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

//get the current users role
function get_user_role() {
    return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
}

//check if user has a specific role
function has_role($role) {
    return get_user_role() === $role;
}

//restrict access to users with a specicific role
function restrict_to_role($required_role) {
    if(!is_logged_in() || !has_role($required_role)) {
        header('Location: login.php');
        exit();
    }
}

//restrict acces to logged in users
function retsrict_to_logged_in(){
    if(!is_logged_in()) {
        header("Location: login.php");
    }
}

?>
