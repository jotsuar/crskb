<!-- Modal para previsualizar -->
<div class="modal fade" id="modal_previsualizar" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg3" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="modal_previsualizar_label"></h5>
      </div>
      <div class="modal-body">
        <div id="modal_previsualizar_body"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Seguir editando</button>
        <button type="button" id="btn_guardar_previsualizar" class="btn btn-primary">Listo, guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- FLUJOS DE NEGOCIOS -->
<!-- Modal para registrar un requerimiento -->
<div class="modal fade" id="agregarTareaModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_requerimiento_label">Registrar requerimiento</h2>
      </div>

      <div class="modal-body">
        <form class="countdatainput" id="form_new_requerimiento">
          <?php 
            echo $this->Form->hidden('contact');
          ?>
          <div class="row"> 
            <div class="col-md-10">
              <?php echo $this->Form->input('origin',array('label' => 'Origen:', 'options' => (isset($origen)) ? $origen:"")); ?>
            </div>
            <div class="col-md-2">
              <?php echo $this->Form->input('flujo_no_valido',array('type' => 'checkbox','label' => 'Flujo no válido'));?>
            </div>
          </div>
          <?php 
            echo $this->Form->input('reason',array('label' => "Asunto/Motivo/Solicitud/Requerimiento",'placeholder' => 'Por favor ingresa un nombre para esta Solicitud o Requerimiento'));
            echo $this->Form->input('description',array('type' => 'textarea','rows'=>'3','label' => "Comentario",'placeholder' => 'Por favor detalla el requerimiento del cliente'));
          ?>
          <div id="user_asignado_div">
            <?php
              echo $this->Form->input('user_id',array('label' => 'Asignado a:','value'=>AuthComponent::user('id'), 'options' => (isset($usuarios)) ? $usuarios:""));
            ?>
          </div>
        </form> 
      </div>

      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
        <a class="savedata" id="btn_registrar_flujo">Registrar requerimiento</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal para el paso de contactado del flujo -->
<div class="modal fade" id="modal_contactado" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_contactado_label"></h2>
      </div>
      <div class="modal-body">
        <div id="modal_contactado_body"></div>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
        <a class="savedata btn_guardar_contactado">Procesar flujo</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal para el paso de cotizado del flujo -->
<div class="modal fade" id="modal_cotizado" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">}
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_cotizado_label"></h2>
      </div>
      <div class="modal-body">
        <div id="modal_cotizado_body"></div>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
        <a class="savedata btn_guardar_cotizado">Procesar flujo</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal para el paso de negociado del flujo -->
<div class="modal fade" id="modal_negociado" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_negociado_label"></h2>
      </div>
      <div class="modal-body">
        <div id="modal_negociado_body"></div>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
        <a class="savedata btn_guardar_negociado">Procesar flujo</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal para el paso de pagado del flujo -->
<div class="modal fade" id="modal_pagado" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_pagado_label"></h2>
      </div>
      <div class="modal-body">
        <div id="modal_pagado_body"></div>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
        <a class="savedata btn_guardar_pagado">Procesar flujo</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal para el paso de despachado del flujo -->
<div class="modal fade" id="modal_despachado" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_despachado_label"></h2>
      </div>
      <div class="modal-body">
        <div id="modal_despachado_body"></div>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
        <a class="savedata btn_guardar_despachado">Procesar flujo</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal para el siguiente estado del flujo, Se debe hacer un modal por cada estado -->
<div class="modal fade" id="modal_big" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_big_label"></h2>
      </div>
      <div class="modal-body">
        <div id="modal_big_body"></div>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
        <a class="savedata">Procesar flujo</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal informativo, cuando se intenta acceder a otro estado que no sigue el proceso de los flujos (Pequeño) -->
<div class="modal fade" id="modal_big_information" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-small" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="modal_big_information_label"></h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="modal_big_information_body"></div>
      </div>
        <button type="button" class="btn btn-incorrecto" data-dismiss="modal">Entendido</button>
    </div>
  </div>
