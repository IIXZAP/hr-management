<?php

class Contract
{
    public static function findByEmpId($empId)
    {
        $conn = Database::connect();

        $sql = "SELECT * FROM contract WHERE emp_id = :emp_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':emp_id' => $empId]);
        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        return $result;
    }

    // create
    public static function create($empId, $data, $conn = null)
    {
        if ($conn === null) {
            $conn = Database::connect();
        }

        $sql = "INSERT INTO contract
                (emp_id, cont_position, cont_start_date, cont_duration_time,
                 cont_status, cont_salary, cont_bank, cont_bank_no)
                VALUES
                (:emp_id, :cont_position, :cont_start_date, :cont_duration_time,
                 :cont_status, :cont_salary, :cont_bank, :cont_bank_no)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':emp_id'           => $empId,
            ':cont_position'     => $data['cont_position'] ?? '',
            ':cont_start_date'   => $data['cont_start_date'] ?? '',
            ':cont_duration_time' => $data['cont_duration_time'] ?? '',
            ':cont_status'       => $data['cont_status'] ?? '',
            ':cont_salary'       => $data['cont_salary'] ?? '',
            ':cont_bank'         => $data['cont_bank'] ?? '',
            ':cont_bank_no'      => $data['cont_bank_no'] ?? '',
        ]);

        return true;
    }

    // update
    public static function update($empId, $data, $conn = null)
    {
        if ($conn === null) {
            $conn = Database::connect();
        }

        $sql = "UPDATE contract
                SET cont_position = :cont_position,
                    cont_start_date = :cont_start_date,
                    cont_duration_time = :cont_duration_time,
                    cont_status = :cont_status,
                    cont_salary = :cont_salary,
                    cont_bank = :cont_bank,
                    cont_bank_no = :cont_bank_no
                WHERE emp_id = :emp_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':cont_position'      => $data['cont_position'],
            ':cont_start_date'    => $data['cont_start_date'],
            ':cont_duration_time' => $data['cont_duration_time'],
            ':cont_status'        => $data['cont_status'],
            ':cont_salary'        => $data['cont_salary'],
            ':cont_bank'          => $data['cont_bank'],
            ':cont_bank_no'       => $data['cont_bank_no'],
            ':emp_id'             => $empId,
        ]);

        return $stmt->rowCount() > 0;
    }
}
