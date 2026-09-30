<?php

class DashboardController
{
    public function index()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        if (Auth::isAdmin()) {
            $view = '/views/admin/dashboard/index.php';
        } else {
            $view = '/views/staff/dashboard/index.php';

            $empId = Auth::empId();

            // ชื่อ -> $employee
            $employee = Employee::find($empId);

            $record = Attendance::findRecordToday($empId, date('Y-m-d'));
            $todayScan = [
                'check_in'  => $record['check_in']  ?? null,
                'check_out' => $record['check_out'] ?? null,
            ];

            // วันลาคงเหลือ -> $leaveBalance
            // $leaveBalance = Leave::balance($empId);
            $leaveBalance = Leave::balance($empId, 1); // 1 = leave_type_id ของ "ลาพักร้อน" (เดา, ตรวจตาราง leave_types)
            $leaveBalance = $leaveBalance ?? 0;

            // คำขอลาล่าสุด -> $recentLeaves
            $recentLeaves = [];
            foreach (Leave::recentByEmployee($empId, 5) as $row) {
                $days = (float) $row['leave_days'];
                $recentLeaves[] = [
                    'type'       => $row['leave_type_name'],
                    'date_range' => $this->thaiDate($row['leave_date']),
                    'days'       => rtrim(rtrim(number_format($days, 1), '0'), '.') . ' วัน',
                    'status'     => $row['leave_status'], // approved | pending | rejected
                ];
            }
        }

        require BASE_PATH . '/views/shared/layouts/header.php';
        require BASE_PATH . $view;
        require BASE_PATH . '/views/shared/layouts/footer.php';
    }

    // 2026-09-18 -> 18 ก.ย. 2569
    private function thaiDate($date)
    {
        $months = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        $ts = strtotime($date);

        if ($ts === false) {
            return '-';
        }

        return date('j', $ts) . ' ' . $months[(int) date('n', $ts) - 1] . ' ' . (date('Y', $ts) + 543);
    }
}
