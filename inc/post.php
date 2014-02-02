<?php
/**
* Автор: Станислав Бойко
* Сайт: http://stanislavboyko.ru
* Copyright: 2013
*/
  if(isset($_POST['save_settings'])){    $status_job = intval($_POST['link']);
    $api_id = trim(strip_tags($_POST['api_id']));
    $payeer_number = trim(strip_tags($_POST['payeer_number']));
    $api_key = trim(strip_tags($_POST['api_key']));
    $shop_key = trim(strip_tags($_POST['shop_key']));
    //mer
    $merchant_uri = trim(strip_tags($_POST['merchant_uri']));
    $id_shop = intval($_POST['id_shop']);
    $pay_ammount = intval($_POST['pay_ammount']);
    $pay_curr = intval($_POST['pay_curr']);

    switch($pay_curr){    	case 1:
    	 $pay_curr = "RUB";
    	break;
    	case 2:
    	 $pay_curr = "USD";
    	break;
    	case 3:
    	 $pay_curr = "EUR";
    	break;    }
    $pay_description = strip_tags($_POST['pay_description']);

  $data = file_put_contents("data.conf",json_encode(array(
      'status' => $status_job,
      'api_id' => $api_id,
      'payeer_number' => $payeer_number,
      'api_key' =>  $api_key,
      'shop_key' => $shop_key,
      'merchant_uri' => $merchant_uri,
      'id_shop' => $id_shop,
      'pay_ammount' => $pay_ammount,
      'pay_curr' => $pay_curr,
      'pay_description' => $pay_description
  )));
  if(!$data) die("Error file created");

  echo "<strong style='color:green;'>Настройки успешно сохранены!</strong>";
  }
?>