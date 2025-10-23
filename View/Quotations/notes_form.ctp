<?php echo $this->Form->create('Product',array("id" => "formComentaryProducto")); ?>
  <?php echo $this->Form->input('id',array('type' => "hidden","id" => "productoIdNotasCotiza", "value" => $id ));?>
  <?php if (!empty($garantias)): ?>
    <div class="form-group">
      <label for="garantiesNotes">Agregar nota de garantía</label>
      <?php
        echo $this->Form->input('garantiaId',array('label' => false,"options"=>$garantias, "empty" => "Seleccionar nota de garantía" ,"id" => "garantiaId"));
      ?>
    </div>
  <?php endif ?>
  <?php
    echo $this->Form->input('notes',array('label' => "Notas del producto",'placeholder' => 'Notas al producto',"id" => "notasDelProductoIdCotizacion"));
  ?>
  <input type="submit" class="btn btn-success float-right" value="Guardar nota">
</form>