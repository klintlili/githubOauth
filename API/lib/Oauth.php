<?php
namespace API\lib;
use API\lib\ErrorCase;
use API\lib\URL;
use API\lib\Recorder;

class Oauth{

    const VERSION = "1.0";
    const GET_AUTH_CODE_URL = "https://github.com/login/oauth/authorize";
    const GET_ACCESS_TOKEN_URL = "https://github.com/login/oauth/access_token";

    protected $recorder;
    public $urlUtils;
    protected $error;
    

    function __construct(){
        $this->recorder = new Recorder();
        $this->urlUtils = new URL();
        $this->error = new ErrorCase();
    }

    public function github_login(){
        $appid = $this->recorder->readInc("appid");
        $callback = $this->recorder->readInc("callback");
        $scope = $this->recorder->readInc("scope");

        //-------生成唯一随机串防CSRF攻击
        $state = md5(uniqid(rand(), TRUE));
        $this->recorder->write('state',$state);

        //-------构造请求参数列表
        $keysArr = array(
            //"response_type" => "code",
            "client_id" => $appid,
            "redirect_uri" => $callback,
            "state" => $state,
            "scope" => $scope
        );

        $login_url =  $this->urlUtils->combineURL(self::GET_AUTH_CODE_URL, $keysArr);

        header("Location:$login_url");
    }

    public function github_callback(){
        $state = $this->recorder->read("state");

        //--------验证state防止CSRF攻击
        if($_GET['state'] != $state){
            $this->error->showError("30001");
        }

        //-------请求参数列表
        $keysArr = array(
            //"grant_type" => "authorization_code",
            "client_id" => $this->recorder->readInc("appid"),
            //"redirect_uri" => urlencode($this->recorder->readInc("callback")),
            "client_secret" => $this->recorder->readInc("appkey"),
            "code" => $_GET['code']
        );

        //------构造请求access_token的url
        //$token_url = $this->urlUtils->combineURL(self::GET_ACCESS_TOKEN_URL, $keysArr);
        $response = $this->urlUtils->post(self::GET_ACCESS_TOKEN_URL,http_build_query($keysArr));
        var_dump($response);
        
        $params = array();
        parse_str($response, $params);
        if(isset($params['error'])) {
            //看看github的有关出错时的响应是什么
            $this->error->showError($params['error'], $params['error']);
        }
        if(isset($params["access_token"])){
            //保存到recorder对象中，
            $this->recorder->write("access_token", $params["access_token"]);
            return $params["access_token"];
        }

    }

}
