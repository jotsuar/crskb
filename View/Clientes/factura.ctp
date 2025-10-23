<div class="blockwhite mb-3 p-2">
<?php echo $this->element('vistaFacturaWo',["factValue" => $factValue]) ?>
<div class="col-md-12">
	<button class="btn btn-success btn-block" id="sendCommentPago" data-toggle="modal" data-target="#modalAprove">
		Enviar comentario y/o comprobante de pago
	</button>
</div>
</div>

<div class="modal fade " id="modalAprove" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Enviar mensaje a kebco</h5>
      </div>
      <div class="modal-body" id="cuerpoCotizacion">
      	<form action="#" method="post" id="formAprovee" enctype="multipart/form-data">      		
			<div class="form-group">
				<input type="hidden" name="email" value="<?php echo $user["User"]["email"] ?>">
				<input type="hidden" name="factura" value="<?php echo $factura ?>">
				<input type="hidden" name="cliente" value="<?php echo $factValue["datos_factura"]->Identificacion ?>- <?php echo $factValue["datos_factura"]->Cliente ?>">
				<label for="comentarioCotizacion">Por favor escriba un comentario </label>
				<textarea name="comentarioCotizacion" id="comentarioCotizacion" cols="30" rows="30" class="form-control" required=""></textarea>
			</div>
			<div class="form-group">
				<label for="archivoOrden">Por favor suba el comprobante de pago en caso de ya haber pagado (PDF) </label>
				<input type="file" id="archivoOrden" name="archivoOrden" class="form-control dropify" data-allowed-file-extensions = "pdf" data-max-file-size = "5M">
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-success float-right mt-3" value="Enviar cotización" >
			</div>
      	</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<?php
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
echo $this->Html->script("controller/clientes/login.js?".rand(),					array('block' => 'AppScript'));
?>

<style>
	.codeEmail,#btnIngreso,#validarCodigo{
		display:none;
	}
</style>