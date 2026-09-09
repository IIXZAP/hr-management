<?php

class ProfileController 
{
    public function show()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        if (Auth::isAdmin() === true) {
            redirect('/employees');
        }

        $employee = Employee::find(Auth::empId());
        $contract = Contract::findByEmpId(Auth::empId());

        if ($employee === null) {
            echo 'ไม่พบข้อมูลพนักงาน';
            return;
        }

        require BASE_PATH . '/views/layouts/header.php';
        require BASE_PATH . '/views/profile/show.php';
        require BASE_PATH . '/views/layouts/footer.php';
    }
}