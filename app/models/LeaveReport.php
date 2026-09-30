<?php
// app/models/LeaveReport.php
// หน้าที่: รายงานสรุปวันลา — แปลงมาจาก logic เดิมที่เคยเขียนแบบ mysqli ฝังในหน้า view ตรงๆ
// แยกออกมาเป็น Model ตาม pattern ของโปรเจกต์นี้ (Model ไม่รู้เรื่อง HTML เลย)
//
// ต่างจาก logic เดิม 3 จุดสำคัญ (ตาม schema จริงของเรา ไม่ใช่ schema เก่า):
//   1. ตาราง employees (ไม่ใช่ employee), เชื่อมด้วย emp_id
//   2. leave_type เดิมเป็น text ตรงๆ ในตาราง time_leave -> ตอนนี้แยกเป็น leave_types ต้อง JOIN
//   3. em_date_start (วันเริ่มงาน) เดิมอยู่ในตาราง employee -> ตอนนี้อยู่ใน contract.cont_start_date แทน

class LeaveReport
{
    // รายชื่อพนักงานที่ยังทำงานอยู่ — ใช้สร้างแท็บ
    public static function activeEmployees($onlyEmpId = null)
    {
        $conn = Database::connect();

        $sql = "SELECT emp_id, emp_name_th, emp_sname_th, emp_nickname_th
            FROM employees
            WHERE emp_cancel = 1";

        $params = [];

        // ถ้าระบุ emp_id มา (กรณี staff) → กรองแค่คนนั้น
        // ถ้าไม่ระบุ (กรณี admin) → ทำงานเหมือนเดิมทุกอย่าง
        if ($onlyEmpId !== null) {
            $sql .= " AND emp_id = :emp_id";
            $params[':emp_id'] = $onlyEmpId;
        }

        $sql .= " ORDER BY emp_id ASC";

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    // ประวัติการลาของพนักงาน 1 คน ในปีที่ระบุ — เรียงตามวันที่ (ใช้แสดงตารางในแท็บ)
    // public static function historyByEmployeeYear($empId, $year)
    // {
    //     $conn = Database::connect();

    //     $sql = "SELECT
    //                 time_leave.*,
    //                 leave_types.leave_type_name
    //             FROM time_leave
    //             LEFT JOIN leave_types ON leave_types.leave_type_id = time_leave.leave_type_id
    //             WHERE time_leave.emp_id = :emp_id
    //               AND YEAR(time_leave.leave_date) = :year
    //             ORDER BY time_leave.leave_date ASC";

    //     $stmt = $conn->prepare($sql);
    //     $stmt->bindValue(':emp_id', $empId, PDO::PARAM_INT);
    //     $stmt->bindValue(':year', $year, PDO::PARAM_INT);
    //     $stmt->execute();

    //     return $stmt->fetchAll();
    // }

    public static function historyByYear($empIds, $year)
    {
        if (empty($empIds)) return [];

        $conn = Database::connect();
        $placeholders = implode(',', array_fill(0, count($empIds), '?'));

        $sql = "SELECT tl.*, lt.leave_type_name
            FROM time_leave tl
            LEFT JOIN leave_types lt ON lt.leave_type_id = tl.leave_type_id
            WHERE tl.emp_id IN ($placeholders) AND YEAR(tl.leave_date) = ?
            ORDER BY tl.emp_id, tl.leave_date";

        $params = array_merge($empIds, [$year]);

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        $grouped = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $grouped[$row['emp_id']][] = $row;
        }

        return $grouped;
    }

    // ยอดรวมวันลาแยกตามประเภท ของพนักงาน 1 คน ในปีที่ระบุ
    // คืนเป็น array แบบ [ชื่อประเภทลา => จำนวนวันรวม] — ใช้แสดงแถว "รวม" ท้ายตาราง
    // public static function totalsByType($empId, $year)
    // {
    //     $conn = Database::connect();

    //     $sql = "SELECT
    //                 leave_types.leave_type_name,
    //                 SUM(time_leave.leave_days) AS total_days
    //             FROM time_leave
    //             LEFT JOIN leave_types ON leave_types.leave_type_id = time_leave.leave_type_id
    //             WHERE time_leave.emp_id = :emp_id
    //               AND YEAR(time_leave.leave_date) = :year
    //               AND time_leave.leave_status = 'approved'
    //             GROUP BY leave_types.leave_type_name";

