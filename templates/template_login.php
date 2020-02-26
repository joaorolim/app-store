<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App-Store</title>

    <link href="<?php echo getBaseURL().'assets/img/Logo_sistema_ ajustado.png?'.VERSION ?>" rel="shortcut icon" type="image/ico" />

    <!-- Bootstrap core CSS-->
    <link rel="stylesheet" href="<?php echo getBaseURL().'assets/css/bootstrap.min.css?'.VERSION ?>">
    <!-- Custom fonts for this template-->
    <link href="<?php echo getBaseURL().'assets/font-awesome/css/font-awesome.min.css?'.VERSION ?>" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="<?php echo getBaseURL().'assets/css/sb-admin.css?'.VERSION ?>" rel="stylesheet">
    <link href="<?php echo getBaseURL().'assets/css/styles.css?'.VERSION ?>" rel="stylesheet">

</head>

<body class="bg-dark">

    <div class="container container-login">
        <?php
        if ( isset( $viewName ) )
        {
            $path = viewsPath() . $viewName . '.php';
            if ( file_exists( $path ) )
            {
              require_once $path;
          }
      }
      ?>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo getBaseURL().'assets/js/jquery.min.js?'.VERSION ?>"></script>
    <script src="<?php echo getBaseURL().'assets/js/bootstrap.min.js?'.VERSION ?>"></script>
    <!-- Core plugin JavaScript-->
    <script src="<?php echo getBaseURL().'assets/js/jquery.easing.min.js?'.VERSION ?>"></script>
</body>

</html>
