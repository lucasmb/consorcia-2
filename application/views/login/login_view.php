<!DOCTYPE HTML>
<html>
  <head>
    <title>Consortium</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="<?= url_js('/home/jquery.js')?>"></script>
    <script type="text/javascript" src="<?= url_js('/home/jquery-ui.js')?>"></script>
    <script type="text/javascript" src="<?= url_js('/home/jquery.form.js')?>"></script>
    <link rel="stylesheet" type="text/css" href="<?= url_css("/home/jquery-ui-1.8.20.custom.css") ?>" />
    <link rel="stylesheet" type="text/css" href="<?= url_css("/home/style.css") ?>" />
    <link rel="stylesheet" type="text/css" href="<?= url_css("/login/login.css") ?>" />
  </head>
  <body>
      <div class="main">
          <div id="header">
              <div id="logo">
                <div id="logo_text">
                  <!-- class="logo_colour", allows you to change the colour of the text -->
                  <h1><a href="index.html">Administracion Consorcios <span class="logo_colour">JH</span></a></h1>
                  <h2>Sistema de Mantenimiento de Consorcios</h2>
                </div>
              </div>
          </div>

          <div class="login_header">Inicie sesión</div>

          <div class="login_div">
              <?php echo validation_errors(); ?>
              <?php echo form_open('verifylogin'); ?>
                  
                  <div class="adv_row">
                      <div class="adv_row_div_label"><label for="username">Usuario:</label></div>
                      <input type="text" size="20" id="username" name="username"/>
                  </div>
                      
                  <div class="adv_row">
                      <div class="adv_row_div_label"><label for="password">Contraseña:</label></div>
                      <input type="password" size="20" id="passowrd" name="password"/>  
                  </div>
                  
                  <div class="adv_row">
                      <button type="submit"/> Ingresar </div>
                  </div>

              </form>
          </div>  
      </div>
      
  </body>
</html>