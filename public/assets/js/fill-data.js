function fillTestData() {
        // สร้างรหัสพนักงานแบบสุ่มท้าย กันชนกับข้อมูลเดิมตอนกดทดสอบซ้ำหลายรอบ
        var randomSuffix = Math.floor(Math.random() * 9000 + 1000);

        // ---- แท็บ 1: ข้อมูลส่วนตัว ----
        setFieldValue('emp_no', 'EMP-' + randomSuffix);
        setFieldValue('emp_idcard', '1234567890123');
        setFieldValue('emp_prefix_th', 'นาย');
        setFieldValue('emp_name_th', 'ทดสอบ');
        setFieldValue('emp_sname_th', 'ระบบ');
        setFieldValue('emp_prefix_en', 'Mr.');
        setFieldValue('emp_name_en', 'Test');
        setFieldValue('emp_sname_en', 'System');
        setFieldValue('emp_nickname_th', 'เทส');
        setFieldValue('emp_nickname_en', 'Test');
        setFieldValue('emp_birthday', '1995-01-01');
        setFieldValue('emp_tel', '081-234-5678');
        setFieldValue('emp_email', 'test' + randomSuffix + '@example.com');
        setFieldValue('emp_line', 'test_line_id');
        setFieldValue('emp_address', '123 ถนนทดสอบ แขวงทดสอบ เขตทดสอบ กรุงเทพฯ 10000');

        // ---- แท็บ 2: ผู้ติดต่อฉุกเฉิน ----
        // เติมเฉพาะแถวแรกที่มีอยู่แล้วตอนโหลดหน้า (contacts[0][...])
        setFieldValue('contacts[0][name]', 'สมชาย ทดสอบ');
        setFieldValue('contacts[0][relationship]', 'บิดา');
        setFieldValue('contacts[0][tel]', '089-999-8888');
        setFieldValue('contacts[0][contact_type]', 'emergency');
        // ติ๊กให้แถวแรกเป็นผู้ติดต่อหลักอัตโนมัติ
        var primaryRadio = document.querySelector('input[name="primary_contact_index"][value="0"]');
        if (primaryRadio) primaryRadio.checked = true;

        // ---- แท็บ 3: บัญชีเข้าระบบ ----
        setFieldValue('username', 'test.user' + randomSuffix);
        setFieldValue('password', 'password123');

        // ---- แท็บ 4: สัญญาจ้าง ----
        // เลือกตำแหน่งแรกที่มีในลิสต์อัตโนมัติ (ไม่ hardcode id เพราะไม่รู้ id จริงล่วงหน้า)
        var positionSelect = document.querySelector('[name="cont_position"]');
        if (positionSelect && positionSelect.options.length > 1) {
            positionSelect.selectedIndex = 1; // ข้าม option แรกที่เป็น "-- เลือกตำแหน่ง --"
        }
        setFieldValue('cont_start_date', new Date().toISOString().slice(0, 10));
        setFieldValue('cont_duration_time', '12');
        setFieldValue('cont_salary', '15000');
        setFieldValue('cont_bank', 'ธนาคารกสิกรไทย');
        setFieldValue('cont_bank_no', '123-4-56789-0');

        alert('กรอกข้อมูลทดสอบเรียบร้อย ตรวจดูแต่ละแท็บแล้วกด "บันทึกพนักงานใหม่" ได้เลย');
    }