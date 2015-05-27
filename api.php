<?php

/**
 * API class
 *
 * The base class that process a request.
 *
 * @author Nikos Kirtsis <nkirtsis@gmail.com>
 * @copyright 2015 Nikos Kirtsis
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
abstract class api
{

    protected $requestPath;
    protected $method;
    protected $entity;
    protected $id;
    protected $action;
    protected $filters;
    protected $input;

    public function __construct()
    {
        $this->requestPath    = $this->getRequestPath();
        $this->method         = $this->getMethod();
        $this->entity         = $this->getEntity();
        $this->id             = $this->getId();
        $this->action         = $this->getAction();
        $this->filters        = $this->getFilters();
        $this->input          = $this->getInput();
    }

    private function getRequestPath()
    {
        if (array_key_exists('request', $_REQUEST)) {
            return explode('/', trim($_REQUEST['request']));
        }
    }

    private function getMethod()
    {
        if (array_key_exists('REQUEST_METHOD', $_SERVER)) {
            return $_SERVER['REQUEST_METHOD'];
        }
    }

    private function getEntity()
    {
        if (count($this->requestPath) > 1) {
            return $this->requestPath[1];
        }
    }

    private function getId()
    {
        if (count($this->requestPath) > 2) {
            return $this->requestPath[2];
        }
    }

    private function getAction()
    {
        if (count($this->requestPath) > 3) {
            return $this->requestPath[3];
        }
    }

    private function getFilters()
    {
        $filters = $_REQUEST;
        array_shift($filters);

        return $filters;
    }

    private function getInput()
    {
        if ($this->method == 'POST' or $this->method == 'PUT') {
            return json_decode(file_get_contents("php://input"), true);
        }
    }

    protected function response($data, $status)
    {
        if (!headers_sent()) {
            header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
            header("Accept: application/json");
            header("Content-Type: application/json");
        }
        echo json_encode($data);
    }

    private function requestStatus($code)
    {
        $status = [
            200 => 'OK',
            201 => 'Created',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            409 => 'Conflict',
            500 => 'Internal Server Error',
        ];

        return ($status[$code]) ? $status[$code] : $status[500];
    }
}
