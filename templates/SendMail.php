<?php
// отладка
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

session_start();
include ('body.php'); // подключаем меню
include ('lib/connect.php'); // подключаемся к БД
include ('lib/fun.php'); // подключаем библиотеку функций
?>
<center><div class="container"><div class="jumbotron">
<?php

echo "UserID   = ".$_SESSION['UserID']."<BR>";
echo "MySQLiEerror   = ".$_SESSION['MySQLiEerror']."<BR>";
echo "SQLTxt   = ".$_SESSION['SQLTxt']."<BR>";

if ( ($_SESSION['UserID']>0) and ($_SESSION['MySQLiEerror'] and $_SESSION['SQLTxt']) ) {
  // Посылаем сообщение пользователю
  $message  = "Помилка : ".$_SESSION['MySQLiEerror']."\r\n";
  $toemail  = "Aleksandr.Dikiy@gmail.com";
  $subject  = "Помилка : ".$_SESSION['MySQLiEerror']."\r\n";
  $subject .= "SQL : ".$_SESSION['SQLTxt']."\r\n";
  $headers  = "From: ".$toemail."\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n"; // поменять Content-Type: text/html на Content-Type: text/plain.
  $headers .= "Reply-To: Aleksandr.Dikiy@gmail.com";
  if (mail($toemail,$subject,$message,$headers))
    echo '<div class="alert alert-success">УСПІШНЕ ВІДПРАВЛЕННЯ ПОВІДОМЛЕННЯ АДМУНУСТРАТОРУ</div>';
  else
    echo '<div class="alert alert-danger">ПОМИЛКА ВІДПРАВКИ ПОВІДОМЛЕННЯ АДМІНІСТРАТОРУ</div>';
}
?>
</div></div></center>
<?php
include ('bottom.php'); // подключаем подвал
?>
