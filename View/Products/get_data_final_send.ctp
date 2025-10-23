

<?php echo $this->Form->create('Product',array('data-parsley-validate'=>true,'id' => 'form_product_envio',"type" => "file")); ?>
<div class="row">
	<div class="col-md-4 border-right">
		<div class="form-group">
			<label for="" class="mb-3">Correos electrónicos a los que será enviado, encontrados (<?php echo count($correos) ?>) <br> <span id="addListCorreo" class="bg-warning pointer p-1 text-white">Añadir lista de correos <i class="fa fa-plus vtc"></i> </span> </label>
			<?php echo $this->Form->input('correos',array('label' => false,'placeholder' => 'Escribe y/o modifica la lista de correos',"id" => "correosEnvio", "value" => implode(",", $correos),"type" => "text" ));?>		
			
			<div class="row mt-3 mb-3">
				<div class="col-md-12">
					<a href="#" class="btn btn-warning btn-block" id="crearListaCorreos">
						Crear lista de difusión de correos
					</a>
				</div>
			</div>

		</div>
		<div class="form-group">
			<label for="" class="mb-3">Celulares para envio de campaña (<?php echo count($celulares) ?>) <span id="addList" class="bg-danger pointer p-1 text-white">Añadir lista de distribución <i class="fa fa-plus vtc"></i> </span> </label>
			<?php echo $this->Form->input('whatsapps',array('label' => false,'placeholder' => 'Escribe y/o modifica la lista de celulares',"id" => "celularesEnvio", "value" => str_replace("+", "", implode(",", $celulares) ),"type" => "text" ));?>		
			<div style="display: none">
				<table id="tablaCelulares">
					<tr>
						<th>Celular</th>
					</tr>
					<?php foreach ($celulares as $key => $value): ?>
						<tr>
							<td>
								<?php echo trim($value) ?>
							</td>
						</tr>
					<?php endforeach ?>
				</table>
			</div>
			<?php if (!empty($celulares)): ?>
				<div class="row mt-3">
					<div class="col-md-6">
						<a href="#" class="btn btn-danger btn-block" id="crearLista">
							Crear lista de difusión
						</a>
					</div>
					<div class="col-md-6">
						<a href="#" class="btn btn-info btn-block" id="descargarCelulares">
							Descargar celulares
						</a>
					</div>
				</div>				
			<?php endif ?>
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