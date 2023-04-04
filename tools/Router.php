<?php
namespace Tools;

Use Tools\Response;

class Router extends Response
{
  protected $class;
  protected $function;

//is callable
  /**
    * @param mixed nullable $class
    * @return Instance
    */
  public function __construct($class = null, $function = null){
    $namespace = ucfirst($class);
    $O_response = new Response();
    $this->class = (isset($class) ? ucfirst($class) . 'Controller' : null);

    if (is_null($this->class)) {
        include "templates/index.html";
        exit();
    }

    if (class_exists($namespace."\\".$this->class, true)) {
      $this->class = $namespace."\\".$this->class;
      $this->class = new $this->class($function);
    } else {
      $O_response->renderResponse(Response::ERROR_CALL);
    }
  }
}