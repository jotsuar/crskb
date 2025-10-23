<!-- Modal para crear o editar un producto desde la vista de crear una cotización -->

<div class="modal fade" id="modalComentario" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title">
        	Detalle de ventas de producto
        </h2>
      </div>
      <div class="modal-body" id="cuerpoModalViewVentas">
        <?php echo $this->Form->create('Product',array("id" => "formComentary")); ?>
          <?php echo $this->Form->input('id',array('type' => "hidden","id" => "productoIdNotas" ));?>
          <?php
            echo $this->Form->input('notes',array('label' => "Notas del producto",'placeholder' => 'Notas al producto',"id" => "notasDelProductoId"));
          ?>
          <input type="submit" class="btn btn-success float-right" value="Guardar nota">
        </form>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal btn btn-primary" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>

<div class="modal fade" id="modalComentarioCotizacion" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title">
          Detalle de ventas del producto para esta cotización
        </h2>
      </div>
      <div class="modal-body" id="cuerpoModalViewVentas">
        <?php echo $this->Form->create('Product',array("id" => "formComentaryProducto")); ?>
          <?php echo $this->Form->input('id',array('type' => "hidden","id" => "productoIdNotasCotiza" ));?>
          <?php
            echo $this->Form->input('notes',array('label' => "Notas del producto",'placeholder' => 'Notas al producto',"id" => "notasDelProductoIdCotizacion"));
          ?>
          <input type="submit" class="btn btn-success float-right" value="Guardar nota">
        </form>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal btn btn-primary" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>


<?php 
    
    echo $this->Html->script("controller/product/comentarios.js?".rand(),           array('block' => 'AppScript'));

 ?>