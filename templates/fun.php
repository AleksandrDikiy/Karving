<?php
$msearch = array(".", ",", " ", "-", "_", "(", ")"
,"А","Б","В","Г","Д","Е","Ё","Ж","З","И","Й"
,"К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х"
,"Ц","Ч","Ш","Щ","Ъ","Ы","Ь","Э","Ю","Я","а","б"
,"в","г","д","е","ё","ж","з","и","й","к","л","м"
,"н","о","п","р","с","т","у","ф","х","ц","ч","ш"
,"щ","ъ","ы","ь","э","ю","я"
,".",",","_","-","(",")","*","!","@","№","$","%","^","&","=","+","#","~");

global $msearch;

// отримання посилання
function GetLink() {
  return preg_replace('/\?(.)*$/','',$_SERVER['REQUEST_URI']);
}
// получення параметра запроса
function GetSQLResult($db,$sqlt) {
	$result = mysqli_query($db,$sqlt) or die("<hr><br>немає підключення до MySQL:<br>".mysqli_error($db)."<HR><BR>");
  $row = mysqli_fetch_row($result);
  if ($row[0])
    return $row[0];
  else
    return null;
}
// Функция для генерации случайной строки
function generateCode($length=6) {
  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
  $code = "";
  $clen = strlen($chars) - 1;
  while (strlen($code) < $length) {
          $code .= $chars[mt_rand(0,$clen)];
  }
  return $code;
}
// получение IP
function getIP() {
  if (!empty($_SERVER['HTTP_CLIENT_IP'])){
    //check ip from share internet
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
    //to check ip is pass from proxy
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}
// проверка регистрации
function registrationCorrect() {
	if ($_POST['login'] == "") return false; //не пусто ли поле логина 	
	if ($_POST['password'] == "") return false; //не пусто ли поле пароля
	if ($_POST['password2'] == "") return false; //не пусто ли поле подтверждения пароля
	if ($_POST['ip'] == "") return false; //не пусто ли поле e-mail
	if ($_POST['lic'] != "ok") return false; //приняты ли правила
	if (!preg_match('/^([a-z0-9])(\w|[.]|-|_)+([a-z0-9])@([a-z0-9])([a-z0-9.-]*)([a-z0-9])([.]{1})([a-z]{2,4})$/is', $_POST['mail'])) return false; //соответствует ли поле e-mail регулярному выражению
	if (!preg_match('/^([a-zA-Z0-9])(\w|-|_)+([a-z0-9])$/is', $_POST['login'])) return false; // соответствует ли логин регулярному выражению
	if (strlen($_POST['password']) < 5) return false; //не меньше ли 5 символов длина пароля
 	if ($_POST['password'] != $_POST['password2']) return false; //равен ли пароль его подтверждению
	$login = $_POST['login'];
	$rez = mysql_query("SELECT * FROM Users WHERE User_Login=$login");
	if (@mysql_num_rows($rez) != 0) return false; // проверка на существование в БД такого же логина
	return true; //если выполнение функции дошло до этого места, возвращаем true 
}
// получение группы пользователя
//function CheckUserRole($user_id,$role_id) {
//  $sqlt = "SELECT Role_ID FROM Users_Role WHERE Role_ID='".$role_id."' AND User_ID='".$user_id."'";
////ECHO "SQL = ".$sqlt."<BR>";
//	$sql = mysql_query($sqlt) or die("<hr><br>нет подключения к MySQL:<br>".mysql_error()."<HR><BR>"); // */
//	if (mysql_num_rows($sql) != 0)
//    return True;
//  else
//    return False;
//}
// Генерация "равномерного" пароля
// Параметр $number - сообщает число символов в пароле 
function generate_password($number) {  
  $arr = array('a','b','c','d','e','f',  
               'g','h','i','j','k','l',  
               'm','n','o','p','r','s',  
               't','u','v','x','y','z',  
               'A','B','C','D','E','F',  
               'G','H','I','J','K','L',  
               'M','N','O','P','R','S',  
               'T','U','V','X','Y','Z',  
               '1','2','3','4','5','6',  
               '7','8','9','0');  
  // Генерируем пароль  
  $pass = "";  
  for($i = 0; $i < $number; $i++) {  
    // Вычисляем случайный индекс массива  
    $index = rand(0, count($arr) - 1);  
    $pass .= $arr[$index];  
  }  
  return $pass;  
}
// запись в таблицу протокола
function SetLogs($db, $userid, $type, $name, $text, $error) {
  $sqlt  = 'INSERT INTO Logs (Logs_DateCreate, User_ID, Logs_Type, Logs_Name, Logs_Text, Logs_Error) ';
  $sqlt .= ' VALUES(now(), "';
  $sqlt .= $userid.'","';
  $sqlt .= $type.'","';
  $sqlt .= $name.'","';
  $sqlt .= str_replace('"',"'",$text).'","';
  $sqlt .= $error.'")';
//ECHO '<BR> sqlt-INSERT = '.$sqlt."<BR>";
  if (mysqli_query($db, $sqlt))
    return true;
  else
    return false;
}

// транслитерация украинского в английский
function translit($s) {
  $s = (string) $s; // преобразуем в строковое значение
  $s = strip_tags($s); // убираем HTML-теги
  $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
  $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
  $s = trim($s); // убираем пробелы в начале и конце строки
  //$s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
  $s = strtr($s, 
              array(
                  'а'=>'a','А'=>'A','б'=>'b','Б'=>'B'
                  ,'в'=>'v','В'=>'V','г'=>'h','Г'=>'H',"ґ"=>"g", "Ґ"=>"G"
                  ,'д'=>'d','Д'=>'D','е'=>'e','Е'=>'E','є'=>'ie','Є'=>'Ye'
                  ,"ж"=>"zh","Ж"=>"ZH",'з'=>'z','З'=>'Z'
                  ,'и'=>'y','И'=>'Y','і'=>'i','І'=>'I'
                  ,"ї"=>"i", "Ї"=>"Yi",'й'=>'i','Й'=>'Y'
                  ,'к'=>'k','К'=>'K','л'=>'l','Л'=>'L'
                  ,'м'=>'m','М'=>'M','н'=>'n','Н'=>'N'
                  ,'о'=>'o','О'=>'O','п'=>'p','П'=>'P','р'=>'r','Р'=>'R'
                  ,'с'=>'s','С'=>'S','т'=>'t','Т'=>'T','у'=>'u','У'=>'U'
                  ,'ф'=>'f','Ф'=>'F','х'=>'kh','Х'=>'Kh'
                  ,"ц"=>"ts","Ц"=>"Ts","ч"=>"ch","Ч"=>"Ch"
                  ,"ш"=>"sh","Ш"=>"Sh"
                  ,"щ"=>"shch","Щ"=>"Shch"
                  ,"ю"=>"iu","Ю"=>"Yu"
                  ,"я"=>"ia","Я"=>"Ya"
                  ,'ы'=>'y','э'=>'e'
                  ,'ъ'=>'','ь'=>''
                  ,'Ы'=>'Y','Э'=>'E'
                  ,'Ъ'=>'','Ь'=>''
                  )
             );
  $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
  //$s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
  return $s; // возвращаем результат
}
// кнопка НАЗАД
function ButtonBack($getlnk,$name,$nvalue) {
  $txt  = '<FORM ACTION="'.$getlnk.'" method="post"><INPUT TYPE="HIDDEN" TYPE="submit">';
  if ($name and $nvalue)
    $txt .= '<INPUT TYPE="HIDDEN" NAME="'.$name.'" VALUE="'.$nvalue.'">';  
  $txt .= '<BUTTON type="submit" class="btn btn-group btn-warning" TITLE="назад"><span class="glyphicon glyphicon-arrow-left"> НАЗАД</BUTTON></FORM>';
  return $txt;
}

// ПОЛУЧЕНИЕ И ОБНОВЛЕНИЕ ПАРАМЕТРА ПО ПОЛЬЗОВАТЕЛЮ И ФОРМЕ
function GetParams($db, $UserID, $name, $val) {
  if (isset($val)) {
    $limit = $val;
    // проверяем и записываем в БД
    $sqlt = "SELECT UserParams_Value FROM vUserParams WHERE UserParams_Name='".$name."' AND User_ID='".$UserID."'";
    $result = mysqli_query($db,$sqlt) or die("<hr><br>немає підключення до MySQL1:<br>".mysqli_error($db)."<HR><BR>");
    $row = mysqli_fetch_row($result);
    if ($row) {
      if ($row[0]<>$val) {
        // если поменяловь значение, то обновляем в БД
        $sqlt = "SELECT UserParams_ID FROM vUserParams WHERE UserParams_Name='".$name."' AND User_ID='".$UserID."'";
        $result = mysqli_query($db,$sqlt) or die("<hr><br>немає підключення до MySQL1:<br>".mysqli_error($db)."<HR><BR>");
        $row = mysqli_fetch_row($result);
        if ($row) {
          $sqlt = 'UPDATE UserParams SET UserParams_Value="' . $val . '"';
          $sqlt .= ' WHERE UserParams_ID = "' . $row[0] . '"';
//echo "SQL = ".$sqlt."<BR>";
          mysqli_query($db, $sqlt);
        }
      } else {
        $limit = $row[0];
      }
    } else {
      $sqlt  = "INSERT INTO UserParams (User_ID, UserParams_Name, UserParams_Value) VALUES( '";
      $sqlt .= $UserID."','";
      $sqlt .= $name."','";
      $sqlt .= $val."')";
//echo "SQL = ".$sqlt."<BR>";
      mysqli_query($db,$sqlt) or die("<hr><br>немає підключення до MySQL11:<br>".mysqli_error($db)."<HR><BR>");
    }
  } else {
    $sqlt = "SELECT UserParams_Value FROM vUserParams WHERE UserParams_Name='".$name."' AND User_ID='".$UserID."'";
    $result = mysqli_query($db,$sqlt) or die("<hr><br>немає підключення до MySQL2:<br>".mysqli_error($db)."<HR><BR>");
    $row = mysqli_fetch_row($result);
    if ($row) {
      $limit = $row[0];
    } else {
      $limit = '10';
      $sqlt  = "INSERT INTO UserParams (User_ID, UserParams_Name, UserParams_Value) VALUES( '";
      $sqlt .= $UserID."','";
      $sqlt .= $name."','";
      $sqlt .= $limit."')";
//echo "SQL = ".$sqlt."<BR>";
      mysqli_query($db,$sqlt) or die("<hr><br>немає підключення до MySQL22:<br>".mysqli_error($db)."<HR><BR>");
    }
  }
  return $limit;
}
function getExtension2($filename) {
  $path_info = pathinfo($filename);
  return $path_info['extension'];
}
function getExtension4($filename) {
  return substr(strrchr($filename, '/'), 1);
}

?>
