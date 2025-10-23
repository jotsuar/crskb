
<div class="users form blockwhite">

	<div class="col-md-12">
		<?php echo $this->Form->create('User',array('enctype'=>"multipart/form-data",'data-parsley-validate'=>true,"autocomplete"=>"off")); ?>
				<div class="row">
					<div class="col-md-4">
						<div class="col-md-12 text-center">
							<img class="brandlogin my-3 img-responsive" src="/CRM/img/assets/brand2.png">
						</div>
					</div>
					<div class="col-md-8">
						<h1 class="colorazul font-weight-bold sizeref mt-4" style="font-size: 2rem">Bienvenido, registro de asesor externo</h1>
					</div>
				</div>
				
				<div class="row">
					
				
					<?php
						echo $this->Form->input('name',array('placeholder' => 'Nombre completo','label' => 'Nombre completo','div' => 'col-md-3 form-group my-2', "class" => "form-control" ));
						echo $this->Form->input('identification',array('placeholder' => 'Número de Identificación','label' => 'Número de Identificación','div' => 'col-md-3 form-group my-2', "class" => "form-control" ));
						echo $this->Form->input('rut',array('placeholder' => 'RUT','label' => 'RUT','div' => 'col-md-3 form-group my-2', "class" => "form-control","type" => "file","required" => true ));
						echo $this->Form->input('img',array('type' => 'file','label' => 'Foto de perfil',"required"=>true,"data-parsley-fileextension" => "1",'div' => 'col-md-3 form-group my-2', "class" => "form-control"));
					?>

					
					<?php
						echo $this->Form->input('city',array('placeholder' => 'Ciudad','label' => 'Ciudad','autocomplete'=>'on','required'=>true,'div' => 'col-md-3 form-group my-2', "class" => "form-control"));
						echo $this->Form->input('address',array('placeholder' => 'Dirección de recidencia','label' => 'Dirección de recidencia','autocomplete'=>'on','required'=>true,'div' => 'col-md-3 form-group my-2', "class" => "form-control"));
						echo $this->Form->input('email',array('placeholder' => 'Correo electrónico','label' => 'Correo electrónico', "required"=> true,'div' => 'col-md-3 form-group my-2', "class" => "form-control"));
					?>
					<div class="col-md-3 form-group my-2">
						<?php echo $this->Form->label("Fecha de nacimiento");
						echo $this->Form->text('date_born',array('placeholder' => 'Fecha de nacimiento','label' => 'Fecha de nacimiento',"type" => "date","class" => "form-control","required","max"=> date("Y-m-d", strtotime("-18 years")), "value" => date("Y-m-d", strtotime("-18 years")) )); ?>
					</div>
					<?php
						
						echo $this->Form->input('img_identification_up',array('type' => 'file','label' => 'Foto cédula delantera',"required"=>true,"data-parsley-fileextension" => "1",'div' => 'col-md-3 form-group my-2', "class" => "form-control"));
						echo $this->Form->input('img_identification_down',array('type' => 'file','label' => 'Foto cédula trasera',"required"=>true,"data-parsley-fileextension" => "1",'div' => 'col-md-3 form-group my-2', "class" => "form-control"));

					?>
					
					<?php
						echo $this->Form->input('company',array('placeholder' => 'Empresa donde trabaja','label' => 'Empresa donde trabaja', "required"=> true,'div' => 'col-md-3 form-group my-2', "class" => "form-control"));
						echo $this->Form->input('company_role',array('placeholder' => 'Cargo actual','label' => 'Cargo actual', "required"=> true,'div' => 'col-md-3 form-group my-2', "class" => "form-control"));
						echo $this->Form->input('cell_phone',array('placeholder' => 'Celular','label' => 'Celular',"type" => "phone", "required"=> true,'div' => 'col-md-3 form-group my-2', "class" => "form-control"));
						echo $this->Form->input('telephone',array('placeholder' => 'Teléfono','label' => 'Teléfono',"type" => "phone", "required"=> true,'div' => 'col-md-3 form-group my-2', "class" => "form-control"));
						
						
						echo $this->Form->input('role',array('label' => 'Rol:', "type" => "hidden", "value" => "Asesor Externo" ));
						echo $this->Form->input('password',array('placeholder' => 'Contraseña','label' => 'Contraseña',"autocomplete"=>false, "required"=> true,'div' => 'col-md-3 form-group my-2', "class" => "form-control"));
						echo $this->Form->input('re_password',array('placeholder' => 'Confirmar Contraseña','label' => 'Confirmar Contraseña',"autocomplete"=>false,"required",'data-parsley-equalto'=>"#UserPassword","data-parsley-equalto-message"=>"Las contraseñas deben de ser iguales",'div' => 'col-md-3 form-group my-2', "class" => "form-control","type" => "password"));
					?>
					<div class="col-md-12" style="display: none">
						
						<div class="form-check Topics__checkbox checkgeneral my-2">
							<div class="content-label">
								<?php echo $this->Form->input("terminos", array(
									'class' => 'form-control form-check-input',
									'type'=>'checkbox', 'value' => true, "checked" => true, 'label'=>false, 'div'=>false, 'hiddenField'=>false,"required"=>true,
									'after'=> "<div style='margin-left:20px'>".__("Acepto términos y condiciones:")." <a href='/#cookies' target='_blank'>Términos y condiciones</a></div>")); ?>	
							</div>
						</div>
					</div>
					<div class="col-md-12" style="display: none">
						<div class="form-check Topics__checkbox checkgeneral my-2">
							<div class="content-label">
								<?php echo $this->Form->input("accepting_data_collection", array(
									'class' => 'form-control form-check-input',
									'type'=>'checkbox', 'value' => true, "checked" => true, 'label'=>false, 'div'=>false, 'hiddenField'=>false,"required"=>true,
									'after'=> "<div style='margin-left:20px'>".__("Doy mi consentimiento para la recopilación, uso, retención, transferencia, divulgación y procesamiento de mis datos personales. Los datos personales son recopilados, usados, retenidos, transferidos, divulgados y procesados ​​de acuerdo con nuestra")." <a href='https://strategeesuite.com/legal#Privacity' target='_blank'>Privacy Policy</a> ".__("que cumple con las leyes nacionales e internacionales, incluidas las normas GDPR.")."</div>",
									)); ?>	
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12 py-3 pb-5">
						<div class="form-group">
							<input type="submit" class="btn btn-success float-right " value="Registrar información">
						</div>
					</div>

				</div>	
		<?php echo $this->Form->end(); ?>
	</div>
