<?php

class DashboardController
{
    public function index()
    {
        if (Auth::check() === false) {
            redirect('/login');
            
        }

        require BASE_PATH . '/views/layouts/header.php';
        require BASE_PATH . '/views/dashboard/index.php';
        require BASE_PATH . '/views/layouts/footer.php';
    }
}
