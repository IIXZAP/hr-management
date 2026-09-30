<?php
// จับคู่ path กับ controller

class Router 
{
   private $routes = [];

   public function add($path, $controllerName, $methodName)
   {
        $this->routes[$path] = [
            'controller' => $controllerName,
            'method' => $methodName
        ];
   }

   // path ทั้งหมดที่ลงทะเบียนไว้ (ใช้ตอน rewrite ลิงก์ในโหมด query)
   public function paths()
   {
        return array_keys($this->routes);
   }

   public function dispatch()
   {
        // โหมด query (?r=login) ใช้บน server ที่ไม่มี rewrite, โหมด path (/login) ใช้บน localhost
        if (isset($_GET['r']) && is_string($_GET['r'])) {
            $currentPath = '/' . trim($_GET['r'], '/');
        } else {
            $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            // เข้าหน้าแรก (/ หรือ /index.php) ส่งไปหน้าที่เหมาะสม
            if ($currentPath === '/' || $currentPath === '/index.php') {
                redirect(Auth::check() ? '/dashboard' : '/login');
            }
        }

        // Check 
        if(isset($this->routes[$currentPath]) === false) {
            http_response_code(404);
            require BASE_PATH . '/views/shared/layouts/header.php';
            require BASE_PATH . '/views/shared/errors/404.php';
            require BASE_PATH . '/views/shared/layouts/footer.php';
            
            return;
        }

        $route = $this->routes[$currentPath];
        $controllerName = $route['controller'];
        $methodName = $route['method'];

        require_once BASE_PATH . '/controllers/' . $controllerName . '.php';

        $controller = new $controllerName();
        $controller->$methodName();

   }
}