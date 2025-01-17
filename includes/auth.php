<?php

require_once '../config/config.php';
require_once '..incldes/utils/session.php';

// Utility function to get the database connection
function get_connection() {
    $connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($connection->connect_error) {
        die("Database connection failed: " . $connection->connect_error);
    }
    return $connection;
}

// Register a new user
function register_user($first_name, $last_name, $email, $password, $role = 'reader') {
    $connection = get_connection();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert the new user into the database
    $stmt = $connection->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        $stmt->close();
        $connection->close();
        return true; // Registration successful
    } else {
        $stmt->close();
        $connection->close();
        return false; // Registration failed
    }
}

// Authenticate user on login
function authenticate_user($email, $password) {
    $connection = get_connection();

    // Retrieve the user record
    $stmt = $connection->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];

            $stmt->close();
            $connection->close();
            return true; // Authentication successful
        }
    }

    $stmt->close();
    $connection->close();
    return false; // Authentication failed
}

// Logout user
function logout_user() {
    session_start();
    session_destroy();
}

// Check if a user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Get the current user's role
function get_user_role() {
    return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
}

// Check if a user has a specific role
function has_role($role) {
    return get_user_role() === $role;
}

// Restrict access to users with a specific role
function restrict_to_role($required_role) {
    if (!is_logged_in() || !has_role($required_role)) {
        header("Location: /login.php?error=unauthorized");
        exit();
    }
}

// Restrict access to logged-in users
function restrict_to_logged_in() {
    if (!is_logged_in()) {
        header("Location: /login.php?error=login_required");
        exit();
    }
}
