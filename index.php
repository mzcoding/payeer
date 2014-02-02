<?php
/**
* Автор: Станислав Бойко
* Сайт: http://stanislavboyko.ru
* Copyright: 2013
*/
if(!file_exists("data.conf")){  	require_once 'settings.php';
  	exit;}
?>

<html>
<head>
<title>Скрипт для работы с платежной системой Payeer</title>
<script type="text/javascript" src="tmp/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="tmp/tooltip.css">
<script type="text/javascript" src="tmp/tooltip.js"></script>
</head>
<body>
<center>
<p>Как использовать скрипт, после настроек!</p>
<br>
<h2>Примеры кода</h2>
</center>
          <br>
          <em><strong>Продажа одного товара</strong></em>
          <br>
          <p>Цена и описание товара устанавливаются на странице настроек!</p>

<pre>
 <?php
 $code = '<?php
  try{
   require_once "libs/payeer_order.lib.php";
   $payeer = new Payeer_Order();
   echo $payeer->form_data("Оплатить товар за 100 рублей!");
  }catch(Exception $e){
   	 die($e->getMessage());
  }
 ?>  ';
 highlight_string($code);
 ?>
</pre>
  <br><hr><br>
<p>Для продажи нескольких товаров. Данные поступают из базы данных. В метод следут передать имя полей
цены товара и описания товара.</p>
<p>Так же следует передать html структуру блока с товаром, вместо ссылки на оплату прописываете <b>[link]</b>.</p>
           <br>
          <em><strong>Продажа нескольких товаров</strong><em>
          <br>
    <pre>
    <?php
     $code_2 = '
     <?php
     require "inc/conf.php";
     $mysql = new mysqli("localhost","mzcoding","240290mzzAm19S","register");
     try{
      require_once "libs/payeer_order.lib.php";
      $payeer = new Payeer_Order($mysql);
      //Устанавливаем запрос
      $sql = "SELECT * FROM `products`";
      $result = $payeer->form_data_products($sql,  "Преобрести" , array(
              "title",
              "price",
              "description"
      ));
      foreach($result as $key => $value){
        $data = explode("|",$value);
   	    echo "<div style="width:250px;border:2px solid #000;"><center>
              <strong>$data[1]</strong> <br>
              <strong>Стоимость: {$data[2]} </strong><br>
              <strong>Описание: {$data[3]}</strong><br>
              {$data[4]}
              </center></div><br><br>";
      }
     }catch(Exception $e){
   	   die($e->getMessage());
     }
     ?>';
        highlight_string($code_2);
     ?>
    </pre>
    <div class="text-align:center;">&copy; <?=date('Y');?> Stanislav Boyko</div>
</body>
</html>
