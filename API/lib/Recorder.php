<?php
namespace API\lib;
use API\lib\ErrorCase;
class Recorder{
    private static $data;
    private $inc;
    private $error;

    public function __construct(){
        $this->error = new ErrorCase();

        //-------读取原始配置信息
        $incFileContents = '{"appid":"xxxx52a273c4606b7xxx","appkey":"xxxxxxxe7fb651ea17926484721a4f59exxxxx","callback":"http://mysite.com/API/callback.php","scope":"user"}';
        $this->inc = json_decode($incFileContents);
        if(empty($this->inc)){
            $this->error->showError("20001");
        }

        if(empty($_SESSION['QC_userData'])){
            self::$data = array();
        }else{
            self::$data = $_SESSION['QC_userData'];
        }
    }

    public function write($name,$value){
        self::$data[$name] = $value;
    }

    public function read($name){
        if(empty(self::$data[$name])){
            return null;
        }else{
            return self::$data[$name];
        }
    }

    /**
     * 读取配置信息
     * @param unknown $name
     * @return NULL
     */
    public function readInc($name){
        if(empty($this->inc->$name)){
            return null;
        }else{
            return $this->inc->$name;
        }
    }

    public function delete($name){
        unset(self::$data[$name]);
    }

    function __destruct(){
        $_SESSION['QC_userData'] = self::$data;
    }
}