</div>

<div class="modal fade" id="tyc-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Términos y condiciones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <iframe src="<?php echo $this->Html->url('/legal/contrato.pdf') ?>" frameborder="1" scrolling="auto" width="100%" height="600"></iframe>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Acepto Términos y condiciones</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="autorizacion-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Autorización de tratamiento de datos personales</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<iframe src="<?php echo $this->Html->url('/legal/contrato1.pdf') ?>" frameborder="1" scrolling="auto" width="100%" height="600"></iframe>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Autorizó tratamiento de datos personales</button>
      </div>
    </div>
  </div>
</div>

<style>
	.checkgeneral__label-box {
     width: initial !important;
     height: initial !important;

}
.checkgeneral .form-check-input{
	width: 25px;
    height: 30px;
    margin-right: 1em !important;
}
</style>

<?php 
	$this->start('AppScript'); ?>


<script>
	
	window.Parsley.addValidator('fileextension', function (value, requirement) {
	    var fileExtension = value.split('.').pop();  
	    fileExtension = fileExtension.toLowerCase();          
	    var extenciones_archivos = ["pdf","png","jpg","jpeg"];
	    console.log(extenciones_archivos.indexOf(fileExtension))
	    return extenciones_archivos.indexOf(fileExtension) == -1 ? false : true;

	}, 32)
	.addMessage('es', 'fileextension', 'La extención del archivo debe ser PDF, PNG o JPG');

	$('#UserTerminos').on('change', function(e){
	   if(e.target.checked){
	     $('#tyc-modal').modal();
	   }
	});

	$('#UserAcceptingDataCollection').on('change', function(e){
	   if(e.target.checked){
	     $('#autorizacion-modal').modal();
	   }
	});


</script>

<?php
	$this->end();
?>