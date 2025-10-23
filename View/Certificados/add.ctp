<?php 
	$readonly = "";
	if(AuthComponent::user("role") != 'Gerente General'){
		// $readonly = "readonly";
	}
 ?>
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Crear certificado para el cliente: <?php echo $type == 'natural' ? $customer["ClientsNatural"]["name"] : $customer["ClientsLegal"]["name"] ?></h2>
	</div>	
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-5">
				<?php echo $this->Form->create('Certificado',array('data-parsley-validate'=>true,)); ?>
					<?php
						echo $this->Form->input('name',array("label" => "Nombre","required"));

						if ($type == 'natural' ) {
							echo $this->Form->input('clients_natural_id',["type" => "hidden", "value"=>$customer_id]);
						}else{
							echo $this->Form->input('clients_legal_id',["type" => "hidden", "value"=>$customer_id]);					
						}
						echo $this->Form->input('identification',array("label" => "Identificación","required"));
						echo $this->Form->input('course',array("label" => "Curso realizado","required"));
						echo $this->Form->input('city_date',array("label" => "Ciudad y Fecha de emisión","required"));

					?>
				<?php echo $this->Form->end(__('Guardar')); ?>
			</div>
			<div class="col-md-7">
				<img id="imgCert" src="<?php echo $this->Html->url(["controller"=>"prospective_users","action" => "diploma"]) ?>" data-url="<?php echo $this->Html->url(["controller"=>"prospective_users","action" => "diploma"]) ?>" alt="" class="img-fluid" >
			</div>
		</div>	
	</div>
</div>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
	echo $this->Html->script("controller/certificados/save.js?".rand(),						array('block' => 'AppScript'));
?>
