<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>
	<h2 class="modal-title labeligual"><?php echo 'Agregar nota en estado '.strtolower($etapa) ?></h2>
</div>

<?php echo $this->Form->create('ProgresNote'); ?>
	<?php echo $this->Form->hidden('flujo_id',array('value' => $flujo_id)); ?>
	<?php echo $this->Form->hidden('etapa',array('value' => $etapa)); ?>
	<?php echo $this->Form->input('description',array('label' => false,'type' => 'textarea','rows'=>'3','placeholder' => 'Cuéntanos si sucedió alguna novedad en esta etapa...',"required" => true)); ?>
</form>