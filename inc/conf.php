<?php
/**
* Автор: Станислав Бойко
* Сайт: http://stanislavboyko.ru
* Copyright: 2013
*/
 $explode = explode("/",$_SERVER['PHP_SELF']);
 $url = empty($explode[1]) ? "/" : "/".$explode[1];


 define("URL_ROOT","http://" . $_SERVER['HTTP_HOST'] . $url);
 define("ROOT_DIR", dirname(__FILE__)."/../");

 require 'post.php';
?>