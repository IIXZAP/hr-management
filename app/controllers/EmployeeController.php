<?php

// หน้าที่: จัดการหน้าพนักงาน (list, add, edit, delete)

class EmployeeController
{
    public function index()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        if (Auth::isAdmin() === false) {
            redirect('/profile');
        }

        $page = (int) ($_GET['page'] ?? 1);
        if ($page < 1) {
            $page = 1;
        }

        $perPage = 10;

        $employees = Employee::paginate($page, $perPage);
        $totalRows = Employee::countAll();
        $totalPages = (int) ceil($totalRows / $perPage);

        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';

        $employees = Employee::all($search, $status);

        require BASE_PATH . '/views/layouts/header.php';
        require BASE_PATH . '/views/employees/list.php';
        require BASE_PATH . '/views/layouts/footer.php';
    }

    public function table()
    {
        if (Auth::check() === false) {
            http_response_code(401);
            exit;
        }

        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';

        $employees = Employee::all($search, $status);

        require BASE_PATH . '/views/employees/_table.php';
    }

    // แสดง form add employee
    public function create()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        $positions = Position::all();
        $emp_no = Employee::empno();

        require BASE_PATH . '/views/layouts/header.php';
        require BASE_PATH . '/views/employees/create.php';
        require BASE_PATH . '/views/layouts/footer.php';
    }

    // submit form employee -> $_POST[]
    public function store()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }



        $employeeData = [
            'emp_no' => sanitize($_POST['emp_no'] ?? ''),
            'emp_prefix_th' => sanitize($_POST['emp_prefix_th'] ?? ''),
            'emp_name_th' => sanitize($_POST['emp_name_th'] ?? ''),
            'emp_sname_th' => sanitize($_POST['emp_sname_th'] ?? ''),
            'emp_nickname_th' => sanitize($_POST['emp_nickname_th'] ?? ''),
            'emp_prefix_en' => sanitize($_POST['emp_prefix_en'] ?? ''),
            'emp_name_en' => sanitize($_POST['emp_name_en'] ?? ''),
            'emp_sname_en' => sanitize($_POST['emp_sname_en'] ?? ''),
            'emp_nickname_en' => sanitize($_POST['emp_nickname_en'] ?? ''),
            'emp_idcard' => sanitize($_POST['emp_idcard'] ?? ''),
            'emp_idss' => sanitize($_POST['emp_idss'] ?? ''),
            'emp_birthday' => $_POST['emp_birthday'] ?? '',
            'emp_tel' => sanitize($_POST['emp_tel'] ?? ''),
            'emp_email' => sanitize($_POST['emp_email'] ?? ''),
            'emp_address' => sanitize($_POST['emp_address'] ?? ''),
            'emp_line' => sanitize($_POST['emp_line'] ?? ''),
            'emp_cancel' => sanitize($_POST['emp_cancel'] ?? ''),

        ];

        $contactData = [
            'emp_id' => sanitize($_POST['emp_id'] ?? ''),
            'contact_type' => sanitize($_POST['contact_type'] ?? ''),
            'name' => sanitize($_POST['name'] ?? ''),
            'relationship' => sanitize($_POST['relationship'] ?? ''),
            'tel' => sanitize($_POST['tel'] ?? ''),
            'is_primary' => sanitize($_POST['is_primary'] ?? '1'),
        ];

        $contractData = [
            'emp_id' => sanitize($_POST['emp_id'] ?? ''),
            'cont_position' => sanitize($_POST['cont_position'] ?? ''),
            'cont_start_date' => sanitize($_POST['cont_start_date'] ?? ''),
            'cont_duration_time' => sanitize($_POST['cont_duration_time'] ?? ''),
            'cont_status' => sanitize($_POST['cont_status'] ?? ''),
            'cont_salary' => sanitize($_POST['cont_salary'] ?? ''),
            'cont_bank' => sanitize($_POST['cont_bank'] ?? ''),
            'cont_bank_no' => sanitize($_POST['cont_bank_no'] ?? ''),
        ];

        $conn = Database::connect();

        try {
            $conn->beginTransaction();

            // 1) employees
            $empId = Employee::create($_POST);

            // 2) contract — cont_position ต้องตรงกับ name ของ select ในฟอร์ม (แก้ไปแล้ว)
            Contract::create($empId, $_POST, $conn);

            // 3) employee_login
            EmployeeLogin::create($empId, $_POST, $conn);

            // 4) employee_contact — เพิ่มได้หลายแถว วนตาม contacts[] ที่ส่งมาจากฟอร์ม
            //    is_primary มาจาก primary_contact_index (radio ที่เลือกได้แค่แถวเดียว)
            //    ไม่ได้ส่งมาเป็น contacts[i][is_primary] ตรงๆ ต้องเทียบ index เอาเอง
            if (!empty($_POST['contacts']) && is_array($_POST['contacts'])) {
                $primaryIndex = $_POST['primary_contact_index'] ?? null;

                foreach ($_POST['contacts'] as $index => $contact) {
                    $contact['is_primary'] = ((string) $index === (string) $primaryIndex) ? 1 : 0;
                    EmployeeContact::create($empId, $contact, $conn);
                }
            }

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollBack();
            echo '<pre style="background:#fee;padding:20px;color:#900;">';
            echo 'ERROR: ' . $e->getMessage();
            echo '</pre>';
            exit;
        }

        redirect('/employees');
    }

    // view
    public static function view()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        $empId = $_GET['id'] ?? null;
        $employee = Employee::find($empId);
        $contact = EmployeeContact::findByEmpId($empId);
        $contract = Contract::findByEmpId($empId);
        $positions = Position::all();


        require BASE_PATH . '/views/layouts/header.php';
        require BASE_PATH . '/views/employees/view.php';
        require BASE_PATH . '/views/layouts/footer.php';
    }

    // Edit form 
    public static function edit()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        $empId = $_GET['id'] ?? null;
        $employee = Employee::find($empId);
        $contact = EmployeeContact::findByEmpId($empId);
        $contract = Contract::findByEmpId($empId);
        $positions = Position::all();
        $documents = Document::allByEmployee($empId);

        if ($employee === null) {
            echo 'ไม่พบพนักงาน';
            return;
        }

        require BASE_PATH . '/views/layouts/header.php';
        require BASE_PATH . '/views/employees/edit.php';
        require BASE_PATH . '/views/layouts/footer.php';
    }

    // Save / update form
    public static function update()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        $empId = $_POST['emp_id'] ?? null;

        $employeeData = [
            'emp_no' => sanitize($_POST['emp_no'] ?? ''),
            'emp_prefix_th' => sanitize($_POST['emp_prefix_th'] ?? ''),
            'emp_name_th' => sanitize($_POST['emp_name_th'] ?? ''),
            'emp_sname_th' => sanitize($_POST['emp_sname_th'] ?? ''),
            'emp_nickname_th' => sanitize($_POST['emp_nickname_th'] ?? ''),
            'emp_prefix_en' => sanitize($_POST['emp_prefix_en'] ?? ''),
            'emp_name_en' => sanitize($_POST['emp_name_en'] ?? ''),
            'emp_sname_en' => sanitize($_POST['emp_sname_en'] ?? ''),
            'emp_nickname_en' => sanitize($_POST['emp_nickname_en'] ?? ''),
            'emp_idcard' => sanitize($_POST['emp_idcard'] ?? ''),
            'emp_idss' => sanitize($_POST['emp_idss'] ?? ''),
            'emp_birthday' => $_POST['emp_birthday'] ?? '',
            'emp_tel' => sanitize($_POST['emp_tel'] ?? ''),
            'emp_email' => sanitize($_POST['emp_email'] ?? ''),
            'emp_address' => sanitize($_POST['emp_address'] ?? ''),
            'emp_line' => sanitize($_POST['emp_line'] ?? ''),
            'emp_cancel' => sanitize($_POST['emp_cancel'] ?? ''),

        ];

        $contactData = [
            'emp_id' => sanitize($_POST['emp_id'] ?? ''),
            'contact_type' => sanitize($_POST['contact_type'] ?? ''),
            'name' => sanitize($_POST['name'] ?? ''),
            'relationship' => sanitize($_POST['relationship'] ?? ''),
            'tel' => sanitize($_POST['tel'] ?? ''),
            'is_primary' => sanitize($_POST['is_primary'] ?? '1'),
        ];

        $contractData = [
            'emp_id' => sanitize($_POST['emp_id'] ?? ''),
            'cont_position' => sanitize($_POST['cont_position'] ?? ''),
            'cont_start_date' => sanitize($_POST['cont_start_date'] ?? ''),
            'cont_duration_time' => sanitize($_POST['cont_duration_time'] ?? ''),
            'cont_status' => sanitize($_POST['cont_status'] ?? ''),
            'cont_salary' => sanitize($_POST['cont_salary'] ?? ''),
            'cont_bank' => sanitize($_POST['cont_bank'] ?? ''),
            'cont_bank_no' => sanitize($_POST['cont_bank_no'] ?? ''),
        ];

        $conn = Database::connect();

        try {

            Employee::update($empId, $employeeData, $conn);
            EmployeeContact::update($empId, $contactData, $conn);
            Contract::update($empId, $contractData, $conn);
        } catch (PDOException $e) {
            $conn->rollBack();
            echo 'บันทึกไม่สำเร็จ: ' . $e->getMessage();
            return;
        }

        redirect('/employees');
    }


    // Soft del employee
    public function cancel()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        if (Auth::isAdmin() === false) {
            redirect('/profile');
        }

        $empId = $_POST['emp_id'] ?? null;

        if (empty($empId)) {
            redirect('/employees');
        }

        Employee::cancel($empId);

        redirect('/employees');
    }

    public function reactivate()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        if (Auth::isAdmin() === false) {
            redirect('/profile');
        }

        $empId = $_POST['emp_id'] ?? null;

        if (empty($empId)) {
            redirect('/employees');
        }

        Employee::reactivate($empId);

        redirect('/employees');
    }
}
