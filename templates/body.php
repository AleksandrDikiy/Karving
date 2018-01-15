<?php
$user = wp_get_current_user();
$_SESSION['UserID']=$user->id;
//wp_enqueue_style('dav-table', plugins_url('/css/dav-table.css', URE_FSTU_FULL_PATH), array(), false, 'screen');
// группа пользователей "Администраторы"
$_SESSION['GroupAdm'] = in_array('administrator', $user->roles) ? True : False;
$_SESSION['GroupBoss'] = in_array('GroupBoss', $user->roles) ? True : False;
//echo "GroupAdm = ".$_SESSION['GroupAdm']."<BR>";
//echo "UserID = ".$_SESSION['UserID']."<BR>";
?>

<!DOCTYPE html>
<html lang="ua">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="КАРВИНГ - учет заказов и клиентов">
    <meta name="author" content="Олександр Дикий">
    <link rel="shortcut icon" href="favicon.ico">
    <title>KARVING-КАРВИНГ</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <!--link href="css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="js/fun.js" ></script>
    <script src="js/jquery-1.11.js"></script>
    <script src="js/bootstrap.min.js"></script-->
  </head>

  <body>
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="/index.php">КАРВИНГ</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
<?php
  if ($_SESSION['GroupBoss'] or $_SESSION['GroupAdm']) {
      echo   '<li><a href="/Orders">ЗАКАЗЫ</a></li>';
      echo   '<li><a href="/Customers">КЛИЕНТЫ</a></li>';
  }
?>
            <li class="dropdown">
<?php
  if ($_SESSION['GroupBoss'] or $_SESSION['GroupAdm']) {
      echo   '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">ДОВIДНИКИ <span class="caret"></span></a>
              <ul class="dropdown-menu">';
      echo     '<li role="separator" class="divider"></li>
                <li><a href="/Product">ТОВАРИ</a></li>
                <li><a href="/ProductType">КАТЕГОРІЇ ТОВАРІВ</a></li>';
  }
  if ($_SESSION['GroupBoss'] or $_SESSION['GroupAdm']) {
      echo   '</ul>';
  }
?>
            </li>

          </ul>
          <ul class="nav navbar-nav navbar-right">
<?php
  if ($_SESSION['UserID']) {
            echo '<li><a href="login.php?logout=1">ВИЙТИ</a></li>';
            echo '<li><a href="Personal.php">ОСОБИСТИЙ</a></li>';
  } else {
            echo '<li class="active"><a href="login.php">УВІЙТИ <span class="sr-only">(current)</span></a></li>';
  }
?>
            <li><a href="help.php">ДОПОМОГА</a></li>
          </ul>
        </div>
      </div>
    </nav>
