<?php
namespace Tools;

use DateTime;

class MainController {
    
    /**
     * MAIN Router
     * @param string $call 
     */
    public function __construct($call = null){
        $O_response = new Response();
        if(get_class($this) !== "Tools\MainController"){
            if(in_array ($call ,$this->route) ){
                if(method_exists($this,$call)){
                    $json = file_get_contents('php://input');
                    $T_data = json_decode($json, true);
                    
                    if(empty($T_data)){
                        $T_data = $_GET;
                    }
                    
                    if(empty($T_data)){
                        $T_data = $_POST;
                    }

                    if(!empty($T_data)){
                        call_user_func_array(get_class($this).'::'.$call, $T_data);
                    }else{
                        $this->$call();
                    }
                }else{
                    var_dump("ici". $this->call);
                    $O_response->renderResponse(Response::ERROR_CALL);
                }
            }else{ 
                $method = getenv('REQUEST_METHOD');
                switch($method){
                    case 'GET':
                        $link = $_SERVER['PHP_SELF'];
                        $link_array = explode('/',$link);
                        $this::get(end($link_array));
                        break;
                    case 'POST':
                        $json = file_get_contents('php://input');
                        $T_data = json_decode($json);
                        
                        if(empty($T_data)){
                            $T_data = $_POST;
                        }
                        $this::createOrUpdate($T_data);
                        break;
                    case 'PUT':
                        break;
                    case 'DELETE':
                        break;
                    default:
                        $O_response->renderResponse(Response::ERROR_CALL);
                        break;
                }
            }
        }
    }

    public function convertToFrenchDate($date){
        $date = new DateTime($date);
        return $date->format("d/m/Y");
    }

    public function convertToEnglishDate($date){
        $T_date = explode("/", $date);
        return $T_date[2] . '-' . $T_date[1] . '-' . $T_date[0];
    }

    public function hasRight(int $right){
        if(in_array($right, $_SESSION["right"]))
            return true;
        else
            return false;
    }
}