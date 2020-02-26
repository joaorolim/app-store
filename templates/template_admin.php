<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>App-Store</title>
    <link href="<?php echo getBaseURL().'assets/img/brasao.png?'.VERSION ?>" rel="shortcut icon" type="image/ico" />

    <!-- Bootstrap core CSS-->
    <link rel="stylesheet" href="<?php echo getBaseURL().'assets/css/bootstrap.min.css?'.VERSION ?>">
    <!-- Custom fonts for this template-->
    <link href="<?php echo getBaseURL().'assets/font-awesome/css/font-awesome.min.css?'.VERSION ?>" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="<?php echo getBaseURL().'assets/css/sb-admin.css?'.VERSION ?>" rel="stylesheet">
    <link href="<?php echo getBaseURL().'assets/css/styles.css?'.VERSION ?>" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?php echo getBaseURL().'assets/css/plugins/morris.css?'.VERSION ?>" rel="stylesheet">

    <script src="<?php echo getBaseURL().'assets/js/Chart.min.js?'.VERSION ?>"></script>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo getBaseURL(); ?>adm/home">App-Store</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $name ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo getBaseURL(); ?>adm/perfil"><i class="fa fa-fw fa-user"></i> Perfil</a>
                        </li>
                        <!-- <li>
                            <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                        </li> -->
                        <!-- <li>
                            <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                        </li> -->
                        <li class="divider"></li>
                        <li>
                            <a href="<?php echo getBaseURL(); ?>adm/logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li class="active">
                        <a href="<?php echo getBaseURL(); ?>adm/home"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <!-- <li>
                        <a href="charts.html"><i class="fa fa-fw fa-bar-chart-o"></i> Charts</a>
                    </li>
                    <li>
                        <a href="tables.html"><i class="fa fa-fw fa-table"></i> Tables</a>
                    </li>
                    <li>
                        <a href="forms.html"><i class="fa fa-fw fa-edit"></i> Forms</a>
                    </li>
                    <li>
                        <a href="bootstrap-elements.html"><i class="fa fa-fw fa-desktop"></i> Bootstrap Elements</a>
                    </li>
                    <li>
                        <a href="bootstrap-grid.html"><i class="fa fa-fw fa-wrench"></i> Bootstrap Grid</a>
                    </li> -->
                    <!-- <li>
                        <a href="blank-page.html"><i class="fa fa-fw fa-file"></i> Blank Page</a>
                    </li>
                    <li>
                        <a href="index-rtl.html"><i class="fa fa-fw fa-dashboard"></i> RTL Dashboard</a>
                    </li> -->
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">
            <div class="container-fluid">

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
        </div>

    </div>

    <!-- Modal de Confirmação de Exclusão de Registros-->
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3><span class="glyphicon glyphicon-trash"></span> Deletar Registro</h3>
                </div>
                <div class="modal-body" style="text-align:center;">
                    <h4>Deseja realmente excluir este registro?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                    <a class="btn btn-danger btn-ok">Sim</a>
                </div>
            </div>
        </div>
    </div>
    <!-- / Modal de Confirmação de Exclusão de Registros -->

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo getBaseURL().'assets/js/jquery.min.js?'.VERSION ?>"></script>
    <script src="<?php echo getBaseURL().'assets/js/bootstrap.min.js?'.VERSION ?>"></script>
    <script src="<?php echo getBaseURL().'assets/js/helper.js?'.VERSION ?>"></script>
    <script src="<?php echo getBaseURL().'assets/js/autocomplete.js?'.VERSION ?>"></script>
    <script src="<?php echo getBaseURL().'assets/js/searchbyajax.js?'.VERSION ?>"></script>

    <!-- Morris Charts JavaScript -->
    <script src="<?php echo getBaseURL().'assets/js/plugins/morris/raphael.min.js?'.VERSION ?>"></script>
    <!-- <script src="<?php //echo getBaseURL(); ?>assets/js/plugins/morris/morris.min.js"></script> -->
    <!-- <script src="<?php //echo getBaseURL(); ?>assets/js/plugins/morris/morris-data.js"></script> -->

    <!-- Script Modal de Confirmação de Exclusão -->
    <script>
        $(document).ready(function(){
            $('#confirm-delete').on('show.bs.modal', function(e) {
                $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            });
        });
    </script>
    <!-- / Script Modal de Confirmação de Exclusão -->
</body>

</html>
