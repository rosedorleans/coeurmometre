<?php
namespace Tools;

class Response{

  private $success;
  private $code;
  private $type;
  private $contentText;
  private $contentHtml;
  private $uri;
  private $dataMessage;
  private $data;
  private $structure;

  //success const
  CONST SUCCESS_INSERT = 0001;
  CONST SUCCESS_PUT = 0002;
  CONST SUCCESS_DELETE = 0003;
  CONST SUCCESS_GET = 0004;
  CONST SUCCESS_LOGIN = 0005;

  //warn const

  //error const
  CONST ERROR_ACCESS = 1000;
  CONST ERROR_LOGIN = 1001;
  CONST ERROR_CALL = 1002;
  CONST ERROR_METHOD = 1003;

  CONST ERROR_RECORD_INTERNAL = 1104;
  CONST ERROR_RECORD_EMPTY = 1105;
  CONST ERROR_RECORD_ALREADY = 1106;


  CONST ERROR_PUT_INTERNAL = 1107;
  CONST ERROR_PUT_EMPTY = 1108;
  CONST ERROR_PUT_ALREADY = 1109;
  CONST ERROR_PUT_PASSWORD = 1110;

  CONST ERROR_DELETE_INTERNAL = 1111;
  CONST ERROR_DELETE_EMPTY = 1112;
  CONST ERROR_DELETE_ALREADY = 1113;

  CONST ERROR_GET_EMPTY = 1114;
  CONST ERROR_GET_INDENTIFIER = 1115;
  CONST ERROR_POST = 1116;
  CONST ERROR_GET_RESSOURCE = 1117;
  
  CONST ERROR_INTERNAL = 1118;

  //LAW

  CONST ERROR_LAW = 1500;

  //not callable

    /**
     * @return mixed
     */
    public function renderResponse($code, $data = null){
      $this->prepareReponse($code);
      $this->data = $data;
      $this->structure = array(
                        "success" => $this->success,
                        "messages" => [
                            "type" => $this->type,
                            "contentText" => $this->contentText,
                            "code" => $this->code,
                            ],
                        "data" => $this->data
                      );
        $this->setHTTPHeader();
        if(getenv('IS_TEST')){
          return json_encode($this->structure);
        }else{
          echo json_encode($this->structure);
          exit();
        }
    }

    protected function prepareReponse($code){
      $this->code = $code;
      if ($this->code < 100) {
        $this->success = true;
        $this->type = "Notification";

      }elseif($this->code < 500){
        $this->success = true;
        $this->type = "Notice";

      }else{
        $this->success = false;
        $this->type = "Error";
      }
      switch ($this->code) {
        //success
        case self::SUCCESS_INSERT:
          $this->contentText = "Successful record.";
          break;
        case self::SUCCESS_PUT:
          $this->contentText = "Successful update.";
          break;
        case self::SUCCESS_DELETE:
          $this->contentText = "Successful delete.";
          break;

        case self::SUCCESS_LOGIN:
          $this->contentText = "Successful login.";
          break;

        case self::SUCCESS_GET:
          $this->contentText = "Successful get.";
          break;

        //warn

        //error
        case self::ERROR_ACCESS:
          $this->contentText = "Access denied. You must be logged in to perform this operation.";
          break;

        case self::ERROR_RECORD_INTERNAL:
          $this->contentText = "Record failed. Internal error.";
          break;
        case self::ERROR_RECORD_EMPTY:
          $this->contentText = "Record failed. One of the values sent is empty.";
          break;
        case self::ERROR_RECORD_ALREADY:
          $this->contentText = "Record failed. This record already exists.";
          break;

        case self::ERROR_PUT_INTERNAL:
          $this->contentText = "Update Failed. Internal error.";
          break;
        case self::ERROR_PUT_EMPTY:
          $this->contentText = "Update failed. The identifier is empty.";
          break;
        case self::ERROR_PUT_ALREADY:
          $this->contentText = "Update failed. This record doesn't exists.";
          break;
        case self::ERROR_PUT_PASSWORD:
          $this->contentText = "Update failed. Invalid password.";
          break;

        case self::ERROR_DELETE_INTERNAL:
          $this->contentText = "Delete Failed. Internal error.";
          break;
        case self::ERROR_DELETE_EMPTY:
          $this->contentText = "Delete failed. The identifier is empty.";
          break;
        case self::ERROR_DELETE_ALREADY:
          $this->contentText = "Delete failed. Resource not found.";
          break;

        case self::ERROR_GET_EMPTY:
          $this->contentText = "Get failed. No record";
          break;
          
        case self::ERROR_GET_RESSOURCE:
          $this->contentText = "Ressource not found. Check the data sent.";
          break;

        case self::ERROR_GET_INDENTIFIER:
          $this->contentText = "Get failed. The identifier is empty.";
          break;

        case self::ERROR_POST:
          $this->contentText = "Post failed. Check the data sent.";
          break;

        case self::ERROR_LOGIN:
          $this->contentText = "Wrong email or password.";
          break;
        case self::ERROR_CALL:
          $this->contentText = "Invalid call.";
          break;
        case self::ERROR_METHOD:
          $this->contentText = "Invalid method.";
          break;
          
        case self::ERROR_LAW:
          $this->contentText = "Vous n'êtes pas autorisé a effectuer cette action.";
          break;

        default:
          # code...
          break;
      }
    }

    private function setHTTPHeader(){
      if(!getenv('IS_TEST')){
        header('Content-Type: application/json');
      }
      
      if ($this->code < 100) {
        http_response_code(200);
        return;
      }

      switch ($this->code) {
        //error
        case self::ERROR_ACCESS:
          http_response_code(403);
          break;

        case self::ERROR_RECORD_EMPTY:
          http_response_code(404);
          break;
          
        case self::ERROR_DELETE_ALREADY:
          http_response_code(404);
          break;

        case self::ERROR_GET_EMPTY:
          break;

        case self::ERROR_POST:
          http_response_code(400);
          break;

        case self::ERROR_LOGIN:
          http_response_code(401);
          break;

        case self::ERROR_CALL:
          http_response_code(404);
          break;

        case self::ERROR_METHOD:
          http_response_code(405);
          break;

        default:
            http_response_code(200);
          break;
      }
    }
    /**
     * @return mixed
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @param mixed $success
     *
     * @return self
     */
    public function setSuccess($success)
    {
        $this->success = $success;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     *
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContentText()
    {
        return $this->contentText;
    }

    /**
     * @param mixed $contentText
     *
     * @return self
     */
    public function setContentText($contentText)
    {
        $this->contentText = $contentText;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContentHtml()
    {
        return $this->contentHtml;
    }

    /**
     * @param mixed $contentHtml
     *
     * @return self
     */
    public function setContentHtml($contentHtml)
    {
        $this->contentHtml = $contentHtml;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     *
     * @return self
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataMessage()
    {
        return $this->dataMessage;
    }

    /**
     * @param mixed $dataMessage
     *
     * @return self
     */
    public function setDataMessage($dataMessage)
    {
        $this->dataMessage = $dataMessage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * @param mixed $structure
     *
     * @return self
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;

        return $this;
    }
}
