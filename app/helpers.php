<?php
use Rakibhstu\Banglanumber\NumberToBangla;

if(!function_exists('css')){
    function css($file, $directory = 'css'){
        return asset("$directory/$file");
    }
}



if(!function_exists('js')){
    function js($file, $directory = 'js'){
        return asset("/$directory/$file");
    }
}



if (!function_exists('smsNumberFilter')) {
    function smsNumberFilter($numbers)
    {
        $validNumbers = [];

        foreach ($numbers as $number) {

            // remove space, dash, plus
            $number = preg_replace('/[^0-9]/', '', $number);

            // empty check
            if (empty($number)) {
                continue;
            }

            // BD number normalization
            if (strlen($number) === 10) {
                $number = '0' . $number;
            }

            // must be 11 digit & start with 01
            if (strlen($number) !== 11 || !str_starts_with($number, '01')) {
                continue;
            }

            $validNumbers[] = $number;
        }

        // remove duplicate numbers
        return array_values(array_unique($validNumbers));
    }
}



// send sms
function sendSms($number, $text){
    $url = "http://bulksmsbd.net/api/smsapi";
    $api_key = env('BULK_SMS_API_KEY', 'zectIEhXQYGyFV4JmBLl');
    $senderid = env('BULK_SMS_SENDER_ID', 'INNOVA IT');
    $number = $number;
    $message = $text;
    $data = [
        "api_key" => $api_key,
        "senderid" => $senderid,
        "number" => $number,
        "message" => $message
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}



function convert_base64_from_path($path){
    $path = public_path().$path;

    if (file_exists($path)) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
    return null;
}


function square_img($path = null){
    if($path){
        if(file_exists(public_path().$path)){
            return $path;
        }
    }

    return '/backend/images/square-img.png';
}
function welcome_img($path = null){
    if($path){
        if(file_exists(public_path().$path)){
            return $path;
        }
    }

    return '/img/demo-welcome.png';
}

function get_settings($title){
    return \App\Models\Setting::where('id', 1)->first()->$title ?? null;
}

function shortText($string,$length = 50){
    $string = strip_tags($string);
    if (strlen($string) > $length) {

        // truncate string
        $stringCut = substr($string, 0, $length);
        $endPoint = strrpos($stringCut, ' ');

        //if the string doesn't contain any space then it will cut without word basis.
        $string = $endPoint? substr($stringCut, 0, $endPoint)."..." : substr($stringCut, 0)."...";
    }
    return $string;
}

function day_convertion($day){
      $bnday ='';
       if($day =='Sat'){
        $bnday ='শনিবার';
       }
       if($day =='Sun'){
        $bnday ='রবিবার';
       }
       if($day =='Mon'){
        $bnday ='সোমবার';
       }
       if($day =='Tue'){
        $bnday ='মঙ্গলবার';
       }
       if($day =='Wed'){
        $bnday ='বুধবার';
       }
       if($day =='Thu'){
        $bnday ='বৃহস্পতিবার';
       }
       if($day =='Fri'){
        $bnday ='শুক্রবার';
       }
       return $bnday;
}
function user_img($path = null){
    if($path){
        if(file_exists(public_path().$path)){
            return $path;
        }
    }

    return '/img/user.png';
}

function cover_img($path = null){
    if($path){
        if(file_exists(public_path().$path)){
            return $path;
        }
    }

    return '/img/cover-img.png';
}


function sendMail($email, $text){
  \Mail::to($email)->send(new \App\Mail\MyTestMail($text));
}
function translate($val){
  return $val;
}



function sms_send($number,$message) {
    $url = "http://bulksmsbd.net/api/smsapi";
    $api_key = "K9H6k6kXhBy7dw39on2f";
    $senderid = "8809617627306";
    $number = $number;
    $message = $message;
    $data = [
        "api_key" => $api_key,
        "senderid" => $senderid,
        "number" => $number,
        "message" => $message
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     $response = curl_exec($ch);    curl_close($ch);
      return $response;
    }

if(!function_exists('bn2en')){

    function bn2en($number)
    {
        $bn_number = ["১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০"];
        $en_number = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];

        return str_replace($bn_number, $en_number, $number);
    }
}

if(!function_exists('en2bn')){

    function en2bn($number)
    {
        $bn_number = ["১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০"];
        $en_number = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];

        return str_replace($en_number, $bn_number, $number);
    }
}


if(!function_exists('en2arabic')){

  function en2arabic($number)
  {
      $arabic = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
      $en_number = ['0','1','2','3','4','5','6','7','8','9'];

      return str_replace($en_number, $arabic, $number);
  }
}


if(!function_exists('en2arabic')){

  function en2arabic($number)
  {
      $arabic = ['0','1','2','3','4','5','6'];
      $en_number = ['A+','A','B+','B','C','D','F'];

      return str_replace($en_number, $arabic, $number);
  }
}


if(!function_exists('bn2en_only')){

    function bn2en_only($number)
    {
        $bn_number = ["১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০"];
        $en_number = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];

        $string = str_replace($bn_number, $en_number, $number);

        $number = preg_replace('/[^0-9 ."\']/',null, $string);
        if($number == ''){
            return null;
        }
        return $number;
    }
}

function active_route($routes){

    $route_name = \Request::route()->getName();

    if(in_array($route_name, $routes)){
        return 'mm-active';
    }
    return '';
}
function active_expanded($routes){

    $route_name = \Request::route()->getName();

    if(in_array($route_name, $routes)){
        return 'true';
    }
    return 'false';
}


function gpaCalculationArbi($point){
    $gpa = 'راسب';
    if($point >=4){
     $gpa = 'ممتاز مرتفع';
    }
    elseif($point >=3.50){
      $gpa = 'ممتاز';
    }
    elseif($point >=3.00){
      $gpa = 'جيد جدا مرتفع';
    }
    elseif($point >=2.50){
      $gpa = 'جيد جدا';
    }
    elseif($point >=2.00){
      $gpa = 'جيد';
    }
    elseif($point >=1.50){
      $gpa = 'مقبول';
    }else{
      $gpa = 'راسب';
    }
    return $gpa;
}


function date_maker($date, $format)
{

    if ($date == null) {
        return '00:00';
    }
    return date_format(date_create($date), $format);
}

function bangla_month($date)
{
    $month = date_maker($date, 'm');
    $bangla_month = ['জানুয়ারী', 'ফেব্রুয়ারী', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
    $minus_index = ($month - 1);
    return $bangla_month[$minus_index];
}

function bangla_year($date)
{
    $month = date_maker($date, 'Y');

    return bangla_number($month);
}

function bangla_number($number)
{
    if (!is_numeric($number)) {
        return $number;
    }
    $bangla_number = new NumberToBangla();
    return $bangla_number->bnNum($number);
}
function bangla_bnWord($number)
{
    if (!is_numeric($number)) {
        return $number;
    }
    $bangla_number = new NumberToBangla();
    return $bangla_number->bnWord($number);
}
function bangla_month_year($date){
    return bangla_month($date)." ".bangla_year($date);
}

function bangla_date_month_year($date){
    return bangla_number(date_maker($date,'d' )).' '.bangla_month($date).", ".bangla_year($date);
}

