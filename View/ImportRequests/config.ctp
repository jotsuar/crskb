<?php 
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),array('block' => 'jqueryApp'));
$roles = Configure::read("variables.roles_usuarios");
?>
<div class=" widget-panel widget-style-2 bg-azul big">
	<i class="fa fa-1x flaticon-settings"></i>
	<h2 class="m-0 text-white bannerbig" >Módulo de Configuraciones </h2>
</div>

<div class="blockwhite spacebtn20">
	<div class="col-md-12">
		<h1 class="nameview spacebtnm">Configuración de variables general</h1>
		<hr>
		<ul class="subpagos">
			<li>
				<a href="<?php echo $this->Html->url(array('controller'=>'Manages','action'=>'diary')) ?>">Mi Agenda</a>
			</li>
			<li>
				<a href="<?php echo $this->Html->url(array('controller'=>'ManagementNotices','action'=>'index')) ?>">Avisos Públicos</a>
			</li>
			<li>
				<a href="<?php echo $this->Html->url(array('controller'=>'Headers','action'=>'index')) ?>">Banners de Cotizaciones</a>
			</li>
			<li>
				<a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'index')) ?>">Gestión de Usuarios</a>
			</li>
			<li class="activesub">
				<a href="<?php echo $this->Html->url(array('controller'=>'ImportRequests','action'=>'config')) ?>">Configuraciones generales</a>
			</li>
		</ul>				
	</div>
</div>		

