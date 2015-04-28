<!DOCTYPE HTML>
<html>
<head>
<title>CISC 650 Final Project</title>
    <!--<link rel="icon" href="/img/favicon.ico" type="image/x-icon" />-->
    <link rel="stylesheet" href="/css/bootstrap.min.css" >
    <link rel="stylesheet" type="text/css" href="/css/boot-style.css">
    <link rel="stylesheet" type="text/css" href="/css/dataTables.css">
    <link rel="stylesheet" type="text/css" href="/css/dropzone.css">
    <script type='text/javascript' src='/js/jquery-2.1.3.js'></script>
    
    <!--[if lte IE 9]>
    <script type="text/javascript">
        location.assign('<?php echo site_url('auth/version_error') ?>');
    </script>
    <![endif]-->
    
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>
    <!-- Navigation -->
    <nav id="app-navbar" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid" style="min-height: 80px">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!--<a class="navbar-toggle" href="<?php //echo site_url('dashboard/index') ?>"><span class="glyphicon glyphicon-off"></span></a>-->
                <a class="navbar-left navbar-brand" href="/">
                    <img src="/img/pcapviewer_logo.png" alt="">
                </a>
            </div>
                                            
            
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav  navbar-right">
                    <li><a href="<?php echo site_url('upload/index') ?>"><span class="glyphicon glyphicon-home"></span>Dashboard</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
                    
                    
        </div>
        <!-- /.container -->
        <div id="accent" class="container-fluid">
            <div class="row" style="background-color: #BEBEBE;"> <!-- Give this div your desired background color -->
                <div class="col-md-12 text-right">
                    <span style="padding-right: 25px;"> 
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div id="page-content" class="container">
    <?php if(isset($success) && !empty($success)) { ?> 
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo $success; ?>
    </div>
    <?php } ?>
    <?php if(isset($info) && !empty($info)) { ?> 
    <div class="alert alert-info alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo $info; ?>
    </div>
    <?php } ?>
    <?php if(isset($warning) && !empty($warning)) { ?> 
    <div class="alert alert-warning alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo $warning; ?>
    </div>
    <?php } ?>
    <?php if(isset($danger) && !empty($danger)) { ?> 
    <div class="alert alert-info alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo $danger; ?>
    </div>
    <?php } ?>