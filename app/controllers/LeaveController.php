<?php

class LeaveController
{
    public function index()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        $isAdmin = Auth::isAdmin();

        if ($isAdmin === true) {
            $leaves = Leave::all();
        } else {
            $leaves = Leave::allByEmployee(Auth::empId());
        }

        require BASE_PATH . '/views/layouts/header.php';
        require BASE_PATH . '/views/leave/list.php';
        require BASE_PATH . '/views/layouts/footer.php';
    }

    // create
    // date, type, comment, duration
    public static function create()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        $leaveTypes = LeaveType::all();

        require BASE_PATH . '/views/layouts/header.php';
        require BASE_PATH . '/views/leave/create.php';
        require BASE_PATH . '/views/layouts/footer.php';
    }


    // update status (approve) - check admin
    public function approve()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        if (Auth::isAdmin() === false) {
            redirect('/profile');
        }

        $leaveId = $_POST['leave_id'] ?? null;
        $action  = $_POST['action'] ?? ''; // 'approve' หรือ 'reject' จากปุ่มในฟอร์ม
 
        $status = ($action === 'approve') ? 'approved' : 'rejected';

        Leave::updateStatus($leaveId, $status);

        redirect('/leave');
    }

    public function store()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        $empId = Auth::empId();

        $data = [
            'emp_id' => $empId,
            'leave_type_id' => $_POST['leave_type_id'],
            'leave_date' => $_POST['leave_date'],
            'leave_day' => $_POST['leave_day'],
            'leave_days' => $_POST['leave_days'],
            'leave_comment' => $_POST['leave_comment'],
        ];

        Leave::create($data);

        redirect('/leave');
    }

    // delete
    public static function delete()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        $leaveId = $_POST['leave_id'] ?? null;
        $leave = Leave::find($leaveId);

        $isOwner = ((int) $leave['emp_id'] === (int) Auth::empId());
 
        if ($isOwner === false && Auth::isAdmin() === false) {
            redirect('/leave');
        }

        if ($leave['leave_status'] !== 'pending') {
            redirect('/leave');
        }

        Leave::delete($leaveId);

        redirect('/leave');
    }

}
