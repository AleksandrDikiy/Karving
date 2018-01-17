<?php
/** 
  * Template Name: КЛИЕНТЫ
  *       Version: 1.0.0.0
  *          Name: Customers
*/ 
//ini_set('display_errors', 1);
//ini_set('error_reporting', E_ALL);
session_start();
include ('body.php'); // подключаем меню
?>
<script type="text/javascript">
  function delete_record($del_id) {
    if (confirm("Ви дійсно бажаете ВИДАЛИТИ запис?") === false) { return; }
    document.location.href = window.location.pathname+"?del_id="+$del_id;
  }
</script>

<center><div class="container"><div class="jumbotron">
<?php
///////////////////////////
wp_enqueue_style('dav-table', plugins_url('/css/dav-table.css', URE_KARVING_PATH), array(), false, 'screen');
//echo "URE_KARVING_PATH   = ".URE_KARVING_PATH."<BR>";
if($_SESSION['GroupAdm']) {
  global $wpdb;
  include ('fun.php'); // подключаем библиотеку функций
  $LogName  = 'Customers';
  
  if (isset($_GET['pages']))
    $pages = $_GET['pages'];
  elseif (isset($_POST['pages']))
    $pages = $_POST['pages'];
  else
    $pages = 1;
  // ViewID
  if (isset($_POST['ViewID']))
    $ViewID = $_POST['ViewID'];
  elseif (isset($_GET['ViewID']))
    $ViewID = $_GET['ViewID'];
  else
    $ViewID = null;
  // EditID
  if (isset($_GET['EditID']))
    $EditID = $_GET['EditID'];
  elseif (isset($_POST['EditID']))
    $EditID = $_POST['EditID'];
  elseif ($_SESSION['ViewID'])
    $EditID = $_SESSION['ViewID'];

  if (isset($_POST['Customers_ID']))
    $_SESSION['Customers_ID'] = $_POST['Customers_ID'];
  elseif (isset($_GET['Customers_ID']))
    $_SESSION['Customers_ID'] = $_GET['Customers_ID'];

  // ВИДАЛЕННЯ
  if (isset($_GET['del_id'])) {
    if ($_SESSION['GroupAdm']) {
      $wpdb->show_errors();
      $res = $wpdb->delete( 'Customers', array( 'Customers_ID' => $_GET['del_id'] ) );
      $_SESSION['SQLTxt'] = str_replace('"',"'",$wpdb->last_query);
      $_SESSION['MySQLiEerror'] = 'помилка:'.$wpdb->last_error;
      if ($res) {
        echo '<div class="alert alert-success">УСПІШНЕ ВИДАЛЕННЯ!</div>';
        //      SetLogs($UserID,'D',$LogName,str_replace('"',"'",$wpdb->last_query),'успішно');
        echo '<BR>query : '.$wpdb->last_query.'<BR>';
        header ('Location: '.GetLink()); // перенаправление
      } else {
        //      SetLogs($UserID,'D',$LogName,str_replace('"',"'",$wpdb->last_query),$wpdb->last_error);
        echo '<BR>query : '.$wpdb->last_query.'<BR>';
        echo '<BR>error : '.$wpdb->last_error.'<BR>';
        echo '<div class="alert alert-danger">ПОМИЛКА ВИДАЛЕННЯ! ВІДПРАВИТИ ПОВІДОМЛЕННЯ ДО <a href="/SendMail.php" target="_blank" class="alert-link">АДМІНІСТРАТОРА</a></div>';
      }
      $wpdb->hide_errors();
    } else {
      echo '<div class="alert alert-danger">НЕМАЕ ДОСТУПУ</div>';
    }
    echo ButtonBack(null,null); // кнопка НАЗАД
  }
  // ДОДАВАННЯ
  elseif (isset($_POST['InsertID'])) {
    if ($_SESSION['GroupAdm']) {
      $wpdb->show_errors();
      $res = $wpdb->insert('Customers',
        array(
          'Customers_SurName' => $_POST['Customers_SurName']
        , 'Customers_Name' => $_POST['Customers_Name']
        , 'Customers_Partronic' => $_POST['Customers_Partronic']
        , 'Customers_Phone1' => $_POST['Customers_Phone1']
        , 'Customers_Phone2' => $_POST['Customers_Phone2']
        , 'Customers_Phone3' => $_POST['Customers_Phone3']
        , 'Customers_From' => $_POST['Customers_From']
        , 'Customers_To' => $_POST['Customers_To']
        , 'Customers_Note' => $_POST['Customers_Note']
        , 'Customers_BirthDay' => $_POST['Customers_BirthDay'] ),
        array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
      );
      $_SESSION['SQLTxt'] = str_replace('"',"'",$wpdb->last_query);
      $_SESSION['MySQLiEerror'] = 'помилка:'.$wpdb->last_error;
      if ($res) {
        echo '<div class="alert alert-success">УСПІШНЕ ДОДАВАННЯ!</div>';
//        SetLogs($UserID,'I',$LogName,str_replace('"',"'",$wpdb->last_query),'успішно');
        echo '<BR>query : '.$wpdb->last_query.'<BR>';
        header ('Location: '.GetLink()); // перенаправление
      } else {
//        SetLogs($UserID,'I',$LogName,str_replace('"',"'",$wpdb->last_query),$wpdb->last_error);
//        $_SESSION['SQLTxt'] = $sqlt;
//        echo $_SESSION['MySQLiEerror']."<BR>";
//        if ($_SESSION['GroupAdm']) echo 'SQL = '.$_SESSION['SQLTxt']."<BR>";
        echo '<BR>query : ' . $wpdb->last_query . '<BR>';
        echo '<BR>error : ' . $wpdb->last_error . '<BR>';
        echo '<div class="alert alert-danger">ПОМИЛКА ДОДАВАННЯ! ВІДПРАВИТИ ПОВІДОМЛЕННЯ ДО <a href="/SendMail.php" target="_blank" class="alert-link">АДМІНІСТРАТОРА</a></div>';
      }
      $wpdb->hide_errors();
    } else {
      echo '<div class="alert alert-danger">НЕМАЕ ДОСТУПУ</div>';
    }
    echo ButtonBack(null,null); // кнопка НАЗАД
  }
  // ОНОВЛЕННЯ
  elseif (isset($_POST['UpdateID'])) {
    $wpdb->show_errors();
    $res = $wpdb->update( 'Customers',
      array(
        'Customers_SurName' => $_POST['Customers_SurName']
      , 'Customers_Name' => $_POST['Customers_Name']
      , 'Customers_Partronic' => $_POST['Customers_Partronic']
      , 'Customers_Phone1' => $_POST['Customers_Phone1']
      , 'Customers_Phone2' => $_POST['Customers_Phone2']
      , 'Customers_Phone3' => $_POST['Customers_Phone3']
      , 'Customers_From' => $_POST['Customers_From']
      , 'Customers_To' => $_POST['Customers_To']
      , 'Customers_Note' => $_POST['Customers_Note']
      , 'Customers_BirthDay' => $_POST['Customers_BirthDay'] ),
      array( 'Customers_ID' => $_POST['UpdateID'] )
    );
//    $_SESSION['GroupAdm'] = $GroupAdm;
//    $_SESSION['UserID'] = $UserID;
    $_SESSION['SQLTxt'] = str_replace('"',"'",$wpdb->last_query);
    $_SESSION['MySQLiEerror'] = 'помилка:'.$wpdb->last_error;
    if ($res) {
      echo '<div class="alert alert-success">УСПІШНЕ ОНОВЛЕННЯ!</div>';
//      SetLogs($UserID,'U',$LogName,str_replace('"',"'",$wpdb->last_query),'успішно');
//      header ('Location: '.GetLink().'?pages='.$pages.''); // перенаправление
      header ('Location: '.GetLink().'?ViewID='.$_POST['UpdateID']); // перенаправление
    } else {
//      SetLogs($UserID,'U',$LogName,str_replace('"',"'",$wpdb->last_query),$wpdb->last_error);
//      echo '<BR>query : '.$_SESSION['SQLTxt'].'<BR>';
//      echo 'MySQLiError = '.$_SESSION['MySQLiEerror']."<BR>";
      echo '<BR>query : '.$wpdb->last_query.'<BR>';
      echo '<BR>error : '.$wpdb->last_error.'<BR>';
      echo '<div class="alert alert-danger">ПОМИЛКА ОНОВЛЕННЯ! ВІДПРАВИТИ ПОВІДОМЛЕННЯ ДО <a href="/SendMail" target="_blank" class="alert-link">АДМІНІСТРАТОРА</a></div>';
    }
    $wpdb->hide_errors();
    echo ButtonBack(GetLink(),null,null); // кнопка НАЗАД
  }
  // ПЕРЕГЛЯД ДАНИХ
  elseif (isset($ViewID)) {
    echo '<B>ПЕРЕГЛЯД ДАНИХ</B><BR><BR>';
    // запрос
    $sqlt  = "SELECT Customers_ID, Customers_SurName, Customers_Name, Customers_Partronic
    , Customers_Email, Customers_Phone1, Customers_Phone2, Customers_Phone3
    , Customers_From, Customers_To, Customers_Note, Customers_BirthDay 
    FROM vCustomers ";
    $sqlt .= " WHERE Customers_ID = '".$ViewID."'";
//if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
    $res = $wpdb->get_results($sqlt);
    if ( $res ) {
      foreach ( $res as $data ) {
        $html  = '<TABLE COLS="2" BORDER="0">';
        // ФАМИЛИЯ
        $html .= '<tr><td ALIGN="RIGHT">ФАМИЛИЯ : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_SurName.'</B></td></tr>';
        // ИМЯ
        $html .= '<tr><td ALIGN="RIGHT">ИМЯ : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_Name.'</B></td></tr>';
        // ОТЧЕСТВО
        $html .= '<tr><td ALIGN="RIGHT">ОТЧЕСТВО : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_Partronic.'</B></td></tr>';
        // ДАТА НАРОДЖЕННЯ
        $html .= '<tr><td ALIGN="RIGHT">ДАТА НАРОДЖЕННЯ : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_BirthDay.'</B></td></tr>';
        // EMAIL
        $html .= '<tr><td ALIGN="RIGHT">EMAIL : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_Email.'</B></td></tr>';
        // ТЕЛЕФОН
        $html .= '<tr><td ALIGN="RIGHT">ТЕЛЕФОН : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_Phone1.'</B></td></tr>';
        // ТЕЛЕФОН
        $html .= '<tr><td ALIGN="RIGHT">ТЕЛЕФОН 2 : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_Phone2.'</B></td></tr>';
        // ТЕЛЕФОН
        $html .= '<tr><td ALIGN="RIGHT">ТЕЛЕФОН 3 : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_Phone3.'</B></td></tr>';
        // КТО ПОСОВЕТОВАЛ
        $html .= '<tr><td ALIGN="RIGHT">КТО ПОСОВЕТОВАЛ: </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_From.'</B></td></tr>';
        // КОМУ МЕНЯ РЕКОМЕНДОВАЛ
        $html .= '<tr><td ALIGN="RIGHT">КОМУ МЕНЯ РЕКОМЕНДОВАЛ : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_To.'</B></td></tr>';
        // ПРИМЕЧАНИЕ
        $html .= '<tr><td ALIGN="RIGHT">ПРИМЕЧАНИЕ : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_Note.'</B></td></tr>';
        //
        $html .= '</TABLE>';
      }
      // КНОПКИ
      $html .= '<TABLE BORDER="0"><tr><td>';
      $html .= ButtonBack(GetLink(),null,null); // кнопка НАЗАД
      $html .= '</td></tr></table>';
      echo $html;
    }
  }
  // РЕДАГУВАННЯ
  elseif (isset($EditID)) {
    echo '<B>РЕДАГУВАННЯ ДАНИХ</B><BR><BR>';
    //~ echo 'ПОТОЧНЕ НАЙМЕНУВАННЯ : <B>'.$_POST['Edit_Name'].'</B><BR>';
    // запрос
    $sqlt  = "SELECT Customers_ID, Customers_SurName, Customers_Name, Customers_Partronic
    , Customers_Email, Customers_Phone1, Customers_Phone2, Customers_Phone3
    , Customers_From, Customers_To, Customers_Note, Customers_BirthDay 
    FROM vCustomers ";
    $sqlt .= " WHERE Customers_ID = '".$EditID."'";
//if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
    $res = $wpdb->get_results($sqlt);
    if ( $res ) {
      $html  = '<FORM role="form" ACTION="'.GetLink().'" id="editform" method="post">';
      $html .= "<INPUT TYPE='HIDDEN' NAME='UpdateID' VALUE='".$EditID."'>";
      foreach ( $res as $data ) {
        $html .= '<TABLE COLS="2" BORDER="0">';
        // ФАМИЛИЯ
        $html .= '<tr><td ALIGN="RIGHT">ФАМИЛИЯ : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_SurName" Value="'.$data->Customers_SurName.'" size="20" /></td></tr>';
        // ИМЯ
        $html .= '<tr><td ALIGN="RIGHT">* ИМЯ : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_Name" Value="'.$data->Customers_Name.'" size="20" required /></td></tr>';
        // ОТЧЕСТВО
        $html .= '<tr><td ALIGN="RIGHT">ОТЧЕСТВО : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_Partronic" Value="'.$data->Customers_Partronic.'" size="20"  /></td></tr>';
        // ДАТА НАРОДЖЕННЯ
        $html .= '<tr><td ALIGN="RIGHT">ДАТА НАРОДЖЕННЯ : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="date" NAME="Customers_BirthDay" Value="'.$data->Customers_BirthDay.'"  /></td></tr>';
        // Email
        $html .= '<tr><td ALIGN="RIGHT">Email : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_Email" Value="'.$data->Customers_Email.'" size="30" /></td></tr>';
        // ТЕЛЕФОН
        $html .= '<tr><td ALIGN="RIGHT">ТЕЛЕФОН : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="phone" NAME="Customers_Phone1" Value="'.$data->Customers_Phone1.'" size="10" /></td></tr>';
        // ТЕЛЕФОН
        $html .= '<tr><td ALIGN="RIGHT">ТЕЛЕФОН 2 : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="phone" NAME="Customers_Phone2" Value="'.$data->Customers_Phone2.'" size="10" /></td></tr>';
        // ТЕЛЕФОН
        $html .= '<tr><td ALIGN="RIGHT">ТЕЛЕФОН 3 : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="phone" NAME="Customers_Phone3" Value="'.$data->Customers_Phone3.'" size="10" /></td></tr>';
        // КТО ПОСОВЕТОВАЛ ИЛИ ОТКУДА УЗНАЛИ
        $html .= '<tr><td ALIGN="RIGHT">КТО ПОСОВЕТОВАЛ ИЛИ ОТКУДА УЗНАЛИ	: </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_From" Value="'.$data->Customers_From.'" size="50" /></td></tr>';
        // КОМУ МЕНЯ РЕКОМЕНДОВАЛ
        $html .= '<tr><td ALIGN="RIGHT">КОМУ МЕНЯ РЕКОМЕНДОВАЛ : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_To" Value="'.$data->Customers_To.'" size="50" /></td></tr>';
        // ПРИМЕЧАНИЕ
        $html .= '<tr><td ALIGN="RIGHT">ПРИМЕЧАНИЕ : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_Note" Value="'.$data->Customers_Note.'" size="70"  /></td></tr>';
        //
        $html .= '</TABLE>';
      }
      // КНОПКИ
      $html .= '<TABLE BORDER="0"><tr><td>';
      $html .= '<INPUT TYPE="HIDDEN" TYPE="submit">';
      $html .= '<BUTTON type="submit" class="btn btn-group btn-warning" TITLE="зерегти ">';
      $html .= '<span class="glyphicon glyphicon-ok"></span> ЗБЕРЕГТИ</BUTTON></FORM>';
      $html .= '</td>';
      // НАЗАД
      $html .= '<td>';
      $html .= ButtonBack(GetLink(),null,null); // кнопка НАЗАД
      $html .= '</td></tr></table>';
      echo $html;
    } else {
      echo '<div class="alert alert-danger">НЕМАЄ ДАНИХ! </div>';
      echo '<div class="alert alert-danger">ПОМИЛКА : '.$wpdb->last_error.'</div>';
      echo ButtonBack(GetLink(),null,null); // кнопка НАЗАД
    }
  }
  // ФОРМА ДОДАВАННЯ
  elseif (isset($_POST['Adding'])) {
    echo '<B>ФОРМА ДОДАВАННЯ</B><BR><BR>';
    $html  = '<FORM role="form" ACTION="'.GetLink().'" method="post" id="addform" enctype="multipart/form-data">';  
    $html .= '<INPUT TYPE="hidden" name="InsertID" value="'.$_SESSION['UserID'].'">';
    $html .= '<TABLE COLS="2" BORDER="0">';
    // ФАМИЛИЯ
    $html .= '<tr><td ALIGN="RIGHT">ФАМИЛИЯ : </td>';
    $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_SurName" size="20" /></td></tr>';
    // ИМЯ
    $html .= '<tr><td ALIGN="RIGHT">* ИМЯ : </td>';
    $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_Name" size="20" required /></td></tr>';
    // ОТЧЕСТВО
    $html .= '<tr><td ALIGN="RIGHT">ОТЧЕСТВО : </td>';
    $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_Partronic" size="20"  /></td></tr>';
    // ДАТА НАРОДЖЕННЯ
    $html .= '<tr><td ALIGN="RIGHT">ДАТА НАРОДЖЕННЯ : </td>';
    $html .= '<td ALIGN="LEFT"><INPUT TYPE="date" NAME="Customers_BirthDay" Value="'.date('Y-m-d').'" /></td></tr>';
    // Email
    $html .= '<tr><td ALIGN="RIGHT">Email : </td>';
    $html .= '<td ALIGN="LEFT"><INPUT TYPE="email" NAME="Customers_Email" size="30" /></td></tr>';
    // ТЕЛЕФОН
    $html .= '<tr><td ALIGN="RIGHT">ТЕЛЕФОН : </td>';
    $html .= '<td ALIGN="LEFT"><INPUT TYPE="phone" NAME="Customers_Phone1" size="10" /></td></tr>';
    // ТЕЛЕФОН
    $html .= '<tr><td ALIGN="RIGHT">ТЕЛЕФОН 2 : </td>';
    $html .= '<td ALIGN="LEFT"><INPUT TYPE="phone" NAME="Customers_Phone2" size="10" /></td></tr>';
    // ТЕЛЕФОН
    $html .= '<tr><td ALIGN="RIGHT">ТЕЛЕФОН 3 : </td>';
    $html .= '<td ALIGN="LEFT"><INPUT TYPE="phone" NAME="Customers_Phone3" size="10" /></td></tr>';
    // КТО ПОСОВЕТОВАЛ ИЛИ ОТКУДА УЗНАЛИ
    $html .= '<tr><td ALIGN="RIGHT">КТО ПОСОВЕТОВАЛ ИЛИ ОТКУДА УЗНАЛИ	: </td>';
    $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_From" size="50" /></td></tr>';
    // КОМУ МЕНЯ РЕКОМЕНДОВАЛ
    $html .= '<tr><td ALIGN="RIGHT">КОМУ МЕНЯ РЕКОМЕНДОВАЛ : </td>';
    $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_To" size="50" /></td></tr>';
    // ПРИМЕЧАНИЕ
    $html .= '<tr><td ALIGN="RIGHT">ПРИМЕЧАНИЕ : </td>';
    $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_Note" size="70"  /></td></tr>';
    //
    $html .= '</TABLE>';
    // КНОПКИ
    $html .= '<TABLE BORDER="0"><tr><td>';
    $html .= '<INPUT TYPE="HIDDEN" TYPE="submit">';
    $html .= '<BUTTON type="submit" class="btn btn-group btn-warning" TITLE="зерегти ">';
    $html .= '<span class="glyphicon glyphicon-ok"></span> ЗБЕРЕГТИ</BUTTON></FORM>';
    $html .= '</td>';
    // НАЗАД
    $html .= '<td>';
    $html .= ButtonBack(GetLink(),null,null); // кнопка НАЗАД
    $html .= '</td></tr></table>';
    echo $html;
  }
  // ОСНОВНАЯ ФОРМА
  else {
    $n=0;
    echo '<B>КЛИЕНТЫ</B><BR>';
    // Додати
    echo '<TABLE class="tbutton" BORDER="0">';
    // ОНОВИТИ
    echo '<td class="tdbutton">';
    echo '<FORM ACTION="'.GetLink().'" method="post">';
    echo ' <INPUT TYPE="HIDDEN" NAME="Customers_Phone1" VALUE="">';
    echo ' <INPUT TYPE="HIDDEN" NAME="Orders_DateOrders" VALUE="">';
    echo ' <INPUT TYPE="HIDDEN" NAME="BirthMonth" VALUE="">';
    echo ' <INPUT TYPE="HIDDEN" NAME="FIO" VALUE="">';
    echo ' <INPUT TYPE="HIDDEN" TYPE="submit">';
    echo '<BUTTON type="submit" class="btn btn-group btn-xs btn-warning" TITLE="оновити">';
    echo '<span class="glyphicon glyphicon-refresh"></span> ОНОВИТИ</BUTTON>';
    echo '</FORM>';
    echo '</td>'; 
    if ($_SESSION['GroupBoss'] or $_SESSION['GroupAdm']) {
      echo '<td class="tdbutton">';
      echo '<FORM ACTION="'.GetLink().'" method="post">';
      echo ' <INPUT TYPE="HIDDEN" NAME="Adding" VALUE="'.$_SESSION['UserID'].'">';
      echo ' <INPUT TYPE="HIDDEN" TYPE="submit">';
      echo '<BUTTON type="submit" class="btn btn-group btn-xs btn-warning" TITLE="додати">';
      echo '<span class="glyphicon glyphicon-plus"></span> ДОДАТИ</BUTTON>';
      echo '</FORM>';
      echo '</td>';
    }
    echo '</TR></TABLE>';
    
    $html = '<TABLE border="0">';
    $html .= '<FORM action="'.GetLink().'" id="FindForm" method="post">';
    //  ФІЛЬТР ПО МІСЯЦЮ НАРОДЖЕННЯ
    $html .= '<TR>';
    $html .= '<td colspan="2" ALIGN="RIGHT">МІСЯЦЬ НАРОДЖЕННЯ: </td>';
    $html .= '<td ALIGN="LEFT"><SELECT name="BirthMonth" id="BirthMonth" />';
    $html .= '<OPTION '; if ('0'==$_SESSION['BirthMonth']) $html .= ' SELECTED '; $html .= "VALUE='0'>усі місяці</OPTION>"; 
    $html .= '<OPTION '; if ('1'==$_SESSION['BirthMonth']) $html .= ' SELECTED '; $html .= "VALUE='1'>1-січень</OPTION>"; 
    $html .= '<OPTION '; if ('2'==$_SESSION['BirthMonth']) $html .= ' SELECTED '; $html .= "VALUE='2'>2-лютий</OPTION>"; 
    $html .= '<OPTION '; if ('3'==$_SESSION['BirthMonth']) $html .= ' SELECTED '; $html .= "VALUE='3'>3-березень</OPTION>"; 
    $html .= '<OPTION '; if ('4'==$_SESSION['BirthMonth']) $html .= ' SELECTED '; $html .= "VALUE='4'>4-квітень</OPTION>"; 
    $html .= '<OPTION '; if ('5'==$_SESSION['BirthMonth']) $html .= ' SELECTED '; $html .= "VALUE='5'>5-травень</OPTION>"; 
    $html .= '<OPTION '; if ('6'==$_SESSION['BirthMonth']) $html .= ' SELECTED '; $html .= "VALUE='6'>6-червень</OPTION>"; 
    $html .= '<OPTION '; if ('7'==$_SESSION['BirthMonth']) $html .= ' SELECTED '; $html .= "VALUE='7'>7-липень</OPTION>"; 
    $html .= '<OPTION '; if ('8'==$_SESSION['BirthMonth']) $html .= ' SELECTED '; $html .= "VALUE='8'>8-серпень</OPTION>"; 
    $html .= '<OPTION '; if ('9'==$_SESSION['BirthMonth']) $html .= ' SELECTED '; $html .= "VALUE='9'>9-вересень</OPTION>"; 
    $html .= '<OPTION '; if ('10'==$_SESSION['BirthMonth']) $html .= ' SELECTED '; $html .= "VALUE='10'>10-жовтень</OPTION>"; 
    $html .= '<OPTION '; if ('11'==$_SESSION['BirthMonth']) $html .= ' SELECTED '; $html .= "VALUE='11'>11-листопад</OPTION>"; 
    $html .= '<OPTION '; if ('12'==$_SESSION['BirthMonth']) $html .= ' SELECTED '; $html .= "VALUE='12'>12-грудень</OPTION>"; 
    $html .= '</SELECT></td>';
    //-------
    $html .= '<td ALIGN="LEFT" VALIGN="BOTTOM"><BUTTON type="submit" class="btn dropdown-toggle btn-xs btn-warning" TITLE="вибрати">'; 
    $html .= '<span class="glyphicon glyphicon-search"></span> ВИБРАТИ</BUTTON></FORM></td>'; 
    $html .= '</tr></TABLE>';
    echo $html;
    
    // общее кол-во
    $cnt = intval( $wpdb->get_var( "SELECT COUNT(Customers_ID) as cnt FROM vCustomers" ));

    $number_records = $_SESSION['LIMIT'];
    $n=0;
    if ($cnt==0)
      $number_pages = 0;
    else
      $number_pages = CEIL($cnt/$number_records);
    if ($pages) {
      $Limitsql = ' LIMIT ';
      // с какой строки выводить
      if ($pages==1)
        $Limitsql .= 0;
      else {
        $Limitsql .= $_SESSION['LIMIT']*($pages-1);
        $n = $_SESSION['LIMIT']*($pages-1);
      }
      $Limitsql .= ',';
      // сколько строк выводить
      $Limitsql .= $_SESSION['LIMIT'];
    } else
      $Limitsql = ' LIMIT 0,'.$_SESSION['LIMIT']-1;  
    ///// ЗАПРОС
    $sqlt  = "SELECT Customers_ID, FIO
    , Customers_Email, Customers_Phone1, Customers_Phone2, Customers_Phone3
    , Customers_From, Customers_To, Customers_Note, Customers_BirthDay 
    FROM vCustomers ";
    if ($_SESSION['Customers_Phone1'] or $_SESSION['Customers_BirthDay'] or $_SESSION['Orders_DateOrders'] or $_SESSION['FIO']) {
      $sqlf .= " WHERE Customers_ID>0 ";
      if ($_SESSION['Customers_BirthDay'])
        $sqlf .= " AND Customers_BirthDay = '".$_SESSION['Customers_BirthDay']."'";
      if ($_SESSION['Customers_Phone1'])
        $sqlf .= " AND Customers_Phone1 LIKE '%".$_SESSION['Customers_Phone1']."%'";
      if ($_SESSION['FIO'])
        $sqlf .= " AND FIO LIKE '%".$_SESSION['FIO']."%' ";
    } else {
      if ($_SESSION['BirthMonth']>0)
        $sqlf .= " WHERE MONTH(Customers_BirthDay)='".$_SESSION['BirthMonth']."'";
    }
    $sqlt .= $sqlf;
//    $sqlt .= $Limitsql;
//if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
    if ($res = $wpdb->get_results($sqlt)) {
      $html  = '<TABLE class="table table-bordered table-hover table-striped" COLS="5" >';
      // ПОШУК ПО ПОЗИВНОМУ АБО ПРІЗВИЩУ
      $html .= '<TR><FORM ACTION="'.GetLink().'" method="post">';
      $html .= '<INPUT TYPE="HIDDEN" NAME="FindUser" Value="'.$_SESSION['UserID'].'" >';
      $html .= '<TD></TD>';
      $html .= '<TD align="center"><INPUT TYPE="text" NAME="FIO" value="'.$_SESSION['FIO'].'" maxlength="20" placeholder="пошук по ПІБ" /></TD>';
      $html .= '<TD align="center"><INPUT TYPE="date" NAME="Customers_BirthDay" value="'.$_SESSION['Customers_BirthDay'].'" maxlength="20" placeholder="пошук по дате народження" /></TD>';
      $html .= '<TD align="center"><INPUT TYPE="tel" NAME="Customers_Phone1" value="'.$_SESSION['Customers_Phone1'].'" maxlength="20" placeholder="пошук по телефону" /></TD>';
      $html .= '<TD></TD>';
      $html .= '<TD align="left" COLSPAN="3"><BUTTON type="submit" class="btn btn-group btn-xs btn-warning" title="пошук">';
      $html .= '<span class="glyphicon glyphicon-search"></span> ПОШУК </BUTTON></FORM></td>'; 
      $html .= '</TR>';
      //--------------
      $html .= '<TR>';
      $html .= ' <TD width="20" ALIGN="CENTER" VALIGN="MIDDLE"><B>№</B></TD>';
      $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>ПІБ</B></TD>';
      $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>НАРОДЖЕННЯ</B></TD>';
      $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>ТЕЛЕФОН</B></TD>';
      $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>EMAIL</B></TD>';
      $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>ВІД КОГО</B></TD>';
      $html .= ' <TD width="20" ALIGN="CENTER" VALIGN="MIDDLE" title="опції"><span class="glyphicon glyphicon-th"></span></TD>';
      $html .= '</TR>';
      //~ $n=0;
      foreach ( $res as $data ) {
        $n++; // подсчёт кол-ва участников
        $html .= '<TR>';
        $html .= ' <TD align="center">'.$n.'</TD>';
        $html .= ' <TD align="left"><a href="'.GetLink().'?ViewID='.$data->Customers_ID.'" >'.$data->FIO.'</a></TD>';
        $html .= ' <TD align="left" TITLE="примітка : '.$data->Customers_Note.'" >'.date('d.m.Y',strtotime($data->Customers_BirthDay)).'</TD>';
        $html .= ' <TD align="left" TITLE="телефон 2 : '.$data->Customers_Phone2.'" ><a href="'.GetLink().'?EditID='.$data->Customers_ID.'" >'.$data->Customers_Phone1.'</a></TD>';
        $html .= ' <TD align="left" TITLE="телефон 3 : '.$data->Customers_Phone3.'" >'.$data->Customers_Email.'</TD>';
        $html .= ' <TD align="left" TITLE="Кому мені рекомендовал : '.$data->Customers_To.'" >'.$data->Customers_From.'</TD>';
        // меню
        $html .= ' <TD ALIGN="center">';
        $html .= '<div class="btn-group">';
        $html .= '<button type="button" class="btn dropdown-toggle btn-xs btn-warning" data-toggle="dropdown">'; // btn-default 
        $html .= '<span class="caret"></span></button>';
        $html .= '<ul class="dropdown-menu" role="menu">';
        //-ПЕРЕГЛЯД
        $html .= '<li><FORM role="form" ACTION="'.GetLink().'" method="post">';
        $html .= ' <INPUT TYPE="HIDDEN" NAME="ViewID" VALUE="'.$data->Customers_ID.'">';
        $html .= '<BUTTON type="submit" class="btn btn-group btn-xs btn-warning" TITLE="перегляд">';
        $html .= '<span class="glyphicon glyphicon-search"></span> ПЕРЕГЛЯД</BUTTON>';
        $html .= '</FORM></li>';
        //-РЕДАГУВАННЯ
        $html .= '<li><FORM role="form" ACTION="'.GetLink().'" method="post">';
        $html .= ' <INPUT TYPE="HIDDEN" NAME="EditID" VALUE="'.$data->Customers_ID.'">';
        $html .= ' <INPUT TYPE="HIDDEN" NAME="Edit_Name" VALUE="'.$data->Product_Name.'">';
        $html .= '<BUTTON type="submit" class="btn btn-group btn-xs btn-warning" TITLE="редагування">';
        $html .= '<span class="glyphicon glyphicon-pencil"></span> РЕДАГУВАННЯ</BUTTON>';
        $html .= '</FORM></li>';
        // удаление
        if ($_SESSION['GroupAdm']) {
          $html .= '<li>';
          $html .= '<BUTTON type="button" class="btn btn-group btn-xs btn-warning" onclick="delete_record('.$data->Customers_ID.')" title="видалення">';
          $html .= '<span class="glyphicon glyphicon-remove"></span> ВИДАЛЕННЯ</BUTTON>';
          $html .= '</li>';
        }
        $html .= '</ul>';
        $html .= '</div>';
        $html .= ' </TD>';
        // 
        $html .= '</TR>';
      }
      //
      $html .= '</TABLE>';
      echo $html;
      // сторінці
      if ($cnt>$_SESSION['LIMIT']) {
        //echo '<div class="btn-toolbar" role="toolbar">';
        echo '<div class="btn-toolbar" >';
        for ($x=0; $x++<$number_pages;) {
          if ($x == $pages) {
            //echo '<div class="btn-group">';
            echo '<button type="button" class="btn btn-default btn-sm btn-warning" disabled="disabled">';
            echo '<B>'.$x.'</B><span class="sr-only">(поточна)</span></button>';
            //echo '</div>';
          } else {
            //echo '<div class="btn-group">';
            echo '<FORM ACTION="'.GetLink().'" method="post">';
            echo '<INPUT TYPE="HIDDEN" NAME="pages" VALUE="'.$x.'">';
            echo '<INPUT TYPE="HIDDEN" TYPE="submit">';
            echo '<button type="submit" class="btn btn-default btn-sm" title="'.$x.'">'.$x.'</button>';
            echo '</FORM>';
            //echo '</div>';
          }
        }
        echo '</div>';
      }
      echo '<TABLE class="tbutton" BORDER="0"><TR><TD>ВСЬОГО : <span class="badge"><B>'.$cnt.'</B></span></TD></TR>';
    } else {
      echo '<div class="alert alert-danger">НЕМАЄ ДАНИХ! </div>';
      echo '<div class="alert alert-danger">ПОМИЛКА : '.$wpdb->last_error.'</div>';
      echo ButtonBack(GetLink(),null,null); // кнопка НАЗАД
    }
  }
} else
  echo '<div class="alert alert-danger">НЕМАЄ ДОСТУПУ! <a href="/wp-login.php" class="alert-link">УВІЙТИ</a></div>';
////
?>
</div></div></center>
<?php
include ('bottom.php'); // подключаем подвал
?>

