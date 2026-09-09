<?php

class ScanController
{
    private $dupWindowSeconds = 60;

    public function scan()
    {
        header('Content-Type: application/json; charset=utf-8');

        $uid = '';

        if (isset($_POST['uid']) === true) {
            $uid = trim($_POST['uid']);
        } elseif (isset($_GET['uid']) === true) {
            $uid = trim($_GET['uid']);
        }

        $uid = strtoupper($uid);

        if ($uid === '') {
            $this->respondJson('error', 'ไม่พบ UID ของบัตร');
            return;
        }

        // ขั้นตอนที่ 2: กันแตะรัว
        $isDuplicate = ScanLog::isDuplicate($uid, $this->dupWindowSeconds);

        if ($isDuplicate === true) {
            $this->respondJson('error', 'แตะบัตรถี่เกินไป กรุณารอสักครู่');
            return;
        }

        // ขั้นตอนที่ 3: หาบัตรจาก uid
        $tag = RfidTag::findByUid($uid);

        if ($tag === null) {
            $this->respondJson('error', 'ไม่พบบัตรนี้ในระบบ');
            return;
        }

        // ขั้นตอนที่ 4: หาพนักงานที่ผูกกับบัตรนี้
        $employee = Employee::findByAssign($tag['assign']);

        if ($employee === null) {
            // บัตรมีจริงแต่ไม่มีคนผูก → บันทึก log ไว้เป็น error ด้วย (ไม่มี employee_id)
            ScanLog::create($tag['tag_id'], null, 'in', 'error', 'ไม่พบพนักงานที่ผูกกับบัตรนี้');
            $this->respondJson('error', 'ไม่พบพนักงานที่ผูกกับบัตรนี้');
            return;
        }

        // ขั้นตอนที่ 5: เช็คว่าพนักงานถูกยกเลิกไปแล้วหรือยัง
        if ((int) $employee['emp_cancel'] === 1) {
            ScanLog::create($tag['tag_id'], $employee['emp_id'], 'in', 'error', 'บัญชีพนักงานถูกระงับ');
            $this->respondJson('error', 'บัญชีพนักงานถูกระงับ');
            return;
        }

        // ขั้นตอนที่ 6: ประมวลผลการสแกน (Attendance model บันทึก ScanLog ให้เองข้างในแล้ว)
        $result = Attendance::processScan($tag['tag_id'], $employee['emp_id']);

        // ขั้นตอนที่ 7: ตอบกลับเป็น JSON
        echo json_encode([
            'status'        => 'success',
            'action'        => $result['action'],   // in / out / already_done
            'employee_id'   => $employee['emp_id'],
            'employee_name' => $employee['emp_name_th'] . ' ' . $employee['emp_sname_th'],
        ]);
    }

    // helper ตอบกลับ error สั้นๆ
    private function respondJson($status, $message)
    {
        echo json_encode([
            'status'  => $status,
            'message' => $message,
        ]);
    }
}
