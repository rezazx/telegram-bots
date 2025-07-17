<?php
namespace MRZX;
use DB;

class Tools
{

    public function __construct(){

    }

    public static function safeReadInput($t,$html=false)
    {
        $t=trim($t);
        $t=stripslashes($t);
        if(!$html){
            $t=strip_tags($t);
            $t=htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
        }
        return $t;
    }

    public static function sanitize($t)
    {
        return self::safeReadInput($t);
    }

    public static function sanitizeArray(array $data){
        $cleanData = [];
    
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // If the value is an array, clean it recursively
                $cleanData[$key] = self::sanitizeArray($value);
            } else {
                // Remove extra spaces, strip tags, and convert special characters to HTML entities
                $cleanData[$key] = self::safeReadInput($value);
            }
        }
    
        return $cleanData;
    }
    
    public static function sSql($t,$html=false,$mysqli=null){
        if(is_null($mysqli))
           $mysqli = DB::get();
           
        $t=$mysqli->real_escape_string(Tools::safeReadInput($t,$html));
        return $t;
    }

    public static function postValue($str,$defValue=false)
    {
        if (!isset($str) || empty($str) || !is_string($str)) {
            return false;
        }
        $str=Tools::safeReadInput($str);
        $str=(isset($_POST[$str]))?Tools::safeReadInput($_POST[$str]):$defValue;
        return $str;
    }
    
    public static function getValue($str,$defValue=false)
    {
        if (!isset($str) || empty($str) || !is_string($str)) {
            return false;
        }
        $str=Tools::safeReadInput($str);
        $str=(isset($_GET[$str]))?Tools::safeReadInput($_GET[$str]):$defValue;
        return $str;
    }
    public static function postOrGet($str,$defValue=false)
    {
        return (Tools::postValue($str,$defValue))?Tools::postValue($str,$defValue):Tools::getValue($str,$defValue);
    }

    public static function test()
    {
        echo 'TOOLS Module is work!';
    }

    public static function encrypt($str){
        return md5(APP_KEY.$str);
    }

    public static function isEmail($email)
    {
        return !empty($email) && preg_match(Tools::cleanNonUnicodeSupport('/^[a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]+[.a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]*@[a-z\p{L}0-9]+(?:[.]?[_a-z\p{L}0-9-])*\.[a-z\p{L}0-9]+$/ui'), $email);
    }

    public static function isPhone($number)
    {
        return !empty($number) && preg_match('/^([0]{1})([9]{1})([0-9]{9})$/', $number);
    }

    public static function isString($data)
    {
        return is_string($data);
    }

    public static function isUrl($url)
    {
        return preg_match(Tools::cleanNonUnicodeSupport('/^[~:#,$%&_=\(\)\.\? \+\-@\/a-zA-Z0-9\pL\pS-]+$/u'), $url);
    }

    public static function cleanNonUnicodeSupport($pattern)
    {
        if (!defined('PREG_BAD_UTF8_OFFSET')) {
            return $pattern;
        }
        return preg_replace('/\\\[px]\{[a-z]{1,2}\}|(\/[a-z]*)u([a-z]*)$/i', '$1$2', $pattern);
    }

    public static function strtolower($str)
    {
        if (is_array($str)) {
            return false;
        }
        if (function_exists('mb_strtolower')) {
            return mb_strtolower($str, 'utf-8');
        }
        return strtolower($str);
    }

    public static function getRequestUri($n=0)
    {
        $req = Tools::safeReadInput(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
        $req=explode('?',$req)[0];
        if(_BASE_PATH_!='/')
            $req=str_replace(_BASE_PATH_,'',$req);
        if(count(explode('/',$req))<=$n )
            return false;
        $req=explode('/',$req)[$n];
        return $req;
    }

    /**
     * get current client ip
     *
     * @return string
     */
    public static function getClientIp() {
        if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) )
            $ip = Tools::safeReadInput($_SERVER['HTTP_CLIENT_IP']);
        elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
            $ip = Tools::safeReadInput($_SERVER['HTTP_X_FORWARDED_FOR']);
        elseif(isset($_SERVER['REMOTE_ADDR']))
            $ip = Tools::safeReadInput($_SERVER['REMOTE_ADDR']);
        
        return $ip;
    }

    /**
     * get current client user_agent
     *
     * @return string
     */
    public static function getClientAgent(){
        return Tools::safeReadInput($_SERVER['HTTP_USER_AGENT']);
    }

    public static function sessionStart()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function createCaptcha($name='captcha-code')
    {
        Tools::sessionStart();
        $im = imagecreate(128, 44)or die("Cannot Initialize new GD image stream");
        $background_color = imagecolorallocate($im, 250, 250, 250); 
        $red = imagecolorallocate($im, 255, 100, 100);
        $blue = imagecolorallocate($im,100,100,255);
        $green = imagecolorallocate($im,100,255,100);
        $white=imagecolorallocate($im,250,250,250);

        $strnum=substr(str_shuffle('ABCDEFGHMRZX123456789'),0,6);
        $_SESSION[$name]=strtolower($strnum);

        for($i=0;$i<3;$i++){
            imageline ($im,  rand(2,35),  rand(0,45), rand(40,125), rand(10,40), $red);
            imageline ($im,  rand(2,35),  rand(0,45), rand(40,125), rand(10,40), $blue);        
            imageline ($im,  rand(2,35),  rand(0,45), rand(40,125), rand(10,40), $green);
            imagefilledellipse($im, rand(10,110),  rand(0,45), 10, 10, $white);
            imagefilledellipse($im, rand(10,110),  rand(0,45), 5, 10, $green);
            imagefilledellipse($im, rand(10,110),  rand(0,45), 5, 5, $blue);
            imagefilledellipse($im, rand(10,110),  rand(0,45), 7, 12, $red);
        }
        $font=5;
        $ff=[__DIR__.'/../front/iransans.ttf',__DIR__.'/../front/lobster.otf'];

    
        imagettftext($im, 22,0, 5 ,  rand(30,40),imagecolorallocate($im, rand(0,80), rand(0,100),rand(50,150)),$ff[rand(0,1)],$strnum[0] );
        imagettftext($im, 22,0, 25,  rand(30,40),imagecolorallocate($im, rand(0,80), rand(0,100),rand(50,150)),$ff[rand(0,1)],$strnum[1] );
        imagettftext($im, 23,0, 45,  rand(30,40),imagecolorallocate($im, rand(0,80), rand(0,100),rand(50,150)),$ff[rand(0,1)],$strnum[2] );
        imagettftext($im, 22,0, 65,  rand(30,40),imagecolorallocate($im, rand(0,80), rand(0,100),rand(50,150)),$ff[rand(0,1)],$strnum[3] );
        imagettftext($im, 22,0, 85,  rand(30,40),imagecolorallocate($im, rand(0,80), rand(0,100),rand(50,150)),$ff[rand(0,1)],$strnum[4] );
        imagettftext($im, 24,0, 105,  rand(30,40),imagecolorallocate($im, rand(0,80), rand(0,100),rand(50,150)),$ff[rand(0,1)],$strnum[5] );

        for($i=0;$i<3;$i++){
            imageline ($im,  rand(2,35),  rand(0,45), rand(40,125), rand(10,40), $red);
            imageline ($im,  rand(2,35),  rand(0,45), rand(40,125), rand(10,40), $blue);        
            imageline ($im,  rand(2,35),  rand(0,45), rand(40,125), rand(10,40), $green);
        }

        ob_start();
		imagepng($im);
		$img64 = base64_encode(ob_get_clean());
        imagedestroy($im);
		return '<img src="data:image/png;base64,'.$img64.'" />';  
    }

    public static function checkCaptcha($name='captcha-code',$input='captchacode')
    {
        Tools::sessionStart();
        $r=false;
        if(isset($_SESSION[$name]) && !empty($_SESSION[$name]) && strtolower(Tools::postOrGet($input))==$_SESSION[$name])
            $r=true;
        $strnum=substr(str_shuffle('@#$&*()IJKLMNOPQRSTU1234567890'),0,8);
        $_SESSION[$name]=strtolower($strnum);
        return $r;
    }
}
