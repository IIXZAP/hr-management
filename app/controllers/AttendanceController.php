<?php

class AttendanceController
{
    public function index()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        if (Auth::isAdmin()) {
            $selectId = $_GET['emp_id'] ?? '';
            $start = $_GET['start'] ?? '';
            $end = $_GET['end'] ?? '';

            $attendances = Attendance::all($selectId, $start, $end);
            $employees = Employee::all();
            $countAll = Employee::countAll();
            $countAttendance = Attendance::countAttendance();
            $countLeave = Leave::countLeave();

            $view = '/views/admin/attendance/list.php';
        } else {
            $empId = Auth::empId();

            if (!$empId) {
                redirect('/login');
            }

            // $start = date('Y-m-01');
            // $end = date('Y-m-d');

            $employee = Employee::find($empId);
            $select = $_GET['month'] ?? date('Y-m');
            $rawRows = Attendance::allSelfEmployee($empId, $select);

            $history = [];

            foreach ($rawRows as $row) {
                if ($row['att_id'] === null && $row['leave_id'] === null) {
                    continue;
                }

                $history[] = [
                    'date'            => $row['work_date'] ?? $row['leave_date'],
                    'check_in'        => $row['check_in'],
                    'check_out'       => $row['check_out'],
                    'start_time'      => $row['start_time'] ?? null,
                    'leave_type_name' => $row['leave_type_name'],
                    'status'          => $row['check_out'] !== null ? 'on_time' : 'working',
                ];
            }

            $stats = [
                'recorded_days' => count($history),
                'on_time_days'  => count($history),
                'late_count'    => 0,
                'late_minutes'  => 0,
                'absent_days'   => 0,
            ];

            // $periodLabel = $start . ' ถึง ' . $end;
            // ---- แบ่งหน้า (ทำหลังจากสร้าง $history ครบแล้ว ก่อน require view) ----
            $perPage = 10;
            $totalRecords = count($history); // นับจากของจริงทั้งหมดก่อนตัดหน้า

            $currentPage = max(1, (int) ($_GET['page'] ?? 1));
            // var_dump($currentPage);
            // die();
            $totalPages = max(1, (int) ceil($totalRecords / $perPage));

            // กันหน้าที่ขอมาเกินจริง (เช่นแก้ ?page=999 เอง) ให้เด้งกลับมาหน้าสุดท้ายที่มีจริง
            if ($currentPage > $totalPages) {
                $currentPage = $totalPages;
            }

            $offset = ($currentPage - 1) * $perPage;
            $history = array_slice($history, $offset, $perPage); // ตัดมาแสดงแค่หน้านี้

            $todayScan = Attendance::findRecordToday($empId, date('Y-m-d'));
            $todayStatus = [
                'date_label'  => date('Y-m-d'),
                'shift_label' => '08:30 - 17:30 น.',
                'check_in'    => $todayScan['check_in'] ?? null,
                'check_out'   => $todayScan['check_out'] ?? null,
                'status'      => 'working',
            ];

            $view = '/views/staff/attendance/list.php';
        }

        // ---- ตรงนี้รวมได้จริง: มีแค่ require 3 บรรทัดนี้เหมือนกันทั้ง 2 ฝั่ง ----
        require BASE_PATH . '/views/shared/layouts/header.php';
        require BASE_PATH . $view;
        require BASE_PATH . '/views/shared/layouts/footer.php';
    }
    // public function index()
    // {
    //     if (Auth::check() === false) {
    //         redirect('/login');
    //     }

    //     if (Auth::isAdmin()) {
    //         // ---- Admin: เห็นภาพรวมทุกคน เลือกดูใครก็ได้ผ่าน $_GET ----
    //         $selectId = $_GET['emp_id'] ?? '';
    //         $start = $_GET['start'] ?? '';
    //         $end = $_GET['end'] ?? '';

    //         $attendances = Attendance::all($selectId, $start, $end);
    //         $employees = Employee::all();
    //         $countAll = Employee::countAll();
    //         $countAttendance = Attendance::countAttendance();
    //         $countLeave = Leave::countLeave();

    //         require BASE_PATH . '/views/shared/layouts/header.php';
    //         require BASE_PATH . '/views/admin/attendance/list.php';
    //         require BASE_PATH . '/views/shared/layouts/footer.php';
    //         return;
    //     }

    //     $empId = Auth::empId();

    //     if (!$empId) {
    //         redirect('/login');
    //     }

    //     $start = $_GET['start'] ?? date('Y-m-01');
    //     $end = $_GET['end'] ?? date('Y-m-d');

    //     $attendances = Attendance::all($empId, $start, $end);

    //     require BASE_PATH . '/views/shared/layouts/header.php';
    //     require BASE_PATH . '/views/staff/attendance/list.php';
    //     require BASE_PATH . '/views/shared/layouts/footer.php';
    // }
    // แสดง form กรอกข้อมูลย้อนหลัง
    public function create()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        require BASE_PATH . '/views/shared/layouts/header.php';
        require BASE_PATH . '/views/admin/attendance/create.php';
        require BASE_PATH . '/views/shared/layouts/footer.php';
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

        /** เขียนแบบ if-else
         * if ($_POST['check_in']) {
         *   $checkIn = $_POST['check_in'];
         * } else {
         *    $checkIn = null;
         * } 
         **/
        

        if ($attId > 0) {
            Attendance::update($attId, $checkIn, $checkOut);
        } else {
            $empId = (int) ($_POST['emp_id'] ?? 0);
            $workDate = $_POST['work_date'] ?? '';

            if ($empId > 0 && $workDate !== '') {
                Attendance::createByAdmin([
                    'emp_id' => $empId,
                    'work_date' => $workDate,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                ]);
            }
        }

        redirect('/attendance');
    }

    private function indexAttendance() {}
}
