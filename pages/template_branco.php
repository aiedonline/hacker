<!DOCTYPE html>
<?php 
    
	require_once __DIR__ . '/funcoes.php'; 
    require_once dirname(__DIR__) . '/api/utilitario.php'; 
	$PREFIX = "/" . $SITE .  "/";
	print( "<script language='JavaScript'>var PREFIX = '". $PREFIX . "';</script>");

	require_once dirname(__DIR__, 1)  . '/api/user.php';
	if(!session_exist('user_cookie')) {
			
			return;  
	}
	print( "<script language='JavaScript'>var USER = ". session_load('user_cookie') . ";</script>");

?>
<script>
	function main() { console.log('Main não definido');}
</script>

<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>CPS</title>

  <!-- Custom fonts for this template-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	
  <link href="/<?php  print($CONFIG->ui->sigla);  ?>/vendor/fontawesome-free/css/all.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
<link href="https://bootstrap-combobox-test.herokuapp.com/css/bootstrap-combobox.css" rel="stylesheet" type="text/css">
	
  <!-- Custom styles for this template-->
  <link href="/<?php  print($CONFIG->ui->sigla);  ?>/css/sb-admin-2.css" rel="stylesheet">
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

	<div id='raiz' class="container-fluid" style="min-height: 500px;">
			<!-- AQUI FICA TODO O CÓDIGO ESPECÍFICO DA PÁGINA -->
	</div>


   </div>

  <!-- Bootstrap core JavaScript-->
  <?php echo '<script src="/' . $CONFIG->ui->sigla  .  '/vendor/jquery/jquery.js?id=' . $UUID_JS . '"></script>';  ?>
  <?php echo '<script src="/' . $CONFIG->ui->sigla  .  '/vendor/bootstrap/js/bootstrap.bundle.js?id=' . $UUID_JS . '"></script>';  ?>
  <!-- Core plugin JavaScript-->
  <?php echo '<script src="/' . $CONFIG->ui->sigla  .  '/vendor/jquery-easing/jquery.easing.js?id=' . $UUID_JS . '"></script>';  ?>
  <!-- Custom scripts for all pages-->
  <?php echo '<script src="/' . $CONFIG->ui->sigla  .  '/js/sb-admin-2.js?id=' . $UUID_JS . '"></script>';  ?>
	<?php //echo '<script src="/' . $CONFIG->ui->sigla  .  '/js/moment-with-locales.min.js?id=' . $UUID_JS . '"></script>';  ?>
  <?php echo '<script src="/' . $CONFIG->ui->sigla  .  '/js/esb.js?id=' . $UUID_JS . '"></script>';  ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js?id=<?php echo $UUID_JS; ?>"></script>
  <script src="http://www.aied.com.br/linux/js/ckeditor/ckeditor.js?id=<?php echo $UUID_JS; ?>"></script>
	<script src="http://www.aied.com.br/linux/js/ckeditor/config.js?id=<?php echo $UUID_JS; ?>"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js?id=<?php echo $UUID_JS; ?>"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css?id=<?php echo $UUID_JS; ?>" rel="stylesheet"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js?id=<?php echo $UUID_JS; ?>"></script>
  <script src="/secanalysis/js/jsonedit/json-edit.js?id=<?php echo $UUID_JS; ?>"></script>
  <link href="/secanalysis/js/jsonedit/json-edit.css?id=<?php echo $UUID_JS; ?>" rel="stylesheet" type="text/css" />

	
	
  <?php require __DIR__ . '/js.php'; ?>
</body>

</html>
