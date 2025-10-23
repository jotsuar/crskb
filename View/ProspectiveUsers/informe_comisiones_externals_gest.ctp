<div class="col-md-12">
  <div class=" widget-panel widget-style-2 bg-verde big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Tesorería <?php echo AuthComponent::user("role") != "Asesor Externo" ? "Asesores Externos" : "" ?> </h2>
  </div>
  <div class=" blockwhite spacebtn20">
    <div class="row ">
      <div class="col-md-12 text-center">
        <h1 class="nameview">Cuentras de cobro generadas</h1>
      </div>
    </div>
  </div>

  <div class=" blockwhite spacebtn20">
    <div class="row">
      <div class="col-md-12">
        <h2 class="mb-2">INFORMES DE TESORERÍA</h2>
        <ul class="subpagos-box2">
          <li>
            <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>AuthComponent::user("role") != "Asesor Externo" ? 'informe_ventas_externos':'informe_ventas')) ?>"><b>1-</b> Informe de ventas</a>
          </li> 
          <li >
            <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>"informe_comisiones_externas")) ?>"><b>2-</b>Informe de Comisiones ganadas sin pagar</a>
          </li> 
          <li class="activesub">
            <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_comisiones_externals_gest')) ?>"><b>3-</b> Informe de Comisiones gestionadas y/o pagadas</a>
          </li>       
        </ul>
      </div>
    </div>
  </div>
  <?php if (in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])): ?>
  <div class="blockwhite spacebtn20">
    <div class="row">
      <div class="col-md-4">
        <h1 class="nameview2">INFORME DE COMISIONES SIN PAGAR</h1>
        <span class="subname">Informe de comisiones teniendo en cuenta las facturas y los recibos de caja no pagados para exportar excel</span>
      </div>
      <div class="col-md-8">
        <div class="row">
          <?php echo $this->Form->create('ProspectiveUser',array('class' => 'form w-100',"url"=>["controller"=>"Accounts","action"=>"report"])); ?>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <span>Seleccionar rango de fechas:</span>
                </div>
                <div class="form-group">
                  <input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <?php echo $this->Form->input('user_id',array("required" => false, "label" => "Seleccionar usuario" ,"options" => $users, "empty" => "Todos los usuarios","value" => $user_id)); ?>
                  <?php echo $this->Form->hidden('state',array("required" => false, "label" => "Seleccionar usuario","value" => 1)); ?>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <?php echo $this->Form->input('excel',array("required" => true, "label" => "Generar Informe Excel" ,"options" => ["1" => "SI"] )); ?>
                </div>
              </div>
              <div class="col-md-2 pt-4">
                <input type="date" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none" name="fechaIni">
                <input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none" name="fechaEnd">
                <button type="submit" class="btn btn-success pull-right btn-block" id="btn_find_adviser">Generar informe en excel</button>
              </div>
            </div>
            
            
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php endif ?>
</div>

