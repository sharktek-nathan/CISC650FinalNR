<!DOCTYPE HTML>
<html>
<head>
<title>RedFax.com</title>
	<link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,900' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/css/main-style.css">
    <link rel="stylesheet" type="text/css" href="/css/dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/3.8.4/css/dropzone.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css">
    <script type='text/javascript' src='https://code.jquery.com/jquery-1.9.1.js'></script>
    <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js'></script>
    <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/dropzone/3.8.4/dropzone.min.js'></script>
    <script type='text/javascript' src="/js/jquery.dataTables.js"></script>
    <script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  		ga('create', 'UA-57179649-1', 'auto');
  		ga('send', 'pageview');
		
	</script>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <!--[if lte IE 9]>
    <script type="text/javascript">
        location.assign('<?php echo site_url('auth/version_error') ?>');
    </script>
    <![endif]-->

    
    <div id="main-wrapper">
    	<header id="main-header" class="home-header">
        	<nav id="main-nav-left">
            	<a id="logo" href="/index.html">
                	<img src="/img/white-logo.png" alt="RedFax.com">
                </a>
            </nav>
            <nav id="main-nav-right">
            	<ul class="main-nav-ul">
                <?php
				if ($this->ion_auth->logged_in())
					{
					echo '<li class="main-nav-li home">
							<a href="' . base_url('dashboard/index') .'" title="Dashboard" class="right-nav-link">Dashboard</a>
						</li>
						<li class="main-nav-li logs">
							<a href="' . base_url('archive/index') .'" title="Archive" class="right-nav-link">Archive</a>
						</li>
						<li class="main-nav-li account">
							<a href="' . base_url('account/index') .'" title="Blog" class="right-nav-link">Account</a>
						</li>';
					} else {
						echo '<li class="main-nav-li home"><a href="#" class="right-nav-link"></a></li>';
					}
				?>
                </ul>
            </nav>
            <div class="clear"></div>
            <div id="accent-header">
            <?php
				if ($this->ion_auth->logged_in())
					{
						$useremail = $this->ion_auth->user()->row()->email;
						echo "<p>Welcome $useremail | <a href='" . site_url('auth/logout') ."'>Log Out</a></p>";
					}
			?>
            </div>
        </header>
		<div id="front-page" class="page-content min-width-container">