<?php 
	$anios 			= [];
	for ($i=2020; $i <= date("Y") ; $i++) { 
		$anios[] = $i;
	}
?>
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-verde big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Tesorería </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Crear meta</h2>
			</div>
		</div>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="goals form">
			<?php echo $this->Form->create('Goal'); ?>
			<div class="row mb-3">
				
				<?php
					echo $this->Form->input('year',["label" => "Año","options" => array_combine($anios, $anios),"div" => "col-md-4 form-group" ]);
					echo $this->Form->input('user_id',["label" => "Usuario","class"=>"form-control", "div" => "col-md-4 form-group" ]);
					echo $this->Form->input('name', ["label" => "Nombre en WO","class"=>"form-control", "div" => "col-md-4 form-group"  ]);
					echo $this->Form->input('01',["label"=>"Enero","class"=>"form-control goalClass", "min" => 0,"value"=>0, "div" => "col-md-4 form-group" ]);
					echo $this->Form->input('02',["label"=>"Febrero","class"=>"form-control goalClass", "min" => 0,"value"=>0, "div" => "col-md-4 form-group" ]);
					echo $this->Form->input('03',["label"=>"Marzo","class"=>"form-control goalClass", "min" => 0,"value"=>0, "div" => "col-md-4 form-group" ]);
					echo $this->Form->input('04',["label"=>"Abril","class"=>"form-control goalClass", "min" => 0,"value"=>0, "div" => "col-md-4 form-group" ]);
					echo $this->Form->input('05',["label"=>"Mayo","class"=>"form-control goalClass", "min" => 0,"value"=>0, "div" => "col-md-4 form-group" ]);
					echo $this->Form->input('06',["label"=>"Junio","class"=>"form-control goalClass", "min" => 0,"value"=>0, "div" => "col-md-4 form-group" ]);
					echo $this->Form->input('07',["label"=>"Julio","class"=>"form-control goalClass", "min" => 0,"value"=>0, "div" => "col-md-4 form-group" ]);
					echo $this->Form->input('08',["label"=>"Agosto","class"=>"form-control goalClass", "min" => 0,"value"=>0, "div" => "col-md-4 form-group" ]);
					echo $this->Form->input('09',["label"=>"Septiembre","class"=>"form-control goalClass", "min" => 0,"value"=>0, "div" => "col-md-4 form-group" ]);
					echo $this->Form->input('10',["label"=>"Octubre","class"=>"form-control goalClass", "min" => 0,"value"=>0, "div" => "col-md-4 form-group" ]);
					echo $this->Form->input('11',["label"=>"Noviembre","class"=>"form-control goalClass", "min" => 0,"value"=>0, "div" => "col-md-4 form-group" ]);
					echo $this->Form->input('12',["label"=>"Diciembre","class"=>"form-control goalClass", "min" => 0,"value"=>0, "div" => "col-md-4 form-group" ]);
				?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<h3>Valor total: $<span id="totalGoal">0</span> </h3>
						</div>
						<div class="col-md-6">
							<input type="submit" value="Guardar" class="btn btn-success float-right">
						</div>
					</div>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>

<?php 
	
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script(array('controller/goals/admin.js?'.rand()),						array('block' => 'AppScript'));

?>