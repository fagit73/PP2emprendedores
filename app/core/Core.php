<?php
class Core {
    protected $controladorActual = 'AuthController';
    protected $metodoActual = 'login';
    protected $parametros = [];

    public function __construct() {
        $url = $this->getUrl();

        // Buscar controlador
        if (isset($url[0]) && file_exists('app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controladorActual = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        require_once 'app/controllers/' . $this->controladorActual . '.php';
        $this->controladorActual = new $this->controladorActual;

        // Buscar método
        if (isset($url[1])) {
            if (method_exists($this->controladorActual, $url[1])) {
                $this->metodoActual = $url[1];
                unset($url[1]);
            }
        }

        // Obtener parámetros
        $this->parametros = $url ? array_values($url) : [];

        // LA LÍNEA QUE FALTABA: Ejecutar el método del controlador con los parámetros
        call_user_func_array([$this->controladorActual, $this->metodoActual], $this->parametros);
    }

    public function getUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}