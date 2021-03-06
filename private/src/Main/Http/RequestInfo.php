<?php
/**
 * Created by PhpStorm.
 * User: p2
 * Date: 7/17/14
 * Time: 1:01 PM
 */

namespace Main\Http;


use Main\DB\Medoo\MedooFactory;
use Main\View\JsonView;

class RequestInfo {
    private $inputs = array(),
        $params = array(),
        $queries = array(),
        $files = array(),
        $method = 'GET',
        $url_params = array(),
        $auth_info = null;

    public function __construct($method, $queries, $params, $files, $url_params){
        $this->method = $method;
        $this->queries = $queries;
        $this->params = $params;
        $this->files = $files;
        $this->url_params = $url_params;

        $this->inputs = array_merge($this->queries, $this->params);
    }

    public static function loadFromGlobal(array $options = null)
    {
        $ctType = isset($_SERVER['CONTENT_TYPE'])? $_SERVER['CONTENT_TYPE']: null;
        $method = isset($_SERVER['REQUEST_METHOD'])? $_SERVER['REQUEST_METHOD']: 'GET';

        $files = array();
        if(preg_match('/^(application\/json)/', $ctType) != 0){ // or $ctType=='application/json'
            $jsonText = file_get_contents('php://input');
            $params = json_decode($jsonText, true);
            $params = array_merge($_GET, $params);
        }
        else if($method=='POST'){
            $params = $_POST;
            $files = $_FILES;
        }
        else if($method=='PUT' || $method == 'DELETE'){
            $put = array();
            if(strpos($ctType, 'multipart/form-data') !== false){
                $parse = ParseInput::multiPartFormData(file_get_contents('php://input'));
                $put = $parse['data'];
                $files = $parse['files'];
            }
            else {
                parse_str(file_get_contents("php://input"), $put);
            }
            $params = $put;
        }
        else {
            $params = $_GET;
        }

        if(isset($options['url_params'])){
            $url_params = $options['url_params'];
        }
        else {
            $url_params = array();
        }

        return new self($method, $_GET, $params, $files, $url_params);
    }

    public function params()
    {
        return $this->params;
    }

    public function param($name, $default = null){
        return isset($this->params[$name])? $this->params[$name]: $default;
    }

    public function hasParam($name){
        return isset($this->params[$name]);
    }

    public function inputs()
    {
        return $this->inputs;
    }

    public function input($name, $default = null)
    {
        return isset($this->inputs[$name])? $this->inputs[$name]: $default;
    }

    public function hasInput($name){
        return isset($this->inputs[$name]);
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function file($name){
        return isset($this->files[$name])? $this->files[$name]: null;
    }

    public function files(){
        return $this->files;
    }

    /**
     * @return array
     */
    public function urlParams()
    {
        return $this->url_params;
    }

    public function urlParam($name)
    {
        return $this->url_params[$name];
    }

    // request auth
    public function getAuthAccount(){
        $token = $this->input("auth_token");
        if(is_null($this->auth_info) && !is_null($token)){
            $db = MedooFactory::getInstance();
            $this->auth_info = $db->get("account", "*", ["auth_token"=> $token]);
            if($this->auth_info === false){
                $view = new JsonView(array(
                    "error"=> array(
                        "code"=> 402,
                        "message"=> "authorize error"
                    )
                ));

                $view->render();
                exit();
            }
        }
        return $this->auth_info;
    }
}