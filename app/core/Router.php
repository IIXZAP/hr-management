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

   public function dispatch()
   {
        // $currentPath = $_SERVER['REQUEST_URI'];
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Check 
        if(isset($this->routes[$currentPath]) === false) {
            http_response_code(404);
            require BASE_PATH . '/views/layouts/header.php';
            require BASE_PATH . '/views/errors/404.php';
            require BASE_PATH . '/views/layouts/footer.php';
            
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