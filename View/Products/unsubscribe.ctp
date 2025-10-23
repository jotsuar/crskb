<!DOCTYPE html>
<html>
<head>
  <title>DESUSCRIPCIÓN DE CORREO ELECTRÓNICO</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>
<body>

  <header>
    <div class="container-fluid bg-light pb-4 pt-4">
      <div class="container">
        <div class="row">
          <div class="col-md-3 text-center">
            <img src="https://airless.kebco.co/assets/img/logo.png" class="img-fluid">
          </div>
          <div class="col-md-5"> </div>
          <div class="col-md-4 text-center">
            <div class="wp-icon"><img src="https://www.almacendelpintor.com/img/cms/home/wp.png" alt=""></div>
            <div class="wp">
              <h4><a href="https://wa.me/573014485566" class="mideclicwp">Línea de Whatsapp</a></h4>
              <p><a href="https://wa.me/573014485566" class="mideclicwp">+57 301 448 5566</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <nav class="navbar navbar-dark navbar-expand-md bg-primary justify-content-center">
      <button class="navbar-toggler ml-1" type="button" data-toggle="collapse" data-target="#collapsingNavbar2">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse collapse justify-content-between align-items-center w-100" id="collapsingNavbar2">
        <ul class="navbar-nav mx-auto text-center text-uppercase">
          <li class="nav-item">
            <a class="nav-link" href="https://www.almacendelpintor.com/3-equipos-para-pintar">Equipos para pintar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://www.almacendelpintor.com/16-equipos-para-blasting">Equipos para Blasting</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://www.almacendelpintor.com/15-hidrolavadoras">Hidrolavadoras</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://www.almacendelpintor.com/13-equipos-de-medicion">Equipos de Medición</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://www.almacendelpintor.com/14-equipos-de-seguridad-industrial">Equipos y Sistemas de Seguridad</a>
          </li>
        </ul>
      </div>
    </nav>

  </header>
  <section class="mb-5">
    <div class="container-fluid text-center">
      <div class="row">
        <img src="http://almacendelpintor.com/images/bannerunsuscribe.jpg" class="img-fluid d-block d-sm-none">
        <img src="http://almacendelpintor.com/images/bannerunsuscribe2.jpg" class="img-fluid d-none d-lg-block">
        <div class="container pt-4">
          <p><strong>KEBCO S.A.S.</strong> está comprometido con el tratamiento leal, lícito, confidencial y seguro de sus datos personales. Por favor consulte nuestra Política de Tratamiento de Información en: <a href="https://www.almacendelpintor.com/">www.almacendelpintor.com.co</a> en donde puede conocer sus derechos constitucionales y legales así como la forma de ejercerlos. Con mucho gusto atenderemos todas sus observaciones y consultas en el correo electrónico <a href="mailto:info@kebco.co">info@kebco.co</a></p>

			<?php if (!isset($dataList)): ?>
				<?php echo $this->Form->create('Product',array('id' => 'form_product')); ?>
					<?php echo $this->Form->input('email',array('label' => 'Ingrese el correo que se desea eliminar de nuestra base de datos:', "class" => "form-control", "placeholder" => "Su correo" , "type" => !empty($email_user) && filter_var($email_user, FILTER_VALIDATE_EMAIL) ? "hidden" : "text", "value" => $email_user,"required" )); ?>
					<?php echo $this->Form->input('state_email',array('label' => 'No deseo recibir más información por correo electrónico de Kebco SAS.', "type" => "checkbox","required","readonly" )); ?>
					<button type="submit" class="btn btn-primary">
						No quiero recibir más descuentos
					</button>
				</form>
			<?php else: ?>
				<h2>
					tu correo fue eliminado correctamente y no recibirás mas información.
				</h2>
			<?php endif ?>

        </div>
      </div>
    </div>
  </section>
  <style type="text/css">
    .bg-primary {
      background-color: #00338c !important;
    }
    .navbar-dark .navbar-nav .nav-link {
      color: rgba(255, 255, 255, 0.87) !important;
    }
    p {
      line-height: 17px;
    }      
    .wp-icon {
      vertical-align: text-bottom;
      cursor: pointer;
    }
    .wp-icon, .wp {
      display: inline-block;
    }
    .wp {
      margin-left: 5px;
      cursor: pointer;
    }   
    .wp h4 a {
      margin: 0 0 5px 0;
      color: #00d42a;
      cursor: pointer;
      font-size: 20px;
    }
    .wp h4 {
      margin: 0;
    }
    .wp p a {
      color: #00d42a;
      font-size: 22px;
      line-height: 0px;
      cursor: pointer;
    }
  </style>
  
</body>
</html>


