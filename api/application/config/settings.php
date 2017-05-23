<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//genel ayarlar
$config['upload_path']='../img/';
$config['base_path']='img/';
$config['image_lib']='gd2';
$config['allowed_types1']='gif|jpg|png|Jpeg|Png|Gif|JPEG|PNG|GIF|jpeg';
$config['default_img']='default.jpg';

$config['error']=array(
    0 => 'Giriş bilgileri hatalı',
    1=> 'Database hatası oluştu',
    2=> 'Bilinmeyen bir hata oluştu',
    3=> 'Dosya yükleme hatası',
    10=> 'Ürün bulunamadı yada pasif',
    11 => 'Bu grup adı daha önceden tanımlanmış',
    13 => 'Varyant eklenemedi',
    20=> 'Kayıt bulunamadı',
    50=> 'Lütfen gerekli alanları doldurun',
    60=> 'En az bir kategori seçmelisiniz',
);

$config['product_amount_info']=1; // 0= Sistem stok tutacak / 1=Sistem stok tutmayacak 

//ürün fotoğraf boyutları
$config['image_size']=array(
    0 =>array(123,163),
    1 =>array(240,312),
    2 =>array(255,332),
    3 =>array(515,670)
);

//ticket categories
$config['tickets']=array(
    1 =>"Müşteri Hizmetleri",
    2 =>"Muhasebe",
    3 =>"Teknik Desktek"
);

$config['payment_methods']=array(
    7 =>"PayU",
    14 =>"Kapıda Nakit Ödeme",
    15 =>"Kredi/Banka Kartı İle Ödeme",
    16 =>"Kapıda Kredi Kartı İle Ödeme",
    17 =>"Havale İle Ödeme",
    18 =>"EFT"
);

//Slider Backgorund
$config['slider']=array(
    1 =>"bg-blue",
    2 =>"bg-grey",
    3 =>"bg-orange",
    4 =>"bg-green"
);


//front end kısmında kullanılan tema seçimi
$config['front_theme']=array(
    0=>'tema_0',
    1=>'tema_1',
    2=>'tema_2',
);



//aktif olan front end tema seçimi
$config['active_front_theme']=0;


$config['google_site_key']='6LeoMhMTAAAAAHU5mVRk7J8zu1CmGE9mWNV79GPZ';
$config['google_site_secret']='6LeoMhMTAAAAAH44WqTX3Vq15U96KA0ugfrR7saG';
?>
