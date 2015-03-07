<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 10/2/2558
 * Time: 14:04
 */

namespace Main\Http;


use Main\Helper\ArrayHelper;

class FileUpload {
    private $uploaded = false;
    private $original_path, $original_name, $original_ext;
    private $path, $name, $ext;
    public static function load($file){
        return new self($file);
    }

    public function __construct($file){
        if(is_null($file)){
            $this->uploaded = false;
            return $this;
        }

        if(is_null($file["tmp_name"]) || $file["tmp_name"] == "" || !file_exists($file["tmp_name"])){
            $this->uploaded = false;
            return $this;
        }


        $this->original_path = $this->path = $file["tmp_name"];
        $this->original_name = $this->name = $file["name"];
        $this->original_ext = $this->ext = array_pop(explode(".", $file["name"]));
        $this->uploaded = true;
    }

    /**
     * @return boolean
     */
    public function isUploaded()
    {
        return $this->uploaded;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $ext
     */
    public function setExt($ext)
    {
        $this->ext = $ext;
    }

    /**
     * @return mixed
     */
    public function getOriginalPath()
    {
        return $this->original_path;
    }

    /**
     * @return mixed
     */
    public function getOriginalName()
    {
        return $this->original_name;
    }

    /**
     * @return mixed
     */
    public function getOriginalExt()
    {
        return $this->original_ext;
    }

    // function
    public function generateName($with_ext = false){
        $name = uniqid("file");
        if($with_ext)
            $name .= ".".$this->getExt();

        return $name;
    }

    public function move($des){
        $return = copy($this->getPath(), $des);
        @unlink($this->getPath());

        $this->path = $des;
        $this->name = array_pop(explode("/", $des));
        $this->ext = array_pop(explode(".", $this->name));

        return $return;
    }
}