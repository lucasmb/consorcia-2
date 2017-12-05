<!DOCTYPE html>
<!--[if IE 9]>         <html class="ie9 no-focus" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-focus" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
    <head>
    <title>Consortium</title>
        <meta name="description" content="website description" />
        <meta name="keywords" content="website keywords, website keywords" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <script src="/assets/js/core/jquery.min.js"></script>
        <script type="text/javascript" src="/assets/js/home/jquery.js"></script>
        <script type="text/javascript" src="/assets/js/home/jquery-ui.js"></script>
        <script type="text/javascript" src="/assets/js/home/jquery.form.js"></script>
        <script type="text/javascript" src="/assets/js/home/jquery.scrollLock.js"></script>
        <script type="text/javascript" src="/assets/js/home/home.js"></script>
        <script type="text/javascript" src="/assets/js/home/tabs.js"></script>
        <link rel="stylesheet" type="text/css" href="/assets/css/home/jquery-ui-1.8.20.custom.css"/>
        <link rel="stylesheet" type="text/css" href="/assets/css/home/style.css" />
        <link rel="stylesheet" type="text/css" href="/assets/css//home/tabs.css"/>
        <link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.min.css"/>
    <? if(uri_string() != '' ): ?>
      <?var_dump( url_js("/".uri_string()."/".uri_string().".js") );?>
        <script type="text/javascript" src="<?= url_js("/".uri_string()."/".uri_string().".js")?>"></script>
        <link rel="stylesheet" type="text/css" href="<?= url_css("/".uri_string()."/".uri_string().".css") ?>" />
    <? endif; ?>    
        <!-- Added for fancy box -->
      	<!-- Add mousewheel plugin (this is optional) -->
      	<script type="text/javascript" src="/assets/js/home/jquery.mousewheel-3.0.6.pack.js"></script>

      	<!-- Add fancyBox main JS and CSS files -->
      	<script type="text/javascript" src="/assets/js/home/jquery.fancybox.js?v=2.0.6"></script>
      	<link rel="stylesheet" type="text/css" href="/assets/css/home/jquery.fancybox.css?v=2.0.6" media="screen" />

      	<!-- Add Button helper (this is optional) -->
      	<link rel="stylesheet" type="text/css" href="/assets/css/home/jquery.fancybox-buttons.css?v=1.0.2" />
      	<script type="text/javascript" src="/assets/js/home/jquery.fancybox-buttons.js?v=1.0.2"></script>

      	<!-- Add Thumbnail helper (this is optional) -->
      	<link rel="stylesheet" type="text/css" href="/assets/css/home/jquery.fancybox-thumbs.css?v=1.0.2" />
      	<script type="text/javascript" src="/assets/js/home/jquery.fancybox-thumbs.js?v=1.0.2"></script>

      	<!-- Add Media helper (this is optional) -->
      	<script type="text/javascript" src="/assets/js/home/jquery.fancybox-media.js?v=1.0.0"></script>
    </head>

<body>
<div id="main">

    <img id="loading" style="display: none;" src="<?= url_img("/home/cargando.gif")?>">

    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="index.html">Administracion Consorcios <span class="logo_colour">JH</span></a></h1>
          <h2>Sistema de Mantenimiento de Consorcios</h2>
        </div>
      </div>
      <div id="menubar">
        <ul id="menu">
          <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
          <li <? if(current_url() == url_view('home') || current_url() == url_view('') ): ?>class="selected"<? endif; ?>><a href="<?= url_view('home')?>">Inicio</a></li>
          <li <? if(current_url() == url_view('buildings')){ ?>class="selected"<? } ?> ><a href="<?= url_view('buildings')?>">Edificios</a></li>
          <li <? if(current_url() == url_view('incomes')): ?>class="selected"<? endif;?>> <a href="<?= url_view('incomes')?>">Ingresos</a></li>
          <li <? if(current_url() == url_view('expenses')): ?>class="selected"<? endif;?>><a href="<?= url_view('expenses')?>">Egresos</a></li>
          <li <? if(current_url() == url_view('adjustments')): ?>class="selected"<? endif;?>><a href="<?= url_view('adjustments')?>">Ajuste base</a></li>
          <li <? if(current_url() == url_view('reports')): ?>class="selected"<? endif;?>><a href="<?= url_view('reports')?>">Reportes</a></li>
          <li <? if(current_url() == url_view('bankPayment')): ?>class="selected"<? endif;?>><a href="<?= url_view('bankPayment')?>">Pago por Banco</a></li>
        </ul>
      </div>
    </div>
    <div id="content_header"></div>