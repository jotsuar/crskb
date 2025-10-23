<?php echo $this->Form->create('User',array("id" => "userFormCopias","type" => "file")); ?>
<?php echo $this->Form->input('id',array('label' => false, "value" => $user_id ) );?>
<div class="col-md-12">
	<h3 class="text-center mb-3">
		Usuarios asignados para revisar cotizaciones 
		<br>
		<span class="text-info text-center">
		Los usuarios de la derecha son los que tiene asignados actualmente
	</span>
	</h3>
	
	<div class="form-group mt-2">
		<?php echo $this->Form->input('copias',array('label' => false,"multiple","options" => $usuarios, "value" => $actuales, "style" => 'height: 200px;',"multiple" => true ));?>
	</div>
	<div class="form-group">
		<input type="submit" value="Guardar configuración" class="btn btn-success float-right">
	</div>
</div>
</form>