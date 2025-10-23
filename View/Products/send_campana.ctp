<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Envio de campañas de markegint</h2>
			</div>
			<div class="col-md-6">
				<a href="<?php echo $this->Html->url(array("controller" => "campaigns", "action" => "index")) ?>" class="btn btn-primary pull-right">
					Campañas activas
				</a>
				<a href="<?php echo $this->Html->url(array("controller" => "mailing_lists", "action" => "index","?"=>["type"=>"1"])) ?>" class="btn btn-warning pull-right mr-3">
					Listas de teléfonos creadas
				</a>
				<a href="<?php echo $this->Html->url(array("controller" => "mailing_lists", "action" => "index","?"=>["type"=>"2"])) ?>" class="btn btn-danger pull-right mr-3">
					Listas de correos creadas
				</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h2 class="titlesectionquote text-primary text-center">CONSTRUIR CAMPAÑA DE MARKETING</h2>
			<div class=" blockwhiteabajo spacebtn20" id="paso1">
				


				<?php echo $this->Form->create('Product',array('data-parsley-validate'=>true,'id' => 'form_product_envio',"type" => "file")); ?>
				<div class="row">
					<div class="col-md-4 border-right">
						<div class="form-group">
							<label for="" class="mb-3">Correos electrónicos a los que será enviado <span id="addListCorreo" class="bg-warning pointer p-1 text-white">Añadir lista de correos <i class="fa fa-plus vtc"></i> </span> </label>
							<?php echo $this->Form->input('correos',array('label' => false,'placeholder' => 'Escribe y/o modifica la lista de correos',"id" => "correosEnvio","type" => "text" ));?>			
						</div>
						<div class="form-group">
							<label for="" class="mb-3">Celulares para envio de campaña <span id="addList" class="bg-danger pointer p-1 text-white">Añadir lista de distribución <i class="fa fa-plus vtc"></i> </span> </label>
							<?php echo $this->Form->input('whatsapps',array('label' => false,'placeholder' => 'Escribe y/o modifica la lista de celulares',"id" => "celularesEnvio","type" => "text" ));?>
						</div>
					</div>
					<div class="col-md-8 mb-4">
						<h2 class="text-center text-info">
							Envio de información a los clientes
						</h2>
						<div class="form-group mt-2">
							<?php echo $this->Form->input('name_campaign',array('label' => 'Nombre de la campaña','placeholder' => 'Ingresa el nombre de la campaña de marketing', "id" => "nombrecampana", "required"));?>			
						</div>
						<div class="form-group mt-2">
							<?php echo $this->Form->input('name',array('label' => 'Asunto del correo electrónico','placeholder' => 'Ingresa el asunto del correo electrónico', "id" => "asuntocorreo"));?>			
						</div>
						<div class="form-group mt-2">
							<?php echo $this->Form->input('type',array('label' => 'Seleccionar medio de envío','placeholder' => 'Selecciona por favor el médio de envio de esta información', "options" => ["EMAIL" => "Correo eléctronico", "WHATSAPP" => "Whatsapp"], "id" => "envioMetodo", "required" => true));?>
						</div>
						<div class="form-group mt-2">
							<?php echo $this->Form->input('test',array('label' => 'Enviar prueba', "options" => ["0" => "NO", "1" => "SI"], "id" => "testEnvio", "required" => true));?>
						</div>
						<div class="form-group" id="whatsappMsgData" style="display: none">
							<?php echo $this->Form->input('msg_text',array('label' => 'Mensaje que se enviará por whatsapp','placeholder' => 'Ingresa el mensaje que se enviará por whatsapp', "type" => "textarea", "id" => "msgWp", "required" => false));?>
							<?php echo $this->Form->input('msg_file',array('label' => 'Archivo que se enviará por whatsapp','placeholder' => 'Ingresa el mensaje que se enviará por whatsapp', "type" => "file", "id" => "fileWp", "required" => false, "data-allowed-file-extensions" => "png jpg jpeg gif mp3 mov ogg swf pdf xlx xslx csv doc docx", "data-max-file-size" => "5M", "class" => "dropify" ));?>
							<?php echo $this->Form->input('msg_file_txt',array('label' => false,'placeholder' => 'Ingresa el mensaje que se enviará por whatsapp', "type" => "textarea", "id" => "fileWpMsg", "required" => false, "style" => "display: none;" ));?>
						</div>
						<div class="form-group" id="cuepoCorreoDiv">
							<?php echo $this->Form->input('cuerpo',array('label' => 'Cuerpo del correo electrónico','placeholder' => 'Ingresa el cuerpo del correo electrónico', "type" => "textarea", "id" => "cuerpocorreo", "required" => false));?>
						</div>
						
						<div class="form-group">
							<input type="submit" class="btn btn-success pull-right" value="Enviar información" >
						</div>
					</div>
				</div>
				</form>
				
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="listModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg3" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Crear lista de difusión</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       	<?php echo $this->Form->create('MailingList',array('id' => 'form_product_difusion')); ?>
			<?php echo $this->Form->input('name',array('label' => 'Ingrese el nombre de la nueva lista de difusión:', "type" => "text","required" )); ?>
			<?php echo $this->Form->input('numbers',array('label' => 'Celulares que conformarán la lista de difusión', "type" => "textarea","required","autocapitalize" => "characters","rows" => 50,"readonly", "id" => "WhatsappListEmails" )); ?>
			<button type="submit" class="btn btn-success float-right">
				Guardar lista
			</button>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="listModalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg3" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Seleccionar lista de difusión</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       	<div class="form-group">
       		<label for="listadoName">Selecciona el nombre de la lista que deseas agregar</label>
       		<select name="listadoName" id="listadoWhatsapp" class="form-control listadoName">
       			<option value="">Seleccionar</option>
       			<?php foreach ($lists as $key => $value): ?>
       				<option value="<?php echo $value["id"] ?>"><?php echo $value["name"] ?></option>
       			<?php endforeach ?>
       		</select>
       		<select name="listadoName" id="listadoCorreos" class="form-control listadoName">
       			<option value="">Seleccionar</option>
       			<?php foreach ($listEmails as $key => $value): ?>
       				<option value="<?php echo $value["id"] ?>"><?php echo $value["name"] ?></option>
       			<?php endforeach ?>
       		</select>
       		<textarea name="listadoCompleto" id="listadoCompleto" cols="30" rows="10" class="form-control mt-3" readonly=""></textarea>
       		<a href="#" class="btn btn-success selectList mt-3 pull-right" style="display: none"> Seleccionar lista</a>
       		<input type="hidden" id="typeSendList">
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<script>
	<?php if(!empty($lists)): ?>
		var lists 		= <?php echo json_encode($lists); ?>;
	<?php endif; ?>
	<?php if(!empty($listEmails)): ?>
		var listEmails 	= <?php echo json_encode($listEmails); ?>;
	<?php endif; ?>
</script>
<?php echo $this->Html->css(array('lib/jquery.typeahead.css'), array('block' => 'AppCss'));?>
<?php echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));?>
<?php 
	
		$whitelist = array(
            '127.0.0.1',
            '::1'
        ); 

 ?>


<?php
	echo $this->Html->script("controller/product/marketing.js?".rand(),						array('block' => 'AppScript'));
?>

