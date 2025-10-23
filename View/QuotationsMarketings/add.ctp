<div class=" widget-panel widget-style-2 bg-azul big">
	<i class="fa fa-1x flaticon-settings"></i>
	<h2 class="m-0 text-white bannerbig" >Módulo de Configuraciones </h2>
</div>

<div class="blockwhite spacebtn20">
	<div class="col-md-12">
		<h1 class="nameview spacebtnm">
			<?php if (is_null($bot)): ?>
						Gestión de cotizaciones para marketing y envíos masivos - Crear
				<?php else: ?>
					Gestión de cotizaciones para cotizar automaticamente en el chat - Crear
				<?php endif ?>
			
		</h1>
		<hr>			
	</div>
</div>		
<?php echo $this->Form->create('QuotationsMarketing'); ?>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-6">
				<?php echo $this->Form->input('name',["label" => "Asunto de la cotización"]); ?>
				<?php echo $this->Form->hidden('type',["value" => $bot == null ? 1 : 2]); ?>
			</div>
			<div class="col-md-6">
				<?php echo $this->Form->input('origin',["label" => "Origen de la cotización","options" => Configure::read("variables.origenContact"), "default" => "Marketing" ]); ?>
			</div>
			<div class="col-md-12">
				<?php echo $this->Form->input('customer_note',["label" => "Nota que se enviará al cliente"]); ?>
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('header_id',["label" => "Banners por defecto"]); ?>
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('total_visible',["label" => "Mostrar total en la cotización","options" => ["1" => "SI", "2" => "NO"]]); ?>
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('notes',["label" => "Nota previa por defecto","empty" => "Seleccionar", "options" => $notas_previas ]); ?>
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('notes_description',["label" => "Nota descriptiva por defecto", "empty" => "Seleccionar", "options" => $notas_descriptivas]); ?>
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('conditions',["label" => "Condiciones de negociación por defecto","options" => $formas_pago]); ?>
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('user_id',["label" => "Usuario asignado por defecto","empty" => "Ninguno", "options" => $users ]); ?>
			</div>
		</div>
		<div class="col-md-12">
			<h2 class="text-center text-info">
				Productos para la cotización
			</h2>
			<div class="col-md-12 compuestoData p-4">
		
			</div>
		</div>
		<div class="col-md-12 pb-4 mb-4">
			<input type="submit" class="btn btn-success float-right mb-2 mt-2" value="Guardar información">
		</div>
	</div>
<?php echo $this->Form->end(); ?>


<div class="modal fade" id="modalProductoData" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Gestionar producto </h4>
      </div>
      <div class="modal-body" id="cuerpoAdd">
      </div>
      <div class="modal-footer m-t-4">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

<?php 
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),array('block' => 'jqueryApp'));
$this->Html->scriptStart(array('block' => "AppScript"));

?>
$("#QuotationsMarketingCustomerNote").summernote(
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