<?php 
$whitelist = array(
	'127.0.0.1',
	'::1'
); ?>
<?php echo $this->Html->css(array('lib/jquery.typeahead.css'), array('block' => 'AppCss'));?>
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

<div class="col-md-12 p-0">
	<div class="blockwhitearriba">
		<div class="row">
			<div class="col-md-12 p-0 text-center">
				<div class="pull-right configotherdataquote">
					<span>Configuración</span> <i class="fa fa-cog fa-spin fa-2x fa-fw"></i>
					<div class="opcionesadicionales">
						<h2 class="">PLANTILLAS (PRODUCTOS)</h2>
						<div id="plantillas">
							<?php echo $this->Form->input('template',array('type' => 'select','label'=>false,'options' => $plantillas,'empty' => 'Elige una plantilla...')); ?>
						</div>
						<hr>
						<h2 class="">Cambiar pais actual del flujo</h2>
						<div class="form-check form-check-inline float-right monedaselect">
							<?php echo $this->Form->input('pais',array("type" => "select", "options" => Configure::read("PAISES"), "label" => false, "div" => false, "value" => $datos["ProspectiveUser"]["country"], "id" => "PaisFlujo", "data-flujo" => $datos["ProspectiveUser"]["id"] )) ?>
						</div>
					</div>
				</div>

				<h1 class="nameview">
					PANEL DE CREACIÓN DE COTIZACIONES - 
					<small>FLUJO 
						<span>
							<a target="_blank" href="<?php echo Router::url('/', true).'prospectiveUsers/index?q='.$datos['ProspectiveUser']['id'] ?>">
								<?php echo $datos['ProspectiveUser']['id'] ?>
							</a>
						</span>
					</small>
				</h1>
			</div>
		</div>
	</div>


	<div class="col-md-12 mb-3 p-0 blockdatauserblue">
		<div class="blockwhiteabajo">
			<div class="contentdataclient">
				<h3>
					<p>Le estás cotizando a</p>
					<?php echo mb_strtoupper($this->Utilities->name_prospective($datos['ProspectiveUser']['id'])); ?>
					<?php if ($datos['ProspectiveUser']['contacs_users_id'] > 0){ ?>
						<span>de <?php echo $this->Utilities->name_bussines($datosC['ContacsUser']['clients_legals_id']); ?></span>
					<?php } ?>
				</h3>
			</div>
		</div>
	</div>


</div>

	<div class="col-md-12 mb-3 p-0">
		<div class="row">
			<div class="col-md-4">
				<h3 class="titlesectionquote">POR FAVOR SELECCIONA LA PLANTILLA DE LA COTIZACIÓN</h3>
				<div class="blockwhiteabajo">
					<div class="bannerprincipal">
						<div class="condiciones-block">
							<form id="form_header">
								<div id="headers">
									<?php if ($datos["ProspectiveUser"]["country"] == "Colombia"): ?>
										<?php unset($headers[3]); ?>
										<?php echo $this->Form->input('headers', array('type' => 'select','name' => 'inlineRadioOptionsHeaders','div' => false,'label' => false,'legend' => false,'options' => $headers,'hiddenField' => false, "required" => true
										));?>
									<?php else: ?>
											<?php 
												unset($headers[1]);
												unset($headers[2]);
												unset($headers[4]);
												unset($headers[5]);
												unset($headers[6]);
												unset($headers[7]);
												unset($headers[8]);
												unset($headers[9]);
												unset($headers[10]);
											 ?>
											<?php echo $this->Form->input('headers', array('type' => 'select','name' => 'inlineRadioOptionsHeaders','div' => false,'label' => false,'legend' => false,'options' => $headers,'hiddenField' => false,  "required" => true, "value" => 3
										));?>
									<?php endif ?>
								</div>
								<div class="mt-2" id="formCurrency" <?php echo $datos['ProspectiveUser']['country'] != "Colombia" ? 'style="display:none"' : '' ?>>
									<?php 
										$optionsMoney = [ "0" => "No seleccionar una moneda" ];
										$value = "0";
									 	$optionsMoney["COP"] = "PESOS";
									 	$optionsMoney["USD"] = "DOLARES";

									 	if(isset($this->request->query["money"]) && $this->request->query["money"] != "0"){
									 		$value = $this->request->query["money"];
									 	}
										echo $this->Form->input('money_format', array('type' => 'select','name' => 'optionsMoney','div' => false,'label' => "Usar moneda general",'legend' => false,'options' => $optionsMoney,'hiddenField' => false,  "required" => true, "value" => $value
										));
									 ?>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-8">
				<h3 class="titlesectionquote">¿DESEAS TRAER UNA COTIZACIÓN DE OTRO FLUJO?</h3>
				<div class="blockwhiteabajo">
					<div class="row">



						<div class="col-md-5">
							<div class="groupoidflow">
								<input class="form-control" id="txt_buscar_flujo" placeholder="Ingresa el ID del flujo">
								<button id="btn_buscar_flujo">Buscar</button>
							</div>
						</div>
						<div class="col-md-7">
							<div class="row">
								<div class="col-lg-6 col-md-6">
									<?php echo $this->Form->input('cotizaciones_flujo',array('type' => 'select','label' => false, 'options' => array(),'empty' => 'Cotizaciones traidas del flujo que buscaste'));?>
								</div>
								<div class="col-lg-6 col-md-6">
									<?php echo $this->Form->input('cotizaciones_creadas',array('label' => false, 'options' => $cotizacionesOption,'empty' => 'Cotizaciones creadas en este flujo'));?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
