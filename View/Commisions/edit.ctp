<div class="commisions form">
<?php echo $this->Form->create('Commision'); ?>
	<div class="row">
		<di class="col-md-6 border-right">
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('user_id',array("value" => $user_id,"type"=>"hidden"));
				echo $this->Form->input('User.id',array("value" => $user_id,"type"=>"hidden"));
			?>
			<div class="card border-0">
				<div class="card-body">
					<h1 class="card-title text-center font-weight-bold">
						Valor de comisión por recaudo
					</h1>
				</div>
			</div>
			<div class="card border-0">
				<div class="card-body">
					<h5 class="card-title">Usuario: <?php echo $user["User"]["name"] ?> 

						<?php if ($user["User"]["role"] == "Asesor Externo"): ?>
							- Asesor externo
						<?php endif ?>

					 </h5>
				</div>
			</div>
			<div class="card border-0">
				<div class="card-body">
					<h5 class="card-title">Primer rango</h5>
					<div class="row card-text">
						<div class="col-md-4 col-sm-4"><?php echo $this->Form->input('range_one_init',array("label" => "Días inicio", "min"=> 0, "value"=> 0)); ?></div>
						<div class="col-md-4 col-sm-4"><?php echo $this->Form->input('range_one_end',array("label" => "Días fin", "min"=> 0, $actual ? "readonly" : "", "value"=> 35)); ?></div>
						<div class="col-md-4 col-sm-4"><?php echo $this->Form->input('range_one_percentage',array("label" => "Porcentaje", "min"=> 0, $actual ? "readonly" : "", "values"=> 3)); ?></div>
					</div>
				</div>
			</div>
			<div class="card border-0">
				<div class="card-body">
					<h5 class="card-title">Segundo rango</h5>
					<div class="row card-text">
						<div class="col-md-4 col-sm-4"><?php echo $this->Form->input('range_two_init',array("label" => "Días inicio", "min"=> 0, $actual ? "readonly" : "", "value"=> 36 )); ?></div>
						<div class="col-md-4 col-sm-4"><?php echo $this->Form->input('range_two_end',array("label" => "Días fin", "min"=> 0, $actual ? "readonly" : "", "value"=> 60 )); ?></div>
						<div class="col-md-4 col-sm-4"><?php echo $this->Form->input('range_two_percentage',array("label" => "Porcentaje", "min"=> 0, $actual ? "readonly" : "", "values"=> 2)); ?></div>
					</div>
				</div>
			</div>
			<div class="card border-0">
				<div class="card-body">
					<h5 class="card-title">Tercer rango</h5>
					<div class="row card-text">
						<div class="col-md-4 col-sm-4"><?php echo $this->Form->input('range_three_init',array("label" => "Días inicio", "min"=> 0, $actual ? "readonly" : "", "value"=> 61)); ?></div>
						<div class="col-md-4 col-sm-4"><?php echo $this->Form->input('range_three_end',array("label" => "Días fin", "min"=> 0, $actual ? "readonly" : "", "value"=> 90)); ?></div>
						<div class="col-md-4 col-sm-4"><?php echo $this->Form->input('range_three_percentage',array("label" => "Porcentaje", "min"=> 0, $actual ? "readonly" : "", "values"=> 1)); ?></div>
					</div>
				</div>
			</div>
			<div class="card border-0">
				<div class="card-body">
					<h5 class="card-title">Cuarto rango</h5>
					<div class="row card-text">
						<div class="col-md-4 col-sm-4"><?php echo $this->Form->input('range_four_init',array("label" => "Días inicio", "min"=> 0, $actual ? "readonly" : "", "value"=> 91)); ?></div>
						<div class="col-md-4 col-sm-4"><?php echo $this->Form->input('range_four_end',array("label" => "Días fin", "min"=> 0, $actual ? "readonly" : "", "value"=> 365)); ?></div>
						<div class="col-md-4 col-sm-4"><?php echo $this->Form->input('range_four_percentage',array("label" => "Porcentaje", "min"=> 0, $actual ? "readonly" : "", "values"=> 0)); ?></div>
					</div>
				</div>
			</div>

			<div class="card mt-2 <?php echo $user["User"]["role"] == "Asesor Externo" ? "d-none" : ""; $user["User"]["users_money"] = "[]"; ?>">
				<div class="card-body">
					<h5 class="card-title">Asignar usuarios dependientes para sumar comision</h5>
					<div class="row card-text">
						<div class="col-md-12 col-sm-12"><?php echo $this->Form->input('User.users_money',array("class"=>"form-control","label" => "Usuarios", "options" => $usuarios, "multiple" => true, "value" => array_combine(json_decode($user["User"]["users_money"]), json_decode($user["User"]["users_money"]) ) )); ?></div>
					</div>
				</div>
			</div>

			<div class="card mt-2 <?php echo $user["User"]["role"] == "Asesor Externo" ? "d-none" : "";?>">
				<div class="card-body">
					<h5 class="card-title">Porcentaje de eliminación de flujos permitidos para efectividad</h5>
					<div class="row card-text">
						<div class="col-md-12 col-sm-12"><?php echo $this->Form->input('User.percent_deleted',array("class"=>"form-control","label" => false,"value" => $user["User"]["percent_deleted"]  )); ?></div>
					</div>
				</div>
			</div>
		</di>
		<div class="col-md-6">
			<h3 class="text-info text-center">% de comision sobre margen sobre la venta </h3>
			<div class="row">
				<?php foreach ($porcentajes as $key => $value): ?>
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-4">
								<?php echo $this->Form->input("Percentage.$key.id", ["value" => $value["Percentage"]["id"],"type"=>"hidden" ] ) ?>
								<?php echo $this->Form->input("Percentage.$key.user_id", ["value" => $value["Percentage"]["user_id"],"type"=>"hidden" ] ) ?>
								<?php echo $this->Form->input("Percentage.$key.min", ["value" => $value["Percentage"]["min"],"readonly" => true, "label" => "Margen Min" ] ) ?>
							</div>
							<div class="col-md-4"><?php echo $this->Form->input("Percentage.$key.value", ["value" => $value["Percentage"]["value"],"readonly" => false, "label" => "Porcentaje" ] ) ?></div>
							<div class="col-md-4"><?php echo $this->Form->input("Percentage.$key.max", ["value" => $value["Percentage"]["max"],"readonly" => true, "label" => "Margen Max", "type" => "text" ] ) ?></div>
						</div>
					</div>
				<?php endforeach ?>
			</div>
			<hr>
			<h3 class="text-info text-center">% de comision sobre margen sobre la venta local</h3>
			<div class="row">
				<?php foreach ($porcentajesLocal as $key => $value): ?>
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-4">
								<?php echo $this->Form->input("PercentagesLocal.$key.id", ["value" => $value["PercentagesLocal"]["id"],"type"=>"hidden" ] ) ?>
								<?php echo $this->Form->input("PercentagesLocal.$key.user_id", ["value" => $value["PercentagesLocal"]["user_id"],"type"=>"hidden" ] ) ?>
								<?php echo $this->Form->input("PercentagesLocal.$key.min", ["value" => $value["PercentagesLocal"]["min"],"readonly" => true, "label" => "Margen Min" ] ) ?>
							</div>
							<div class="col-md-4"><?php echo $this->Form->input("PercentagesLocal.$key.value", ["value" => $value["PercentagesLocal"]["value"],"readonly" => false, "label" => "Porcentaje" ] ) ?></div>
							<div class="col-md-4"><?php echo $this->Form->input("PercentagesLocal.$key.max", ["value" => $value["PercentagesLocal"]["max"],"readonly" => true, "label" => "Margen Max", "type" => "text" ] ) ?></div>
						</div>
					</div>
				<?php endforeach ?>
			</div>
			<hr>
			<h3 class="text-info text-center">% de comision efectividad mensual </h3>
			<div class="row">
				<?php foreach ($efectividad as $key => $value): ?>
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-4">
								<?php echo $this->Form->input("Effectivity.$key.id", ["value" => $value["Effectivity"]["id"],"type"=>"hidden" ] ) ?>
								<?php echo $this->Form->input("Effectivity.$key.user_id", ["value" => $value["Effectivity"]["user_id"],"type"=>"hidden" ] ) ?>
								<?php echo $this->Form->input("Effectivity.$key.min", ["value" => $value["Effectivity"]["min"],"readonly" => true, "label" => "Margen Min" ] ) ?>
							</div>
							<div class="col-md-4"><?php echo $this->Form->input("Effectivity.$key.value", ["value" => $value["Effectivity"]["value"],"readonly" => false, "label" => "Porcentaje" ] ) ?></div>
							<div class="col-md-4"><?php echo $this->Form->input("Effectivity.$key.max", ["value" => $value["Effectivity"]["max"],"readonly" => true, "label" => "Margen Max", "type" => "text" ] ) ?></div>
						</div>
					</div>
				<?php endforeach ?>
			</div>
		</div>

		<div class="col-md-6">
			
		</div>
	</div>
	
	<div class="form-group mt-3">
		<input type="Submit" value="Guardar" class="btn btn-success float-right">
	</div>
	
</div>
