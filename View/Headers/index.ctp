<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azul big">
		<i class="fa fa-1x flaticon-settings"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Configuraciones </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h1 class="nameview spacebtnm">Headers de Cotizaciones</h1>

			</div>
			<div class="col-md-6 text-right">
				<h1 class="nameview spacebtnm"></h1>
			
		<div class="input-group stylish-input-group">
			<a href="<?php echo $this->Html->url(array('controller'=>'Headers','action'=>'add')) ?>" class="crearclientej btn">
					<i class="fa fa-1x fa-plus-square"></i> <span>Crear header</span>
				</a>
			<?php if (isset($this->request->query['q'])){ ?>
				<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador por titulo o descripción">
				<span class="input-group-addon btn_buscar btn">
	                <i class="fa fa-search"></i>
	            </span>

			<?php } else { ?>
				<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por titulo o descripción">
				<span class="input-group-addon btn_buscar btn">
	                <i class="fa fa-search"></i>
	            </span>
			<?php } ?>
		</div>
		</div>
		
		</div>
	</div>
	<div class="headers index blockwhite">
		<div class="contenttableresponsive">
			<table cellpadding="0" cellspacing="0" class="tabletemplates table-striped table-bordered responsive">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('Header.name', 'Nombre'); ?></th>
						<th>Imagen (Header)</th>
						<th>Imagen footer</th>
						<th class="actions">Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($headers as $header): ?>
						<tr>
							<td><?php echo h($header['Header']['name']); ?>&nbsp;</td>
							<td>
								<img dataimg="<?php echo $this->Html->url('/img/header/header/'.$header['Header']['img_big']) ?>" dataname="<?php echo h($header['Header']['name']); ?>" src="<?php echo $this->Html->url('/img/header/header/'.$header['Header']['img_big']) ?>" width="30px" height="22px" class="imgmin-product">&nbsp;
							</td>
							<td>
								<img dataimg="<?php echo $this->Html->url('/img/header/miniatura/'.$header['Header']['img_small']) ?>" dataname="<?php echo h($header['Header']['name']); ?>" src="<?php echo $this->Html->url('/img/header/miniatura/'.$header['Header']['img_small']) ?>" width="30px" height="22px" class="imgmin-product">&nbsp;
							</td>
							<td class="actions">
								<!-- <a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($header['Header']['id']))) ?>" data-toggle="tooltip" data-placement="right" title="Ver nota"><i class="fa fa-fw fa-eye"></i> -->
					            </a>
								<a href="<?php echo $this->Html->url(array('action' => 'edit', $header['Header']['id'])) ?>" data-toggle="tooltip" data-placement="right" title="Editar nota"><i class="fa fa-fw fa-pencil"></i>
					            </a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/header/index.js?".rand(),						array('block' => 'AppScript'));
?>

<div class="popup">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
		<img src="" id="img-product" alt="">
		<p id="contenido"></p>
	</div>
<div class="fondo"></div>