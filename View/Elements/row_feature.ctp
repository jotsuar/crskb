<?php $rowId = time(); ?>
<div class="col-md-12 border" id="row_<?php echo $rowId ?>">
	<div class="row">
		<div class="col-md-5">

			<div class="row">
				<div class="col-md-6">
					<?php if (isset($seleted_value)): ?>
						<?php echo $this->Form->input('feature_id.'.$rowId,["label" => "Característica","options"=>$features, "empty" => "Seleccionar" ,"class"=>"form-control select22 features_ids","data-id" => $rowId, "value" => $seleted_value, "data-valor" => $seleted_valor,  ]); ?>
					<?php else: ?>
						<?php echo $this->Form->input('feature_id.'.$rowId,["label" => "Característica","options"=>$features, "empty" => "Seleccionar" ,"class"=>"form-control select22 features_ids","data-id" => $rowId,"data-valor" => 0]); ?>
					<?php endif ?>
				</div>
				<div class="col-md-6">
					<?php echo $this->Form->input('feature_name.'.$rowId,["label" => "Nueva Característica","class"=>"form-control" ,"data-id" => $rowId, "id" => "features_name_id_".$rowId]); ?>
				</div>
			</div>

			

			
			
		</div>
		<div class="col-md-5">
			<div class="row">
				<div class="col-md-6">
					<?php echo $this->Form->input('features_value_id.'.$rowId,["label" => "Valor","options"=>[], "empty" => "Seleccionar" ,"class"=>"form-control select22 features_value_ids", "id" => "features_value_id_".$rowId ]); ?>
				</div>
				<div class="col-md-6">
					<?php echo $this->Form->input('features_value_id_name.'.$rowId,["label" => "Nuevo valor","class"=>"form-control", "id" => "features_value_name_id_".$rowId]); ?>
				</div>
			</div>		
			
		</div>
		<div class="col-md-2">
			<button class="btn btn-warning delete_row mt-4" data-id="<?php echo $rowId ?>">Eliminar</button>
		</div>
	</div>	
</div>		