<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-4">
				<h2 class="titleviewer">Campañas de re-marketing</h2>
			</div>
			<div class="col-md-8 text-right">
				<a href="<?php echo $this->Html->url(array("controller" => "products", "action" => "remarketing")) ?>" class="btn btn-warning mr-1"><i class="fa fa-plus-square vtc"></i> <span>Crear campaña manual</span></a>
				<a href="<?php echo $this->Html->url(array("controller" => "campaigns", "action" => "add")) ?>" class="btn btn-warning mr-1"><i class="fa fa-plus-square vtc"></i> <span>Crear campaña Automática</span></a>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="input-group">
			<?php if (isset($this->request->query['q'])){ ?>
				<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador por nombre o asunto">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } else { ?>
				<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por nombre o asunto">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } ?>
		</div>			
	</div>	
	<div class="clientsLegals index blockwhite">
		<div class="contenttableresponsive">
			<table cellpadding="0" cellspacing="0" class='myTable table-striped table-bordered'>
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('name',"Nombre Campaña"); ?></th>
						<th><?php echo $this->Paginator->sort('subject',"Asunto"); ?></th>
						<th><?php echo $this->Paginator->sort('type',"Tipo de campaña"); ?></th>
						<th><?php echo $this->Paginator->sort('mails_senders','Emails enviados'); ?></th>
						<th><?php echo $this->Paginator->sort('created','Fecha de envio'); ?></th>
						<th><?php echo __("Ver detalle") ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($campaigns)): ?>
						<?php foreach ($campaigns as $key => $campaign): ?>
							<tr>
								<td><?php echo h($campaign['Campaign']['name']); ?>&nbsp;</td>
								<td><?php echo h($campaign['Campaign']['subject']); ?>&nbsp;</td>
								<td>
									<?php echo $campaign["Campaign"]["type"] == 1 ? "Manual" : "Automática" ?>
								</td>
								<td><?php echo count($campaign['EmailTracking']); ?>&nbsp;</td>
								<td><?php echo h($campaign['Campaign']['created']); ?>&nbsp;</td>
								<td>
									<a class="btn btn-outline-primary" href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($campaign['Campaign']['id']))) ?>" data-placement="top" data-toggle="tooltip" title="Ver detalle de campaña">
										<i class="fa fa-fw fa-eye vtc"></i>
									</a>
								</td>
							</tr>
						<?php endforeach ?>
					<?php else: ?>
						<tr>
							<td colspan="5" class="text-center">
								No hay campañas en el momento
							</td>
						</tr>
					<?php endif ?>
				</tbody>
			</table>
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
</div>


<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/clientsLegal/index.js?".rand(),				array('block' => 'AppScript'));
?>