<?php echo $this->Form->create('Quotation',array('data-parsley-validate'=>true,'id' => 'form_quotations','name'=>'formulario1','class' => "col-md-12")); ?>

	<div class="col-md-12 mb-3 p-0">
		<div class="blockwhite">
				<?php echo $this->Form->hidden('etapa_id',array('value' => $flujo_id,'id' => 'etapa_id')); ?>
				<?php echo $this->Form->hidden('prospective_users_id',array('value' => $prospective_users_id, 'id' => 'prospective_users_id')); ?>
				<?php echo $this->Form->hidden('cliente_id',array('value' => isset($datosC["ClientsLegal"]) ? $datosC["ClientsLegal"]["id"] : $datosC["ClientsNatural"]["id"] , 'id' => 'cliente_cotiza_id')); ?>
				<?php echo $this->Form->hidden('cliente_type',array('value' => isset($datosC["ClientsLegal"]) ? "legal" : "natural" , 'id' => 'cliente_cotiza_type')); ?>
				
				<h3 class="row mb-4">
					<!-- <b>Nombre de la Cotización</b> -->

					<?php if (AuthComponent::user("id") != $datos["ProspectiveUser"]["user_id"] && ( AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Logística"  ) ): ?>
						<?php echo $this->Form->input('user_cotiza',array('label' => 'Usuario de la Cotización','div'=>'col-12 mb-3','class'=>'form-control','value' => AuthComponent::user("id") , 'id' => 'user_cotiza',"options" => [
							$datos["User"]["id"] => $datos["User"]["name"], AuthComponent::user("id") => AuthComponent::user("name")
						] )); ?>
					<?php else: ?>
						<?php echo $this->Form->hidden('user_cotiza',array('value' => AuthComponent::user("id") , 'id' => 'user_cotiza')); ?>
					<?php endif ?>

					<?php echo $this->Form->input('name',array('label' => 'Nombre de la Cotización','div'=>'col','class'=>'form-control','placeholder' => 'Escribe un asunto para identificar esta cotización')); ?>
					<?php if ($datos["User"]["role"] == "Gerente General" || AuthComponent::user("role") == "Logística" || AuthComponent::user("role") == "Gerente General" ): ?>
						<?php echo $this->Form->input('show',array("label"=>"Mostrar valores unitarios",'div' => 'col', "options"=>["1"=>"SI","0"=>"NO"])); ?>
						<?php //echo $this->Form->input('design',array("label"=>"Diseño de cotización",'div' => 'col', "options"=>["1"=>"Diseño normal","2"=>"Nuevo diseño"])); ?>
						<?php echo $this->Form->hidden('design',array("value"=>1)); ?>
						<?php echo $this->Form->input('type',array("label"=>"Tipo de cotización",'div' => 'col', "options"=>["1"=>"Normal","0"=>"Sin restricciones"])); ?>
						<?php echo $this->Form->input('show_ship',array("label"=>"Mostrar flete",'div' => 'col', "options"=>["1"=>"SI","0"=>"No"],"default"=>0)); ?>
					<?php else: ?>
						<?php echo $this->Form->hidden('show',array("value"=>1)); ?>
						<?php echo $this->Form->hidden('design',array("value"=>1)); ?>
						<?php echo $this->Form->hidden('type',array("value"=>1)); ?>

						<?php if (AuthComponent::user("id") == 7): ?>
							<?php echo $this->Form->input('show_ship',array("label"=>"Mostrar flete",'div' => 'col', "options"=>["1"=>"SI","0"=>"No"],"default"=>0)); ?>
						<?php else: ?>
							<?php echo $this->Form->hidden('show_ship',array("value"=>1)); ?>
						<?php endif ?>
						
					<?php endif ?>
					
				</h3>
				<h3 class="mb-4">
					<label>Mensaje al cliente</label> <br>
					<select name="noticeSelectData" id="noticeSelectData" class="d-inline w-50 form-control">
						<option value="">Seleccione una nota</option>
						<?php $num = 1; ?>
						<?php foreach ($notices as $key => $value): ?>
							<option value="<?php echo $key ?>"> <?php echo $num; $num++; ?>. <?php echo $value ?></option>
						<?php endforeach ?>
					</select>
					<?php if (count($notices) <= 20): ?>						
						<a href="javascript:void(0)" class="btn btn-success addNotice btn-sm"><i class="fa vtc fa-plus"></i> Agregar nota</a> 
					<?php endif ?>
				</h3>
				<?php echo $this->Form->input('customer_note',array('label' => false,'placeholder' => 'Mensaje adicional para el cliente')); ?>
		</div>
	</div>

	<!-- cuando es de servicio técnico -->
	<?php if ($datos['ProspectiveUser']['type'] > 0): ?>
		<div class="col-md-12 mb-3 p-0">
			<h3 class="titlesectionquote">RELACIÓN DE EQUIPOS DEL CLIENTE EN SERVICIO TÉCNICO</h3>
			<div class="blockwhiteabajo">
				<div class="producstorderst">
					<table cellpadding="0" cellspacing="0" class="table-striped table-bordered tableproductsst">
						<tr class="text-center">
							<th colspan="6">PRODUCTO DEL CLIENTE - ORDEN DE SERVICIO <b><?php echo $this->Utilities->consult_cod_service($datos['ProspectiveUser']['type']) ?></b></th>
						</tr>
						<tr class="titles-tablest">
							<td>Equipo</td>
							<td>Número de parte</td>
							<td>Serie</td>
							<td>Serial</td>
							<td>Marca</td>
						</tr>
						<?php foreach ($productClient as $valueP): ?>
							<tr>
								<td><?php echo $valueP['ProductTechnical']['equipment'] ?></td>
								<td><?php echo $valueP['ProductTechnical']['part_number'] ?></td>
								<td><?php echo $valueP['ProductTechnical']['serial_number'] ?></td>
								<td><?php echo $this->Utilities->data_null($valueP['ProductTechnical']['serial_garantia']) ?></td>
								<td><?php echo $valueP['ProductTechnical']['brand'] ?></td>
							</tr>
						<?php endforeach ?>
					</table>
				</div>
			</div>
		</div>
	<?php endif ?>


	<div class="col-md-12 mb-3 p-0">
		<h3 class="titlesectionquote">
			SELECCIONA LOS PRODUCTOS QUE DESEAS COTIZAR

		</h3>
		<div class="blockwhiteabajo">
			<h2>Registrar producto <a id="btn_registrar_products" data-toggle="tooltip" data-placement="right" title="Crear producto"><i class="fa fa-plus-square"></i></a></h2>
			<div class="typeahead__container">
				<div class="typeahead__field">
					<span class="typeahead__query">
						<input class="js-typeahead" type="search" autofocus autocomplete="off" placeholder="Busca tu producto por nombre o referencia">
					</span>
				</div>
			</div>

			<h2>
				Productos añadidos a la cotización
				<div class="d-inline input-group-prepend ml-5" id="htmlOtrasCotizaciones">
			  	</div>
			</h2>

			<div id="contentproductquote">
				<table class="table table-striped table-bordered table-hover" id="details-country">
					<tr class="titletablequote">
						<td>Foto</td>
						<td class="size4">Nombre</td>
						<td class="size5">Descripción</td>
						<td>Moneda</td>
						<td>IVA</td>
						<td>Ref.</td>
						<td>Entrega</td>
						<td>Marca</td>
						<td class="size4">Inventario actual</td>
						<td>Precio</td>
						<td>Cant.</td>
						<td>Subtotal</td>
						<td>Acción</td>
					</tr>
					<tbody id="milista"></tbody>
				</table>
			</div>
			<div class="row">
				<div class="col-md-12 calculatebox">
					<span>
						<?php
						echo $this->Form->hidden('notes');
						echo $this->Form->hidden('notes_description');
						echo $this->Form->hidden('conditions');
						echo $this->Form->hidden('currency_data', ["value" => isset($this->request->query["money"]) && !empty($this->request->query["money"]) ? "money_".$this->request->query["money"] : "" ] );
						echo $this->Form->hidden('header_id');
						
						?>
					</span>

					<div class="form-group row">
						<label for="totalCalculado" class="col-md-1 col-lg-1">Descuento</label>
						<?php echo $this->Form->input('descuento',array('id'=>'totalDescuento','readonly' => false,'value' => '0','class' => 'form-control col-md-4 col-lg-4',"label" => false,"div" => false,"min"=>0, "type" => "number", "step" => "0.01",'max' => 3 ));  ?>
						<label for="totalCalculado" class="col-md-1 col-lg-1">Valor total</label>
						<?php echo $this->Form->input('total',array('id'=>'totalCalculado','readonly' => true,'value' => '0','class' => 'form-control col-md-3 col-lg-3',"label" => false,"div" => false));  ?>
						<button id="btn_total_cotizacion" class="col-md-2 col-lg-2 ml-md-5 ml-lg-5" type="button">Actualizar total</button>
					</div>

					
				</div>
				<div class="col-md-12 text-center">
					<br>
					<span class="errorUsd text-danger text-uppercase font-italic parpadea" style="display: none">
						<b>Alerta: </b> ¿Es correcto que haya productos cotizados en USD que superen los 50.000 USD?, revisa muy bien.
					</span>
				</div>
				<div class="col-md-12 text-center">
					<br>
					<div class="errorMargenReason w-50 m-auto form-group text-danger text-uppercase font-italic " style="display: none">
						<label id="razonMenorLb" for="razonMenor" style="color: red !important;">Escribe la razón por la que debe ser aprobada esta cotización <br>
							<small>Esta será tenida en cuenta al momento de la aprobación. (Margen, moneda, o pesos) </small></label>
						<br>
						<label id="razonrazonBoquilla" style="color: red !important;">

							Debido a que estas cotizando un valor superior a 10.000.000 COP es necesario que respondas las siguientes preguntas adicionalmente a la nota
							<br>
							<small>Esta será tenida en cuenta al momento de la aprobación.</small></label>

					<!-- 	<?php echo $this->Form->input("es_cliente",["label" => "¿Es cliente final?","class" => "form-control notas10","div" => "center_element col-md-5 form-group",  "options" =>  ["SI"=>"SI","NO"=>"NO"], "empty" => "seleccionar" ]) ?>
						<?php echo $this->Form->input("competencia",["label" => "¿Actualmente se compite con otra empresa?","class" => "form-control notas10", "div" => "center_element col-md-5 form-group", "options" =>  ["SI"=>"SI","NO"=>"NO"], "empty" => "seleccionar" ]) ?>

						<?php echo $this->Form->input("ya_cotizado",["label" => "¿Ya se ha cotizado antes a este cliente?","class" => "form-control notas10", "div" => "center_element col-md-5 form-group", "options" =>  ["SI"=>"SI","NO"=>"NO"], "empty" => "seleccionar" ]) ?>

						<?php echo $this->Form->input("tipo_compra",["label" => "¿La compra será licitación o compra directa?","class" => "form-control notas10", "div" => "center_element col-md-5 form-group", "options" =>  ["Licitación"=>"Licitación","Compra directa"=>"Compra directa"], "empty" => "seleccionar" ]) ?>
						
						<?php echo $this->Form->input("fecha_comptra",["label" => "¿Cuando tienen pensado realizar la compra?","class" => "form-control notas10", "div" => "center_element col-md-5 form-group" ]) ?> -->

						<?php echo $this->Form->input("reason",["label" => "Nota","id"=>"razonMenor", "class" => "form-control", "div" => "center_element col-md-12 form-group"]) ?>
						<?php echo $this->Form->input("comision_completa",["label" => "Solicitar pago de comisión completa","id"=>"comision_completa", "class" => "form-control", "div" => "center_element col-md-12 form-group","options"=>["0"=>"NO","1"=>"SI"]]) ?>

					</div>
				</div>
			</div>
			<br>
			<label class="labeltotaliza">¿Deseas totalizar la cotización?</label>
			<div class="text-center">
				<div class="form-check form-check-inline">
					<label class="form-check-label">
						<input class="form-check-input" type="radio" name="radio_option" id="inlineRadio1" value="1" checked> SI
					</label>
				</div>
				<div class="form-check form-check-inline">
					<label class="form-check-label">
						<input class="form-check-input" type="radio" name="radio_option" id="inlineRadio2" value="0" > NO
					</label>
				</div>
			</div>
			<br>
			<label class="labeltotaliza">¿Deseas detallar el iva?</label>
			<div class="text-center">
				<div class="form-check form-check-inline">
					<label class="form-check-label">
						<input class="form-check-input" type="radio" name="radio_option_iva" id="inlineRadioIva1" value="1" checked> SI
					</label>
				</div>
				<div class="form-check form-check-inline">
					<label class="form-check-label">
						<input class="form-check-input" type="radio" name="radio_option_iva" id="inlineRadioIva2" value="0" > NO
					</label>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-12 mb-3 p-0 selectallbox">
		<div class="row">
			<div class="col-md-4 mb-3">
				<h3 class="titlesectionquote">NOTAS PREVIAS <a href="javascript:void(0)" class="limpiarIntro pull-right" data-intro="introtext">Borrar nota</a></h3>
				<div class="blockwhiteabajo col-md-12">
					<div id="notasprevias">
						<select name="notasPreviasInput" id="notasPreviasInput">
							<option value="">Seleccionar nota previa</option>
							<?php foreach ($notas_previas as $key => $value): ?>
								<option value="<?php echo $value["Note"]["id"] ?>">
									<?php echo $value["Note"]["name"] ?>
								</option>
							<?php endforeach ?>
						</select>
					</div>

					<p id="introtext"></p>
				</div>
			</div>

			<div class="col-md-4 mb-3">
				<h3 class="titlesectionquote">NOTAS DESCRIPTIVAS <a href="javascript:void(0)" class="limpiarIntro pull-right" data-intro="introtext2">Borrar nota</a></h3>
				<div class="blockwhiteabajo col-md-12">
					<div id="notasdescriptivas">
						<select name="notasDescriptivasInput" id="notasDescriptivasInput">
							<option value="">Seleccionar nota descriptiva</option>
							<?php foreach ($notas_descriptivas as $key => $value): ?>
								<option value="<?php echo $value["Note"]["id"] ?>">
									<?php echo $value["Note"]["name"] ?>
								</option>
							<?php endforeach ?>
						</select>
					</div>				
					<p id="introtext2" class=""></p>
				</div>
			</div>

			<div class="col-md-4 mb-3">
				<h3 class="titlesectionquote">CONDICIONES COMERCIALES</h3>
				<div class="blockwhiteabajo col-md-12">
					<div id="condiciones">
						<select name="condicionesInput" id="condicionesInput">
							<option value="">Seleccionar condición de pago</option>
							<?php foreach ($formas_pago as $key => $value): ?>
								<option value="<?php echo $value["Note"]["id"] ?>">
									<?php echo $value["Note"]["name"] ?>
								</option>
							<?php endforeach ?>
						</select>
					</div>
					<ul class="condiciones_negociacion"></ul>
					<!-- <div class="bannequotationfooter"></div> -->
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-12 text-right mb-5">
		<div class="submit"><a id="btn_borrador" class="col-lg-2 col-md-2 col-sm-12 text-center">Guardar borrador</a></div>
		<input type="submit" class="btn btn-primary col-lg-2 col-md-2 col-sm-12 mt-xs-2" value="Previsualizar cotización">
	</div>

</form>


<div class="popup3">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
	<div class="contentpopup">
		<img src="" class="img-quote" alt="">
	</div>
</div>
<div class="fondo"></div>

<div class="modal fade" id="modalSugestions" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Sugerencia de cotización</h5>
      </div>
      <div class="modal-body" id="bodySuggestion">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalNotices" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Crear notas</h5>
      </div>
      <div class="modal-body" id="bodyNotices">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>

	const FLUJO_ACTUAL = '<?php echo $datos["ProspectiveUser"]["id"]; ?>';

	bloqueo = parseInt(<?php echo $bloqueo ?>);
	requestChange = parseInt(<?php echo $requestChange ?>);
	addProduct = <?php echo $addProduct ? "true" : "false" ?>;
	editProduct = <?php echo $editProduct ? "true" : "false" ?>;
	categoriesInfoFinal = <?php echo json_encode($categoriesInfoFinal); ?>;
	pricesAbrasivo = <?php echo json_encode($pricesAbrasivo); ?>;
	var country = "<?php echo $datos['ProspectiveUser']['country'] ?>";

	category1Select = null;
	category2Select = null;
	category3Select = null;
	category4Select = null;
</script>
<?php //echo $this->element("categories_select", array("categorias" => $categoriesSelect)); ?>
<?php echo $this->Html->css('jquery.flexdatalist.min.css') ?>
<?php 
echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));
echo $this->Html->script(array('https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js?'.rand()),				array('block' => 'jqueryApp'));
echo $this->Html->script("lib/jquery.typeahead.js",								array('block' => 'fullCalendar'));
echo $this->Html->script("jquery.flexdatalist.min.js?".rand(),						array('block' => 'AppScript'));
echo $this->Html->script("controller/quotations/add.js?".time(),				array('block' => 'AppScript'));
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

<script>
	var actual_uri = "<?php echo Router::reverse($this->request, true) ?>";
	var actual_url2 = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ?>?";
</script>



<?php echo $this->element("comentario"); ?>

<?php 
	echo $this->Html->script("jquery.flexdatalist.min.js?".rand(),						array('block' => 'AppScript'));
	echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript'));
	echo $this->Html->script("controller/product/edit_products.js?".rand(),    array('block' => 'AppScript')); 

 ?>

 <?php if (AuthComponent::user("role") == "Asesor Externo"): ?>
	<style>
		.fila1block,.suggestions_2,#htmlOtrasCotizaciones,.requestEditProduct{
			display: none !important;
		}
	</style>
<?php endif ?>

<style>
	.text-danger>h2{
		color: red !important;
	}
	#razonrazonBoquilla,#razonMenorLb{
		display: none;
	}
</style>

<?php echo $this->element("flujoModal",["aprobar" => false]); ?>