<div class="col-md-12">
  <div class=" blockwhite spacebtn20">
    <div class="table-responsive">
      <table cellpadding="0" cellspacing="0" class="myTable table-striped table-bordered">
        <thead>
          <tr>
              <th><?php echo $this->Paginator->sort('id',"Código"); ?></th>
              <th><?php echo $this->Paginator->sort('date_send',"Fecha de envió"); ?></th>
              <th><?php echo $this->Paginator->sort('initial_value',"Valor solicitado"); ?></th>
              <?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
                <th><?php echo $this->Paginator->sort('user_id', "Asesor"); ?></th>                
              <?php endif ?>
              <th><?php echo $this->Paginator->sort('date_gest',"Fecha de gestión"); ?></th>
              <th><?php echo $this->Paginator->sort('date_deadline',"Posible fecha de pago"); ?></th>
              <th><?php echo $this->Paginator->sort('date_payment',"Fecha de pago"); ?></th>
              <th><?php echo $this->Paginator->sort('value_payment',"Valor pagado"); ?></th>
              <th><?php echo $this->Paginator->sort('document',"Evidencia de pago"); ?></th>
              <th><?php echo $this->Paginator->sort('notes',"Notas adicionales"); ?></th>
              <th><?php echo $this->Paginator->sort('state',"Estado"); ?></th>
              <th class="actions"><?php echo __('Acciones'); ?></th>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($accounts as $account): ?>
          <tr>
            <td>CBKEB #<?php echo h($account['Account']['id']); ?>&nbsp;</td>
            <td><?php echo h($account['Account']['date_send']); ?>&nbsp;</td>
            <td>$<?php echo number_format($account['Account']['initial_value']); ?>&nbsp;</td>
            <?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
              <td>
                <?php echo $account['User']['name']; ?>
              </td>
            <?php endif ?>
            <td><?php echo h($account['Account']['date_gest']); ?>&nbsp;</td>
            <td><?php echo h($account['Account']['date_deadline']); ?>&nbsp;</td>
            <td><?php echo h($account['Account']['date_payment']); ?>&nbsp;</td>
            <td>$<?php echo number_format($account['Account']['value_payment']); ?>&nbsp;</td>
            <td>
              <?php if (!empty($account['Account']['document'])): ?>
                <a href="javascript:void(0)" class="Comprobanteacep btn btn-info">
                  Ver imagen
                  <img datacomprobante="<?php echo $this->Html->url('/img/accounts/'.$account['Account']['document']) ?>" title="CBKEB #<?php echo $account["Account"]["id"] ?>" src="<?php echo $this->Html->url('/img/accounts/'.$account['Account']['document']) ?>" width="100px" height="100px" class="d-none">
              </a>
              <?php endif ?>
            </td>
            <td><?php echo h($account['Account']['notes']); ?>&nbsp;</td>
            <td>
              <?php $estados = ["0" => "Solicitada", "1" => "En gestión", "2" => "Pagada", "3" => "Rechazada" ]; ?>
                <?php echo $estados[$account['Account']['state']] ?>
              &nbsp;
            </td>
            <td class="actions">
                <a class="btn btn-outline-primary" href="<?php echo $this->Html->url(array('action' => 'informe_comisiones_externals_view', $this->Utilities->encryptString($account['Account']['id']))) ?>" data-placement="top" data-toggle="tooltip" title="Ver detalle">
                  <i class="fa fa-fw fa-eye vtc"></i>
                </a>
                <?php if (AuthComponent::user("role") != "Asesor Externo" ): ?>
                  <?php if ($account["Account"]["state"] < 2 ): ?>                
                    <a href="" class="btn btn-danger float-right rechazarCuenta" data-placement="top" data-toggle="tooltip" title="Rechazar cuenta de cobro" data-id="<?php echo $account["Account"]["id"] ?>">
                      <i class="fa fa-times vtc"></i>
                    </a>
                    <a href="" class="btn btn-success float-right gestionarCuenta" data-placement="top" data-toggle="tooltip" title="Gestionar cuenta" data-id="<?php echo $account["Account"]["id"] ?>">
                      <i class="fa fa-check vtc"></i>
                    </a>
                  <?php endif ?>
                <?php endif ?>
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

<div class="modal fade" id="modalFacturaDetalle" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Detalle factura </h2>
      </div>
      <div class="modal-body" id="bodyDetalleFactura">
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>

<div class="modal fade" id="modalCambioEstado" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Cambio de estado de gestión</h2>
      </div>
      <div class="modal-body" id="bodyCambioEstado">
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/index.js?".rand(),			array('block' => 'AppScript')); 
	echo $this->Html->script("controller/prospectiveUsers/externos.js?".rand(),			array('block' => 'AppScript')); 
?>

<div class="popup2">
  <span class="cierra"> <i class="fa fa-remove"></i> </span>
    <div class="contentpopup">
      <img src="" class="img-product" alt="">
    </div>
  </div>
<div class="fondo"></div>

<?php echo $this->element("picker"); ?>