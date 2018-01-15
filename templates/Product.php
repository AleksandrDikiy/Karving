<?php
/** 
  * Template Name: ТОВАРЫ
  *       Version: 1.0.0.0
  *          Name: Product
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
  $LogName  = 'Product';

  // ViewID
  if (isset($_GET['ViewID']))
    $ViewID = $_GET['ViewID'];
  elseif (isset($_POST['ViewID']))
    $ViewID = $_POST['ViewID'];
  elseif ($_SESSION['ViewID'])
    $ViewID = $_SESSION['ViewID'];
  // EditID
  if (isset($_GET['EditID']))
    $EditID = $_GET['EditID'];
  elseif (isset($_POST['EditID']))
    $EditID = $_POST['EditID'];
  elseif ($_SESSION['ViewID'])
    $EditID = $_SESSION['ViewID'];
  // pages
  if (isset($_GET['pages']))
    $pages = $_GET['pages'];
  elseif (isset($_POST['pages']))
    $pages = $_POST['pages'];
  else
    $pages = 1;

  if (isset($_POST['Product_ID']))
    $_SESSION['Product_ID'] = $_POST['Product_ID'];
  elseif (isset($_GET['Product_ID']))
    $_SESSION['Product_ID'] = $_GET['Product_ID'];

  // ВИДАЛЕННЯ
  if (isset($_GET['del_id'])) {
    if ($_SESSION['GroupAdm']) {
      $wpdb->show_errors();
      $res = $wpdb->delete( 'Product', array( 'Product_ID' => $_GET['del_id'] ) );
      $_SESSION['GroupAdm'] = $GroupAdm;
      $_SESSION['UserID'] = $UserID;
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
        echo '<div class="alert alert-danger">ПОМИЛКА ВИДАЛЕННЯ! ВІДПРАВИТИ ПОВІДОМЛЕННЯ ДО <a href="/SendMail.php" target="_blank" class="alert-link">АДМІНІСТРАТОРА</a></div>';
      }
      // перенаправление
      header ('Location: '.GetLink());
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
      $res = $wpdb->insert('Product',
        array(
          'ProductType_ID' => $_POST['ProductType_ID']
        , 'Product_Note' => $_POST['Product_Note']
        , 'Product_Kod' => $_POST['Product_Kod']
        , 'Product_Summa' => $_POST['Product_Summa'] ),
        array( '%d', '%s', '%s', '%s' )
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
        $_SESSION['SQLTxt'] = $sqlt;
        echo $_SESSION['MySQLiEerror']."<BR>";
        if ($_SESSION['GroupAdm']) echo 'SQL = '.$_SESSION['SQLTxt']."<BR>";
        echo '<BR>query : '.$wpdb->last_query.'<BR>';
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
    if ($_SESSION['GroupAdm']) {
      $wpdb->show_errors();
      $res = $wpdb->update( 'Product',
        array(
          'Product_Note' => $_POST['Product_Note']
        , 'ProductType_ID' => $_POST['ProductType_ID']
        , 'Product_Summa' => $_POST['Product_Summa']
        , 'Product_Kod' => $_POST['Product_Kod'] ),
        array( 'Product_ID' => $_POST['UpdateID'] )
      );
      $_SESSION['SQLTxt'] = str_replace('"',"'",$wpdb->last_query);
      $_SESSION['MySQLiEerror'] = 'помилка '.$wpdb->last_error;
      if ($res) {
        echo '<div class="alert alert-success">УСПІШНЕ ОНОВЛЕННЯ!</div>';
//        SetLogs($UserID,'U',$LogName,str_replace('"',"'",$wpdb->last_query),'успішно');
        header ('Location: '.GetLink().'?pages='.$pages.''); // перенаправление
      } else {
        echo '<BR>query : '.$wpdb->last_query.'<BR>';
        echo '<BR>error : '.$wpdb->last_error.'<BR>';
//        SetLogs($UserID,'U',$LogName,str_replace('"',"'",$wpdb->last_query),$wpdb->last_error);
        echo '<div class="alert alert-danger">ПОМИЛКА ОНОВЛЕННЯ! ВІДПРАВИТИ ПОВІДОМЛЕННЯ ДО <a href="/SendMail.php" target="_blank" class="alert-link">АДМІНІСТРАТОРА</a></div>';
      }
      echo '<BR>query : '.$wpdb->last_query.'<BR>';
    } else {
      echo '<div class="alert alert-danger">НЕМАЕ ДОСТУПУ</div>';
    }
    echo ButtonBack(null,null); // кнопка НАЗАД
  }
  // ПЕРЕГЛЯД ДАНИХ
  elseif (isset($ViewID)) {
    echo '<B>ПЕРЕГЛЯД ДАНИХ</B><BR><BR>';
    // запрос
    $sqlt = "SELECT Product_ID, ProductType_ID, Product_Kod, Product_Note, Product_Summa, ProductType_Name, ProductType_Order FROM vProduct"; 
    $sqlt .= " WHERE Product_ID = '".$ViewID."'";
//if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
    $res = $wpdb->get_results($sqlt);
    if ( $res ) {
      foreach ( $res as $data ) {
        $html  = '<TABLE COLS="2" BORDER="0">';
        // КАТЕГОРІЯ
        $html .= ' <tr>';
        $html .= '  <td ALIGN="RIGHT">КАТЕГОРІЯ : </td>';
        $html .= '  <td ALIGN="LEFT"><B>'.$data->ProductType_Name.'</B></td>';
        $html .= ' </tr>';
        // КОД
        $html .= ' <tr>';
        $html .= '  <td ALIGN="RIGHT">КОД : </td>';
        $html .= '  <td ALIGN="LEFT"><B>'.$data->Product_Kod.'</B></td>';
        $html .= ' </tr>';
        // ОПИСАНИЕ
        $html .= ' <tr>';
        $html .= '  <td ALIGN="RIGHT">ОПИСАНИЕ : </td>';
        $html .= '  <td ALIGN="LEFT"><B>'.$data->Product_Note.'</B></td>';
        $html .= ' </tr>';
        // СУМА
        $html .= ' <tr>';
        $html .= '  <td ALIGN="RIGHT">СУМА : </td>';
        $html .= '  <td ALIGN="LEFT"><B>'.$data->Product_Summa.'</B></td>';
        $html .= ' </tr>';
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
    if ($_POST['Edit_Name'])
      echo 'ПОТОЧНЕ НАЙМЕНУВАННЯ : <B>'.$_POST['Edit_Name'].'</B><BR>';
    // запрос
    $sqlt = "SELECT Product_ID, ProductType_ID, Product_Kod, Product_Note, Product_Summa, ProductType_Name, ProductType_Order  FROM vProduct"; 
    $sqlt .= " WHERE Product_ID = '".$EditID."'";
//if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
  $res = $wpdb->get_results($sqlt);
  if ( $res ) {
      $html  = '<FORM role="form" ACTION="'.GetLink().'" id="editform" method="post">';
      $html .= "<INPUT TYPE='HIDDEN' NAME='UpdateID' VALUE='".$EditID."'>";
      foreach ($res as $data) {
        $html .= '<TABLE COLS="2" BORDER="0">';
        // КАТЕГОРІЯ
        $html .= '<TR>';
        $html .= ' <TD align="RIGHT">* КАТЕГОРІЯ : </TD>';
        $html .= ' <TD><SELECT name="ProductType_ID" id="ProductType_ID" >';
        $sqlt = "SELECT ProductType_ID, ProductType_Name FROM vProductType";
// if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
        $res = $wpdb->get_results($sqlt);
        if ( $res ) {
          foreach ($res as $data2) {
            $html .= '<OPTION ';
            if ($data2->ProductType_ID == $data->ProductType_ID) $html .= ' SELECTED ';
            $html .= "VALUE='" . $data2->ProductType_ID . "'>" . $data2->ProductType_Name."</OPTION>";
          }
        }
        $html .= '</SELECT></TD></TR>';
        // КОД
        $html .= ' <tr>';
        $html .= '  <td ALIGN="RIGHT">* КОД : </td>';
        $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Product_Kod" Value="'.$data->Product_Kod.'" size="20" required /></td>';
        $html .= ' </tr>';
        // ОПИСАНИЕ
        $html .= ' <tr>';
        $html .= '  <td ALIGN="RIGHT">* ОПИСАНИЕ : </td>';
        $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Product_Note" Value="'.$data->Product_Note.'" size="50" required /></td>';
        $html .= ' </tr>';
        // СУМА
        $html .= ' <tr>';
        $html .= '  <td ALIGN="RIGHT">* СУМА : </td>';
        $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Product_Summa" Value="'.$data->Product_Summa.'" required /></td>';
        $html .= ' </tr>';
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
    }  
  }
  // ФОРМА ДОДАВАННЯ
  elseif (isset($_POST['Adding'])) {
    echo '<B>ДОДАВАННЯ ТОВАРА</B><BR><BR>';
    $html  = '<FORM role="form" ACTION="'.GetLink().'" method="post" id="addform" enctype="multipart/form-data">';  
    $html .= '<INPUT TYPE="hidden" name="InsertID" value="'.$_SESSION['UserID'].'">';
    $html .= '<table COLS="2" BORDER="0">';
    //~ $html .= "<COLGROUP></COLGROUP>";
    //~ $html .= "<COLGROUP WIDTH='100'></COLGROUP>";
    // КАТЕГОРІЯ
    $html .= '<TR>';
    $html .= ' <TD align="RIGHT">* КАТЕГОРІЯ : </TD>';
    $html .= ' <TD><SELECT name="ProductType_ID" id="ProductType_ID" >';
    $sqlt = "SELECT ProductType_ID, ProductType_Name FROM vProductType";
// if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
    $res = $wpdb->get_results($sqlt);
    if ( $res ) {
      foreach ($res as $data) {
        $html .= '<OPTION ';
        if ($data->ProductType_ID == $_SESSION['ProductType_ID']) $html .= ' SELECTED ';
        $html .= "VALUE='" . $data->ProductType_ID . "'>" . $data->ProductType_Name."</OPTION>";
      }
    }
    $html .= '</SELECT></TD></TR>';
    // КОД
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">* КОД : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Product_Kod" size="10" required /></td>';
    $html .= ' </tr>';
    // ОПИСАНИЕ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">* ОПИСАНИЕ : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Product_Note" required placeholder="найменування" size="50" /></td>';
    $html .= ' </tr>';
    // СУМА
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">* СУМА : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Product_Summa" size="10" required /></td>';
    $html .= ' </tr>';
    //
    $html .= '</table>';
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
    echo '<B>ТОВАТИ</B><BR>';
    // Додати
    if ($_SESSION['GroupAdm']) {
      echo '<FORM ACTION="'.GetLink().'" method="post">';
      echo ' <INPUT TYPE="HIDDEN" NAME="Adding" VALUE="'.$_SESSION['UserID'].'">';
      echo ' <INPUT TYPE="HIDDEN" TYPE="submit">';
      echo '<BUTTON type="submit" class="btn btn-group btn-warning" TITLE="додати">';
      echo '<span class="glyphicon glyphicon-plus"></span> ДОДАТИ</BUTTON>';
      echo '</FORM>';
    }
    // общее кол-во
    $cnt = intval( $wpdb->get_var( "SELECT COUNT(Product_ID) as cnt FROM vProduct" ));

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
    $sqlt  = "SELECT Product_ID, ProductType_ID, Product_Kod, Product_Note, Product_Summa, ProductType_Name, ProductType_Order 
    FROM vProduct ";
//    $sqlt .= $Limitsql;
//if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
    if ($res = $wpdb->get_results($sqlt)) {
      $html  = '<TABLE class="table table-bordered table-hover table-striped" COLS="5" >';
      $html .= '<TR>';
      $html .= ' <TD width="20" ALIGN="CENTER" VALIGN="MIDDLE"><B>№</B></TD>';
      $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>КОД</B></TD>';
      $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>ОПИСАНИЕ</B></TD>';
      $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>СУМА</B></TD>';
      $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>ТИП ТОВАРА</B></TD>';
      $html .= ' <TD width="20" ALIGN="CENTER" VALIGN="MIDDLE" title="опції"><span class="glyphicon glyphicon-th"></span></TD>';
      $html .= '</TR>';
      //~ $n=0;
      foreach ( $res as $data ) {
        $n++; // подсчёт кол-ва участников
        $html .= '<TR>';
        $html .= ' <TD align="center">'.$n.'</TD>';
        $html .= ' <TD align="left"><a href="'.GetLink().'?ViewID='.$data->Product_ID.'" >'.$data->Product_Kod.'</a></TD>';
        $html .= ' <TD align="left">'.$data->Product_Note.'</TD>';
        $html .= ' <TD align="left"><a href="'.GetLink().'?EditID='.$data->Product_ID.'" >'.$data->Product_Summa.'</a></TD>';
        $html .= ' <TD align="left">'.$data->ProductType_Name.'</TD>';
        // меню
        $html .= ' <TD ALIGN="center">';
        $html .= '<div class="btn-group">';
        $html .= '<button type="button" class="btn dropdown-toggle btn-xs btn-warning" data-toggle="dropdown">'; // btn-default 
        $html .= '<span class="caret"></span></button>';
        $html .= '<ul class="dropdown-menu" role="menu">';
        //-ПЕРЕГЛЯД
        $html .= '<li><FORM role="form" ACTION="'.GetLink().'" method="post">';
        $html .= ' <INPUT TYPE="HIDDEN" NAME="ViewID" VALUE="'.$data->Product_ID.'">';
        $html .= '<BUTTON type="submit" class="btn btn-group btn-xs btn-warning" TITLE="перегляд">';
        $html .= '<span class="glyphicon glyphicon-search"></span> ПЕРЕГЛЯД</BUTTON>';
        $html .= '</FORM></li>';
        //-РЕДАГУВАННЯ
        $html .= '<li><FORM role="form" ACTION="'.GetLink().'" method="post">';
        $html .= ' <INPUT TYPE="HIDDEN" NAME="EditID" VALUE="'.$data->Product_ID.'">';
        $html .= ' <INPUT TYPE="HIDDEN" NAME="Edit_Name" VALUE="'.$data->Product_Note.'">';
        $html .= '<BUTTON type="submit" class="btn btn-group btn-xs btn-warning" TITLE="редагування">';
        $html .= '<span class="glyphicon glyphicon-pencil"></span> РЕДАГУВАННЯ</BUTTON>';
        $html .= '</FORM></li>';
        // удаление
        if ($_SESSION['GroupAdm']) {
          $html .= '<li>';
          $html .= '<BUTTON type="button" class="btn btn-group btn-xs btn-warning" onclick="delete_record('.$data->Product_ID.')" title="видалення">';
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
            echo '<button type="button" class="btn btn-default btn-sm btn-warning" disabled="disabled"><B>'.$x.'</B><span class="sr-only">(поточна)</span></button>';
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
    } else
      echo '<div class="alert alert-danger">НЕМАЄ ДАНИХ! </div>';
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

