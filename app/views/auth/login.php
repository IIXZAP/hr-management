<?php
// views/auth/login.php
// หน้าที่: ฟอร์ม login
// เวอร์ชัน basic — form POST ไปที่ backend จริง (/login/submit)
// รับตัวแปร $loginError มาจาก AuthController::showLogin() (เป็น null ถ้าไม่มี error)
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ | HR Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1D4ED8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        },
                        bgsoft: '#F8FAFC'
                    },
                    fontFamily: {
                        sans: ['Noto Sans Thai', 'Inter', 'sans-serif']
                    }
                }
            }
        };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.2/flowbite.min.css" rel="stylesheet" />
</head>

<body class="font-sans bg-gray-50 dark:bg-gray-900 min-h-screen">

    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto min-h-screen">
            <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <div class="w-9 h-9 mr-2 rounded-lg bg-primary-700 text-white flex items-center justify-center font-bold text-sm">HR</div>
                HR Center
            </a>
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        เข้าสู่ระบบ
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 -mt-2">กรอกรหัสพนักงานหรืออีเมล เพื่อเข้าใช้งานระบบ</p>

                    <?php if ($loginError !== null): ?>
                        <div class="flex items-start gap-2 bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 text-sm px-4 py-3 rounded-lg border border-red-200 dark:border-red-500/20">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none">
                                <path d="M12 9v4M12 17h.01M10.3 3.9L2.7 18a1.5 1.5 0 001.3 2.2h16a1.5 1.5 0 001.3-2.2L13.7 3.9a1.5 1.5 0 00-2.6 0z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                            </svg>
                            <span><?php echo htmlspecialchars($loginError); ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="/login/submit" method="POST" class="space-y-4 md:space-y-6">
                        <div>
                            <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">รหัสพนักงานหรืออีเมล</label>
                            <input type="text" id="username" name="username" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="EMP-1001 หรือ name@company.com">
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">รหัสผ่าน</label>
                            <div class="relative">
                                <input type="password" id="password" name="password" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 pr-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <button type="button" onclick="togglePw()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" stroke="currentColor" stroke-width="1.6" />
                                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            เข้าสู่ระบบ
                        </button>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                            © 2026 HR Center
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        function togglePw() {
            const el = document.getElementById("password");
            el.type = el.type === "password" ? "text" : "password";
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.2/flowbite.min.js"></script>
</body>

</html>