    //     $stmt = $conn->prepare($sql);
    //     $stmt->bindValue(':emp_id', $empId, PDO::PARAM_INT);
    //     $stmt->bindValue(':year', $year, PDO::PARAM_INT);
    //     $stmt->execute();

    //     $totals = [];
    //     foreach ($stmt->fetchAll() as $row) {
    //         $totals[$row['leave_type_name']] = (float) $row['total_days'];
    //     }

    //     return $totals;
    // }

    // Optimize จาก function totalsByType($empId, $year)
    public static function totalsByTypeAll($empIds, $year)
    {
        if (empty($empIds)) return [];

        $conn = Database::connect();
        $placeholders = implode(',', array_fill(0, count($empIds), '?'));

        $sql = "SELECT tl.emp_id, lt.leave_type_name, SUM(tl.leave_days) as total_days
            FROM time_leave tl
            LEFT JOIN leave_types lt ON lt.leave_type_id = tl.leave_type_id
            WHERE tl.emp_id IN ($placeholders) AND YEAR(tl.leave_date) = ?
            GROUP BY tl.emp_id, lt.leave_type_name";

        $params = array_merge($empIds, [$year]);
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        $grouped = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $grouped[$row['emp_id']][$row['leave_type_name']] = $row['total_days'];
        }

