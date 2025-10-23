<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Gestión de valores de la característica: <b><?php echo $feature["Feature"]["name"] ?></b></h2>
			</div>
			<div class="col-md-6 text-right">
				<a href="<?php echo $this->Html->url(array('controller'=>'features','action'=>'index')) ?>" class="crearclientej"><i class="fa fa-1x fa-list"></i> <span>Listar características</span></a>
				<a href="<?php echo $this->Html->url(array('controller'=>'features','action'=>'add')) ?>" class="crearclientej"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nueva característica</span></a>
				<a href="<?php echo $this->Html->url(array('controller'=>'features_values','action'=>'add',$this->Utilities->encryptString($feature["Feature"]["id"]))) ?>" class="crearclientej"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nuevo valor</span></a>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="input-group">
			<?php if (isset($this->request->query['q'])){ ?>
				<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador por nombre de accesorio">
				<span class="input-group-addon btn_buscar" onclick="buscadorFiltro()">
					<i class="fa fa-search"></i>
				</span>
			<?php } else { ?>
				<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por nombre">
				<span class="input-group-addon btn_buscar" onclick="buscadorFiltro()">
					<i class="fa fa-search"></i>
				</span>
			<?php } ?>
		</div>			
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="myTable table-striped table-bordered">
				<thead>
				<tr>
						<th><?php echo $this->Paginator->sort('name',"Característica"); ?></th>
						<th><?php echo $this->Paginator->sort('state',"Estado"); ?></th>
						<th class="actions"><?php echo __('Acciones'); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($featuresValues as $featuresValue): ?>
					<tr>
						<td>
							<a href="<?php echo $this->Html->url(["controller"=>"features_values","action"=>"edit",$this->Utilities->encryptString($featuresValue["FeaturesValue"]["id"])]) ?>" class="btn-sm">
								<?php echo h($featuresValue['FeaturesValue']['name']); ?>&nbsp;
							</a>
						</td>								
						<td><?php echo $featuresValue['FeaturesValue']['state'] == 1 ? "Activo" : "Inactivo"; ?>&nbsp;</td>
						<td class="actions">
							<a href="<?php echo $this->Html->url(array('action' => 'edit', $this->Utilities->encryptString($featuresValue['FeaturesValue']['id']) )) ?>" data-toggle="tooltip" title="Editar característica">
								<i class="fa fa-fw fa-pencil"></i>
					        </a>
					        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php echo $feature["Feature"]["state"] == 1 ? "Deshabilitar característica" : "Habilitar característica" ?>" data-uid='<?php echo $featuresValue['FeaturesValue']['id'] ?>' class='<?php echo $featuresValue['FeaturesValue']["state"] == 1 ? "btn_deshabilitar" : "btn_habilitar" ?>' >
					        	<?php if ($featuresValue['FeaturesValue']["state"] == 1): ?>
					        		<i class="fa fa-trash"></i>
					        	<?php else: ?>
					        		<i class="fa fa-check"></i>		        			        		
					        	<?php endif ?>
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
	 echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript'));
	 	echo $this->Html->script("controller/clientsLegal/index.js?".time(),				array('block' => 'AppScript'));
?>

