<?php echo $this->Form->create('ImportRequestsDetail',array('id' => 'formInformeCliente','enctype'=>'multipart/form-data')); ?>
	<div class="row">
		<div class="col-md-12">
			<h3 class="text-center">
				Informar al cliente sobre una demora al/los producto(s)
			</h3>
		</div>
		<?php foreach ($requestData["Product"] as $key => $producto): ?>
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3">
						<?php $ruta = $this->Utilities->validate_image_products($producto['img']); ?>
						<div class="imginv mb-3" style="background-image: url(<?php echo $this->Html->url('/img/products/'.$ruta) ?>); height: 180px !important;"></div>
					</div>
					<div class="col-md-8 py-5">
						<span class="text-success">Referencia: <?php echo $producto['part_number'] ?> / Marca: <?php echo $producto['brand'] ?></span> 
						<span class=""><?php echo $this->Text->truncate(strip_tags($producto['name']), 70,array('ellipsis' => '...','exact' => false)); ?></span> 
					</div>

				</div>
			</div>
		<?php endforeach ?>
		
	</div>		
	<?php echo $this->Form->input("id",["value" => $id]) ?>
	<?php echo $this->Form->input("gest",["value" => 1, "type"=>"hidden"]) ?>
	<?php echo $this->Form->input("texto", array("label" => "Razón que le dará al cliente por la demora" ,"placeholder" => "Por favor detalla muy bien porque este producto sufrirá una demora" ,"required", )) ?>
	<div class="form-group mb-4" style="margin-bottom: 15px !important;">
		<label for="">Fecha Probable de entrega</label>
		<?php echo $this->Form->text("deadline", array("label" => "Fecha probable de entrega" ,"placeholder" => "Por favor detalla muy bien porque este producto sufrirá una demora" ,"required","type" => "date","class" => "form-control", "value" => date("Y-m-d",strtotime("+7 day")) )) ?>
	</div>

	<input type="submit" class="btn btn-success" value="Enviar informe">
</form>