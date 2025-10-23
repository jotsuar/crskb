<div class="col-md-12">
  <div class=" widget-panel widget-style-2 bg-verde big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Tesorería</h2>
  </div>
  <div class=" blockwhite spacebtn20">
    <div class="row ">
      <div class="col-md-12 text-center">
        <h1 class="nameview">
          DETALLE CUENTA DE COBRO GENERADA
          <a href="<?php echo $this->Html->url(["action"=>"informe_comisiones_externals_gest"]) ?>" class="btn btn-info"><i class="fa vtc fa-arrow-left"></i> Volver</a>
        </h1> 
      </div>
    </div>
  </div>

  <div class=" blockwhite spacebtn20">
    <div class="row">
      <div class="col-md-12">
        <h2 class="mb-2">INFORMES DE TESORERÍA</h2>
        <ul class="subpagos-box2">
          <li>
            <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_ventas')) ?>"><b>1-</b> Informe de ventas</a>
          </li> 
          <li >
            <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_comisiones_externals')) ?>"><b>2-</b> Informe de Comisiones ganadas sin pagar</a>
          </li> 
          <li>
            <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_comisiones_externals_gest')) ?>"><b>3-</b> Informe de Comisiones gestionadas y/o pagadas</a>
          </li>       
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="col-md-12">
  <div class="row">
    <div class="col-md-4">
      <div class="blockwhite spacebtn20">
        <div class="row">
          <div class="col-md-6">
            <h2 class="p-2 mb-2">Datos de la gestión</h2>            
          </div>
          <div class="col-md-6">
            <?php if (AuthComponent::user("role") != "Asesor Externo" ): ?>
              <?php if ($account["Account"]["state"] < 2 ): ?>                
                <a href="" class="btn btn-danger float-right rechazarCuenta" data-placement="top" data-toggle="tooltip" title="Rechazar cuenta de cobro" data-id="<?php echo $account["Account"]["id"] ?>">
                  <i class="fa fa-times vtc"></i>
                </a>
                <a href="" class="btn btn-success float-right gestionarCuenta" data-placement="top" data-toggle="tooltip" title="Gestionar cueta" data-id="<?php echo $account["Account"]["id"] ?>">
                  <i class="fa fa-check vtc"></i>
                </a>
              <?php endif ?>
            <?php endif ?>
          </div>
        </div>
        
        <table class="table table-bordered">
          <tbody>
            <tr>
              <th>Estado actual cuenta de cobro</th>
              <td>
                <?php $estados = ["0" => "Solicitada", "1" => "En gestión", "2" => "Pagada", "3" => "Rechazada" ]; ?>
                <?php echo $estados[$account['Account']['state']] ?>
              </td>
            </tr>
            <tr>
              <th>Fecha de generación </th>
              <td>
                <?php echo $account['Account']['date_send'] ?>
              </td>
            </tr>
            <tr>
              <th>Valor inicial solicitdado </th>
              <td>
                <?php echo number_format($account['Account']['initial_value']) ?>
              </td>
            </tr>
            <tr>
              <th>Asesor solicita</th>
              <td>
                <?php echo $account['User']['name'] ?> - <?php echo $account['User']['identification'] ?>
              </td>
            </tr>
            <tr>
              <th>Fecha de gestión inicial</th>
              <td>
                <?php echo $account['Account']['date_gest'] ?>
              </td>
            </tr>
            <tr>
              <th>Posible fecha de pago</th>
              <td>
                <?php echo $account['Account']['date_deadline'] ?>
              </td>
            </tr>
            <tr>
              <th>Fecha de pago</th>
              <td>
                <?php echo $account['Account']['date_payment'] ?>
              </td>
            </tr>
            <tr>
              <th>Valor de pago</th>
              <td>
                <?php echo number_format($account['Account']['value_payment']) ?>
              </td>
            </tr>
            <tr>
              <th>Evidencia del pago</th>
              <td>
                <a href="javascript:void(0)" class="Comprobanteacep">
                  
                <img datacomprobante="<?php echo $this->Html->url('/img/accounts/'.$account['Account']['document']) ?>" title="CBKEB #<?php echo $account["Account"]["id"] ?>" src="<?php echo $this->Html->url('/img/accounts/'.$account['Account']['document']) ?>" width="100px" height="100px" class=>
                </a>
              </td>
            </tr>
            <tr>
              <th>Notas adicionales</th>
              <td>
                <?php echo $account['Account']['notes'] ?>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-md-8">
      
      <!--  -->

        <div class="containerCRM blockwhite spacebtn20">
      <div class="row">
        <div class="col-md-6 pl-5">
          <img src="/CRM/files/logoProveedor.png" alt="Logo Proveedor" class="img-fluid mb-2 w-75">

          <h2 class="text-mobile"><b> Linea Gratuita  018000 425700</b></h2>
        </div>
        <div class="col-md-6">
          <h3 class="strongtittle spacetop text-mobile">KEBCO S.A.S.</h3>
          <h3 class="strongtittle text-mobile">900412283 - 0</h3>
          <h3 class="text-mobile"><b>CALLE 10 # 52A - 18 INT 104</b></h3>
          <h3 class="text-mobile"><b>PBX (4) 448 5566</b></h3>
        </div>
        <div class="col-md-12 mt-4 table-responsive">
          <table class="table table-bordered" id="orderproveedor">
            <tbody>
              <tr class="text-center">
                <th>Fecha: </th>
                <td>
                  <?php echo date("Y-m-d") ?>
                </td>
              </tr>
              <tr class="text-center">
                <th>Código cuenta de cobro: </th>
                <td>CBKEB #<?php echo $account["Account"]["id"] ?></td>
              </tr>
              <tr class="text-center">
                <th>Banco </th>
                <td> <?php echo empty(AuthComponent::user("bank")) ? "NO SE ESPECIFICA BANCO" : AuthComponent::user("bank") ?> </td>
              </tr>
              <tr class="text-center">
                <th>Número de cuenta</th>
                <td><?php echo empty(AuthComponent::user("account_number")) ? "NO SE ESPECIFICA NÚMERO DE CUENTA" : AuthComponent::user("account_number") ?></td>
              </tr>
              <tr class="text-center">
                <th>Tipo de cuenta</th>
                <td><?php echo empty(AuthComponent::user("account_type")) ? "NO SE ESPECIFICA TIPO DE CUENTA" : AuthComponent::user("account_type") ?></td>
              </tr>
            </tbody>
          </table>
          <h2 class="text-center my-2"> Detalle de comisiones </h2>
          <table class="table table-bordered <?php echo empty($sales) ? "" : "datosPendientesDespacho" ?>  table-hovered">
              <tbody>           
                <?php if (empty($sales)): ?>
                  <tr>
                    <td class="text-center">
                      <p class="text-danger mb-0">No existen registros de facturación</p>
                    </td>
                  </tr>
                <?php else: ?>
                  <?php $totalPagar = 0; foreach ($sales as $key => $value): ?>
                    <tr>
                      <th class="pt-4">
                        <?php echo $key+1; ?>
                      </th>
                      <td class="p-1">
                        <div class="row">
                          <div class="col-md-4">
                            <ul class="list-unstyled">
                              <li><b>Flujo:</b>  <?php echo $value["ProspectiveUser"]["id"] ?> </li>
                              <li><b>Cliente:</b> <?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["name"] : $value["ContacsUser"]["ClientsLegal"]["name"] ?></li>
                              <li><b>Factura:</b> <?php echo $this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_code") ?> <?php echo $this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date") ?> </li>
                              <li><b>Valor factura: </b>$ <?php echo number_format($this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_value"),0,".",",") ?></li>
                              
                            </ul>
                          </div>
                          <div class="col-md-4">
                            <ul class="list-unstyled">
                              
                              <li><b>Recibo: </b><?php echo $value["Receipt"]["code"] ?></li>
                              <li><b>Fecha pago: </b><?php echo $this->Utilities->date_castellano($value["Receipt"]["date_receipt"]) ?></li>
                              <li><b>Valor pago: </b>$ <?php echo number_format($value["Receipt"]["total"],0,".",",") ?> </li>
                              <li><b>Base comisión: </b>$ <?php echo number_format($value["Receipt"]["total_iva"],0,".",",") ?></li>
                            </ul>
                          </div>
                          <div class="col-md-4">
                            <ul class="list-unstyled">
                              <li><b>Días de pago: </b>
                                <?php $dias = $this->Utilities->calculateDays($this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date"),$value["Receipt"]["date_receipt"]); echo $dias; ?>
                              </li>
                              <li><b>Porcentaje obtenido: </b> <?php $percentaje =  $this->Utilities->getComissionPercentaje($dias,$comision); echo $percentaje; ?>%</li>
                              <li><b>Valor a pagar:</b>
                                $ <?php
                                if ($value["Valores"]["totalProductos"] == 1 && $value["Valores"]["totalSt"] > 0) {
                                  $value["Receipt"]["total_iva"] = $value["Valores"]["valorSt"];
                                }

                                $pagar    = ($percentaje / 100) * floatval($value["Receipt"]["total_iva"] - $value["Valores"]["valorSt"]) + ($value["Valores"]["valorBySt"]) ; 
                                $totalPagar += $pagar; echo number_format($pagar,0,".",",") ?>
                              </li>
                            </ul>
                          </div>
                        </div>
                        
                      </td>
                    </tr>
                  <?php endforeach ?>
                  <tr>
                    <td colspan="2" class="text-right pr-1"><h2>Total a pagar $<?php echo number_format($totalPagar,0,".",",") ?></h2> <br>
                      
                    </td>
                  </tr>
                <?php endif ?>
              </tbody>
            </table>
        </div>
      </div>
    </div>

      <!--  -->

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

<div class="popup2">
  <span class="cierra"> <i class="fa fa-remove"></i> </span>
    <div class="contentpopup">
      <img src="" class="img-product" alt="">
    </div>
  </div>
<div class="fondo"></div>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/index.js?".rand(),			array('block' => 'AppScript')); 
	echo $this->Html->script("controller/prospectiveUsers/externos.js?".rand(),			array('block' => 'AppScript')); 
?>


<?php echo $this->element("picker"); ?>