<?php echo $this->Form->create('Config',array("class" => "")); ?>
	<div class="blockwhite spacebtn20">
		<div class="managementNotices index">
			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center">Configuración de TRM y cotizaciones</h3>
				</div>
				<div class="col-md-3">
					<?php echo $this->Form->input('trm',array('label' => "TRM Actual",'placeholder' => 'TRM ACTUAL',"type" => "number", "value" => $trmActual,"required"));?>
				</div>
				<div class="col-md-3">
					<?php echo $this->Form->input('factorUSA',array('label' => "Factor de importaciones",'placeholder' => 'Factor de importación', "type" => "number", "value" => $factorImport,"required"));?>
				</div>
				<div class="col-md-3">
					<?php echo $this->Form->input('ajusteTrm',array('label' => "¿Ajuste en peso para la TRM?","min"=>0,"required","value" => $ajusteTrm));?>
				</div>
				<div class="col-md-6">
					<?php echo $this->Form->input('bloqueoMargen',array('label' => "¿Bloquear cotizaciones sin margen min?","options" => Configure::read("IMPUESTOS"),"required","value" => $bloqueoMargen));?>
				</div>
				<div class="col-md-6">
					<?php echo $this->Form->input('ivaCol',array('label' => "¿Aplicar IVA en las cotizaciones de colombia?","options" => Configure::read("IMPUESTOS"),"required","value" => $ivaCol));?>
				</div>
				
			</div>
		</div>
	</div>

	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12 mt-3 mb-2 pb-2">
				<h3 class="text-center">Configuración mensajes al cliente</h3>
				<hr>
			</div>
			<div class="col-md-12">
				<?php echo $this->Form->input('textClient',array('label' => "Plantilla de correo a enviar para encuestas","required","value" => $textClient));?>
				<span class="text-danger">
					Nota: Por favor incluir las siguientes etiquetas: <b> @@ASESOR@@</b>, <b>@@CODIGO@@</b>, <b>@@FECHA@@</b>, <b>@@CLIENTE@@</b>. <br>
					La etiqueta @@CORREO@@ es requería al final de la url de la encuesta para precargar el correo del cliente.
					Se debe incluir de la siguiente manera: <b>?m=@@CORREO@@</b>
					<br>
					Para agregar valor pre-cargado se debe agregar a la url final, en formato base64, de la siguiente manera: <b>&v=[valor]</b>. Reemplazar <b>[valor]</b> por la información correcta, se debe convertir en la siguiente url: <a href="https://www.base64encode.org/" target="_blank">https://www.base64encode.org/</a>.
					<br>
					Ejemplo: http://localhost/CRM/quizzes/respond/3al3Mq1JnZNJLHJdPm_JsXDwnV_1ii62dOB5taxolQQ?m=@@CORREO@@&v=TXV5IHNhdGlzZmVjaG8vYQ==
				</span>
			</div>
			<div class="col-md-12">
				<?php echo $this->Form->input('time_factura',array('label' => "Número de días para enviar la encuesta luego de subir factura","required", "class" => "form-control", "value" => $time_factura, "min" => 0));?>
			</div>
		</div>
	</div>

	<div class="blockwhite spacebtn20 d-none" style="display:none !important;">
		<div class="row">
			<div class="col-md-12 mt-3 mb-2 pb-2">
				<h3 class="text-center">Configuración de variables para el envío masivo de cotizaciones</h3>
				<hr>
			</div>
			<div class="col-md-3">
				<?php echo $this->Form->input('reason',array('label' => "Tipo de solicitud por defecto","required", "class" => "form-control", "value" => $reason));?>
			</div>
			<div class="col-md-3">
				<?php echo $this->Form->input('origin',array('label' => "Origen por defecto","required","value" => $origin, "options" => Configure::read("variables.origenContact")));?>
			</div>
			<div class="col-md-3">
				<?php echo $this->Form->input('subject',array('label' => "Asunto por defecto", "class" => "form-control", "required","value" => $subject));?>
			</div>

			<div class="col-md-3">
				<?php echo $this->Form->input('descriptive_id',array('label' => "Nota previa por defecto","required","value" => $descriptive_id, "options" => $notasPrevias, "empty" => "Seleccionar"));?>
			</div>
			<div class="col-md-3">
				<?php echo $this->Form->input('conditions_id',array('label' => "Condiciones de negociación por defecto","required","value" => $conditions_id, "options" => $conditions, "empty" => "Seleccionar"));?>
			</div>
			<div class="col-md-3">
				<?php echo $this->Form->input('header_id',array('label' => "Banners por defecto","required","value" => $header_id, "options" => $headers, "empty" => "Seleccionar"));?>
			</div>
			<div class="col-md-3">
				<?php echo $this->Form->input('template_id',array('label' => "Nota descriptiva por defecto","required","options"=>$descriptives,"empty"=>"Seleccionar","value" => $template_id));?>
			</div>
			<!-- <div class="col-md-12">
				<?php // echo $this->Form->input('textClient',array('label' => "Texto al cliente por defecto para carga masiva para las cotizaciones","required","value" => $textClient));?>
			</div> -->

			<div class="col-md-12">
				<h2 class="text-center text-info">
					Productos para la cotización
				</h2>
				<div class="col-md-12 compuestoData p-4">
			
				</div>
			</div>

		</div>
	</div>

	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12 mt-3 mb-2 pb-2">
				<h3 class="text-center">Configuración para el seguimiento de cotizaciones</h3>
				<hr>
			</div>
			<div class="col-md-3">
				<?php echo $this->Form->input('days_resend',array('label' => "Tiempo en días para que el sistema envíe al cliente la notificación de seguimiento de una cotización","required", "class" => "form-control", "value" => $days_resend));?>
			</div>
			<div class="col-md-9">
				<?php echo $this->Form->input('txt_resend',array('label' => "Mensaje que se enviará al cliente via Whatsapp","required","value" => $txt_resend,"type" => "textarea"));?>
			</div>
			<div class="col-md-12">
				<?php echo $this->Form->input('msg_redend',array('label' => "Mensaje que se enviará al cliente via Email", "class" => "form-control", "required","value" => $msg_redend, "min" => 1));?>
			</div>
		</div>
	</div>

	<div class="blockwhite spacebtn20">
		<div class="col-md-12 mt-3 mb-2">
			<h3 class="text-center">Configuración de roles para administración de productos</h3>
			<hr>
		</div>

		<div class="row">
			<div class="col-md-4">
				<?php echo $this->Form->input('roles_add',array('label' => "Roles que pueden crear productos (selecciona varios presionando CTRL + clic)","required","value" => $roles_add, "options" => $roles, "multiple" => true, "style" => "height: 190px;"));?>
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('roles_edit',array('label' => "Roles que pueden editar productos (selecciona varios presionando CTRL + clic)","required","options"=>$roles,"value" => $roles_edit, "multiple" => true, "style" => "height: 190px;"));?>
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('roles_unlock',array('label' => "Roles que pueden desbloquear productos (selecciona varios presionando CTRL + clic)","required","options"=>$roles,"value" => $roles_unlock, "multiple" => true, "style" => "height: 190px;"));?>
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('users_money',array('label' => "Usuarios que pueden escojer la moneda al cotizar (selecciona varios presionando CTRL + clic)","required","options"=>$users,"value" => $users_money, "multiple" => true, "style" => "height: 190px;"));?>
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('users_validate',array('label' => "Usuarios a los que se les revisará las cotizaciones (selecciona varios presionando CTRL + clic)","required","options"=>$users,"value" => $users_validate, "multiple" => true, "style" => "height: 190px;"));?>
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('users_external',array('label' => "Usuarios asignados para revisar las cotizaciones de externos (selecciona varios presionando CTRL + clic)","required","options"=>$users,"value" => $users_external, "multiple" => true, "style" => "height: 190px;"));?>
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('users_request',array('label' => "Usuarios que no necesitan solicitar cambio de costo (selecciona varios presionando CTRL + clic)","required","options"=>$users,"value" => $users_request, "multiple" => true, "style" => "height: 190px;"));?>
			</div>
			<div class="col-md-12 p-2">
				<hr>
			</div>
			<div class="col-md-6">
				<?php echo $this->Form->input('users_med',array('label' => "Usuarios que se asocian a la ciudad de Medellín (selecciona varios presionando CTRL + clic)","required","options"=>$users,"value" => $users_med, "multiple" => true, "style" => "height: 190px;"));?>
			</div>
			<div class="col-md-6">
				<?php echo $this->Form->input('users_bog',array('label' => "Usuarios que se asocian a la ciudad de Bogotá (selecciona varios presionando CTRL + clic)","required","options"=>$users,"value" => $users_bog, "multiple" => true, "style" => "height: 190px;"));?>
			</div>
			<div class="col-md-6">
				<?php echo $this->Form->input('users_blogs',array('label' => "Usuarios pueden crear contenido en la biblioteca (selecciona varios presionando CTRL + clic)","required","options"=>$users,"value" => $users_blogs, "multiple" => true, "style" => "height: 190px;"));?>
			</div>
			<div class="col-md-6">
				<?php echo $this->Form->input('users_verify_blogs',array('label' => "Usuarios pueden aprobar contenido en la biblioteca(selecciona varios presionando CTRL + clic)","required","options"=>$users,"value" => $users_verify_blogs, "multiple" => true, "style" => "height: 190px;"));?>
			</div>
			<div class="col-md-3">
				<?php echo $this->Form->input('user_remarketing',array('label' => "Usuario al que se le asignaran los flujos para remarketing","required","options"=>$users,"value" => $user_remarketing));?>
			</div>
			<div class="col-md-3">
				<?php echo $this->Form->input('days_remarketing',array('label' => "Número de días que tendrá un asesor para continuar un flujo antes de asignar a remarketing","required","value" => $days_remarketing));?>
			</div>
			<div class="col-md-3">
				<?php echo $this->Form->input('days_prorroga',array('label' => "Número de días como prorroga final en caso de que sea solicitado por el asesor para pasar un flujo a remarketing","required","value" => $days_prorroga));?>
			</div>

			<div class="col-md-3">
				<?php echo $this->Form->input('max_prorroga',array('label' => "Número de veces que el asesor podrá solicitar prorroga antes de pasar a remarketing","required","value" => $max_prorroga));?>
			</div>
			
		<div class="col-md-12">
			<input type="submit" class="btn btn-success float-right mb-2 mt-2">
		</div>
		</div>
	</div>
