<?php
// app/controllers/LeaveReportController.php
// หน้าที่: หน้ารายงานสรุปวันลา (admin เท่านั้น) — เดินลูปพนักงานทุกคน รวมข้อมูล 3 อย่างต่อคน
// (ประวัติการลา, ยอดรวมแยกประเภท, สิทธิ์วันลาพักร้อน) ส่งให้ view จัดแท็บเอง

class LeaveReportController
{
    public function index()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        if (Auth::isAdmin() === false) {
            redirect('/profile');
        }

        $year = (int) ($_GET['year'] ?? date('Y'));

        $employees = LeaveReport::activeEmployees();
        $leaveTypes = LeaveType::all();

        // รวมข้อมูลทุกอย่างต่อคนไว้ล่วงหน้า กัน view ต้อง query ซ้ำในลูป (N+1 query problem)
        $reportData = [];
        foreach ($employees as $employee) {
            $empId = $employee['emp_id'];

            $reportData[$empId] = [
                'employee'   => $employee,
                'history'    => LeaveReport::historyByEmployeeYear($empId, $year),
                'totals'     => LeaveReport::totalsByType($empId, $year),
                'vacation'   => LeaveReport::vacationEntitlement($empId, $year),
            ];
        }



        // ปีให้เลือกใน dropdown — ย้อนกลับไปถึงปี 2016 ตามของเดิม
        $yearOptions = range((int) date('Y'), 2016);

        require BASE_PATH . '/views/layouts/header.php';
        require BASE_PATH . '/views/leave/report.php';
        require BASE_PATH . '/views/layouts/footer.php';
    }
}