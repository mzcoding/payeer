<?php
/**
* Автор: Станислав Бойко
* Сайт: http://stanislavboyko.ru
* Copyright: 2013
*/
require_once dirname(__FILE__).'/api/cpayeer.php';

class Payeer_Order{
 private $_db;

 public function __construct($db = null){
  	$this->_db = $db;
  	if($this->_db != null)
  	    $this->_db->query("SET NAMES utf8");
 }
 public function _db(){
  		return $this->_db;
 }
 public static function config(){ 	if(!file_exists("data.conf"))
       return false;
    $data_sign = file_get_contents("data.conf");
    $data = json_decode($data_sign);
    if(empty($data)) return false;

    return $data; }
 static function sign($arHash){
    $sign = strtoupper(hash('sha256', implode(":", $arHash)));

    return $sign;
 }
 //один товар
 function form_data($button_text = "Оплатить"){  if(!self::config())
      throw new Exception("Файл настроек не существует или пуст!");
    $order_id = uniqid(rand(1000, 9999999));
 	$form = "<form method='GET' action='".self::config()->merchant_uri."'>
             <input type='hidden' name='m_shop' value='".self::config()->id_shop."'>
             <input type='hidden' name='m_orderid' value='".$order_id."'>
             <input type='hidden' name='m_amount' value='".number_format(self::config()->pay_ammount, 2, '.', '')."'>
             <input type='hidden' name='m_curr' value='".self::config()->pay_curr."'>
             <input type='hidden' name='m_desc' value='".base64_encode(self::config()->pay_description)."'>
             <input type='hidden' name='m_sign' value='".self::sign(array(
               self::config()->id_shop,
               $order_id,
               number_format(self::config()->pay_ammount, 2, '.', ''),
               self::config()->pay_curr,
               base64_encode(self::config()->pay_description),
               self::config()->shop_key
             ))."'>
             <input type='submit' name='m_process' value='".$button_text."' />
     </form>";

     return $form; }
 //несколько товаров
 //html код кнопок (ссылок)
 function form_data_products($sql, $link = "Купить", $fields = null){
    if($fields == null){
  			$fields =  array(
  			  'product_title',
  			  'product_ammount',
  			  'product_description'
  			);
  	}
  	if(empty($sql))
  		  throw new Exception("Строка запроса пуста!");

    $query = $this->_db->query($sql);
  	if(!$query) return false;

  	while($row = $query->fetch_object()){
  	  $order_id = uniqid(rand(1000, 9999999));
  	  $sign = self::sign(array(
               self::config()->id_shop,
               $order_id,
               number_format($row->$fields[1], 2, '.', ''),
               self::config()->pay_curr,
               base64_encode($row->$fields[2]),
               self::config()->shop_key
      ));
  	  $return_url = self::config()->merchant_uri.'/?m_shop='.self::config()->id_shop.'&m_orderid='.$order_id.'&m_amount='.
  	                number_format($row->$fields[1], 2, '.', '').'&m_curr='.self::config()->pay_curr.'&m_desc='.
  	                base64_encode($row->$fields[2]).'&m_sign='.$sign;
  	  $return[] =  "|".$row->$fields[0].  "|".
  	              $row->$fields[1].  "|".
  	              $row->$fields[2].  "|".
  	              "<a href='".$return_url."'>{$link}</a>";


  	}

    $array = explode("|",$return);

  	return $return; }

 //Авторизация на сайте в удаленном режиме
 function LoginPayeer(){
    $accountNumber = self::config()->payeer_number;
    $apiId = self::config()->api_id;
    $apiKey = self::config()->api_key;
    $payeer = new CPayeer($accountNumber, $apiId, $apiKey);
    if($payeer->isAuth())
      return true;
    else
      throw new Exception("Произошла ошибка авторизации");
 }
 //Проверка баланса
 function BalanceAccount(){  $accountNumber = self::config()->payeer_number;
  $apiId = self::config()->api_id;
  $apiKey = self::config()->api_key;
  $payeer = new CPayeer($accountNumber, $apiId, $apiKey);
  if($payeer->isAuth()){
	$arBalance = $payeer->getBalance();
	echo '<pre>'.print_r($arBalance, true).'</pre>';
  }else
   throw new Exception("Произошла ошибка авторизации"); }
 //Получение списка доступных платежных систем для вывода
 function PayeerSystem(){  $accountNumber = self::config()->payeer_number;
  $apiId = self::config()->api_id;
  $apiKey = self::config()->api_key;
  $payeer = new CPayeer($accountNumber, $apiId, $apiKey);
  if ($payeer->isAuth()){
	$arPs = $payeer->getPaySystems();
	echo '<pre>'.print_r($arPs, true).'</pre>';
  }else
    throw new Exception("Произошла ошибка авторизации"); }
 //Вывод средств
 function withdrawal(){  $accountNumber = self::config()->payeer_number;
  $apiId = self::config()->api_id;
  $apiKey = self::config()->api_key;
  $payeer = new CPayeer($accountNumber, $apiId, $apiKey);
  if ($payeer->isAuth()){
	// инициализация вывода
	$initOutput = $payeer->initOutput(array(
		// id платежной системы полученный из списка платежных систем
		'ps' => '179',
		// счет, с которого будет списаны средства
		'curIn' => 'USD',
		// сумма вывода
		'sumOut' => 1,
		// валюта вывода
		'curOut' => 'USD',
		// Аккаунт получателя платежа
		'param_ACCOUNT_NUMBER' => 'U39152XX'
	));

	if ($initOutput){
		// Вывод средств
		$historyId = $payeer->output();
		if ($historyId){
			echo "Выплата поставлена в очередь на выполнение";
		}
		else{
			echo '<pre>'.print_r($payeer->getErrors(), true).'</pre>';
		}
	}else{
		echo '<pre>'.print_r($payeer->getErrors(), true).'</pre>';
	}
 }else
  throw new Exception("Произошла ошибка авторизации"); }
 //Информация об операции
 function OperationInfo(){  $accountNumber = self::config()->payeer_number;
  $apiId = self::config()->api_id;
  $apiKey = self::config()->api_key;
  $payeer = new CPayeer($accountNumber, $apiId, $apiKey);
  if($payeer->isAuth()){
	$historyId = '123456';
	$arHistory = $payeer->getHistoryInfo($historyId);
	echo '<pre>'.print_r($arHistory, true).'</pre>';
  }else
  throw new Exception("Произошла ошибка авторизации");

 }
 //Перевод средств
 function transfer(){  $accountNumber = self::config()->payeer_number;
  $apiId = self::config()->api_id;
  $apiKey = self::config()->api_key;
  $payeer = new CPayeer($accountNumber, $apiId, $apiKey);
  if($payeer->isAuth()){
	$arTransfer = $payeer->transfer(array(
		'curIn' => 'USD', // счет списания
		'sum' => 1, // Сумма получения
		'curOut' => 'RUB', // валюта получения
		'to' => 'mail@mail.ru', // Получатель (email)
		//'to' => '+01112223344',  // Получатель (Телефон)
		//'to' => 'P1000000',  // Получатель (Номер счета)
		//'comment' => 'Текст комментария',
		//'anonim' => 'Y', // анонимный перевод
		//'protect' => 'Y', // протекция сделки
		//'protectPeriod' => '3', // период протекции (от 1 до 30 дней)
		//'protectCode' => '12345', // код протекции
	));
	if (!empty($arTransfer["historyId"])){
		echo "Перевод №".$arTransfer["historyId"]." успешно завершен";
	}
	else{
		echo '<pre>'.print_r($arTransfer["errors"], true).'</pre>';
	}
  }else
    throw new Exception("Произошла ошибка авторизации");
}

}

?>
