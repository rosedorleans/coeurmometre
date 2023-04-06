<?php
namespace Value;

Use Value\Value;
use Value\ValueDatabase;
Use Tools\MainController;
Use Tools\Response;

class ValueController extends MainController{
    private $route = ["getAllValue", "getDistinctCategory", "getDistinctUser"];

    /**
     * @param String|null $call: function name
     * Execute function who's called
     * Use to call function from MainController
     * If call is null, try to use default method (get/create/update/delete) switch request type (GET/POST/PUT/DELETE)
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
                    $O_response->renderResponse(Response::ERROR_CALL);
                }
            }else{ 
                $method = getenv('REQUEST_METHOD');
                switch($method){
                    case 'GET':
                        $link = $_SERVER['PHP_SELF'];
                        $link_array = explode('/',$link);
                        // $this::get(end($link_array));
                        break;
                    case 'POST':
                        $json = file_get_contents('php://input');
                        $T_data = json_decode($json);
                        
                        if(empty($T_data)){
                            $T_data = $_POST;
                        }
                        $this::create($T_data);
                        break;
                    case 'PUT':
                        parse_str(file_get_contents("php://input"),$T_data);
                        $this::update($T_data);
                        break;
                    default:
                        $O_response->renderResponse(Response::ERROR_CALL);
                        break;
                }
            }
        }
    }
    
    // /**
    //  * @param int|null $id: Value id
    //  * @return JSON with Value object
    //  * Use to get Value object by id
    //  * If id is null, get all Value
    //  */

    // private function get($id = null){
    //     $O_response = new Response();
    //     $O_valueDatabase = new ValueDatabase();
    //     if($id){
    //         $O_value = $O_valueDatabase->getValue($id);
    //         $data=[
    //             "id" => $O_value->getId(),
    //             "value" => $O_value->getValue(),
    //             "category" => $O_value->getCategory(),
    //             "date" => $O_value->getDate(),
    //             "user" => $O_value->getUser()
    //         ];
    //     }else{
    //         $data =[];
    //         foreach($O_valueDatabase->getAllValue() as $O_value){
    //             $data[]=[
    //                 "id" => $O_value->getId(),
    //                 "value" => $O_value->getValue(),
    //                 "category" => $O_value->getCategory(),
    //                 "date" => $O_value->getDate(),
    //                 "user" => $O_value->getUser()
    //             ];
    //         }
    //     }
    //     $O_response->renderResponse(Response::SUCCESS_GET, $data);
    // }

    /**
	 * @param string category
	 * @param string user
	 * @return Json 
	 */
	public function getAllValue($category, $user){
		$O_response = new Response();
		$T_O_value = (new ValueDatabase())->getAllValue($category, $user);
        // var_dump($category);
		foreach($T_O_value as $O_value){
			if($O_value){
				$data[]=[
                    "id" => $O_value->getId(),
                    "value" => $O_value->getValue(),
                    "category" => $O_value->getCategory(),
                    "date" => $O_value->getDate(),
                    "user" => $O_value->getUser()
				];
			}else{
				$data[]=[
                    "id" => $O_value->getId(),
                    "value" => $O_value->getValue(),
                    "category" => $O_value->getCategory(),
                    "date" => $O_value->getDate(),
                    "user" => $O_value->getUser()
				];
			}
		}
		if(!empty($data))
			return $O_response->renderResponse(Response::SUCCESS_GET, $data);
		else
			return $O_response->renderResponse(Response::ERROR_GET_EMPTY);
	}

    /**
     * @param Array T_data
     * @return JSON with Value object
     */
    private function getDistinctCategory(){
        $O_response = new Response();
        $O_valueDatabase = new ValueDatabase();
        $data = [];
        foreach($O_valueDatabase->getDistinctCategory() as $O_value){
            $data[] = [
                "category" => $O_value->getCategory(),
            ];
        }
        $O_response->renderResponse(Response::SUCCESS_GET, $data);
    }
        /**
     * @param Array T_data
     * @return JSON with Value object
     */
    private function getDistinctUser(){
        $O_response = new Response();
        $O_valueDatabase = new ValueDatabase();
        $data = [];
        foreach($O_valueDatabase->getDistinctUser() as $O_value){
            $data[] = [
                "user" => $O_value->getUser(),
            ];
        }
        $O_response->renderResponse(Response::SUCCESS_GET, $data);
    }
 
    /**
     * @param Array T_data
     * @return JSON with Value object
     */

     private function create($T_data){
        $O_response = new Response();
        $O_value = new Value();
        $O_valueDatabase = new ValueDatabase();
        $O_value->hydrate($T_data);
        $O_value = $O_valueDatabase->createValue($O_value);
        if($O_value)
            $data=[
                "id" => $O_value->getId(),
                "value" => $O_value->getValue(),
                "category" => $O_value->getCategory(),
                "date" => $O_value->getDate(),
                "user" => $O_value->getUser()
            ];
            
		if(!empty($data))
            $O_response->renderResponse(Response::SUCCESS_INSERT, $data);
        else
            $O_response->renderResponse(Response::ERROR_GET_EMPTY);
    }

    /**
     * @param Array T_data
     * @return JSON with Value object
     */

    private function update($T_data){
        $O_response = new Response();
        $O_value = new Value();
        $O_valueDatabase = new ValueDatabase();
        $O_value->hydrate($T_data);
        $O_value = $O_valueDatabase->updateValue($O_value);
        if($O_value)
            $data=[
                "id" => $O_value->getId(),
                "value" => $O_value->getValue(),
                "category" => $O_value->getCategory(),
                "date" => $O_value->getDate(),
                "user" => $O_value->getUser()
            ];
            
		if(!empty($data))
            $O_response->renderResponse(Response::SUCCESS_PUT, $data);
        else
            $O_response->renderResponse(Response::ERROR_GET_EMPTY);
    }
}