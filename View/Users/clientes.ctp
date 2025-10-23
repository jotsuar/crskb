<?php echo $this->Form->create('User',array('enctype'=>"multipart/form-data",'data-parsley-validate'=>true,"autocomplete"=>"off","id"=>"FormCategoriesUser")); ?>
	<?php echo $this->Form->hidden('user_id',array('value' => $id, "required" => false)); ?>
	<?php echo $this->Form->input('cliente_id.',array('value' => $ids, "required" => false,"class"=>"form-control","label"=>"Clientes que deseas asignar","div"=>false,"options"=>$clientes, "multiple")); ?>
	<div class="form-group my-3">
		<input type="submit" class="btn btn-success float-right">
	</div>
<?php echo $this->Form->end(); ?>