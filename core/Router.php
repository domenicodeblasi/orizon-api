<?php

class Router {
    protected $routes;
    public function __construct($routes) {
        $this->routes = $routes;
    }
    public function directTo($method, $uri) {
        if (array_key_exists($method, $this->routes)) {   
            // uri structure -> "api/countries" - "api/trips/:something" - "api/countries/:something"
            $pattern = "/^api\/(countries|trips)([\/?][\w=&]+)?\/?$/";
            if (preg_match($pattern, $uri)) {
                $firstPartUri = $this->getFirstPartUri($uri);
                if (array_key_exists($firstPartUri, $this->routes[$method])) {
                    return $this->routes[$method][$firstPartUri];
                } else {
                    return "errors/404.php";
                }
            } else {
                return "errors/400.php";
            }
        } else {
            return "errors/405.php";
        }
    }

    private function getFirstPartUri($uri) {
        $explosedBySlash = explode("/", $uri);
        $explosedByQMark = explode("?", $explosedBySlash[1]);
        return $explosedBySlash[0] . "/" . $explosedByQMark[0];
    }
}