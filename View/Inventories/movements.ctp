<?php 
	echo $this->Html->css(array('lib/jquery.typeahead.css'),						array('block' => 'AppCss'));
?>
<?php 	
	$rolesPriceImport = array(
		Configure::read('variables.roles_usuarios.Logística'),
		Configure::read('variables.roles_usuarios.Gerente General')
	);
	$validRole = in_array(AuthComponent::user("role"), $rolesPriceImport) ? true : false;
?>
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12">
				<h2 class="titleviewer">Panel de movimientos de inventario de productos</h2>
				<a href="<?php echo $this->Html->url(array("controller" => "inventories", "action" => "blocks")) ?>" class="btn btn-primary float-right">
					Bloqueos actuales (<?php echo $totalBloqueo ?>)
				</a>
			</div>
		</div>
	</div>
	<div class="products index blockwhite">
		<div class="contenttableresponsive">
			<div class="row">
				<div class="col-md-12 mt-3 mb-3">
					<div class="form-group pl-1">
						<label for="optionMovement">Por favor seleccione el tipo de movimiento a realizar de forma masiva</label>
						<div class="row">
							<div class="col-md-2">
								Entrada
								<input type="radio" class="radioOptions" name="optionMovement" id="optionMovement" <?php echo isset($type) && $type == "EN" ? "checked" : "" ?> value="EN">
							</div>
							<div class="col-md-2">
								Salida
								<input type="radio" class="radioOptions" name="optionMovement" id="optionMovement" <?php echo isset($type) && $type == "RM" ? "checked" : "" ?> value="RM">
							</div>
							<div class="col-md-2">
								Traslado
								<input type="radio" class="radioOptions" name="optionMovement" id="optionMovement" <?php echo isset($type) && $type == "TR" ? "checked" : "" ?> value="TR">
							</div>
						</div>
					</div>
				</div>
				<?php if (isset($type)): ?>
					<?php if ($type == "RM"): ?>
						<div class="col-md-12">
							<div class="form-group mb-3">
								<label for="">
									Empaquetar todos los movimientos en 1
								</label>
								<select name="empaque" id="empaque">
									<option value="NO">NO</option>
									<option value="SI">SI</option>
								</select>
							</div>
							<div class="form-group mb-3">
								<label for="razonAll">
									Desea tener una razón general para todos los movimientos
								</label>
								<select name="razonAll" id="razonAll">
									<option value="">Seleccionar</option>
									<option value="NO">NO</option>
									<option value="SI">SI</option>
								</select>
							</div>
							<div class="form-group mb-3" id="razonGeneralDiv" style="display: none">
								<label for="razonGeneral">
									Razón general para todos los movimientos
								</label>
								<input type="text" id="razonGeneral" name="razonGeneral" class="form-control">
							</div>
						</div>
					<?php endif ?>
					<div class="col-md-12 panelMoves" id="<?php echo $type == "RM" ? "panelAllNone" : "" ?>" style="<?php echo $type == "RM" ? "display: none" : "" ?>">
						
						<h3 class="text-info">
							Por favor busque la referencia o el nombre de los productos a los que les desea hacer el/los movimiento(s) de 

							<?php switch ($type) {
								case 'EN':
									echo "Entrada";
									break;
								case 'RM':
									echo "Salida";
									break;
								case 'TR':
									echo "Traslado";
									break;
							} ?>
						</h3>
						
						<div class="typeahead__container">
					        <div class="typeahead__field">
					            <span class="typeahead__query">
					                <input class="js-typeahead" type="search" autofocus autocomplete="off" placeholder="Busca tu producto por nombre o referencia">
					            </span>
					        </div>
					    </div>
					</div>
					<div class="col-md-12 panelMoves" id="<?php echo $type == "RM" ? "panelAllNoneMoves" : "" ?>" style="<?php echo $type == "RM" ? "display: none" : "" ?>">
						<h3 class="text-info text-center">Movimientos a realizar</h3>
						<div class="table-responsive">
							<table class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>
											Imagen
										</th>
										<th>
											Producto
										</th>
										<th>
											Referencia
										</th>
										<th>
											Tipo de movimiento
										</th>
										<th>
											Bodegas afectadas
										</th>
										<th>
											Cantidad movimiento
										</th>
										<th>
											Razón del movimiento
										</th>
										<th>
											Acciones
										</th>
									</tr>
								</thead>
								<tbody id="productosMovimiento">
									
								</tbody>
							</table>
							
							<a href="#" class="btn btnGuardarMovimientos btn-success float-right">Guardar movimientos</a>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<!-- Modal -->
<div class="modal fade " id="modalMovimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Movimiento de inventario</h5>
      </div>
      <div class="modal-body" id="cuerpoMovimiento">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php if (isset($type)): ?>
	<script>
		var type_move = "<?php echo $type ?>";
	</script>
<?php endif ?>

<?php 
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));
	echo $this->Html->script(array('https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js?'.rand()),				array('block' => 'jqueryApp'));
	echo $this->Html->script("lib/jquery.typeahead.js",								array('block' => 'fullCalendar'));
	echo $this->Html->script("controller/inventories/movements.js?".rand(),				array('block' => 'AppScript'));
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

<style>
	.entrada,.salida,.traslado,.infoInventory{
		display: none;
	}
</style>

<?php 
  echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript')); ?>


