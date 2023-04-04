<?php
namespace Tools;

class Document 
{
    private $fileName;
    private $tempName;
    private $extension;
    private $size;

    function __construct($path, $rename, $folder = null){
        if(isset($_FILES['file']) || !$_FILES['file']['error'] == UPLOAD_ERR_NO_FILE) {
            $this->setExtension($_FILES['file']['type']);
            $this->setTempName($_FILES['file']['tmp_name']);
            $this->setFileName(basename($_FILES['file']['name']));
            $this->setSize($_FILES['file']['size']);
            move_uploaded_file($this->getTempName(), $this->getFileName());
            if(strpos($rename, explode("/", $this->getExtension())[1]) == false){
                $rename .= ".".explode("/", $this->getExtension())[1];
            } 
		    $this->save($path, $rename, $folder);
        }
    }
    public function save($pathName, $rename, $folder = null){
        $upload_dir = $this->getFileName();

        if(!is_dir( "../".$pathName."/".$folder)){
            mkdir("../".$pathName."/".$folder);
        }
        rename($upload_dir, "../".$pathName."/".$folder."/".$rename);

    }

    public function exist(){
        return file_exists($this->getFileName());
    }

    /**
     * Get the value of size
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the value of size
     */
    public function setSize($size): self
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get the value of extension
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set the value of extension
     */
    public function setExtension($extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get the value of fileName
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set the value of fileName
     */
    public function setFileName($fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get the value of tempName
     */
    public function getTempName()
    {
        return $this->tempName;
    }

    /**
     * Set the value of tempName
     */
    public function setTempName($tempName): self
    {
        $this->tempName = $tempName;

        return $this;
    }

}