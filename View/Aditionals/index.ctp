<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-rojo big">
         <i class="fa fa-1x flaticon-settings-1"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Servicio Técnico</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Gestión de accesorios</h2>
			</div>
			<div class="col-md-6 text-right">
				<a href="<?php echo $this->Html->url(array('controller'=>'aditionals','action'=>'add')) ?>" class="crearclientej"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nuevo accesorio</span></a>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="input-group">
			<?php if (isset($this->request->query['q'])){ ?>
				<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador por nombre de accesorio">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } else { ?>
				<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por nombre">
				<span class="input-group-addon btn_buscar">
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
						<th><?php echo $this->Paginator->sort('accesorio',"Accesorio"); ?></th>
						<th><?php echo $this->Paginator->sort('state',"Estado"); ?></th>
						<th class="actions"><?php echo __('Acciones'); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($aditionals as $aditional): ?>
					<tr>
						<td><?php echo h($aditional['Aditional']['accesorio']); ?>&nbsp;</td>
						<td><?php echo $aditional['Aditional']['state'] == 1 ? "Activo" : "Inactivo"; ?>&nbsp;</td>
						<td class="actions">
							<a href="<?php echo $this->Html->url(array('action' => 'edit', $this->Utilities->encryptString($aditional['Aditional']['id']) )) ?>" data-toggle="tooltip" title="Editar accesorio">
								<i class="fa fa-fw fa-pencil"></i>
					        </a>
					        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php echo $aditional["Aditional"]["state"] == 1 ? "Deshabilitar accesorio" : "Habilitar accesorio" ?>" data-uid='<?php echo $aditional['Aditional']['id'] ?>' class='<?php echo $aditional["Aditional"]["state"] == 1 ? "btn_deshabilitar" : "btn_habilitar" ?>' >
					        	<?php if ($aditional["Aditional"]["state"] == 1): ?>
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

<script>
	var controlador = "Accesorio";
</script>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js'),						array('block' => 'jqueryApp'));
	 echo $this->Html->script("controller/inventories/detail.js",    array('block' => 'AppScript'));
	 	echo $this->Html->script("controller/clientsLegal/index.js",				array('block' => 'AppScript'));
?>
