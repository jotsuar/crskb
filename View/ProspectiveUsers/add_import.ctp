<?php 
	echo $this->Html->css(array('lib/jquery.typeahead.css'),									array('block' => 'AppCss'));
?>

<div class="col-md-12 p-0">
	<div class=" blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12 text-center">
				<h1 class="nameview">PANEL PRINCIPAL DE COMPRAS</h1>
				<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'flujos_import')) ?>" class="pull-right btn btn-info">					
					<span class="d-block"><i class="fa flaticon-growth ml-0"></i> Flujos con importaciones en proceso</span>
				</a>
			</div>
		</div>
	</div>

	<?php if ($movileAccess && is_null($modal)): ?>
		<?php echo $this->element("order_responsive"); ?>
	<?php endif ?>

<div class="col-md-12">
	<div class="row">
		<?php if (is_null($modal)): ?>
			
			<div class="col-md-6">
				<div class="row">
					<h2 class="titlemenuline">GESTIÓN LOGÍSTICA</h2>
				</div>			
				<div class="row pr-2">
						<?php $roles = array(Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Logística')) ?>
							<?php if (in_array(AuthComponent::user('role'), $roles)): ?>
							<!-- <div class="activesub impblock-color1"> -->
							<div class="col-md-3 item_menu_import">
								<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'request_import_brands')) ?>">
									<i class="fa fa-list-alt d-xs-none vtc"></i>
									<span class="d-block"> Pedidos a Proveedores</span>
								</a>
							</div>
							<div class="col-md-3 item_menu_import">
								<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'request_import_brands','internacional')) ?>">
									<i class="fa fa-list-alt d-xs-none vtc"></i>
									<span class="d-block"> Pedidos Prov Internacionales</span>
								</a>
							</div>
						<?php endif ?>
						<?php $roles = array(Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Logística')) ?>
							<?php if (in_array(AuthComponent::user('role'), $roles)): ?>
							<div class="col-md-3 item_menu_import">
								<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'import_ventas')) ?>">
									<i class="fa d-xs-none fa-dropbox vtc"></i>
									<span class="d-block"> Reposición de Inventario</span>
								</a>
							</div>
						<?php endif ?>
						<div class="col-md-3 item_menu_import activeitem">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'add_import')) ?>">
								<i class="fa d-xs-none fa-cart-plus vtc"></i>
								<span class="d-block"> Crear solicitud Interna</span>
							</a>
						</div>	
							
				</div>	
			</div>
			<div class="col-md-6">
				<?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Logística"): ?>
					<div class="row">
						<h2 class="titlemenuline">GESTIÓN GERENCIAL</h2>
					</div>
					<div class="row pl-2">
							<div class="col-md-3 item_menu_import">
								<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_revisions')) ?>">
									<i class="fa fa-list-alt d-xs-none vtc"></i> <i class="fa fa-plane d-xs-none vtc"></i>
									<span class="d-block"> Gestión y Aprobación</span> </a>
							</div>					
							
							<div class="col-md-3 item_menu_import">
								<a href="<?php echo $this->Html->url(["controller" => "products", "action" => "products_rotation" ]) ?>"><i class="fa d-xs-none fa-cogs vtc"></i>
									<span class="d-block"> Productos configurados</span>
								</a>
							</div>		
							<div class="col-md-3 item_menu_import">
								<a href="<?php echo $this->Html->url(["controller" => "products", "action" => "new_panel" ]) ?>">
									<i class="fa d-xs-none fa-cloud-upload vtc"></i>
									<span class="d-block"> Solicitudes automáticas</span>
								</a>
							</div>
							<?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Logística"): ?>						
								<div class="col-md-3 item_menu_import">
									<a href="<?php echo $this->Html->url(["controller" => "ProspectiveUsers", "action" => "solicitudes_internas" ]) ?>">
										<i class="fa d-xs-none fa-users vtc"></i>
										<span class="d-block"> Solicitudes internas</span>
									</a>
								</div>		
							<?php endif ?>

					</div>
				<?php endif ?>	
			</div>			
		<?php endif ?>
	</div>
</div>



	<div class="blockwhite">
		<?php echo $this->Form->create('Import',array('data-parsley-validate'=>true,'id' => 'form_quotations')); ?>
			<h2>Registrar producto 
				<?php if (!is_null($modal)): ?>
					<a href="<?php echo $this->Html->url(array('controller'=>'Products','action' => 'add')) ?>" data-toggle="tooltip" data-placement="right" target="_blank" title="Crear producto"><i class="fa fa-plus-square vtc"></i></a>
				<?php else: ?>
					<a id="btn_registrar_products" data-toggle="tooltip" data-placement="right" title="Crear producto"><i class="fa fa-plus-square"></i></a>
				<?php endif ?>

				<span class="pull-right"><a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'my_import')) ?>">Ver mis solicitudes internas</a></span>
			</h2>

		    <div class="typeahead__container">
		        <div class="typeahead__field">
		            <span class="typeahead__query">
		                <input class="js-typeahead" type="search" autofocus autocomplete="off" placeholder="Busca tu producto por nombre o referencia">
		            </span>
		        </div>
		    </div>
		    <h2>Productos añadidos</h2>
			<div id="contentproductquote">
				<table class="table" id="details-country">
					<tr class="titletable">
						<td>Foto</td>
						<td>Nombre</td>
						<td>Descripción</td>
						<td>Referencia</td>
						<td>Marca</td>
						<td>Cantidad Solicitada</td>
						<td>Cantidad en Inventario</td>
						<td>Acción</td>
					</tr>
					<tbody id="milista"></tbody>
				</table>
			</div>
			<br>	
			<h2>Descripción de solicitud</a></h2>
			<br>	

			<?php echo $this->Form->input('description',array('label' => false,'type' => 'textarea','rows'=>'3','placeholder' => 'Por favor ingresa la razón por cual solicitas la importación, ya que la debe aprobar el área de gerencia')); ?>

			<?php if (!is_null($modal)): ?>
				<?php echo $this->Form->input('return_url',array('label' => false,'type' => 'hidden',"value" => $this->Html->url(array("controller" => "ProspectiveUsers", "action" =>"request_import_brands")))); ?>
			<?php endif ?>
			<div class="form-group">
				<label for="internacional">El pedido se hara nacional o internacional</label>
				<select name="internacional" class="form-control" id="internacional">
					<option value="0">Nacional (Uso en colombia)</option>
					<option value="1">Internacional (Uso por fuera de Colombia) </option>
				</select>
			</div>
			<button id="btn_guardar" class="mt-4" type="button">Crear solicitud</button>
		</form>
	</div>
</div>
<div class="popup3">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
	<div class="contentpopup">
		<img src="" class="img-quote" alt="">
	</div>
</div>
<div class="fondo"></div>

<script>
	var editProducts = <?php echo $editProducts ? "true" : "false"; ?>;
	var addProducts  = <?php echo $addProducts ? "true" : "false"; ?>;

	categoriesInfoFinal = <?php echo json_encode($categoriesInfoFinal); ?>;

	category1Select = null;
	category2Select = null;
	category3Select = null;
	category4Select = null;
</script>

<?php
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),							array('block' => 'jqueryApp'));
	echo $this->Html->script("lib/jquery.typeahead.js",											array('block' => 'fullCalendar'));
	echo $this->Html->script("controller/prospectiveUsers/add_import.js?".rand(),				array('block' => 'AppScript'));
?>

<?php 
	echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript'));
	echo $this->Html->script("controller/product/edit_products.js?".rand(),    array('block' => 'AppScript')); 

 ?>



<?php echo $this->element("comentario"); ?>