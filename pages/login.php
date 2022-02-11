<!DOCTYPE html>
<?php 
	require_once __DIR__ . '/funcoes.php'; 
	require_once dirname(__DIR__) . '/api/utilitario.php'; 
	$PREFIX = "/" . $SITE .  "/";
	print( "<script language='JavaScript'>var PREFIX = '". $PREFIX . "';</script>");

?>

<html lang="en">

<head>
 <style>
	.imagemDeFundo{
		background: url('/<?php  print($CONFIG->ui->sigla);  ?>/img/principal.jpg'); 
		width: 800px;
		height: 800px;
		background-position: center;
  		background-size: cover;
	}	 
</style>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php  print($CONFIG->ui->title);  ?></title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div classs="col-lg-6 d-none d-lg-block bg-login-image" class="col-lg-6 imagemDeFundo"></div> 
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4"><?php  print($CONFIG->ui->title);  ?></h1>
                  </div>
                  <form class="user">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" id="cpf" aria-describedby="CPF" placeholder="Digite o e-mail ou número do usuário">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" id="senha" placeholder="Informe a senha">
                    </div>
                    <a id='btn_login' href="JavaScript:Logar();" class="btn btn-primary btn-user btn-block">
                      Entrar
                    </a>
                    <hr>
                  </form>
                  <hr>
				<div class="text-center">
					<?php if ($TEMPLATE['permission']['signup'] == true){
						print('<a class="small" href="novo.php">Cadastrar</a>');
					}
					?>
                    
                  </div>
                  <div class="text-center">
                    <a class="small" href="recuperar.php">Recuperar senha</a>
                  </div>
					
					<br/><br/>
					<?php  
						print($TEMPLATE['page']['pages/login']['help']);
					?>
					
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <?php echo '<script src="../vendor/jquery/jquery.js?id=' . guid() . '"></script>';  ?>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/jquery-easing/jquery.easing.js"></script>

  <!-- Custom scripts for all pages-->
  <?php echo '<script src="../js/sb-admin-2.js?id=' . guid() . '"></script>';  ?>
  <script src='/jscloud/project/layout/EnviarJsonPost.cp/v1.js?id=0a4b0ee1c049f8083de31a5f9302287c' ></script>
  <script src='/jscloud/project/cookie/readCookie.cp/v1.js?id=0a4b0ee1c049f8083de31a5f9302287c' ></script>
  <script src='/jscloud/project/cookie/createCookie.cp/v1.js?id=0a4b0ee1c049f8083de31a5f9302287c' ></script>
  <script src='/jscloud/project/util/ToJson.cp/v1.js?id=0a4b0ee1c049f8083de31a5f9302287c' ></script>
	<script src='/jscloud/project/util/md5.cp/v1.js?id=0a4b0ee1c049f8083de31a5f9302287c' ></script>

  <?php echo '<script src="./login.js?id=' . guid() . '"></script>';  ?>


</body>

</html>
