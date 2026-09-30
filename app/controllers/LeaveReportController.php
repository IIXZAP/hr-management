<?php
// app/controllers/LeaveReportController.php

class LeaveReportController
{
    public function index()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        if (Auth::can('leave', 'read') === false) {
            redirect('/profile');
        }

        $isAdmin = Auth::isAdmin();
        $year = (int) ($_GET['year'] ?? date('Y'));

        // จุดที่แก้ — เลือกว่าจะดึงพนักงานกี่คน ตาม role
        if ($isAdmin) {
            $employees = LeaveReport::activeEmployees();
        } else {
            $employees = LeaveReport::activeEmployees(Auth::empId());
            // ต้องแก้ activeEmployees() ให้รับ filter ได้ — ดูข้อ 2 ด้านล่าง
        }

        $leaveTypes = LeaveType::all();

        $empIds = array_column($employees, 'emp_id');

        $allHistory  = LeaveReport::historyByYear($empIds, $year);
        $allTotals   = LeaveReport::totalsByTypeAll($empIds, $year);
        $allVacation = LeaveReport::vacationEntitlementAll($empIds, $year);

        $reportData = [];
        foreach ($employees as $employee) {
            $empId = $employee['emp_id'];

            $reportData[$empId] = [
                'employee' => $employee,
                'history'  => $allHistory[$empId] ?? [],
                'totals'   => $allTotals[$empId] ?? [],
                'vacation' => $allVacation[$empId] ?? null,
            ];
        }

        $yearOptions = range((int) date('Y'), 2016);

        // แก้บั๊ก: เดิม require ไฟล์ admin ตายตัว ไม่แยก role เลย
        // ทั้งที่ $employees / $reportData ด้านบน filter ตาม role ถูกต้องอยู่แล้ว
        if ($isAdmin === true) {
            $viewPath = '/views/admin/leave/report.php';
        } else {
            $viewPath = '/views/staff/leave/report.php';
        }

        require BASE_PATH . '/views/shared/layouts/header.php';
        require BASE_PATH . $viewPath;
        require BASE_PATH . '/views/shared/layouts/footer.php';
    }
}