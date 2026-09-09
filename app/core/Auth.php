<?php

class Auth
{
    public static function login($username, $password)
    {
        // Connect DB
        $conn = Database::connect();

        $sql = "SELECT
                 em.emp_id AS user_id,
                 em_login.username AS username,
                 em_login.password_hash AS password,
                 em_login.is_admin AS is_admin,
                 em_login.role_id AS role_id,
                 roles.role_name AS role_name
                 FROM employees em
                 LEFT JOIN employee_login em_login
                     ON em.emp_id = em_login.emp_id
                 LEFT JOIN roles
                     ON em_login.role_id = roles.role_id
                 WHERE em_login.username = :username AND em.emp_cancel = 1
                 ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':username' => $username]);
        // $params = [':username' => $username];
        // $stmt = Database::query($sql, $params);
        $user = $stmt->fetch();

        // Check user
        if (!$user) {
            return false;
        }

        $isPasswordCorrect = password_verify($password, $user['password']);

        // Check password hash
        if (!$isPasswordCorrect) {
            return false;
        }

        // session_regenerate_id(true);

        //   Collect session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['is_admin'] = $user['is_admin'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['role_name'] = $user['role_name'];


        return true;
    }

    public static function check()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        }
        return false;
    }

    public static function isAdmin()
    {
        if (isset($_SESSION['is_admin'])) {
            $isAdmin_check = $_SESSION['is_admin'];
        } else {
            $isAdmin_check = 0;
        }

        if ($isAdmin_check == 1){
            return true;
        } else {
            return false;
        }
        // return ($_SESSION['is_admin'] ?? 0) === 1;
    }

    public static function roleName()
    {
        return $_SESSION['role_name'] ?? null;
    }

    public static function empId()
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function logout()
    {
        session_destroy();
    }
}
