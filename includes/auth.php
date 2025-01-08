<?php

require_once '../config/config.php';

//register a new user
function register_user($first_name, $last_name, $email, $password, $role = 'reader' ) {
    $connection = get_connection();

    //hash the password
    $hashed_password = password_hash($password, algo: PASSWORD_BCRYPT);

    //insert the new user into the database
    $stmt = $connection->prepare("INSERT INTO users(first_name, last_name, email, password, role) VLAUES(?, ?, ?, ?, ?) ");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        $stmt->close();
        $connection->close();
        return true; //registration successful.
    }
    else{
        $stmt->close();
        $connection->close();
        return false;
    }
}

//Authenticate user on login
function authenticate_user($first_name, $last_name, $email, $password) {
    $connection = get_connection();

    //retrieve the user record
    $stmt = $connection->prepare("SELECT id, password, role FROM users WHERE email = ? ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1){
        $user = $result->fetch_assoc();
    

    //verify the password
    if (password_verify($password, $user['password'])){
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];

        $stmt->close();
        $connection->close();
        return true; //authentication close
    }
}


$stmt->close();
$connection->close();
return false; //authentication failed
}

//logout user
function logout_user(){
    session_start();
    session_destroy();
}
?>