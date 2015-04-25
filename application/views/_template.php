<?php

defined('BASEPATH') OR exit('No direct script access allowed');

?><!DOCTYPE html>
<html lang="en">
<head>
    <title>{page_title}</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Tangerine:700" type="text/css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Josefin+Sans:300" type="text/css">
    <link rel="stylesheet" href="/assets/css/style.css" type="text/css" media="all">
    <link rel="stylesheet" href="/assets/css/slick.css" type="text/css" media="all">
    <link rel="stylesheet" href="/assets/css/slick-theme.css" type="text/css" media="all">
    <!--[if lt IE 9]>
    <script src="/assets/js/html5.js"></script>
    <style type="text/css">.box1, .box1 .inner{behavior:url("/assets/js/PIE.htc");}</style>
    <![endif]-->
</head>
<body>
    <div id="container">
        <header>
            <div class="wrapper">
                {header}
            </div>
        </header>
        <div id="content">
            <div class="wrapper">
                {content}
            </div>
        </div>
    </div>
    <footer>
        <div class="wrapper">
            {footer}
        </div>
    </footer>
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="/assets/js/slick.min.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
          $('.data-slick').slick({
            accessibility: false,
            autoplay: true
          });
        });
    </script>
    
</body>
</html>