</div>

<!-- Modal informativo, cuando se intenta acceder a otro estado que no sigue el proceso de los flujos (Grande) -->
<div class="modal fade" id="modal_information" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_information_label"></h2>
      </div>
      <div class="modal-body">
        <div id="modal_information_body"></div>
      </div>
        <button type="button" id="btn_action_true" class="btn btn-incorrecto" data-dismiss="modal">Entendido</button>
    </div>
  </div>
</div>

<!-- Modal para realizar una nota al flujo -->
<div class="modal fade" id="modal_form_nota_flujo" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div id="modal_form_nota_body"></div>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
        <a class="savedata" id="btn_save_nota">Guardar</a>
      </div>
    </div>
  </div>
</div>


<!-- Importaciones -->
<!-- Modal informativo, para ver las novedades de las importaciones de los productos -->
<div class="modal fade" id="modal_big_big_information" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_big_big_information_label"></h2>
      </div>
      <div class="modal-body">
        <div id="modal_big_big_information_body"></div>
      </div>
        <button type="button" class="btn btn-incorrecto" data-dismiss="modal">Entendido</button>
    </div>
  </div>
</div>

<!-- Modal para administrar los productos de la orden del despachado del flujo -->
<div class="modal fade" id="modal_administrar_despachado" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg3" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_administrar_despachado_label"></h2>
      </div>
      <div class="modal-body">
        <div id="modal_administrar_despachado_body"></div>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
        <a class="savedata btn_guardar_administrar">Guardar información</a>
      </div>
    </div>
  </div>
</div>



<!-- SERVICIO TECNICO -->
<!-- Modal para pasar un servicio tecnico a finalizado -->
<div class="modal fade" id="modal_nota_flujo" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_requerimiento_label">Novedades de la Gestión</h2>
      </div>
      <div class="modal-body">
        <div id="modal_nota_flujo_body"></div>
      </div>
      <button type="button" class="btn btn-incorrecto" data-dismiss="modal">Cerrar</button>
    </div>
  </div>
</div>

<!-- Modal para pasar un servicio tecnico a finalizado -->
<div class="modal fade" id="modal_servicio_finalizado" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_servicio_finalizado_label"></h2>
      </div>
      <div class="modal-body">
        <div id="modal_servicio_finalizado_body"></div>
      </div>
      <button type="button" class="btn btn-incorrecto" data-dismiss="modal">Cancelar</button>
    </div>
  </div>
</div>



<!-- Agregar contacto, editar asesor y registrar la informacion de la entrega del pedido-->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_form_label"></h2>
      </div>
      <div class="modal-body">
          <br>
          <div id="modal_form_body"></div>
          <?php echo $this->Form->input('cityForm',array('label' => false,'placeholder' => 'Ciudad *', "required" => true)); ?>
      </div>
      <br>
      <br>
      <br>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
        <a class="savedata btn_guardar_form">Guardar</a>
      </div>
    </div>
  </div>
</div>

<!-- Editar cliente natural vista de flujos(index)-->
<div class="modal fade" id="modal_form_edit_natural" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_form_label_edit_natural"></h2>
      </div>
      <div class="modal-body">
          <br>
          <div id="modal_form_body_edit_natural"></div>
          <?php echo $this->Form->input('cityForm1',array('label' => false,'placeholder' => 'Ciudad *', "required" => true,'class' => 'form-control')); ?>
      </div>
      <br>
      <br>
      <br>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
        <a class="savedata btn_guardar_form_edit_natural">Guardar</a>
      </div>
    </div>
  </div>
</div>

<!-- Editar contacto -->
<div class="modal fade" id="modal_form_edit_contacto" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_form_label_edit_contacto"></h2>
      </div>
      <div class="modal-body">
        <form>
          <br>
          <div id="modal_form_body_edit_contacto"></div>
          <?php echo $this->Form->input('cityForm2',array('label' => false,'placeholder' => 'Ciudad *', "required" => true)); ?>
        </form>
      </div>
      <br>
      <br>
      <br>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
        <a class="savedata btn_guardar_form_edit_contacto">Guardar</a>
      </div>
    </div>
  </div>
