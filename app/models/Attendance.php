<?php

class Attendance
{
    // fetch
    // public static function all($selectId = '', $start = '', $end = '')
    // {
    //     $conn = Database::connect();

    //     $sql = "SELECT att.*, em.*
    //     FROM attendance att
    //     LEFT JOIN employees em
    //         ON em.emp_id = att.emp_id
    //     WHERE 1=1
    //     -- WHERE work_date = DATE(NOW())
    //     ";

    //     $params = [];

    //     if ($selectId !== '') {
    //         $sql .= " AND att.emp_id = :emp_id";
    //         $params[':emp_id'] = $selectId;
    //     }

    //     if ($start === '' && $end === '') {
    //         $sql .= " AND att.work_date = CURDATE()";
    //     } else {
    //         if ($start !== '') {
    //             $sql .= " AND att.work_date >= :start";
    //             $params[':start'] = $start;
    //         }

    //         if ($end !== '') {
    //             $sql .= " AND att.work_date <= :end";
    //             $params[':end'] = $end;
    //         }
    //     }

    //     $sql .= " ORDER BY att.work_date DESC";

    //     $stmt = $conn->prepare($sql);
    //     $stmt->execute($params);
    //     $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //     return $result;
    // }

    public static function all($selectId = '', $start = '', $end = '')
    {
        $conn = Database::connect();
        $params = [];

        if ($start === '' && $end === '') {
            $dateConditionAtt = "att.work_date = CURDATE()";
            $dateConditionLeave = "tl.leave_date = CURDATE()";
        } else {
            $conditions = [];
            $leaveConditions = [];
            if ($start !== '') {
                $conditions[] = "att.work_date >= :start";
                $leaveConditions[] = "tl.leave_date >= :start";
                $params[':start'] = $start;
            }
            if ($end !== '') {
                $conditions[] = "att.work_date <= :end";
                $leaveConditions[] = "tl.leave_date <= :end";
                $params[':end'] = $end;
            }
            $dateConditionAtt = implode(' AND ', $conditions);
            $dateConditionLeave = implode(' AND ', $leaveConditions);
        }

        $sql = "SELECT em.emp_id, em.emp_no, em.emp_name_th, em.emp_sname_th,
                   att.att_id, att.work_date, att.check_in, att.check_out,
                   tl.leave_id, tl.leave_date, tl.leave_type_id,
                   lt.leave_type_name
            FROM employees em
            LEFT JOIN attendance att
                ON em.emp_id = att.emp_id AND $dateConditionAtt
            LEFT JOIN time_leave tl
                ON em.emp_id = tl.emp_id AND $dateConditionLeave
            LEFT JOIN leave_types lt
                ON tl.leave_type_id = lt.leave_type_id
            WHERE em.emp_cancel = 1";

        if ($selectId !== '') {
            $sql .= " AND em.emp_id = :emp_id";
            $params[':emp_id'] = $selectId;
        }

        $sql .= " ORDER BY em.emp_no ASC";

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // find Record today
    public static function findRecordToday($empId, $workdate)
    {
        $conn = Database::connect();

        $sql = "SELECT att_id, check_in, check_out FROM attendance WHERE emp_id = :empId AND work_date = :workdate";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':empId' => $empId,
            ':workdate' => $workdate,
        ]);
        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        return $result;
    }

    // Insert time
    public static function InsertCheckIn($empId, $date, $checkin)
    {
        $conn = Database::connect();

        $sql = "INSERT INTO attendance (emp_id, work_date, check_in, created_at, updated_at) VALUES (:empId, :work_date, :check_in, NOW(), NOW()) ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':empId' => $empId,
            ':work_date' => $date,
            ':check_in' => $checkin,
        ]);

        return $conn->lastInsertId();
    }
    // Update time
    public static function UpdateCheckOut($attId, $checkout)
    {
        $conn = Database::connect();

        $sql = "UPDATE attendance SET check_out = :checkout, updated_at = NOW() WHERE att_id = :att_id ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':checkout' => $checkout,
            ':att_id' => $attId
        ]);

        $atffectRow = $stmt->rowCount();

        if ($atffectRow > 0) {
            return true;
        }

        return false;
    }

    // ProcessScan
    public static function processScan($tagId, $empId)
    {
        $today = date('Y-m-d');
        $now   = date('H:i:s');

        $existing = self::findRecordToday($empId, $today);

        if ($existing === null) {
            $attId = self::insertCheckIn($empId, $now, $today);
            ScanLog::create($tagId, $empId, 'in', 'success', null, $attId);
            return ['action' => 'in', 'att_id' => $attId];
        }

        if ($existing['check_out'] === null) {
            self::updateCheckOut($existing['att_id'], $now);
            ScanLog::create($tagId, $empId, 'out', 'success', null, $existing['att_id']);
            return ['action' => 'out', 'att_id' => $existing['att_id']];
        }

        // สแกนซ้ำหลัง check-out ไปแล้ว → ไม่แก้ attendance แต่ยังบันทึก log ไว้ (audit)
        ScanLog::create($tagId, $empId, 'out', 'error', 'สแกนซ้ำ เช็คอิน-เอาท์ครบแล้ว', $existing['att_id']);
        return ['action' => 'already_done', 'att_id' => $existing['att_id']];
    }

    // Insert by Admin
    public static function createByAdmin($data)
    {
        $conn = Database::connect();

        $sql = "INSERT INTO attendance (emp_id, work_date, check_in,check_out, created_at, updated_at) VALUES (:empId, :work_date, :check_in, :check_out, NOW(), NOW()) ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':empId'    => $data['emp_id'],
            ':work_date' => $data['work_date'],
            ':check_in'  => $data['check_in'],
            ':check_out' => $data['check_out'],
        ]);

        return $conn->lastInsertId();
    }

    public static function update($attId, $checkIn, $checkOut)
    {
        $conn = Database::connect();

        $sql = "UPDATE attendance SET check_in = :check_in, check_out = :check_out, updated_at = NOW() WHERE att_id = :att_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':check_in'  => $checkIn,
            ':check_out' => $checkOut,
            ':att_id'    => $attId,
        ]);

        $affectRow = $stmt->rowCount();

        if ($affectRow > 0) {
            return true;
        }

        return false;
    }

    public static function countAttendance()
    {
        $conn = Database::connect();

        $sql = "SELECT COUNT(*) AS totalAtt FROM attendance WHERE work_date = CURDATE() ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();

        return (int) $result['totalAtt'];
    }
}
