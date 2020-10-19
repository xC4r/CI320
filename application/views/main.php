<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sword</title>
    <!-- Bootstrap Core CSS -->
    <link href="assets/libs/icon.ico" rel="icon">
    <link href="assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Fonts --> 
    <link href="assets/libs/font-awesome/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/libs/custom.css" rel="stylesheet">
</head>
<body>
<div id="wrapper">
     <header class="navbar navbar-expand-md navbar-dark bg-dark p-1 fixed-top">
        <div class="col-xl-12 px-0 clearfix">
            <button class=" navbar-toggler float-left" type="button" data-toggle="collapse" data-target="#sidebar-collapse" aria-controls="sidebar-collapse" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <a class="navbar-brand ml-2 float-left" href="#"><i class="fa fa-sword"></i> Sword</a>
            <div class="dropdown float-right">
              <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i></button>
              <div class="dropdown-menu dropdown-menu-right dropdown-menu-sm" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#"><i class="fa fa-user"></i> Perfil</a>
                <a class="dropdown-item" href="#"><i class="fa fa-cog"></i> Ajustes</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="login/sesi_sali"><i class="fa fa-sign-out-alt"></i> Salir</a>
              </div>
            </div>
        </div> 
    </header>
    <div class="container-fluid px-0 pt-5">
            <div class="sidebar" role="navigation">
                <div class="collapse dont-collapse-sm navbar-collapse" id="sidebar-collapse">
                    <ul class="nav navbar-nav navbar-collapse flex-column list-group" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group md-form form-sm form-2 pl-0">
                              <input class="form-control my-0 py-1 red-border" type="text" placeholder="Buscar" aria-label="Search">
                              <div class="input-group-append">
                                <span class="input-group-text red lighten-3"><i class="fa fa-search"></i></span>
                              </div>
                            </div>
                        </li>
                        <!-- Custom Menu -->
                    </ul>
                </div>
            </div>
            <div class="snackbar" id="toast"></div>
            <div class="page-wrapper" id="pagina"></div>  
    </div>

</div>
<!-- jQuery -->
<script src="assets/libs/jquery/jquery-3.4.1.min.js"></script>
<!-- Bootstrap Plugin JavaScript -->
<script src="assets/libs/bootstrap/js/bootstrap.min.js"></script>
<!-- Custom Plugins y JavaScript -->
<script src="application/modules/main.js"></script>
<script src="application/modules/datatable.js"></script>
<script src="application/modules/form.js"></script>

</body>

</html> 
