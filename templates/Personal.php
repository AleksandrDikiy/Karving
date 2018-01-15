<?php
// отладка
//~ ini_set('display_errors', 1);
//~ ini_set('error_reporting', E_ALL);

session_start();
include ('body.php'); // подключаем меню
include ('lib/connect.php'); // подключаемся к БД
include ('lib/fun.php'); // подключаем библиотеку функций
?>
<center><div class="container"><div class="jumbotron">
<?php
/*if ($_SESSION['GroupAdm']) {
	echo "GroupRVK   = ".$_SESSION['GroupRVK']."<BR>";
	echo "GroupOVK   = ".$_SESSION['GroupOVK']."<BR>";
	echo "OK_Name    = ".$_SESSION['OK_Name']."<BR>";
} //*/
///////////////////////////
if (isset($_SESSION['UserID'])) {

  // ОНОВЛЕННЯ В БД
  if (isset($_POST['UpdatePSW'])) {
    //~ ECHO '<B>ОНОВЛЕННЯ</B><BR><BR>';
    // Убераем лишние пробелы и делаем двойное шифрование
    //~ $password = md5(md5(trim($_POST['password_old'])));
    $password = md5(md5($_POST['password_old']));
    $hash = md5(generateCode(10));
    // переірка
    $sqlt = "SELECT Users_Password FROM vUsers WHERE Users_ID='".$_POST['UpdatePSW']."' LIMIT 1";
//~ echo 'SQL = '.$sqlt."<BR>";    
    $query = mysqli_query($db,$sqlt);
    $data = mysqli_fetch_assoc($query);
    //~ echo 'Users_Password = '.$data['Users_Password'].'<BR>';
    //~ echo 'password = '.$password.'<BR>';
    //~ echo 'hash = '.$hash.'<BR>';
    if($data['Users_Password'] == $password) {
      if ($_POST['password_1'] == $_POST['password_2']) {
        $sqlt  = "UPDATE Users SET ";
        $sqlt .= ' Users_Password="'.md5(md5($_POST['password_1'])).'"';
        $sqlt .= ',Users_Hash="'.$hash.'"';
        $sqlt .= ',Users_IP="'.getIP().'"';
        $sqlt .= ' WHERE Users_ID = "'.$_POST['UpdatePSW'].'"';
//~ echo 'SQL = '.$sqlt."<BR>";
        if (mysqli_query($db,$sqlt)) {
          //~ header ('Location: '.GetLink()); // перенаправление
          echo '<div class="alert alert-success">ПАРОЛЬ ЗМІНЕНО ! <a href="Personal.php" class="alert-link">НАЗАД</a></div>';
        } else
          echo '<div class="alert alert-danger">ПОМИЛКА ЗМІНИ ПАРОЛЮ ! <a href="Personal.php" class="alert-link">НАЗАД</a></div>';
      } else 
        echo '<div class="alert alert-danger">НОВІ ПАРОЛІ НЕ ЗБІГАЮТЬСЯ ! <a href="Personal.php" class="alert-link">НАЗАД</a></div>';
    } else
      echo '<div class="alert alert-danger">ПОТОЧНИЙ ПАРОЛЬ НЕ ВІРНИЙ ! <a href="Personal.php" class="alert-link">НАЗАД</a></div>';
  }
  // РЕДАГУВАННЯ
  elseif (isset($_POST['EditPsw'])) {
    echo '<B>ЗМІНА ПАРОЛЮ</B><BR><BR>';
    $html  = '<FORM role="form" ACTION="'.GetLink().'" id="editform" method="post">';
    $html .= "<INPUT TYPE='HIDDEN' NAME='UpdatePSW' VALUE='".$_POST['EditPsw']."'>";
    $html .= '<TABLE COLS="2" BORDER="0">';
    // ПОТОЧНИЙ ПАРОЛЬ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">* ПОТОЧНИЙ ПАРОЛЬ : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="password" NAME="password_old" required /></td>';
    $html .= ' </tr>';
    // НОВИЙ ПАРОЛЬ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">* НОВИЙ ПАРОЛЬ : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="password" NAME="password_1" required /></td>';
    $html .= ' </tr>';
    // ПОВТОР ПАРОЛЯ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">* ПОВТОР ПАРОЛЯ : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="password" NAME="password_2" required /></td>';
    $html .= ' </tr>';
    // КНОПКИ
    $html .= '<TABLE BORDER="0"><tr><td>';
    $html .= '<INPUT TYPE="HIDDEN" TYPE="submit">';
    $html .= '<BUTTON type="submit" class="btn btn-group btn-success" TITLE="змінити ">';
    $html .= '<span class="glyphicon glyphicon-ok"></span> ЗМІНИТИ</BUTTON></FORM>';
    $html .= '</td>';
    // НАЗАД
    $html .= '<td>';
    $html .= ButtonBack(GetLink(),null,null); // кнопка НАЗАД
    $html .= '</td></tr></table>';
    echo $html;
  }
  // ФОРМА ДОДАВАННЯ
  elseif (isset($_POST['Adding'])) {
    echo '<B>ДОДАВАННЯ КОРИСТУВАЧА</B><BR><BR>';
    $html  = '<FORM role="form" ACTION="'.GetLink().'" method="post" id="addform" enctype="multipart/form-data">';  
    $html .= '<INPUT TYPE="hidden" name="InsertID" value="'.$_SESSION['UserID'].'">';
    $html .= '<table COLS="2" BORDER="0">';
    // ВИБІР КОРИСТУВАЧА
    $html .= '<TR>';
    $html .= ' <TD align="RIGHT">ВИБІР КОРИСТУВАЧА : </TD>';
    $html .= ' <TD><SELECT name="Emploees_ID" id="Emploees_ID" >';
    $sqlt = "SELECT 'null' AS Emploees_ID, 'немає' AS FIO 
    union 
    SELECT Emploees_ID, FIO FROM vEmploees ORDER BY FIO";
    //~ if ($_SESSION['Emploees_ID'])
      //~ $sqlt .= " Emploees_ID ".$_SESSION['Emploees_ID'];
//~ if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
    $sql = mysqli_query($db,$sqlt) or die("<hr><br>немає підключення до MySQL:<br>".mysqli_error($db)."<HR><BR>"); // */ 
    while ($data1 = mysqli_fetch_array($sql)) {
      $html .= '<OPTION '; 
      //~ if ($data1['Emploees_ID']==$_SESSION['Emploees_ID']) 
        //~ $html .= ' SELECTED '; 
      $html .= "VALUE='".$data1['Emploees_ID']."'>".$data1['FIO']."</OPTION>"; 
    }
    $html .= '</SELECT></TD></TR>';  
    // ВИБІР РОЛІ
    $html .= '<TR>';
    $html .= ' <TD align="RIGHT">ВИБІР РОЛІ : </TD>';
    $html .= ' <TD><SELECT name="Role_ID" id="Role_ID" >';
    $sqlt = "SELECT Role_ID, Role_Name FROM vRole";
    //~ if ($_SESSION['Emploees_ID'])
      //~ $sqlt .= " Emploees_ID ".$_SESSION['Emploees_ID'];
//~ if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
    $sql = mysqli_query($db,$sqlt) or die("<hr><br>немає підключення до MySQL:<br>".mysqli_error($db)."<HR><BR>"); // */ 
    while ($data1 = mysqli_fetch_array($sql)) {
      $html .= '<OPTION '; 
      //~ if ($data1['Emploees_ID']==$_SESSION['Emploees_ID']) 
        //~ $html .= ' SELECTED '; 
      $html .= "VALUE='".$data1['Role_ID']."'>".$data1['Role_Name']."</OPTION>"; 
    }
    $html .= '</SELECT></TD></TR>';  
    // НАЙМЕНУВАННЯ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">* login : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Users_Login" required placeholder="логін" size="50" /></td>';
    $html .= ' </tr>';
    // СОРТУВАННЯ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">* password : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="password" NAME="Users_Password" required placeholder="пароль" size="50" /></td>';
    $html .= ' </tr>';
    //
    $html .= '</table>';
    // КНОПКИ
    $html .= '<TABLE BORDER="0"><tr><td>';
    $html .= '<INPUT TYPE="HIDDEN" TYPE="submit">';
    $html .= '<BUTTON type="submit" class="btn btn-group btn-success" TITLE="зерегти ">';
    $html .= '<span class="glyphicon glyphicon-ok"></span> ЗБЕРЕГТИ</BUTTON></FORM>';
    $html .= '</td>';
    // НАЗАД
    $html .= '<td>';
    $html .= ButtonBack(GetLink(),null,null); // кнопка НАЗАД
    $html .= '</td></tr></table>';
    echo $html;
  }
  //========= ОСНОВНАЯ ФОРМА ===========
  else {
    $n=0;
    echo '<B>ОСОБИСТИЙ КАБІНЕТ</B><BR>';
    $html  = '<TABLE><TR>';
    // ЗМІНА ПАРОЛЮ
    $html .= '<TD><FORM ACTION="'.GetLink().'" method="post">';
    $html .= ' <INPUT TYPE="HIDDEN" NAME="EditPsw" VALUE="'.$_SESSION['UserID'].'">';
    $html .= ' <INPUT TYPE="HIDDEN" TYPE="submit">';
    $html .= '<BUTTON type="submit" class="btn btn-group btn-xs btn-success" TITLE="зміна паролю">';
    $html .= '<span class="glyphicon glyphicon-plus"></span> ЗМІНА ПАРОЛЮ</BUTTON>';
    $html .= '</FORM></td>';
    //---
    $html .= '</TR></TABLE>';
    
    ///// ЗАПРОС
    $sqlt = "SELECT Users_ID, Users_FIO, Users_Login, Users_DateLast, Users_IP
    FROM vUsers "; 
    $sqlt .= " WHERE Users_ID = '".$_SESSION['UserID']."'"; 
//~ if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
    $sql = mysqli_query($db,$sqlt) or die("<hr><br>немає підключення до MySQL:<br>".mysqli_error($db)."<HR><BR>"); // */ 
    if (mysqli_num_rows($sql) != 0) {
      while ($data = mysqli_fetch_array($sql)) {
        $filename = "/photo/".$data["Emploees_ID"].'.jpg';
        $html .= '<TABLE COLS="2" BORDER="0">';
        //~ $html .= '<tr><TD WIDTH="150" height="201" align="CENTER" valign="top">';
        //~ if ($filename) {
          //~ // ФОТОГРАФІЯ
          //~ $html .= 'ФОТОГРАФІЯ<BR>';
          //~ $html .= '<img src="'.$filename.'" width="150" height="201"><BR>';
        //~ }
        //~ $html .= '</TD><td valign="top">';
        //
        $html .= '<TABLE COLS="2" BORDER="0">';
        // ПІБ
        $html .= '<tr>';
        $html .= ' <td ALIGN="RIGHT">ПІБ : </td>';
        $html .= ' <td ALIGN="LEFT"> <b>'.$data["Users_FIO"].'</b></td>';
        $html .= '</tr>';
        // ЛОГІН
        $html .= '<tr>';
        $html .= ' <td ALIGN="RIGHT">ЛОГІН : </td>';
        $html .= ' <td ALIGN="LEFT"> <b>'.$data["Users_Login"].'</b></td>';
        $html .= '</tr>';
        // ДАТА ТА ЧАС ПОПЕРЕДНЬОГО ВХОДУ
        $html .= '<tr>';
        $html .= ' <td ALIGN="RIGHT">ДАТА ТА ЧАС ПОПЕРЕДНЬОГО ВХОДУ : </td>';
        $html .= ' <td ALIGN="LEFT"> <b>'.$data["Users_DateLast"].'</b></td>';
        $html .= '</tr>';
        // IP
        $html .= '<tr>';
        $html .= ' <td ALIGN="RIGHT">IP : </td>';
        $html .= ' <td ALIGN="LEFT"> <b>'.$data["Users_IP"].'</b></td>';
        $html .= '</tr>';
      }
      //
      $html .= '</TABLE>';
      $html .= '</td></tr>';
      $html .= '</TABLE>';
      echo $html;
    }
  }
} else
  echo '<div class="alert alert-danger">НЕМАЄ ДОСТУПУ! <a href="login.php" class="alert-link">УВІЙТИ</a></div>';
////
?>
</div></div></center>
<?php
mysqli_close($db); // закрываем соединение
include ('bottom.php'); // подключаем подвал
?>
