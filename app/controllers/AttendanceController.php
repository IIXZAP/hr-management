<?php

class AttendanceController
{
    public function index()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        $selectId = $_GET['emp_id'] ?? '';
        $start = $_GET['start'] ?? '';
        $end = $_GET['end'] ?? '';

        $attendances = Attendance::all($selectId, $start, $end);
        $employees = Employee::all();
        $countAll = Employee::countAll();
        $countAttendance = Attendance::countAttendance();
        $countLeave = Leave::countLeave();

        require BASE_PATH . '/views/layouts/header.php';
        require BASE_PATH . '/views/attendance/list.php';
        require BASE_PATH . '/views/layouts/footer.php';
    }

    // แสดง form กรอกข้อมูลย้อนหลัง
    public function create()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        require BASE_PATH . '/views/layouts/header.php';
        require BASE_PATH . '/views/attendance/create.php';
        require BASE_PATH . '/views/layouts/footer.php';
    }

    // บันทึกข้อมูลใหม่
    public function store()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        $data = [
            'emp_id'     => $_POST['emp_id'] ?? null,
            'check_in'   => $_POST['check_in'] ?? null,
            'check_out'  => $_POST['check_out'] ?? null,
            'work_date'  => $_POST['work_date'] ?? null,
        ];

        Attendance::createByAdmin($data);

        redirect('/attendance');
    }

    public function update()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/attendance');
        }

        $attId    = $_POST['att_id'] ?? null;
        $checkIn  = $_POST['check_in'] ?: null;
        $checkOut = $_POST['check_out'] ?: null;

        if (!$attId) {
            header('Location: /attendance');
            return;
        }

        Attendance::update($attId, $checkIn, $checkOut);

        header('Location: /attendance');
    }
}
