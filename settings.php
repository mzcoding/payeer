<?php
/**
* Автор: Станислав Бойко
* Сайт: http://stanislavboyko.ru
* Copyright: 2013
*/
  require 'inc/conf.php';
?>
<!doctype html>
<html>
<head>
<title>Настройки скрипта</title>
<script type="text/javascript" src="tmp/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="tmp/style.css">
<link rel="stylesheet" type="text/css" href="tmp/tooltip.css">
<script type="text/javascript" src="tmp/tooltip.js"></script>
</head>
<body>
<center>
<div class="content">
<h2>Настройка работы</h2>
<br>
<form method="post">
<br>
<em>Основные настройки</em>
<br>
<p><table width="100%">
<tr><td>Форма (удобно для 1го товара):</td><td><input name="link" type="radio" value="1" checked></td></tr>
<tr><td>Ссылка (удобно для нескольких товаров):</td><td><input name="link" type="radio" value="2"></td></tr>
</table></p>
<br>
<p>API ID: <br>
<input name="api_id" type="text" size="25" maxlength="20"></p>
<p>Номер счета в системе Payeer: <br>
<input name="payeer_number" type="text" size="25" maxlength="25"></p>
<p>Секретный API ключ: <br>
<input name="api_key" type="text" size="25" maxlength="20"></p>
<p>Секретный ключ магазина: <br>
<input name="shop_key" type="text" size="25" maxlength="20"></p>
<br>
<hr>
<br>
<em>Настройки мерчанта</em>
<br> <br>
<p>URL мерчанта: [<a href="javascript:void(0)" class="tooltip"  title="Данное поле изменяйте только в случае если вы установили систему  по инструкции
 'Подключение на сайт'. В этом случае укажите url своего сайта.">?</a>]<br><input type="text" name="merchant_uri" value="//payeer.com/api/merchant/m.php">
 <p>Идентификатор магазина:<br>
 <input name="id_shop" type="text"></p>
  <p>Сумма товара: [<a href="javascript:void(0)" class="tooltip" title="Если на сайте несколько товаров, оставьте поле пустым.">?</a>]<br>
 <input name="pay_ammount" type="text"></p>
 <p>Вид принимаемой валюты:<br>
 <select size="1" name="pay_curr">
  <option value="1">Рубли</option>
  <option value="2">Долары</option>
  <option value="3">Евро</option>
</select></p>
<p>Описание товара(услуги):  [<a href="javascript:void(0)" class="tooltip" title="Если на сайте несколько товаров, оставьте поле пустым.">?</a>]<br>
<textarea cols="35" rows="5" name="pay_description"></textarea></p>
<br><hr><br>
<input type="submit" value="Сохранить настройки" name="save_settings">
</div>
</form>
</center>
</body>
</html>