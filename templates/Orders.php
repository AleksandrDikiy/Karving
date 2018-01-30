<?php
/** 
  * Template Name: ЗАКАЗЫ
  *       Version: 1.0.0.0
  *          Name: Orders
*/ 
// ini_set('display_errors', 1);
// ini_set('error_reporting', E_ALL);
session_start();
include ('body.php'); // подключаем меню
?>
<script type="text/javascript">
  function delete_record($del_id) {
    if (confirm("Ви дійсно бажаете ВИДАЛИТИ запис?") === false) { return; }
    document.location.href = window.location.pathname+"?del_id="+$del_id;
  }
  function DeleteOrderProduct($del_id) {
    if (confirm("Ви дійсно бажаете ВИДАЛИТИ запис?") === false) { return; }
    document.location.href = window.location.pathname+"?DelOrderProduct="+$del_id;
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
  $LogName  = 'Orders';
  
  if (!$_SESSION['LIMIT']) 
    $_SESSION['LIMIT'] = 10;

  if (isset($_GET['pages']))
    $pages = $_GET['pages'];
  elseif (isset($_POST['pages']))
    $pages = $_POST['pages'];
  else
    $pages = 1;

  if (isset($_POST['Orders_ID']))
    $_SESSION['Orders_ID'] = $_POST['Orders_ID'];
  elseif (isset($_GET['Orders_ID']))
    $_SESSION['Orders_ID'] = $_GET['Orders_ID'];
    
  // EditOrderProduct
  if (isset($_POST['EditOrderProduct']))
    $EditOrderProduct = $_POST['EditOrderProduct'];
  elseif (isset($_GET['EditOrderProduct']))
    $EditOrderProduct = $_GET['EditOrderProduct'];
  
  // ViewID
  if (isset($_POST['ViewID']))
    $ViewID = $_POST['ViewID'];
  elseif (isset($_GET['ViewID']))
    $ViewID = $_GET['ViewID'];
  else
    $ViewID = null;

  // FIO
  if (isset($_POST['FIO']))
    $_SESSION['FIO'] = $_POST['FIO'];
  elseif (isset($_GET['FIO']))
    $_SESSION['FIO'] = $_GET['FIO'];
  else
    $_SESSION['FIO'] = null;
    
  // Customers_Phone1
  if (isset($_POST['Customers_Phone1']))
    $_SESSION['Customers_Phone1'] = $_POST['Customers_Phone1'];
  elseif (isset($_GET['Customers_Phone1']))
    $_SESSION['Customers_Phone1'] = $_GET['Customers_Phone1'];
  else
    $_SESSION['Customers_Phone1'] = null;
    
  // Orders_DateOrders
  if (isset($_POST['Orders_DateOrders']))
    $_SESSION['Orders_DateOrders'] = $_POST['Orders_DateOrders'];
  elseif (isset($_GET['Orders_DateOrders']))
    $_SESSION['Orders_DateOrders'] = $_GET['Orders_DateOrders'];
  else
    $_SESSION['Orders_DateOrders'] = null;

  // BirthMonth
  if (isset($_POST['BirthMonth']))
    $_SESSION['BirthMonth'] = $_POST['BirthMonth'];
  elseif (isset($_GET['BirthMonth']))
    $_SESSION['BirthMonth'] = $_GET['BirthMonth'];
  elseif (isset($_SESSION['BirthMonth']))
    $_SESSION['BirthMonth'] = $_SESSION['BirthMonth'];
  else
    $_SESSION['BirthMonth'] = 0;

  // ВИДАЛЕННЯ
  if (isset($_GET['del_id'])) {
    $wpdb->show_errors();
    $res = $wpdb->delete( 'OrderProduct', array( 'Orders_ID' => $_GET['del_id'] ) );
//    $_SESSION['GroupAdm'] = $GroupAdm;
//    $_SESSION['UserID'] = $UserID;
    $_SESSION['SQLTxt'] = str_replace('"',"'",$wpdb->last_query);
    $_SESSION['MySQLiEerror'] = 'помилка:'.$wpdb->last_error;
    if ($res) {
      echo '<div class="alert alert-success">УСПІШНЕ ВИДАЛЕННЯ ТОВАРІВ ПО ЗАКАЗУ!</div>';
//      SetLogs($UserID,'D',$LogName,str_replace('"',"'",$wpdb->last_query),'успішно');
      echo '<BR>query : '.$wpdb->last_query.'<BR>';
    } else {
//      SetLogs($UserID,'D',$LogName,str_replace('"',"'",$wpdb->last_query),$wpdb->last_error);
      echo '<BR>query : '.$wpdb->last_query.'<BR>';
      echo 'MySQLiError = '.$wpdb->last_error."<BR>";
      echo '<div class="alert alert-danger">ПОМИЛКА ВИДАЛЕННЯ ТОВАРІВ ПО ЗАКАЗУ! ВІДПРАВИТИ ПОВІДОМЛЕННЯ ДО <a href="/SendMail.php" target="_blank" class="alert-link">АДМІНІСТРАТОРА</a></div>';
    }

    $res = $wpdb->delete( 'Orders', array( 'Orders_ID' => $_GET['del_id'] ) );
    $_SESSION['SQLTxt'] = str_replace('"',"'",$wpdb->last_query);
    $_SESSION['MySQLiEerror'] = 'помилка:'.$wpdb->last_error;
    if ($res) {
      echo '<div class="alert alert-success">УСПІШНЕ ВИДАЛЕННЯ ЗАКАЗУ!</div>';
//      header ('Location: '.GetLink()); // перенаправление
    } else {
//      SetLogs($UserID,'D',$LogName,str_replace('"',"'",$wpdb->last_query),$wpdb->last_error);
      echo '<BR>query : '.$_SESSION['SQLTxt'].'<BR>';
      echo 'MySQLiError = '.$_SESSION['MySQLiEerror']."<BR>";
      echo '<div class="alert alert-danger">ПОМИЛКА ВИДАЛЕННЯ ЗАКАЗУ! ВІДПРАВИТИ ПОВІДОМЛЕННЯ ДО <a href="/SendMail.php" target="_blank" class="alert-link">АДМІНІСТРАТОРА</a></div>';
    }
    echo ButtonBack(GetLink(),null,null); // кнопка НАЗАД
  }
  // ДОДАВАННЯ КЛІЕНТА та ЗАКАЗУ у БД
  elseif (isset($_POST['InsertID'])) {
    if ($_SESSION['GroupAdm']) {
      // форматируем номера телефонов
//      $msearch = array(".", ",", " ", "-", "_", "(", ")"
//      ,"А","Б","В","Г","Д","Е","Ё","Ж","З","И","Й"
//      ,"К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х"
//      ,"Ц","Ч","Ш","Щ","Ъ","Ы","Ь","Э","Ю","Я","а","б"
//      ,"в","г","д","е","ё","ж","з","и","й","к","л","м"
//      ,"н","о","п","р","с","т","у","ф","х","ц","ч","ш"
//      ,"щ","ъ","ы","ь","э","ю","я"
//      ,".",",","_","-","(",")","*","!","@","№","$","%","^","&","=","+","#","~");
      $Customers_Phone1 = str_replace($msearch, '', $_POST['Customers_Phone1']);
      $Customers_Phone2 = str_replace($msearch, '', $_POST['Customers_Phone2']);
      $Customers_Phone3 = str_replace($msearch, '', $_POST['Customers_Phone3']);
      // ищем телефон в БД
      $sqlt = "SELECT Customers_ID FROM vCustomers WHERE Customers_Phone1 LIKE '%" . $Customers_Phone1 . "%' ";
      $Customers_ID = intval($wpdb->get_var($sqlt));
      // нет Клиента в БД, то добавляем его
      if (!$Customers_ID) {
        $wpdb->show_errors();
        $dt = date('Y-m-d H-m-s', time());
        $res = $wpdb->insert('Customers', array('Customers_DateCreate' => $dt, 'Customers_SurName' => $_POST['Customers_SurName'], 'Customers_Name' => $_POST['Customers_Name'], 'Customers_Partronic' => $_POST['Customers_Partronic'], 'Customers_BirthDay' => $_POST['Customers_BirthDay'], 'Customers_Email' => $_POST['Customers_Email'], 'Customers_Phone1' => $Customers_Phone1, 'Customers_Phone2' => $Customers_Phone2, 'Customers_Phone3' => $Customers_Phone3, 'Customers_From' => $_POST['Customers_From'], 'Customers_To' => $_POST['Customers_To'], 'Customers_Note' => $_POST['Customers_Note']), array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
        $_SESSION['SQLTxt'] = str_replace('"', "'", $wpdb->last_query);
        $_SESSION['MySQLiEerror'] = 'помилка:' . $wpdb->last_error;
        if ($res) {
          echo '<div class="alert alert-success">УСПІШНЕ ДОДАВАННЯ НОВОГО КЛІЕНТА!</div>';
//        SetLogs($UserID,'I',$LogName,str_replace('"',"'",$wpdb->last_query),'успішно');
          echo '<BR>query : ' . $wpdb->last_query . '<BR>';
          // ПОЛУЧАЕМ ID КЛИЕНТА
          $Customers_ID = intval($wpdb->get_var("SELECT Customers_ID FROM Customers WHERE Customers_DateCreate='" . $dt . "'"));
        } else {
//        SetLogs($UserID,'I',$LogName,str_replace('"',"'",$wpdb->last_query),$wpdb->last_error);
          $_SESSION['SQLTxt'] = $sqlt;
//          echo $_SESSION['MySQLiEerror'] . "<BR>";
//          if ($_SESSION['GroupAdm']) echo 'SQL = ' . $_SESSION['SQLTxt'] . "<BR>";
          echo '<BR>query : ' . $wpdb->last_query . '<BR>';
          echo '<BR>error : ' . $wpdb->last_error . '<BR>';
          echo '<div class="alert alert-danger">ПОМИЛКА ДОДАВАННЯ НОВОГО КЛІЕНТА! ВІДПРАВИТИ ПОВІДОМЛЕННЯ ДО <a href="/SendMail.php" target="_blank" class="alert-link">АДМІНІСТРАТОРА</a></div>';
        }
      }
      // ДОБАВЛЯЕМ ЗАКАЗ ПО ЭТОМУ КЛИЕНТУ
      $dt = date('Y-m-d H-m-s', time());
      if ($Customers_ID and $dt) {
        $res = $wpdb->insert('Orders', array('Orders_DateCreate' => $dt, 'Customers_ID' => $Customers_ID, 'Orders_DateOpen' => $_POST['Orders_DateOpen'], 'Orders_DateOrders' => $_POST['Orders_DateOrders'], 'Orders_TimeOrders' => $_POST['Orders_TimeOrders'], 'Orders_Discount' => $_POST['Orders_Discount'], 'Orders_Delivery' => $_POST['Orders_Delivery'], 'Orders_PrePayment' => $_POST['Orders_PrePayment'], 'Orders_Adress' => $_POST['Orders_Adress'], 'Orders_Note' => $_POST['Orders_Note']), array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
        if ($res) {
          echo '<div class="alert alert-success">УСПІШНЕ ДОДАВАННЯ ЗАКАЗА!</div>';
//        SetLogs($UserID,'I',$LogName,str_replace('"',"'",$wpdb->last_query),'успішно');
          echo '<BR>query : ' . $wpdb->last_query . '<BR>';
          // ПОЛУЧАЕМ ID ЗАКАЗА
          $Orders_ID = intval($wpdb->get_var("SELECT Orders_ID FROM Orders WHERE Orders_DateCreate='" . $dt . "'"));
          // ДОБАВЛЯЕМ ПРОДУКТ К ЗАКАЗУ
          if ($Orders_ID) {
            $res = $wpdb->insert('OrderProduct', array('Orders_ID' => $Orders_ID, 'Product_ID' => $_POST['Product_ID']), array('%s', '%s', '%s'));
            if ($res) {
              echo '<div class="alert alert-success">УСПІШНЕ ДОДАВАННЯ ПРОДУКТА В ЗАКАЗ!</div>';
//        SetLogs($UserID,'I',$LogName,str_replace('"',"'",$wpdb->last_query),'успішно');
              echo '<BR>query : ' . $wpdb->last_query . '<BR>';
              header('Location: ' . GetLink()); // перенаправление
            } else {
//        SetLogs($UserID,'I',$LogName,str_replace('"',"'",$wpdb->last_query),$wpdb->last_error);
              $_SESSION['SQLTxt'] = $sqlt;
//            echo $_SESSION['MySQLiEerror'] . "<BR>";
//            if ($_SESSION['GroupAdm']) echo 'SQL = ' . $_SESSION['SQLTxt'] . "<BR>";
              echo '<BR>query : ' . $wpdb->last_query . '<BR>';
              echo '<BR>error : ' . $wpdb->last_error . '<BR>';
              echo '<div class="alert alert-danger">ПОМИЛКА ДОДАВАННЯ ПРОДУКТА В ЗАКАЗ! ВІДПРАВИТИ ПОВІДОМЛЕННЯ ДО <a href="/SendMail.php" target="_blank" class="alert-link">АДМІНІСТРАТОРА</a></div>';
            }
          } else {
            echo '<div class="alert alert-danger">ПОМИЛКА! ВІДСУТНІЙ ЗАКАЗ</div>';
            echo '<BR>query : ' . $wpdb->last_query . '<BR>';
            echo '<BR>error : ' . $wpdb->last_error . '<BR>';
            echo '<BR>dt : ' . $dt . '<BR>';
            echo '<BR>Orders_ID : ' . $Orders_ID . '<BR>';
          }
        } else {
//        SetLogs($UserID,'I',$LogName,str_replace('"',"'",$wpdb->last_query),$wpdb->last_error);
          $_SESSION['SQLTxt'] = $sqlt;
//          echo $_SESSION['MySQLiEerror'] . "<BR>";
//          if ($_SESSION['GroupAdm']) echo 'SQL = ' . $_SESSION['SQLTxt'] . "<BR>";
          echo '<BR>query : ' . $wpdb->last_query . '<BR>';
          echo '<BR>error : ' . $wpdb->last_error . '<BR>';
          echo '<div class="alert alert-danger">ПОМИЛКА ДОДАВАННЯ ЗАКАЗА! ВІДПРАВИТИ ПОВІДОМЛЕННЯ ДО <a href="/SendMail.php" target="_blank" class="alert-link">АДМІНІСТРАТОРА</a></div>';
        }
      } else {
        echo '<div class="alert alert-danger">ПОМИЛКА! ВІДСУТНІЙ КІЛЕНТ</div>';
        echo '<BR>query : ' . $wpdb->last_query . '<BR>';
        echo '<BR>error : ' . $wpdb->last_error . '<BR>';
        echo '<BR>dt : ' . $dt . '<BR>';
        echo '<BR>Customers_ID : ' . $Customers_ID . '<BR>';
      }
      $wpdb->hide_errors();

    } else {
      echo '<div class="alert alert-danger">НЕМАЕ ДОСТУПУ</div>';
    }
    echo ButtonBack(null, null); // кнопка НАЗАД
  }

  // ДОДАВАННЯ ПРОДУКТА
  elseif (isset($_POST['InsertProduct'])) {
    if ($_SESSION['GroupAdm']) {
      $wpdb->show_errors();
      $res = $wpdb->insert('OrderProduct',
        array(
          'Orders_ID' => $_POST['InsertProduct']
        , 'Product_ID' => $_POST['Product_ID']
        , 'OrderProduct_Count' => $_POST['OrderProduct_Count']
        , 'OrderProduct_Summa' => $_POST['OrderProduct_Summa']
        , 'OrderProduct_Discount' => $_POST['OrderProduct_Discount'] ),
        array( '%d', '%s', '%s', '%s', '%s' )
      );
      $_SESSION['SQLTxt'] = str_replace('"',"'",$wpdb->last_query);
      $_SESSION['MySQLiEerror'] = 'помилка:'.$wpdb->last_error;
      if ($res) {
        echo '<div class="alert alert-success">УСПІШНЕ ДОДАВАННЯ ПРОДУКТА!</div>';
//        SetLogs($UserID,'I',$LogName,str_replace('"',"'",$wpdb->last_query),'успішно');
        echo '<BR>query : '.$wpdb->last_query.'<BR>';
        header ('Location: '.GetLink().'?ViewID='.$_POST['InsertProduct']); // перенаправление
      } else {
//        SetLogs($UserID,'I',$LogName,str_replace('"',"'",$wpdb->last_query),$wpdb->last_error);
        $_SESSION['SQLTxt'] = $sqlt;
        echo $_SESSION['MySQLiEerror']."<BR>";
        if ($_SESSION['GroupAdm']) echo 'SQL = '.$_SESSION['SQLTxt']."<BR>";
        echo '<BR>query : ' . $wpdb->last_query . '<BR>';
        echo '<BR>error : ' . $wpdb->last_error . '<BR>';
        echo '<div class="alert alert-danger">ПОМИЛКА ДОДАВАННЯ ПРОДУКТА! ВІДПРАВИТИ ПОВІДОМЛЕННЯ ДО <a href="/SendMail.php" target="_blank" class="alert-link">АДМІНІСТРАТОРА</a></div>';
      }
      $wpdb->hide_errors();
    } else {
      echo '<div class="alert alert-danger">НЕМАЕ ДОСТУПУ</div>';
    }
    echo ButtonBack('ViewID',$_POST['InsertProduct']); // кнопка НАЗАД
  }
  // ФОРМА ДОДАВАННЯ ПРОДУКТА
  elseif (isset($_POST['AddingProduct'])) {
    echo '<B>ДОДАВАННЯ ПРОДУКТА</B><BR><BR>';
    $html  = '<FORM role="form" ACTION="'.GetLink().'" method="post" id="addform" enctype="multipart/form-data">';  
    $html .= '<INPUT TYPE="hidden" name="InsertProduct" value="'.$_POST['AddingProduct'].'">';
    $html .= '<table COLS="2" BORDER="0">';
    // ПРОДУКТ
    $html .= '<TR>';
    $html .= ' <TD align="RIGHT">* ПРОДУКТ : </TD>';
    $html .= ' <TD><SELECT name="Product_ID" id="Product_ID" autofocus >';
    $sqlt = "SELECT Product_ID, Product_Kod, KodNoteSummaTypeName FROM vProduct";
//~ if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
    $res = $wpdb->get_results($sqlt);
    if ( $res ) {
      foreach ($res as $data) {
        $html .= '<OPTION ';
        if ($data->Product_ID == $_SESSION['Product_ID']) $html .= ' SELECTED ';
        $html .= "VALUE='".$data->Product_ID."'>".$data->KodNoteSummaTypeName."</OPTION>";
      }
    }
    $html .= '</SELECT></TD></TR>';  
    // КІЛЬКІСТЬ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">* КІЛЬКІСТЬ : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="OrderProduct_Count" size="10" Value="1" required /></td>';
    $html .= ' </tr>';
    // СУМА
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">* СУМА : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="OrderProduct_Summa" size="10" Value="0" required /></td>';
    $html .= ' </tr>';
    // ЗНИЖКА
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">* ЗНИЖКА : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="OrderProduct_Discount" size="10" Value="0" required /></td>';
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
    $html .= ButtonBack(GetLink(),'ViewID',$_POST['AddingProduct']); // кнопка НАЗАД
    $html .= '</td></tr></table>';
    echo $html;
  }

  // ВИДАЛЕННЯ ПРОДУКТА
  elseif (isset($_GET['DelOrderProduct'])) {
    // получаем код заказа
    $Orders_ID = intval( $wpdb->get_var( "SELECT Orders_ID FROM vOrderProduct WHERE OrderProduct_ID='".$_GET['DelOrderProduct']."' LIMIT 1 " ));
    $wpdb->show_errors();
    $res = $wpdb->delete( 'OrderProduct', array( 'OrderProduct_ID' => $_GET['DelOrderProduct'] ) );
    $_SESSION['SQLTxt'] = str_replace('"',"'",$wpdb->last_query);
    $_SESSION['MySQLiEerror'] = 'помилка:'.$wpdb->last_error;
    if ($res) {
      echo '<div class="alert alert-success">УСПІШНЕ ВИДАЛЕННЯ ЗАКАЗУ!</div>';
        header ('Location: '.GetLink().'?ViewID='.$Orders_ID.''); // перенаправление
    } else {
//      SetLogs($UserID,'D',$LogName,str_replace('"',"'",$wpdb->last_query),$wpdb->last_error);
      echo '<BR>query : '.$_SESSION['SQLTxt'].'<BR>';
      echo 'MySQLiError = '.$_SESSION['MySQLiEerror']."<BR>";
      echo '<div class="alert alert-danger">ПОМИЛКА ВИДАЛЕННЯ ЗАКАЗУ! ВІДПРАВИТИ ПОВІДОМЛЕННЯ ДО <a href="/SendMail.php" target="_blank" class="alert-link">АДМІНІСТРАТОРА</a></div>';
    }
    echo ButtonBack(GetLink(),'ViewID',$Orders_ID); // кнопка НАЗАД
  }
  // ОНОВЛЕННЯ ПРОДУКТА
  elseif (isset($_POST['UpdateOrderProduct'])) {
    if ($_SESSION['GroupAdm']) {
      // получаем код заказа
      $Orders_ID = intval( $wpdb->get_var( "SELECT Orders_ID FROM vOrderProduct WHERE OrderProduct_ID='".$_POST['UpdateOrderProduct']."' LIMIT 1 " ));
      $wpdb->show_errors();
      $res = $wpdb->update( 'OrderProduct',
        array(
          'OrderProduct_Count' => $_POST['OrderProduct_Count']
        , 'OrderProduct_Summa' => $_POST['OrderProduct_Summa']
        , 'OrderProduct_Discount' => $_POST['OrderProduct_Discount'] ),
        array( 'OrderProduct_ID' => $_POST['UpdateOrderProduct'] )
      );
  //    $_SESSION['GroupAdm'] = $GroupAdm;
  //    $_SESSION['UserID'] = $UserID;
      $_SESSION['SQLTxt'] = str_replace('"',"'",$wpdb->last_query);
      $_SESSION['MySQLiEerror'] = 'помилка:'.$wpdb->last_error;
      if ($res) {
        $wpdb->hide_errors();
        echo '<div class="alert alert-success">УСПІШНЕ ОНОВЛЕННЯ КЛІЕНТА!</div>';
  //      SetLogs($UserID,'U',$LogName,str_replace('"',"'",$wpdb->last_query),'успішно');
        header ('Location: '.GetLink().'?ViewID='.$Orders_ID.''); // перенаправление
      } else {
        $wpdb->hide_errors();
  //      SetLogs($UserID,'U',$LogName,str_replace('"',"'",$wpdb->last_query),$wpdb->last_error);
        echo '<BR>query : '.$_SESSION['SQLTxt'].'<BR>';
        echo 'MySQLiError = '.$_SESSION['MySQLiEerror']."<BR>";
        echo '<div class="alert alert-danger">ПОМИЛКА ОНОВЛЕННЯ КЛІЕНТА! ВІДПРАВИТИ ПОВІДОМЛЕННЯ ДО <a href="/SendMail.php" target="_blank" class="alert-link">АДМІНІСТРАТОРА</a></div>';
      }
    } else {
      echo '<div class="alert alert-danger">НЕМАЕ ДОСТУПУ</div>';
    }
    echo ButtonBack(GetLink(),'ViewID',$Orders_ID); // кнопка НАЗАД
  }
  // РЕДАГУВАННЯ ПРОДУКТА
  elseif (isset($EditOrderProduct)) {
    echo '<B>РЕДАГУВАННЯ ДАНИХ</B><BR><BR>';
    // получаем код заказа
    $Orders_ID = intval( $wpdb->get_var( "SELECT Orders_ID FROM vOrderProduct WHERE OrderProduct_ID='".$EditOrderProduct."' LIMIT 1 " ));
    // запрос
    $sqlt = "SELECT OrderProduct_ID, Orders_ID, Product_ID, OrderProduct_DateCreate
    , OrderProduct_Count, OrderProduct_Summa, OrderProduct_Discount
    , Product_Kod, Product_Note, Product_Summa, ProductType_Name, ProductType_Order
    FROM vOrderProduct ";
    $sqlt .= " WHERE OrderProduct_ID = '".$EditOrderProduct."'";
// if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
    $res = $wpdb->get_results($sqlt);
    if ( $res ) {
      $html  = '<FORM role="form" ACTION="'.GetLink().'" id="editform" method="post">';
      $html .= "<INPUT TYPE='HIDDEN' NAME='UpdateOrderProduct' VALUE='".$EditOrderProduct."'>";
      foreach ( $res as $data ) {
        $html .= '<TABLE COLS="2" BORDER="0">';
        $html .= ' <tr>';
        $html .= '  <td ALIGN="CENTER" COLSPAN="2"><b>ЗАКАЗ</b></td>';
        $html .= ' </tr>';
        // ДАТА СТВОРЕННЯ
        $html .= '<tr><td ALIGN="RIGHT">ДАТА СТВОРЕННЯ : </td>';
        $html .= '<td ALIGN="LEFT">'.$data->OrderProduct_DateCreate.'</td></tr>';
        // КОД ПРОДУКТА
        $html .= '<tr><td ALIGN="RIGHT">КОД ПРОДУКТА : </td>';
        $html .= '<td ALIGN="LEFT">'.$data->Product_Kod.'</td></tr>';
        // ОПИС ПРОДУКТА
        $html .= '<tr><td ALIGN="RIGHT">ОПИС ПРОДУКТА : </td>';
        $html .= '<td ALIGN="LEFT">'.$data->Product_Note.'</td></tr>';
        // СУМА ПРОДУКТА
        $html .= '<tr><td ALIGN="RIGHT">СУМА ПРОДУКТА : </td>';
        $html .= '<td ALIGN="LEFT">'.$data->Product_Summa.'</td></tr>';
        // ТИП ПРОДУКТА
        $html .= '<tr><td ALIGN="RIGHT">ТИП ПРОДУКТА : </td>';
        $html .= '<td ALIGN="LEFT">'.$data->ProductType_Name.'</td></tr>';
        // КУЛЬКІСТЬ
        $html .= '<tr><td ALIGN="RIGHT">* КУЛЬКІСТЬ : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="OrderProduct_Count" Value="'.$data->OrderProduct_Count.'" /></td></tr>';
        // СУМА
        $html .= '<tr><td ALIGN="RIGHT">* СУМА : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="OrderProduct_Summa" Value="'.$data->OrderProduct_Summa.'" /></td></tr>';
        // ДІСКОНТ
        $html .= '<tr><td ALIGN="RIGHT">ДІСКОНТ : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="OrderProduct_Discount" Value="'.$data->OrderProduct_Discount.'" /></td></tr>';
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
        $html .= ButtonBack(GetLink(),'ViewID',$Orders_ID); // кнопка НАЗАД
        $html .= '</td></tr></table>';
        echo $html;
      }
    } else {
      echo '<div class="alert alert-danger">НЕМАЄ ДАНИХ! </div>';
      echo '<div class="alert alert-danger">ПОМИЛКА : '.$wpdb->last_error.'</div>';
      echo ButtonBack(GetLink(),'ViewID',$Orders_ID); // кнопка НАЗАД
    }
  }

  // ПЕРЕГЛЯД ЗАКАЗА
  elseif (isset($ViewID)) {
    echo '<B>ПЕРЕГЛЯД ЗАКАЗА</B><BR><BR>';
    // запрос
    $sqlt = "SELECT Orders_ID, Customers_ID, Orders_DateOpen, Orders_DateOrders, Orders_TimeOrders
    , Orders_Summa, Orders_Discount, Orders_Delivery, Orders_PrePayment, Orders_Adress, Orders_Note
    , FIO, Customers_Email, Customers_Phone1, Customers_Phone2, Customers_Phone3
    , Customers_From, Customers_To, Customers_Note, Customers_BirthDay, ProductKod
    , ProductCount, ProductSumma, DiscountSumma
    FROM vOrders ";
    $sqlt .= " WHERE Orders_ID = '".$ViewID."'";
// if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
    $res = $wpdb->get_results($sqlt);
    if ( $res ) {
      foreach ( $res as $data ) {
        $html  = '<TABLE COLS="2" BORDER="0"><tr>';
        // ----- КЛІЕНТ
        $html .= '<td VALIGN="TOP">';
        $html .= '<TABLE COLS="2" BORDER="0">';
        $html .= '<tr><td ALIGN="CENTER" COLSPAN="2"><b>КЛІЕНТ</b></td></tr>';
        // ПІБ
        $html .= '<tr><td ALIGN="RIGHT">ПІБ : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->FIO.'</B></td></tr>';
        // ДАТА НАРОДЖЕННЯ
        $html .= '<tr><td ALIGN="RIGHT">ДАТА НАРОДЖЕННЯ : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.date('d.m.Y',strtotime($data->Customers_BirthDay)).'</B></td></tr>';
        // Email
        $html .= '<tr><td ALIGN="RIGHT">Email : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_Email.'</B></td></tr>';
        // ТЕЛЕФОН 1 
        $html .= '<tr><td ALIGN="RIGHT">ТЕЛЕФОН 1 : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_Phone1.'</B></td></tr>';
        // ТЕЛЕФОН 2
        $html .= '<tr><td ALIGN="RIGHT">ТЕЛЕФОН 2 : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_Phone2.'</B></td></tr>';
        // ТЕЛЕФОН 3
        $html .= '<tr><td ALIGN="RIGHT">ТЕЛЕФОН 3 : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_Phone3.'</B></td></tr>';
        // ХТО ПОРАДИВ АБО ЗВІДКИ ДІЗНАЛИСЯ
        $html .= '<tr><td ALIGN="RIGHT">ХТО ПОРАДИВ АБО ЗВІДКИ ДІЗНАЛИСЯ : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_From.'</B></td></tr>';
        // КОМУ МЕНЕ РЕКОМЕНДУВАВ
        $html .= '<tr><td ALIGN="RIGHT">КОМУ МЕНЕ РЕКОМЕНДУВАВ : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_To.'</B></td></tr>';
        // ПРІМІТКА
        $html .= '<tr><td ALIGN="RIGHT">ПРІМІТКА : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Customers_Note.'</B></td></tr>';
        //
        $html .= '</TABLE></td>';

        // ----- ЗАКАЗ
        $html .= '<td VALIGN="TOP">';
        $html .= '<TABLE COLS="2" BORDER="0">';
        $html .= '<tr><td ALIGN="CENTER" COLSPAN="2"><b>ЗАКАЗ</b></td></tr>';
        // ПРОДУКТ
        $html .= '<tr><td ALIGN="RIGHT">ПРОДУКТ : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->ProductKod.'</B></td></tr>';
        // ДАТА НАДХОДЖЕННЯ
        $html .= '<tr><td ALIGN="RIGHT">ДАТА НАДХОДЖЕННЯ : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.date('d.m.Y',strtotime($data->Orders_DateOpen)).'</B></td></tr>';
        // ДАТА ЗАКАЗА
        $html .= '<tr><td ALIGN="RIGHT">ДАТА ЗАКАЗА : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.date('d.m.Y',strtotime($data->Orders_DateOrders)).'</B></td></tr>';
        // ВРЕМЯ
        $html .= '<tr><td ALIGN="RIGHT">ВРЕМЯ ЗАКАЗА : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Orders_TimeOrders.'</B></td></tr>';
        // КІЛЬКІСТЬ
        $html .= '<tr><td ALIGN="RIGHT">КІЛЬКІСТЬ : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->ProductCount.'</B></td></tr>';
        // СУМА
        $html .= '<tr><td ALIGN="RIGHT">СУМА : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->ProductSumma.'</B></td></tr>';
        // ДІСКОНТ
        $html .= '<tr><td ALIGN="RIGHT">ДІСКОНТ : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->DiscountSumma.'</B></td></tr>';
        // ПЕРЕДОПЛАТА
        $html .= '<tr><td ALIGN="RIGHT">ПЕРЕДОПЛАТА : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Orders_PrePayment.'</B></td></tr>';
        // ДОСТАВКА
        $html .= '<tr><td ALIGN="RIGHT">ДОСТАВКА : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Orders_Delivery.'</B></td></tr>';
        // АДРЕСА ДОСТАВКИ
        $html .= '<tr><td ALIGN="RIGHT">АДРЕСА ДОСТАВКИ : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Orders_Adress.'</B></td></tr>';
        // ПРИМІТКИ
        $html .= '<tr><td ALIGN="RIGHT">ПРИМІТКИ : </td>';
        $html .= '<td ALIGN="LEFT"><B>'.$data->Orders_Note.'</B></td></tr>';
        $html .= '</TABLE>';
        //---
        $html .= '</td></tr>';
        $html .= '</TABLE>';
        // ----- ПРОДУКТИ
        $html .= '<TABLE class="table table-bordered table-hover table-striped table-condensed" COLS="7" BORDER="0">';
        $html .= ' <tr><td ALIGN="CENTER" colspan="8">';
        $html .= '<FORM ACTION="'.GetLink().'" method="post">';
        $html .= ' <INPUT TYPE="HIDDEN" NAME="AddingProduct" VALUE="'.$ViewID.'">';
        $html .= ' <INPUT TYPE="HIDDEN" TYPE="submit">';
        $html .= '<BUTTON type="submit" class="btn dropdown-toggle btn-xs btn-warning" TITLE="додати продукт">';
        $html .= '<span class="glyphicon glyphicon-plus"></span> ДОДАТИ ПРОДУКТ</BUTTON>';
        $html .= '</FORM></td></tr>';
        // запрос
        $sqlt2 = "SELECT OrderProduct_ID, Orders_ID, Product_ID
        , OrderProduct_Count, OrderProduct_Summa, OrderProduct_Discount
        , Product_Kod, Product_Note, Product_Summa, ProductType_Name, ProductType_Order
        FROM vOrderProduct ";
        $sqlt2 .= " WHERE Orders_ID = '".$ViewID."'"; 
// if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt2."<BR>";
        $res = $wpdb->get_results($sqlt2);
        if ( $res ) {
          $html .= '<TR>';
          $html .= ' <TD width="20" ALIGN="CENTER" VALIGN="MIDDLE"><B>№</B></TD>';
          $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>КОД</B></TD>';
          $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>НАЙМЕНУВАННЯ</B></TD>';
          $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>КАТЕГОРІЯ</B></TD>';
          $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>КІЛЬКІСТЬ</B></TD>';
          $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>СУМА</B></TD>';
          $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>ЗНИЖКА</B></TD>';
          $html .= ' <TD width="20" ALIGN="CENTER" VALIGN="MIDDLE" title="опції"><span class="glyphicon glyphicon-th"></span></TD>';
          $html .= '</TR>';
          $n=0;
          foreach ( $res as $data2 ) {
            $n++; // подсчёт кол-ва участников
            $html .= '<TR>';
            $html .= ' <TD align="center">'.$n.'</TD>';
            $html .= ' <TD align="center" ><a href="'.GetLink().'?EditOrderProduct='.$data2->OrderProduct_ID.'" >'.$data2->Product_Kod.'</a></TD>';
            $html .= ' <TD align="left" >'.$data2->Product_Note.'</TD>';
            $html .= ' <TD align="left" >'.$data2->ProductType_Name.'</TD>';
            $html .= ' <TD align="center" >'.$data2->OrderProduct_Count.'</TD>';
            $html .= ' <TD align="center" >'.$data2->OrderProduct_Summa.'</TD>';
            $html .= ' <TD align="center" >'.$data2->OrderProduct_Discount.'</TD>';
            // меню
            $html .= ' <TD ALIGN="center">';
            $html .= '<div class="btn-group">';
            $html .= '<button type="button" class="btn dropdown-toggle btn-xs btn-warning" data-toggle="dropdown">'; // btn-default
            $html .= '<span class="caret"></span></button>';
            $html .= '<ul class="dropdown-menu" role="menu">';
            //-ПЕРЕГЛЯД
            // $html .= '<li><FORM role="form" ACTION="'.GetLink().'" method="post">';
            // $html .= ' <INPUT TYPE="HIDDEN" NAME="ViewID" VALUE="'.$data2->OrderProduct_ID.'">';
            // $html .= '<BUTTON type="submit" class="btn btn-group btn-xs btn-warning" TITLE="перегляд">';
            // $html .= '<span class="glyphicon glyphicon-search"></span> ПЕРЕГЛЯД</BUTTON>';
            // $html .= '</FORM></li>';
            //-РЕДАГУВАННЯ
            $html .= '<li><FORM role="form" ACTION="'.GetLink().'" method="post">';
            $html .= ' <INPUT TYPE="HIDDEN" NAME="EditOrderProduct" VALUE="'.$data2->OrderProduct_ID.'"/>';
            $html .= ' <INPUT TYPE="HIDDEN" NAME="Orders_ID" VALUE="'.$data2->Orders_ID.'"/>';
            $html .= '<BUTTON type="submit" class="btn btn-group btn-xs btn-warning" TITLE="редагування"/>';
            $html .= '<span class="glyphicon glyphicon-pencil"></span> РЕДАГУВАННЯ</BUTTON>';
            $html .= '</FORM></li>';
            // ВИДАЛЕННЯ
            if ($_SESSION['GroupAdm']) {
              $html .= '<li>';
              $html .= '<BUTTON type="button" class="btn btn-group btn-xs btn-warning" onclick="DeleteOrderProduct('.$data2->OrderProduct_ID.')" title="видалення">';
              $html .= '<span class="glyphicon glyphicon-remove"></span> ВИДАЛЕННЯ</BUTTON>';
              $html .= '</li>';
            }
            //
            $html .= '</ul>';
            $html .= '</div>';
            $html .= ' </TD>';
            // 
            $html .= '</TR>';
          }
        }
        $html .= '</TABLE>';
      }
      // КНОПКИ
      $html .= '<TABLE BORDER="0"><tr><td>';
      $html .= ButtonBack(GetLink(),null,null); // кнопка НАЗАД
      $html .= '</td></tr></table>';
      echo $html;
    } else {
      echo '<div class="alert alert-danger">ДАНІ ВІДСУТНІ</div>';
      if ($_SESSION['GroupAdm']) {
        if ($wpdb->last_error) {
          echo '<div class="alert alert-danger">ПОМИЛКА : '.$wpdb->last_error.'</div>';
        }
      }
      echo ButtonBack(GetLink(),null,null); // кнопка НАЗАД
    }
  }

  // ОНОВЛЕННЯ ЗАКАЗА та КЛИЕНТА у БД
  elseif (isset($_POST['UpdateID'])) {
    $wpdb->show_errors();
    $res = $wpdb->update( 'Orders',
      array(
        'Orders_DateOpen' => $_POST['Orders_DateOpen']
      , 'Orders_DateOrders' => $_POST['Orders_DateOrders']
      , 'Orders_TimeOrders' => $_POST['Orders_TimeOrders']
      , 'Orders_Summa' => $_POST['Orders_Summa']
      , 'Orders_Discount' => $_POST['Orders_Discount']
      , 'Orders_Delivery' => $_POST['Orders_Delivery']
      , 'Orders_PrePayment' => $_POST['Orders_PrePayment']
      , 'Orders_Adress' => $_POST['Orders_Adress']
      , 'Orders_Note' => $_POST['Orders_Note'] ),
      array( 'Orders_ID' => $_POST['UpdateID'] )
    );
//    $_SESSION['GroupAdm'] = $GroupAdm;
//    $_SESSION['UserID'] = $UserID;
    $_SESSION['SQLTxt'] = str_replace('"',"'",$wpdb->last_query);
    $_SESSION['MySQLiEerror'] = 'помилка:'.$wpdb->last_error;
    if ($res) {
      echo '<div class="alert alert-success">УСПІШНЕ ОНОВЛЕННЯ ЗАКАЗА!</div>';
//      SetLogs($UserID,'U',$LogName,str_replace('"',"'",$wpdb->last_query),'успішно');
//      header ('Location: '.GetLink().'?pages='.$pages.''); // перенаправление
//      header ('Location: '.GetLink()); // перенаправление
    } else {
//      SetLogs($UserID,'U',$LogName,str_replace('"',"'",$wpdb->last_query),$wpdb->last_error);
      echo '<BR>query : '.$_SESSION['SQLTxt'].'<BR>';
      echo 'MySQLiError = '.$_SESSION['MySQLiEerror']."<BR>";
      echo '<div class="alert alert-danger">ПОМИЛКА ОНОВЛЕННЯ ЗАКАЗА! ВІДПРАВИТИ ПОВІДОМЛЕННЯ ДО <a href="/SendMail.php" target="_blank" class="alert-link">АДМІНІСТРАТОРА</a></div>';
    }
    // ОНОВЛЕННЯ КЛИЕНТА
    $res = $wpdb->update( 'Customers',
      array(
        'Customers_SurName' => $_POST['Customers_SurName']
      , 'Customers_Name' => $_POST['Customers_Name']
      , 'Customers_Partronic' => $_POST['Customers_Partronic']
      , 'Customers_Email' => $_POST['Customers_Email']
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
      echo '<div class="alert alert-success">УСПІШНЕ ОНОВЛЕННЯ КЛІЕНТА!</div>';
//      SetLogs($UserID,'U',$LogName,str_replace('"',"'",$wpdb->last_query),'успішно');
//      header ('Location: '.GetLink().'?pages='.$pages.''); // перенаправление
//      header ('Location: '.GetLink()); // перенаправление
    } else {
//      SetLogs($UserID,'U',$LogName,str_replace('"',"'",$wpdb->last_query),$wpdb->last_error);
      echo '<BR>query : '.$_SESSION['SQLTxt'].'<BR>';
      echo 'MySQLiError = '.$_SESSION['MySQLiEerror']."<BR>";
      echo '<div class="alert alert-danger">ПОМИЛКА ОНОВЛЕННЯ КЛІЕНТА! ВІДПРАВИТИ ПОВІДОМЛЕННЯ ДО <a href="/SendMail.php" target="_blank" class="alert-link">АДМІНІСТРАТОРА</a></div>';
    }
    $wpdb->hide_errors();
    header ('Location: '.GetLink()); // перенаправление
    echo ButtonBack(GetLink(),null,null); // кнопка НАЗАД
  }
  // РЕДАГУВАННЯ
  elseif (isset($_POST['EditID'])) {
    echo '<B>РЕДАГУВАННЯ ДАНИХ</B><BR><BR>';
    if ($_POST['Edit_Name']) {
      echo 'ПОТОЧНЕ НАЙМЕНУВАННЯ : <B>' . $_POST['Edit_Name'] . '</B><BR>';
    }
    // запрос
    $sqlt = "SELECT Orders_ID, Customers_ID, Orders_DateOpen, Orders_DateOrders, Orders_TimeOrders
    , Orders_Summa, Orders_Discount, Orders_Delivery, Orders_PrePayment, Orders_Adress, Orders_Note
    , FIO, Customers_SurName, Customers_Name, Customers_Partronic, ProductKod
    , Customers_Email, Customers_Phone1, Customers_Phone2, Customers_Phone3
    , Customers_From, Customers_To, Customers_Note, Customers_BirthDay, Orders_DateCreate
    , ProductCount, ProductSumma, DiscountSumma
    FROM vOrders ";
    $sqlt .= " WHERE Orders_ID = '".$_POST['EditID']."'";
// if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
    $res = $wpdb->get_results($sqlt);
    if ( $res ) {
      $html  = '<FORM role="form" ACTION="'.GetLink().'" id="editform" method="post">';
      $html .= "<INPUT TYPE='HIDDEN' NAME='UpdateID' VALUE='".$_POST['EditID']."'>";
      $html .= "<INPUT TYPE='HIDDEN' NAME='Customers_ID' VALUE='".$_POST['Customers_ID']."'>";
      foreach ( $res as $data ) {
        $html .= '<TABLE COLS="2" BORDER="0">';
        $html .= ' <tr>';
        $html .= '  <td ALIGN="CENTER" COLSPAN="2"><b>ЗАКАЗ</b></td>';
        $html .= ' </tr>';
        // ДАТА СТВОРЕННЯ
        $html .= '<tr><td ALIGN="RIGHT">ДАТА СТВОРЕННЯ : </td>';
        $html .= '<td ALIGN="LEFT">'.$data->Orders_DateCreate.'</td></tr>';
        // ДАТА НАДХОДЖЕННЯ
        $html .= '<tr><td ALIGN="RIGHT">* ДАТА НАДХОДЖЕННЯ : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="date" NAME="Orders_DateOpen" Value="'.$data->Orders_DateOpen.'" required /></td></tr>';
        // ДАТА ЗАКАЗА
        $html .= '<tr><td ALIGN="RIGHT">* ДАТА ЗАКАЗА : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="date" NAME="Orders_DateOrders" Value="'.$data->Orders_DateOrders.'" required /></td></tr>';
        // ВРЕМЯ
        $html .= '<tr><td ALIGN="RIGHT">* ВРЕМЯ ЗАКАЗА : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="time" NAME="Orders_TimeOrders" Value="'.$data->Orders_TimeOrders.'" required /></td></tr>';
        // ДІСКОНТ
        $html .= '<tr><td ALIGN="RIGHT">ДІСКОНТ : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Orders_Discount" Value="'.$data->Orders_Discount.'" /></td></tr>';
        // ПЕРЕДОПЛАТА
        $html .= '<tr><td ALIGN="RIGHT">ПЕРЕДОПЛАТА : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Orders_PrePayment" Value="'.$data->Orders_PrePayment.'" /></td></tr>';
        // ДОСТАВКА
        $html .= '<tr><td ALIGN="RIGHT">ДОСТАВКА : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Orders_Delivery" Value="'.$data->Orders_Delivery.'" /></td></tr>';
        // АДРЕСА ДОСТАВКИ
        $html .= '<tr><td ALIGN="RIGHT">АДРЕСА ДОСТАВКИ : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Orders_Adress" Value="'.$data->Orders_Adress.'" placeholder="м.Дніпро, вул... б... кв..." size="60" /></td></tr>';
        // ПРИМІТКИ
        $html .= '<tr><td ALIGN="RIGHT">ПРИМІТКИ : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Orders_Note" Value="'.$data->Orders_Note.'" size="70" /></td></tr>';
    
        // ----- КЛІЕНТ
        $html .= '<tr><td ALIGN="CENTER" COLSPAN="2"><b>КЛІЕНТ</b></td></tr>';
        // ПРІЗВИЩЕ
        $html .= '<tr><td ALIGN="RIGHT">ПРІЗВИЩЕ : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_SurName" Value="'.$data->Customers_SurName.'" size="20" /></td></tr>';
        // І'МЯ
        $html .= "<tr><td ALIGN='RIGHT'>* І'МЯ : </td>";
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_Name" Value="'.$data->Customers_Name.'" size="20" required /></td></tr>';
        // ПО БАТЬКОВІ
        $html .= '<tr><td ALIGN="RIGHT">ПО БАТЬКОВІ : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_Partronic" Value="'.$data->Customers_Partronic.'" size="20" /></td></tr>';
        // ДАТА НАРОДЖЕННЯ
        $html .= '<tr><td ALIGN="RIGHT">ДАТА НАРОДЖЕННЯ : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="date" NAME="Customers_BirthDay" Value="'.$data->Customers_BirthDay.'" /></td></tr>';
        // Email
        $html .= '<tr><td ALIGN="RIGHT">Email : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="email" NAME="Customers_Email" Value="'.$data->Customers_Email.'" size="30" /></td></tr>';
        // ТЕЛЕФОН 1 
        $html .= '<tr><td ALIGN="RIGHT">* ТЕЛЕФОН 1 : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="tel" NAME="Customers_Phone1" Value="'.$data->Customers_Phone1.'" size="20" required /></td></tr>';
        // ТЕЛЕФОН 2
        $html .= '<tr><td ALIGN="RIGHT">ТЕЛЕФОН 2 : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="tel" NAME="Customers_Phone2" Value="'.$data->Customers_Phone2.'" size="20" /></td></tr>';
        // ТЕЛЕФОН 3
        $html .= '<tr><td ALIGN="RIGHT">ТЕЛЕФОН 3 : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="tel" NAME="Customers_Phone3" Value="'.$data->Customers_Phone3.'" size="20" /></td></tr>';
        // ХТО ПОРАДИВ АБО ЗВІДКИ ДІЗНАЛИСЯ
        $html .= '<tr><td ALIGN="RIGHT">ХТО ПОРАДИВ АБО ЗВІДКИ ДІЗНАЛИСЯ : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_From" Value="'.$data->Customers_From.'" size="50" /></td></tr>';
        // КОМУ МЕНЕ РЕКОМЕНДУВАВ
        $html .= '<tr><td ALIGN="RIGHT">КОМУ МЕНЕ РЕКОМЕНДУВАВ : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_To" Value="'.$data->Customers_To.'" size="50" /></td></tr>';
        // ПРІМІТКА
        $html .= '<tr><td ALIGN="RIGHT">ПРІМІТКА : </td>';
        $html .= '<td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_Note" Value="'.$data->Customers_Note.'" size="70" /></td></tr>';
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
  // ФОРМА ДОДАВАННЯ ЗАКАЗА
  elseif (isset($_POST['Adding'])) {
    echo '<B>ДОДАВАННЯ ЗАКАЗА</B><BR><BR>';
    $html  = '<FORM role="form" ACTION="'.GetLink().'" method="post" id="addform" enctype="multipart/form-data">';  
    $html .= '<INPUT TYPE="hidden" name="InsertID" value="'.$_SESSION['UserID'].'">';
    $html .= '<table COLS="2" BORDER="0">';
    //~ $html .= "<COLGROUP></COLGROUP>";
    //~ $html .= "<COLGROUP WIDTH='100'></COLGROUP>";
    // ТОВАР
    $html .= '<TR>';
    $html .= ' <TD align="RIGHT">* ТОВАР : </TD>';
    $html .= ' <TD><SELECT name="Product_ID" id="Product_ID" autofocus >';
    $sqlt = "SELECT Product_ID, Product_Kod, KodNoteSummaTypeName FROM vProduct";
//if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
    $res = $wpdb->get_results($sqlt);
    if ( $res ) {
      foreach ($res as $data) {
        $html .= '<OPTION ';
        if ($data->Product_ID == $_SESSION['Product_ID']) $html .= ' SELECTED ';
        $html .= "VALUE='".$data->Product_ID."'>".$data->KodNoteSummaTypeName."</OPTION>";
      }
    }
    $html .= '</SELECT></TD></TR>';
    // КІЛЬКІСТЬ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">КІЛЬКІСТЬ : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="OrderProduct_Count" Value="1" size="10" /></td>';
    $html .= ' </tr>';
    // СУМА
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">СУМА : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="OrderProduct_Summa" Value="0" size="10" /></td>';
    $html .= ' </tr>';
    // ДІСКОНТ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">ДІСКОНТ ТОВАРА: </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="OrderProduct_Discount" Value="0" /></td>';
    $html .= ' </tr>';
    // ДАТА НАДХОДЖЕННЯ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">* ДАТА НАДХОДЖЕННЯ : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="date" NAME="Orders_DateOpen" Value="'.date('Y-m-d').'" required /></td>';
    $html .= ' </tr>';
    // ДАТА ЗАКАЗА
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">* ДАТА ЗАКАЗА : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="date" NAME="Orders_DateOrders" Value="'.date('Y-m-d').'"  required /></td>';
    $html .= ' </tr>';
    // ВРЕМЯ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">* ВРЕМЯ ЗАКАЗА : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="time" NAME="Orders_TimeOrders" Value="'.date('H:i').'"  required /></td>';
    $html .= ' </tr>';
    // ДІСКОНТ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">ДІСКОНТ ЗАКАЗА: </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Orders_Discount" Value="0" /></td>';
    $html .= ' </tr>';
    // ПЕРЕДОПЛАТА
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">ПЕРЕДОПЛАТА : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Orders_PrePayment" Value="0" /></td>';
    $html .= ' </tr>';
    // ДОСТАВКА
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">ДОСТАВКА : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Orders_Delivery" Value="0" /></td>';
    $html .= ' </tr>';
    // АДРЕСА ДОСТАВКИ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">АДРЕСА ДОСТАВКИ : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Orders_Adress" placeholder="Дніпро, вул.Бородинська б.41 кв.10" size="60" /></td>';
    $html .= ' </tr>';
    // ПРИМІТКИ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">ПРИМІТКИ : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Orders_Note" size="70" /></td>';
    $html .= ' </tr>';

    // ----- КЛІЕНТ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="CENTER" COLSPAN="2"><b>КЛІЕНТ</b></td>';
    $html .= ' </tr>';
    // ПРІЗВИЩЕ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">ПРІЗВИЩЕ : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_SurName" size="50" /></td>';
    $html .= ' </tr>';
    // І'МЯ
    $html .= ' <tr>';
    $html .= "  <td ALIGN='RIGHT'>* І'МЯ : </td>";
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_Name" size="50" required /></td>';
    $html .= ' </tr>';
    // ПО БАТЬКОВІ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">ПО БАТЬКОВІ : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_Partronic" size="50" /></td>';
    $html .= ' </tr>';
    // ДАТА НАРОДЖЕННЯ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">ДАТА НАРОДЖЕННЯ : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="date" NAME="Customers_BirthDay" size="50" /></td>';
    $html .= ' </tr>';
    // Email
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">Email : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="email" NAME="Customers_Email" size="50" /></td>';
    $html .= ' </tr>';
    // ТЕЛЕФОН 1 
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">* ТЕЛЕФОН 1 : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="tel" NAME="Customers_Phone1" size="50" required /></td>';
    $html .= ' </tr>';
    // ТЕЛЕФОН 2
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">ТЕЛЕФОН 2 : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="tel" NAME="Customers_Phone2" size="50" /></td>';
    $html .= ' </tr>';
    // ТЕЛЕФОН 3
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">ТЕЛЕФОН 3 : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="tel" NAME="Customers_Phone3" size="50" /></td>';
    $html .= ' </tr>';
    // ХТО ПОРАДИВ АБО ЗВІДКИ ДІЗНАЛИСЯ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">ХТО ПОРАДИВ АБО ЗВІДКИ ДІЗНАЛИСЯ : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_From" size="50" /></td>';
    $html .= ' </tr>';
    // КОМУ МЕНЕ РЕКОМЕНДУВАВ
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">КОМУ МЕНЕ РЕКОМЕНДУВАВ : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_To" size="50" /></td>';
    $html .= ' </tr>';
    // ПРІМІТКА
    $html .= ' <tr>';
    $html .= '  <td ALIGN="RIGHT">ПРІМІТКА : </td>';
    $html .= '  <td ALIGN="LEFT"><INPUT TYPE="text" NAME="Customers_Note" size="70" /></td>';
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
    echo '<B>ЗАКАЗЫ</B><BR>';
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
    $cnt = intval( $wpdb->get_var( "SELECT COUNT(Orders_ID) as cnt FROM vOrders" ));
    if ($_SESSION['LIMIT']) 
      $number_records = $_SESSION['LIMIT'];
    else
      $number_records = 10;
    
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
    $sqlt = "SELECT Orders_ID, Customers_ID, Orders_DateCreate, Orders_DateOpen, Orders_DateOrders, Orders_TimeOrders
    , Orders_Summa, Orders_Discount, Orders_Delivery, Orders_PrePayment, Orders_Adress, Orders_Note
    , FIO, OrderCustomersEmail, Customers_Email, Customers_Phone, ProductKod
    , Customers_Phone1, Customers_Phone2, Customers_Phone3
    , Customers_From, Customers_To, Customers_Note, Customers_BirthDay
    , ProductCount, ProductSumma, DiscountSumma
    FROM vOrders "; 

    if ($_SESSION['Customers_Phone1'] or $_SESSION['Customers_BirthDay'] or $_SESSION['Orders_DateOrders'] or $_SESSION['FIO']) {
      $sqlf = " WHERE Orders_ID>0 ";
      if ($_SESSION['Orders_DateOrders'])
        $sqlf .= " AND Orders_DateOrders = '".$_SESSION['Orders_DateOrders']."'";
      if ($_SESSION['Customers_Phone1'])
        $sqlf .= " AND Customers_Phone1 LIKE '%".$_SESSION['Customers_Phone1']."%'";
      if ($_SESSION['FIO'])
        $sqlf .= " AND FIO LIKE '%".$_SESSION['FIO']."%' ";
      $sqls = "SELECT SUM(cnt) AS cnt, SUM(summa) AS summa FROM vOrders";
//      $sqls .= $sqlf;
    } else {
      if ($_SESSION['BirthMonth']>0)
        $sqlf .= " WHERE MONTH(Customers_BirthDay)='".$_SESSION['BirthMonth']."'";
    }
    $sqlf .= " ORDER BY Orders_DateOpen DESC ";
    $sqlt .= $sqlf;
//    $sqlt .= $Limitsql;
//if ($_SESSION['GroupAdm']) echo "SQL = ".$sqlt."<BR>";
    if ($res = $wpdb->get_results($sqlt)) {
      $html  = '<TABLE class="table table-bordered table-hover table-striped" COLS="9" >';
      // ПОШУК ПО ПОЗИВНОМУ АБО ПРІЗВИЩУ
      $html .= '<TR><FORM ACTION="'.GetLink().'" method="post">';
      $html .= '<INPUT TYPE="HIDDEN" NAME="FindUser" Value="'.$_SESSION['UserID'].'" >';
      $html .= '<TD></TD>';
      $html .= '<TD align="center"><INPUT TYPE="text" NAME="FIO" value="'.$_SESSION['FIO'].'" maxlength="20" placeholder="пошук по ПІБ" /></TD>';
      $html .= '<TD align="center"><INPUT TYPE="tel" NAME="Customers_Phone1" value="'.$_SESSION['Customers_Phone1'].'" maxlength="20" placeholder="пошук по телефону" /></TD>';
      //~ $html .= '<TD align="center"><INPUT TYPE="date" NAME="Customers_BirthDay" value="'.$_SESSION['Customers_BirthDay'].'" maxlength="20" placeholder="пошук по дате народження" /></TD>';
      $html .= '<TD></TD>';
      $html .= '<TD></TD>';
      $html .= '<TD align="center"><INPUT TYPE="date" NAME="Orders_DateOrders" value="'.$_SESSION['Orders_DateOrders'].'" maxlength="20" placeholder="пошук по дате заказа" /></TD>';
      $html .= '<TD align="left" COLSPAN="4"><BUTTON type="submit" class="btn btn-group btn-xs btn-warning" title="пошук">';
      $html .= '<span class="glyphicon glyphicon-search"></span> ПОШУК </BUTTON></FORM></td>'; 
      $html .= '</TR>';
      //--------------
      $html .= '<TR>';
      $html .= ' <TD width="20" ALIGN="CENTER" VALIGN="MIDDLE"><B>№</B></TD>';
      $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>КЛІЕНТ</B></TD>';
      $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>ТЕЛЕФОН</B></TD>';
      $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>ТОВАР</B></TD>';
      // $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>EMAIL</B></TD>';
      $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>ДАТА ЗАКАЗА</B></TD>';
      $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>ВРЕМЯ ЗАКАЗА</B></TD>';
      $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>КІЛЬКІСТЬ</B></TD>';
      $html .= ' <TD ALIGN="CENTER" VALIGN="MIDDLE"><B>СУМА</B></TD>';
      $html .= ' <TD width="20" ALIGN="CENTER" VALIGN="MIDDLE" title="опції"><span class="glyphicon glyphicon-th"></span></TD>';
      $html .= '</TR>';
      //~ $n=0;
      foreach ( $res as $data ) {
        $n++; // подсчёт кол-ва участников
        $html .= '<TR>';
        $html .= ' <TD align="center">'.$n.'</TD>';
        $html .= ' <TD align="left" TITLE="кто посоветовал : '.$data->Customers_From.'" >'.$data->FIO.'</TD>';
        $html .= ' <TD align="center" TITLE="телефон : '.$data->Customers_Phone2.'" >'.$data->Customers_Phone1.'</TD>';
        $html .= ' <TD align="center" TITLE="дата создания : '.date('d.m.Y',strtotime($data->Orders_DateCreate)).'" >'.$data->ProductKod.'</TD>';
        // $html .= ' <TD align="center" TITLE="сума : '.$data->ProductSumma.'" >'.$data->Customers_Email.'</TD>';
        if ($data->Orders_DateOrders) 
          $DateOrder=date('d.m.Y',strtotime($data->Orders_DateOrders));
        else
          $DateOrder='';
        $html .= ' <TD align="center" TITLE="доставка : '.$data->Orders_Delivery.'" ><a href="'.GetLink().'?ViewID='.$data->Orders_ID.'" >'.$DateOrder.'</a></TD>';
        $html .= ' <TD align="center" TITLE="предоплата : '.$data->Orders_PrePayment.'" >'.$data->Orders_TimeOrders.'</TD>';
        $html .= ' <TD align="center" TITLE="адреса : '.$data->Orders_Adress.'" >'.$data->ProductCount.'</TD>';
        $html .= ' <TD align="RIGHT" TITLE="дісконт : '.$data->Orders_Discount.'" >'.$data->ProductSumma.'</TD>';
        // меню
        $html .= ' <TD ALIGN="center">';
        $html .= '<div class="btn-group">';
        $html .= '<button type="button" class="btn dropdown-toggle btn-xs btn-warning" data-toggle="dropdown">'; // btn-default 
        $html .= '<span class="caret"></span></button>';
        $html .= '<ul class="dropdown-menu" role="menu">';
        //-ПЕРЕГЛЯД
        $html .= '<li><FORM role="form" ACTION="'.GetLink().'" method="post">';
        $html .= ' <INPUT TYPE="HIDDEN" NAME="ViewID" VALUE="'.$data->Orders_ID.'">';
        $html .= '<BUTTON type="submit" class="btn btn-group btn-xs btn-warning" TITLE="перегляд">';
        $html .= '<span class="glyphicon glyphicon-search"></span> ПЕРЕГЛЯД</BUTTON>';
        $html .= '</FORM></li>';
        //-РЕДАГУВАННЯ
        $html .= '<li><FORM role="form" ACTION="'.GetLink().'" method="post">';
        $html .= ' <INPUT TYPE="HIDDEN" NAME="EditID" VALUE="'.$data->Orders_ID.'">';
        $html .= ' <INPUT TYPE="HIDDEN" NAME="Customers_ID" VALUE="'.$data->Customers_ID.'">';
        $html .= ' <INPUT TYPE="HIDDEN" NAME="Edit_Name" VALUE="'.$data->Orders_Note.'">';
        $html .= '<BUTTON type="submit" class="btn btn-group btn-xs btn-warning" TITLE="редагування">';
        $html .= '<span class="glyphicon glyphicon-pencil"></span> РЕДАГУВАННЯ</BUTTON>';
        $html .= '</FORM></li>';
        // удаление
        $html .= '<li>';
        $html .= '<BUTTON type="button" class="btn btn-group btn-xs btn-warning" onclick="delete_record('.$data->Orders_ID.')" title="видалення">';
        $html .= '<span class="glyphicon glyphicon-remove"></span> ВИДАЛЕННЯ</BUTTON>';
        $html .= '</li>';
        //
        $html .= '</ul>';
        $html .= '</div>';
        $html .= ' </TD>';
        // 
        $html .= '</TR>';
      }
      // общая сумма при фильтре
      if ($sqls) {
//~ if ($_SESSION['GroupAdm']) echo "SQL = ".$sqls."<BR>";
        if ($res = $wpdb->get_results($sqls)) {
          foreach ( $res as $data ) {
            $html .= '<TR>';
            $html .= ' <TD align="RIGHT" colspan="6"><b>ВСЬОГО : </b>b></TD>';
            $html .= ' <TD align="center" ><b>'.$data->cnt.'</b></TD>';
            $html .= ' <TD align="center" ><b>'.$data->summa.'</b></TD>';
            $html .= ' <TD></TD>';
            $html .= '</TR>';
          }
        }
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
} else {
  echo '<div class="alert alert-danger">НЕМАЄ ДОСТУПУ! <a href="/wp-login.php" class="alert-link">УВІЙТИ</a></div>';
}
////
?>
</div></div></center>
<?php
include ('bottom.php'); // подключаем подвал
?>

