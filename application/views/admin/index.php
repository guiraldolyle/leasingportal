<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>LEASING - Admin | Login</title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>img/logo-ico.ico" />
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo base_url(); ?>css/font-awesome.min.css" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>css/admin-style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/admin-style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->

      <div id="login-page">
        <div class="container">
        
              <form class="form-login" method="post" action="<?php echo base_url(); ?>index.php/admin/check_login">
                <h2 class="form-login-heading">Leasing - Admin</h2>
                <div class="login-wrap">
                    <input type="text" class="form-control" autocomplete="off" name = "username" required placeholder="User ID" autofocus>
                    <br>
                    <input type="password" class="form-control" name = "password" required placeholder="Password">
                    <hr>
                    <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-lock"></i> LOGIN IN</button>
                </div>
              </form>       
        
        </div>
      </div>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url(); ?>js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.backstretch.min.js"></script>
    <script>

        $.backstretch("<?php echo base_url(); ?>/img/page-bg.jpg", {speed: 500});
    </script>


  </body>
</html>