        return $grouped;
    }

    // คำนวณสิทธิ์วันลาพักร้อนของพนักงาน 1 คน ในปีที่ระบุ
    // logic ยกมาจากไฟล์ info_leave.php เดิมทั้งหมด แค่เปลี่ยนแหล่งข้อมูลให้ตรง schema ใหม่
    // public static function vacationEntitlement($empId, $year)
    // {
    //     $conn = Database::connect();

    //     // 1. หาวันเริ่มงาน — ใช้ contract ฉบับแรกสุดของพนักงานคนนี้ (MIN คือฉบับเก่าสุด)
    //     $sql = "SELECT MIN(cont_start_date) AS start_date FROM contract WHERE emp_id = :emp_id";
    //     $stmt = $conn->prepare($sql);
    //     $stmt->execute([':emp_id' => $empId]);
    //     $startDateRow = $stmt->fetch();
    //     $startDate = $startDateRow['start_date'] ?? null;

    //     if ($startDate === null) {
    //         $startDate = date('Y-m-d'); // กันไว้เผื่อยังไม่มีสัญญาจ้างเลย
    //     }

    //     // 2. คำนวณอายุงาน (ปีเต็ม) จากวันนี้ลบวันเริ่มงาน
    //     $today = new DateTime();
    //     $startDateObj = new DateTime($startDate);
    //     $workYears = (int) $today->diff($startDateObj)->format('%y');

    //     // 3. สิทธิ์วันลาพักร้อนตามอายุงาน (ปรับ threshold ตามนโยบายบริษัทจริงได้)
    //     if ($workYears >= 5) {
    //         $vacation = 10;
    //     } elseif ($workYears === 4) {
    //         $vacation = 9;
    //     } elseif ($workYears === 3) {
    //         $vacation = 8;
    //     } elseif ($workYears >= 1) {
    //         $vacation = 7;
    //     } else {
    //         $vacation = 0;
    //     }

    //     // 4. หา leave_type_id ของ "ลาพักร้อน" (ต้องมีแถวนี้ใน leave_types อยู่ก่อนแล้ว)
    //     $sql = "SELECT leave_type_id FROM leave_types WHERE leave_type_name = 'ลาพักร้อน' LIMIT 1";
    //     $stmt = $conn->prepare($sql);
    //     $stmt->execute();
    //     $typeRow = $stmt->fetch();
    //     $vacationTypeId = $typeRow['leave_type_id'] ?? null;

    //     // helper ใช้ query ซ้ำหลายรอบด้านล่าง (แทนการ copy SQL เดิมซ้ำ 4 รอบแบบไฟล์ต้นฉบับ)
    //     // รับปีเป็น parameter ตรงๆ ไม่ hardcode ปีไว้ในฐาน SQL — กันปัญหา YEAR(leave_date) ถูกเช็คซ้ำ 2 เงื่อนไขที่ขัดกันเอง
    //     $sumApprovedDays = function ($targetYear, $condition, $params) use ($conn, $empId, $vacationTypeId) {
    //         if ($vacationTypeId === null) {
    //             return 0.0;
    //         }

    //         $sql = "SELECT SUM(leave_days) AS total
    //                 FROM time_leave
    //                 WHERE emp_id = :emp_id
    //                   AND leave_type_id = :leave_type_id
    //                   AND leave_status = 'approved'
    //                   AND YEAR(leave_date) = :target_year
    //                   $condition";

    //         $bindParams = array_merge([
    //             ':emp_id'        => $empId,
    //             ':leave_type_id' => $vacationTypeId,
    //             ':target_year'   => $targetYear,
    //         ], $params);

    //         $stmt = $conn->prepare($sql);
    //         $stmt->execute($bindParams);
    //         $row = $stmt->fetch();

    //         return $row['total'] !== null ? (float) $row['total'] : 0.0;
    //     };

    //     $prevYear = $year - 1;

    //     // 5. วันลาที่ใช้ไปในปีก่อนหน้า (เฉพาะหลัง 31 มี.ค. ของปีก่อน)
    //     //    ใช้คำนวณว่าเหลือวันลาสะสมยกมาให้ปีนี้เท่าไหร่
    //     $prevUsed = $sumApprovedDays(
    //         $prevYear,
    //         "AND leave_date > :prev_cutoff",
    //         [':prev_cutoff' => "$prevYear-03-31"]
    //     );
    //     $previousYearLeaves = max(0, $vacation - $prevUsed);

    //     // 6. วันลาที่ใช้ไปในปีปัจจุบันทั้งหมด (ไม่กรองวันที่)
    //     $currentUsed = $sumApprovedDays($year, "", []);

    //     // 7. แยกวันลาที่ใช้ก่อน/หลัง 31 มี.ค. ของปีปัจจุบัน
    //     $usedBeforeExpiry = $sumApprovedDays($year, "AND leave_date <= :cutoff", [':cutoff' => "$year-03-31"]);
    //     $usedAfterExpiry  = $sumApprovedDays($year, "AND leave_date > :cutoff", [':cutoff' => "$year-03-31"]);

    //     // 8. ตัดว่าวันที่ใช้ก่อน 31 มี.ค. หักจาก "โควตาสะสม" ก่อน ถ้าเกินโควตาสะสมค่อยหักจาก "โควตาปีนี้"
    //     $usedFromPrevious = min($usedBeforeExpiry, $previousYearLeaves);
    //     $usedFromCurrent  = $usedAfterExpiry + max(0, $usedBeforeExpiry - $previousYearLeaves);

    //     // 9. วันลาสะสมคงเหลือ — หมดอายุทันทีถ้าวันนี้เลย 31 มี.ค. ของปีนี้ไปแล้ว
    //     $expiryDate = new DateTime("$year-03-31");
    //     $remainingPrevious = ($today > $expiryDate) ? 0 : max(0, $previousYearLeaves - $usedFromPrevious);
    //     $remainingCurrent  = max(0, $vacation - $usedFromCurrent);

    //     return [
    //         'work_years'             => $workYears,
    //         'vacation_this_year'     => $vacation,
    //         'used_this_year'         => $usedFromCurrent,
    //         'remaining_this_year'    => $remainingCurrent,
    //         'carried_over'           => $previousYearLeaves,
    //         'used_carried_over'      => $usedFromPrevious,
    //         'remaining_carried_over' => $remainingPrevious,
    //         'total_available'        => $vacation + $previousYearLeaves,
    //         'total_used'             => $usedFromCurrent + $usedFromPrevious,
    //         'total_remaining'        => $remainingCurrent + $remainingPrevious,
    //     ];
    // }

    public static function vacationEntitlementAll($empIds, $year)
    {
        if (empty($empIds)) return [];

        $conn = Database::connect();
        $prevYear = $year - 1;

        // ---- Query 1: หาวันเริ่มงานของทุกคนทีเดียว (แทนการ query ทีละคน) ----
        $placeholders = implode(',', array_fill(0, count($empIds), '?'));
        $sql = "SELECT emp_id, MIN(cont_start_date) AS start_date
            FROM contract
            WHERE emp_id IN ($placeholders)
            GROUP BY emp_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute($empIds);

        $startDates = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $startDates[$row['emp_id']] = $row['start_date'];
        }

        // ---- หา vacationTypeId ครั้งเดียว (ค่าเดียวใช้ร่วมกันทุกคนอยู่แล้ว ไม่ต้อง batch) ----
        $sql = "SELECT leave_type_id FROM leave_types WHERE leave_type_name = 'ลาพักร้อน' LIMIT 1";
        $stmt = $conn->query($sql);
        $vacationTypeId = $stmt->fetch()['leave_type_id'] ?? null;

        // ---- Query 2: sum วันลาของทุกคนทีเดียว ด้วย CASE WHEN แยก 3 bucket ----
        $prevCutoff = "$prevYear-03-31";
        $cutoff = "$year-03-31";

        $sumsByEmp = [];
        if ($vacationTypeId !== null) {
            $sql = "SELECT
                    emp_id,
                    SUM(CASE WHEN YEAR(leave_date) = ? AND leave_date > ? THEN leave_days ELSE 0 END) AS prev_used,
                    SUM(CASE WHEN YEAR(leave_date) = ? AND leave_date <= ? THEN leave_days ELSE 0 END) AS used_before_expiry,
                    SUM(CASE WHEN YEAR(leave_date) = ? AND leave_date > ? THEN leave_days ELSE 0 END) AS used_after_expiry
                FROM time_leave
                WHERE emp_id IN ($placeholders)
                  AND leave_type_id = ?
                  AND leave_status = 'approved'
                  AND YEAR(leave_date) IN (?, ?)
                GROUP BY emp_id";

            $params = array_merge(
                [$prevYear, $prevCutoff, $year, $cutoff, $year, $cutoff],
                $empIds,
                [$vacationTypeId, $prevYear, $year]
            );

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $sumsByEmp[$row['emp_id']] = $row;
            }
        }

        // ---- ประกอบผลลัพธ์ต่อคน (คำนวณในหน่วยความจำ ไม่แตะ DB แล้ว) ----
        $today = new DateTime();
        $expiryDate = new DateTime("$year-03-31");
        $result = [];

        foreach ($empIds as $empId) {
            $startDate = $startDates[$empId] ?? date('Y-m-d');
            $startDateObj = new DateTime($startDate);
            $workYears = (int) $today->diff($startDateObj)->format('%y');

            if ($workYears >= 5) {
                $vacation = 10;
            } elseif ($workYears === 4) {
                $vacation = 9;
            } elseif ($workYears === 3) {
                $vacation = 8;
            } elseif ($workYears >= 1) {
                $vacation = 7;
            } else {
                $vacation = 0;
            }

            $sums = $sumsByEmp[$empId] ?? ['prev_used' => 0, 'used_before_expiry' => 0, 'used_after_expiry' => 0];
            $prevUsed = (float) $sums['prev_used'];
            $usedBeforeExpiry = (float) $sums['used_before_expiry'];
            $usedAfterExpiry = (float) $sums['used_after_expiry'];

            $previousYearLeaves = max(0, $vacation - $prevUsed);

            $usedFromPrevious = min($usedBeforeExpiry, $previousYearLeaves);
            $usedFromCurrent  = $usedAfterExpiry + max(0, $usedBeforeExpiry - $previousYearLeaves);

            $remainingPrevious = ($today > $expiryDate) ? 0 : max(0, $previousYearLeaves - $usedFromPrevious);
            $remainingCurrent  = max(0, $vacation - $usedFromCurrent);

            $result[$empId] = [
                'work_years'             => $workYears,
                'vacation_this_year'     => $vacation,
                'used_this_year'         => $usedFromCurrent,
                'remaining_this_year'    => $remainingCurrent,
                'carried_over'           => $previousYearLeaves,
                'used_carried_over'      => $usedFromPrevious,
                'remaining_carried_over' => $remainingPrevious,
                'total_available'        => $vacation + $previousYearLeaves,
                'total_used'             => $usedFromCurrent + $usedFromPrevious,
                'total_remaining'        => $remainingCurrent + $remainingPrevious,
            ];
        }

        return $result;
    }
}
