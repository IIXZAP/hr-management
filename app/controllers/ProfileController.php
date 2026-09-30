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
        $position = Position::find($contract['cont_position']);
        $contact = EmployeeContact::findByEmpId(Auth::empId());
        $login = EmployeeLogin::findByEmpId(Auth::empId());
        $role = Role::find($login['role_id']);

        if ($employee === null) {
            echo 'ไม่พบข้อมูลพนักงาน';
            return;
        }

        // var_dump($contract);
        // var_dump($position);
        // exit;

        require BASE_PATH . '/views/shared/layouts/header.php';
        require BASE_PATH . '/views/staff/profile/show.php';
        require BASE_PATH . '/views/shared/layouts/footer.php';
    }
}