</form>


<div class="managementNotices index blockwhite">
	<div class="table-responsive">
		<h3 class="text-center">TRM de los últimos 10 días</h3>
		<hr>
		<table class="table table-bordered table-hovered" id="trm10">
			<thead>
				<tr>
					<th>
						Valor
					</th>
					<th>
						Fecha inicio vigencia
					</th>
					<th>
						Fecha fin vigencia
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($trms as $key => $value): ?>
					<tr>
						<td>
							<?php echo $value["Trm"]["valor"] ?>
						</td>
						<td>
							<?php echo $value["Trm"]["fecha_inicio"] ?>
						</td>
						<td>
							<?php echo $value["Trm"]["fecha_fin"] ?>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>

<div class="modal fade" id="modalIngrediente" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Agregar producto </h4>
      </div>
      <div class="modal-body" id="cuerpoAdd">
      </div>
      <div class="modal-footer m-t-4">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php 

$this->Html->scriptStart(array('block' => "AppScript"));

?>
$("#ConfigTextClient").summernote(
{
	focus: false,
	disableResizeEditor: true,
	disableDragAndDrop: true
}
);

$("#ConfigMsgRedend").summernote(
{
	height: 70,
	toolbar: [
	['style', ['bold', 'italic', 'underline', 'clear']],
	['para', ['ul', 'ol', 'paragraph']],
	['misc', ['undo', 'redo','codeview']],
	['link', ['linkDialogShow', 'unlink']]
	],
	fontNames: ['Arial', 'Arial Black', 'Comic Sans MS'],
	focus: false,
	disableResizeEditor: true,
	disableDragAndDrop: true
}
);




<?php 


$this->Html->scriptEnd();

	echo $this->Html->script("controller/config/admin.js?".rand(),						array('block' => 'AppScript'));


?>