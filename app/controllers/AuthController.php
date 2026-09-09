<?php
class AuthController
{
    //Show login page
    public function showLogin()
    {
        if(Auth::check() === true){
            redirect('/dashboard');
        }

        require BASE_PATH . '/views/auth/login.php';
    } 

    // Login
    public function login()
    {
       $username = $_POST['username'];
       $password = $_POST['password'];

       if ($username === '' || $password === '') {
            echo 'กรุณากรอก username และ password';
            return;
        }
 
        $isSuccess = Auth::login($username, $password);
 
        if ($isSuccess === false) {
            echo 'username หรือ password ไม่ถูกต้อง';
            return;
        }
 
        redirect('/dashboard');

    }

    public function logout()
    {
        Auth::logout();
        header('Location: /login');
        exit;
    } 
}