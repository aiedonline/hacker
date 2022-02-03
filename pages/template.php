<!DOCTYPE html>
<?php 

	require_once __DIR__ . '/funcoes.php';
  require_once dirname(__DIR__) . '/api/utilitario.php'; 
  require_once dirname(__DIR__) . '/api/json.php'; 
	
  // se for uma página de administraçòa então deve-se aplicar filtro de IP
  if( count($CONFIG->ip) > 0){
    if ( !in_array($ip, $CONFIG->ip, false)){
      echo "IP não cadastrado: " . $ip;
      die;
    }
  }

	$PREFIX = "/" . $SITE .  "/";
	print( "<script language='JavaScript'>var PREFIX = '". $PREFIX . "';</script>");
	require_once dirname(__DIR__, 1)  . '/api/user.php';

	if(!session_exist('user_cookie')) {
			$free = false;
			if (file_exists(dirname(__DIR__) . '/data/free.json') == true){
				$arquivo_free = \fs\Json::FromFile(dirname(__DIR__) . '/data/free.json');
				for($i = 0; $i < count($arquivo_free); $i++) {
					if (  $arquivo_free[$i] == $_SERVER['PHP_SELF']){
						$free = true;
					}
				}
			}
			if( $free == false ) {
				echo '<script>window.location.href = "/'. $SITE .'/pages/login.php";</script>';
				return;  
			}
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

  <title><?php  print($CONFIG->ui->title);  ?></title>

  <!-- Custom fonts for this template-->
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="shortcut icon" href="/<?php  print($CONFIG->ui->sigla);  ?>/favicon.ico" />
  <link href="/<?php  print($CONFIG->ui->sigla);  ?>/vendor/fontawesome-free/css/all.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="https://bootstrap-combobox-test.herokuapp.com/css/bootstrap-combobox.css" rel="stylesheet" type="text/css">
	<!-- Custom styles for this template-->
  <!-- link href="/<php  print($CONFIG->ui->sigla);  >/css/sb-admin-2.css" rel="stylesheet" -->
  	<?php importar_css("css/sb-admin-2");  ?>
	<?php importar_css("pages/template");  ?>
</head>

<body id="page-top">	
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php require __DIR__ . '/slidebar.php'; ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php require __DIR__ . '/topbar.php'; ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div id='raiz' class="container-fluid" style="min-height: 500px;">
			<!-- AQUI FICA TODO O CÓDIGO ESPECÍFICO DA PÁGINA -->
          
        </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; <?php  print($CONFIG->ui->title);  ?> - <?php echo date("Y"); ?></span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

   </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="/<?php  print($CONFIG->ui->sigla);  ?>/vendor/jquery/jquery.js"></script>
  <script src="/<?php  print($CONFIG->ui->sigla);  ?>/vendor/bootstrap/js/bootstrap.bundle.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="/<?php  print($CONFIG->ui->sigla);  ?>/vendor/jquery-easing/jquery.easing.js"></script>
  <script src="/<?php  print($CONFIG->ui->sigla);  ?>/vendor/chart.js/Chart.js"></script>
  <!-- Custom scripts for all pages-->
  <script src="/<?php  print($CONFIG->ui->sigla);  ?>/js/sb-admin-2.js"></script>
  <!script src="/<?php  print($CONFIG->ui->sigla);  ?>/js/moment-with-locales.min.js"></script>
  <?php echo '<script src="/' . $CONFIG->ui->sigla  .  '/pages/template.js?id=' . guid() . '"></script>';  ?>
  <script src="/<?php  print($CONFIG->ui->sigla);  ?>/js/esb.js"></script>
	  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
	
  <?php
    echo '<script src="//cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>';
  ?>
  <script src="/secanalysis/js/jsonedit/json-edit.js"></script>
  <link href="/secanalysis/js/jsonedit/json-edit.css" rel="stylesheet" type="text/css" />

  <?php 
	  require __DIR__ . '/js.php'; 
	  if(file_exists($LOCAL_SITE . "/pages/template.js")) {
			importacoes(file_get_contents($LOCAL_SITE . "/pages/template.js"));
			importar_js("pages/template");
		}
	  ?>
</body>

</html>

	
