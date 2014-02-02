<?php
/**
* Автор: Станислав Бойко
* Сайт: http://stanislavboyko.ru
* Copyright: 2013
*/
require_once 'libs/payeer_order.php';
if (isset($_POST["m_operation_id"]) && isset($_POST["m_sign"]))
{
	$m_key = Payeer_Order::config()->api_key;
	$arHash = array($_POST['m_operation_id'],
			$_POST['m_operation_ps'],
			$_POST['m_operation_date'],
			$_POST['m_operation_pay_date'],
			$_POST['m_shop'],
			$_POST['m_orderid'],
			$_POST['m_amount'],
			$_POST['m_curr'],
			$_POST['m_desc'],
			$_POST['m_status'],
			$m_key);
	$sign_hash = strtoupper(hash('sha256', implode(":", $arHash)));
	if ($_POST["m_sign"] == $sign_hash && $_POST['m_status'] == "success")
	{
		echo $_POST['m_orderid']."|success";
		exit;
	}
	echo $_POST['m_orderid']."|error";
}
?>