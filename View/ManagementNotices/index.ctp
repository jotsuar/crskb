<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azul big">
		<i class="fa fa-1x flaticon-settings"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Configuraciones </h2>
	</div>
</div>
<div class="col-md-12">
	<div class="blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h1 class="nameview spacebtnm">Avisos Públicos</h1>
				<ul class="subpagos">
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'Manages','action'=>'diary')) ?>">Mi Agenda</a>
					</li>
					<li class="activesub">
						<a href="<?php echo $this->Html->url(array('controller'=>'ManagementNotices','action'=>'index')) ?>">Avisos Públicos</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'Headers','action'=>'index')) ?>">Banners de Cotizaciones</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'index')) ?>">Gestión de Usuarios</a>
					</li>
				</ul>				
			</div>
			<div class="col-md-6">
				<h1 class="nameview spacebtnm"></h1>
				<div class="input-group stylish-input-group">
					<?php if (AuthComponent::user('role') == Configure::read('variables.roles_usuarios.Gerente General')): ?>
					<a id="btn_registrar" class="crearclientej btn"><i class="fa fa-1x fa-plus-square"></i> <span>Publicar nuevo aviso</span></a>

				<?php endif ?>
				<?php if (isset($this->request->query['q'])){ ?>
					<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador por titulo o descripción">
					<span class="input-group-addon btn_buscar">
						<i class="fa fa-search"></i>
					</span>
				<?php } else { ?>
					<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por titulo o descripción">
					<span class="input-group-addon btn_buscar">
						<i class="fa fa-search"></i>
					</span>
				<?php } ?>
			</div>
		</div>
	</div>		
</div>
<div class="managementNotices index blockwhite">
	<div class="contenttableresponsive">
		<table cellpadding="0" cellspacing="0" class="tabletemplates table-striped table-bordered responsive">
			<thead>
				<tr>
					<th><?php echo $this->Paginator->sort('ManagementNotice.title', 'Título'); ?></th>
					<th>Descripción</th>
					<th>Precio</th>
					<th>Imagen</th>
					<th><?php echo $this->Paginator->sort('ManagementNotice.fecha_ini', 'Fecha inicio'); ?></th>
					<th><?php echo $this->Paginator->sort('ManagementNotice.fecha_fin', 'Fecha fín'); ?></th>
					<th><?php echo $this->Paginator->sort('ManagementNotice.state', 'Estado'); ?></th>
					<th class="actions">Acciones</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($managementNotices as $managementNotice): ?>
					<tr>
						<td><?php echo $managementNotice['ManagementNotice']['title']; ?>&nbsp;</td>
						<td><?php echo $managementNotice['ManagementNotice']['description']; ?>&nbsp;</td>
						<td><?php echo $this->Utilities->data_null_numeros(number_format((int)h($managementNotice['ManagementNotice']['price']),0,",",".")); ?>&nbsp;</td>

						<?php if ($managementNotice['ManagementNotice']['img'] != ''){ ?>
							<td>
								<img src="<?php echo $this->Html->url('/img/managementNotices/'.$managementNotice['ManagementNotice']['img']) ?>" width="30px" height="22px" class="imgmin-product">
							</td>
						<?php } else { ?>
							<td><?php echo $this->Utilities->data_null($managementNotice['ManagementNotice']['img']); ?>&nbsp;</td>
						<?php } ?>
						<td><?php echo $managementNotice['ManagementNotice']['fecha_ini']; ?>&nbsp;</td>
						<td><?php echo $managementNotice['ManagementNotice']['fecha_fin']; ?>&nbsp;</td>
						<td><?php echo $this->Utilities->state_notice_management($managementNotice['ManagementNotice']['state']); ?></td>
						<td class="actions">
							<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($managementNotice['ManagementNotice']['id']))) ?>" data-toggle="tooltip" data-placement="right" title="Ver nota"><i class="fa fa-fw fa-eye"></i>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
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
echo $this->Html->script("controller/managementNotices/index.js?".rand(),			array('block' => 'AppScript'));
?>