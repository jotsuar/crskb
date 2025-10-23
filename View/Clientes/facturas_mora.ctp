<div class="blockwhite mb-3 p-2">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Facturas en mora, cliente: <?php echo $data->Nombres_terceros ?> | <?php echo $data->Identificacion ?></h2>
	</div>
	<div class="col-md-12 mt-5">
		<div class="table-responsive">
			<table class="table table-hovered table-bordered">
				<thead>
					<tr style="background-color:#073473;color:#fff">
            <th class="text-center">Factura</th>
            <th class="text-center">Fecha Vencimiento</th>
            <th class="text-center">Valor Factura</th>
            <th class="text-center">Ver Factura</td>
          </tr>
				</thead>
				<tbody>
					<?php foreach ($data->details as $key => $value): ?>
						<tr>
              <td class="text-center">
                <?php echo $value->prefijo ?> <?php echo $value->DocumentoNúmero ?>
              </td>
              <td class="text-center">
                <?php echo date("Y-m-d",strtotime($value->Vencimiento)) ?>
              </td>
              <td class="text-center">$<?php echo number_format($value->Saldo) ?></td>
              <td class="text-center">
                <a href="<?php echo Router::url("/",true) ?>clientes/factura/<?php echo $this->Utilities->encryptString($value->prefijo."-".$value->DocumentoNúmero) ?>" class="btn btn-info" target="_blank" >Ver</a>
              </td>
             </tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<?php
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
?>

<style>
	.codeEmail,#btnIngreso,#validarCodigo{
		display:none;
	}
</style>