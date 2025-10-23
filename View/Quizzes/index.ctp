<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Gestión de encuestas</h2>
			</div>
			<div class="col-md-6 text-right">
				<a href="<?php echo $this->Html->url(array('controller'=>'quizzes','action'=>'add')) ?>" class="crearclientej"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nueva encuesta</span></a>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="input-group">
			<?php if (isset($this->request->query['q'])){ ?>
				<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador por nombre de encuesta.">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } else { ?>
				<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por nombre de encuesta.">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } ?>
		</div>			
	</div>

	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table table-hovered">
				<thead>
				<tr>
						<th><?php echo $this->Paginator->sort('name',_("Nombre")); ?></th>
						<th><?php echo $this->Paginator->sort('description','Descripción'); ?></th>
						<th><?php echo $this->Paginator->sort('date_ini',"Fecha inicio publicación"); ?></th>
						<th><?php echo $this->Paginator->sort('date_end',"Fecha fin publicación"); ?></th>
						<!-- <th><?php echo $this->Paginator->sort('state',"Estado"); ?></th> -->
						<th><?php echo $this->Paginator->sort('type',"Tipo de encuesta"); ?></th>
						<th><?php echo "Link de la encuesta"; ?></th>
						<th><?php echo $this->Paginator->sort('created',"Fecha de creación"); ?></th>
						<th class="actions"><?php echo __('Acciones'); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($quizzes as $quiz): ?>
						<tr>
							<td><?php echo h($quiz['Quiz']['name']); ?>&nbsp;</td>
							<td><?php echo h($quiz['Quiz']['description']); ?>&nbsp;</td>
							<td><?php echo h($quiz['Quiz']['date_ini']); ?>&nbsp;</td>
							<td><?php echo h($quiz['Quiz']['date_end']); ?>&nbsp;</td>
							<!-- <td><?php echo ($quiz['Quiz']['state']) == "1" ? "Activo" : "No activo"; ?>&nbsp;</td> -->
							<td><?php echo ($quiz['Quiz']['type']) == "1" ? "Para el cliente" : "Interna"; ?>&nbsp;</td>
							<td>
								<a href="javascript:void(0)" data-url="<?php echo $this->Html->url(array('action' => 'respond', $this->Utilities->encryptString($quiz["Quiz"]['id']),"controller" => "quizzes"),true) ?>" class="bg-blue-sky mr-3 get_link btn p-1">
						              Obtener link
						            </a>
							&nbsp;</td>
							<td><?php echo h($quiz['Quiz']['created']); ?>&nbsp;</td>
							<td class="actions">
									<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($quiz['Quiz']['id']))) ?>" data-toggle="tooltip" data-placement="right" title="Ver encuesta"><i class="fa fa-fw fa-eye"></i>
					            </a>
								<a href="<?php echo $this->Html->url(array('action' => 'edit', $this->Utilities->encryptString($quiz['Quiz']['id']))) ?>" data-toggle="tooltip" data-placement="right" title="Editar encuesta"><i class="fa fa-fw fa-pencil"></i>
					            </a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<div class="row numberpages">
			<?php
				echo $this->Paginator->first('<< ', array('class' => 'prev'), null);
				echo $this->Paginator->prev('< ', array(), null, array('class' => 'prev disabled'));
				echo $this->Paginator->counter(array('format' => '{:page} de {:pages}'));
				echo $this->Paginator->next(' >', array(), null, array('class' => 'next disabled'));
				echo $this->Paginator->last(' >>', array('class' => 'next'), null);
			?>
			<b> <?php echo $this->Paginator->counter(array('format' => '{:count} en total')); ?></b>
		</div>
	</div>
</div>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
?>

<script>
	$("body").on('click', '.get_link', function(event) {
		event.preventDefault();
		var url = $(this).data("url");
		var $temp = $("<input>")
		  $("body").append($temp);
		  $temp.val(url).select();
		  document.execCommand("copy");
		  $temp.remove();
		message_alert("Copiado correctamente al portapapeles", "Bien");
	});
</script>