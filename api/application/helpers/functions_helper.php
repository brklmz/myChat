<?php

function mysqldate_to_mer_date($tarih){
    $p=explode('-',$tarih);
    return $p[2].'-'.$p[1].'-'.$p[0];
    
}

function mer_date_to_mysqldate($tarih){
    $p=explode('-',$tarih);
    return $p[2].'-'.$p[1].'-'.$p[0];
    
}


function ay_ismi_bul($tarih){
    $pp=explode('-',$tarih);
    $aylar=array(
        '01'=>'Ocak',
        '02'=>'Şubat',
        '03'=>'Mart',
        '04'=>'Nisan',
        '05'=>'Mayıs',
        '06'=>'Haziran',
        '07'=>'Temmuz',
        '08'=>'Ağustos',
        '09'=>'Eylül',
        '10'=>'Ekim',
        '11'=>'Kasım',
        '12'=>'Aralık',
    );
    return $aylar[$pp[1]];
}

function hangi_ay($tarih){
    $pp=explode('-',$tarih);
    return intval($pp[1]);
}


function harf_buyut($text){
    $ara=array('ı','ö','ç','ş','i','ğ','ü');
    $degis=array('I','Ö','Ç','Ş','İ','Ğ','Ü');
    $text=str_replace($ara,$degis,$text);
    $text=strtoupper($text);
    return $text;
}

function price_format($number){
    $number=number_format($number, 2, '.', '');
    return $number;
}


function clean_string($string) {
  $s = trim($string);
  $s = iconv("UTF-8", "UTF-8//IGNORE", $s); // drop all non utf-8 characters

  // this is some bad utf-8 byte sequence that makes mysql complain - control and formatting i think
  $s = preg_replace('/(?>[\x00-\x1F]|\xC2[\x80-\x9F]|\xE2[\x80-\x8F]{2}|\xE2\x80[\xA4-\xA8]|\xE2\x81[\x9F-\xAF])/', ' ', $s);

  $s = preg_replace('/\s+/', ' ', $s); // reduce all multiple whitespace to a single space

  return $s;
}

function seolink($yazi){
   $ara = array('ş', 'Ş', 'ı', 'İ', 'ğ', 'Ğ', 'ü', 'Ü', 'ö', 'Ö', 'Ç', 'ç', '_');
   $degis = array('s', 's', 'i', 'i', 'g', 'g', 'u', 'u', 'o', 'o', 'c', 'c', '-');
   $yazi = str_replace($ara, $degis, $yazi);
   $yazi = str_replace(' ', '-', $yazi);
   $yazi = preg_replace('/[^0-9a-zA-Z-]/', ' ', $yazi);
   $yazi = str_replace('-', ' ', $yazi);
   $yazi = preg_replace('/^\s+|\s+$/', '', $yazi);
   $yazi = preg_replace('/\s+/', ' ', $yazi);
   $yazi = str_replace(' ', '-', $yazi);
   return strtolower($yazi);
}


//Email Gönderimi
 function email_send($to,$subject,$message,$from){
 
  $config = Array(
      'protocol' => 'smtp',
      'smtp_host' => 'ssl://etic7.miraec.com',
      'smtp_port' => 465,
      'smtp_user' => 'bilgi@etic7.miraec.com',
      'smtp_pass' => '2925125',
      'mailtype'  => 'html', 
      'charset'   => 'utf-8'
  );

    $CI =& get_instance();
    $CI->load->library('email', $config);
    $CI->email->set_newline("\r\n");
    $CI->email->from($from);
    $CI->email->to($to);
    $CI->email->subject($subject);
    $CI->email->message($message);
    if ( ! $CI->email->send()) {
        show_error($CI->email->print_debugger());
    } 

        
} 

?>