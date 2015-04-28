<!DOCTYPE HTML>
<html>
<head>
<title>RedFax.com</title>
    <!--<link rel="icon" href="/img/favicon.ico" type="image/x-icon" />-->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/boot-style.css">
    <link rel="stylesheet" type="text/css" href="/css/dataTables.css">
    <link rel="stylesheet" type="text/css" href="/css/dropzone.css">
    <script type='text/javascript' src='/js/jquery-2.1.3.js'></script>
    
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>
    <!-- Navigation -->
    <nav id="app-navbar" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
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
                    <img src="/img/white-logo.png" alt="">
                </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav  navbar-right">
                    <li><a href="<?php echo site_url('dashboard/index') ?>"><span class="glyphicon glyphicon-home"></span>Dashboard</a></li>
                    <li><a href="<?php echo site_url('archive/index') ?>"><span class="glyphicon glyphicon-list"></span>Archive</a></li>
                    <li><a href="<?php echo site_url('account/index') ?>"><span class="glyphicon glyphicon-cog"></span>Account</a></li>
                    <li id="nav-logout"><a href="<?php echo site_url('auth/logout') ?>"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
        <div id="accent" class="container-fluid">
            <div class="row" style="background-color: #BEBEBE;"> <!-- Give this div your desired background color -->
                <div class="col-md-12 text-right">
                    <?php if($this->ion_auth->logged_in()) { 
                        $useremail = $this->ion_auth->user()->row()->email;
                        echo "Welcome $useremail | <a href='" . site_url('auth/logout') ."'>Log Out</a>";        
                    } ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div id="page-content" class="container">
        Your Internet browser is out of date.<br>
        Current browser: <?php echo $browser . ' version ' . $version ?><br>
        Please follow the links below to download the latest version of your web browser of choice.<br><br>
        <a href="https://www.google.com/chrome/browser/desktop/">Google Chrome</a><br>
        <a href="https://www.mozilla.org/en-US/firefox/new/">Mozilla Firefox</a><br>
        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">Internet Explorer</a><br>