</div>

<!-- Crear cliente y editar cliente desde el index -->
<div class="modal fade" id="modal_small" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_small_label"></h2>
      </div>
      <div class="modal-body">
        <div id="modal_small_body"></div>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
        <a class="savedata" id="btn_guardar_modal_cliente">Guardar</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal para crear o editar un producto desde la vista de crear una cotización -->
<div class="modal fade" id="modal_form_products" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_form_products_label"></h2>
      </div>
      <div class="modal-body">
          <div id="modal_form_products_body"></div>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
        <a class="savedata" id="btn_action_products">Crear producto</a>
      </div>      
    </div>
  </div>
</div>

<!-- Modal informativo, cuando hay un aviso activo por parte de la gerencia -->
<div class="modal fade" id="modal_big_information_aviso" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-small" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="modal_big_information_aviso_label">Aviso(s) de gerencia</h2>
        <button type="button" class="close btn_entendido_aviso">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="modal_big_information_aviso_body">
          <?php if (isset($notices_gerencia)): ?>
            
            <?php 

              $cont = count($notices_gerencia); ?>
              <?php if ($cont > 0): ?>
                
              <?php
                $keys = array_keys($notices_gerencia);
                $numer = $cont == 0 ? 0 : $cont-1;
                $ultima_key = empty($keys) ? 0 : $keys[$numer];
                foreach ($notices_gerencia as $key => $value): ?>
                  <p><b>Título:</b> <?php echo $value['ManagementNotice']['title'] ?></p>
                  <p><b>Descripción:</b> <?php echo $value['ManagementNotice']['description'] ?></p>
                  <p><b>Fecha límite:</b> <?php echo $value['ManagementNotice']['fecha_fin'] ?></p>
                  <p><b>Precio:</b>
                    <?php echo $this->Utilities->data_null_numeros(number_format((int)h($value['ManagementNotice']['price']),0,",",".")); ?>
                  </p>
                  <p><b>Imagen:</b>
                    <?php if ($value['ManagementNotice']['img'] != ''){ ?>
                      <img src="<?php echo $this->Html->url('/img/managementNotices/'.$value['ManagementNotice']['img']) ?>" width="30px" height="22px" class="imgmin-product">
                    <?php } else { ?>
                      <?php echo $this->Utilities->data_null($value['ManagementNotice']['img']); ?>&nbsp;
                    <?php } ?>
                  </p>
                  <?php if ($key != $ultima_key): ?>
                    <hr>
                  <?php endif ?>
              <?php endforeach ?>
              <?php endif ?>
          <?php endif ?>
        </div>
      </div>
        <button type="button" class="btn btn-incorrecto btn_entendido_aviso">Entendido</button>
    </div>
  </div>
</div>

<!-- Modal informativo al usuario que se le a cerrado la sesion -->
<div class="modal fade" id="modal_small_information" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      </div>

      <div class="modal-body">
        <div id="modal_small_information_body"></div>
      </div>

      <div class="modal-footer">
        <a class="savedata" href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'login')) ?>">Ingresar de nuevo</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal informativo (Notificaciones)-->
<div class="modal fade" id="modal_information_notificaciones" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="label">Notificaciones</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="body">
          <p>Tienes <?php echo $this->Utilities->count_notificaciones_user() ?> o mas notificaciones sin leer</p>
          <a href="<?php echo $this->Html->url(array('controller' => 'Manages')) ?>" class="btn btn-primary">Ver todas</a>
        </div>
      </div>
        <button type="button" id="btn_action_true" class="btn btn-incorrecto" data-dismiss="modal">Entendido</button>
    </div>
  </div>
</div>

<div class="modal fade" id="modalInforme" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Informar demora </h2>
      </div>
      <div class="modal-body" id="bodyDemora">
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>
