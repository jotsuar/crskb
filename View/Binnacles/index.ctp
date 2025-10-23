<?php if (empty($binnacles)): ?>
	<h1 class="text-center text-info">
		No existe bitácora de trabajo registrado
	</h1>
<?php else: ?>
	<div id="accordion">
		<?php foreach ($binnacles as $key => $binnacle): ?>
			<div class="card">
				<div class="card-header" id="heading_<?php echo $key ?>">
				    <h5 class="mb-0">
				        <button class="btn btn-link2 text-nowrap text-uppercase" data-toggle="collapse" data-target="#collapse_<?php echo $key ?>" aria-expanded="true" aria-controls="collapse_<?php echo $key ?>">
				          <?php echo $binnacle["User"]["name"] ?> - <?php echo date("Y-m-d H:i A",strtotime($binnacle['Binnacle']['modified'])) ?>
				        </button>
				    </h5>
			    </div>
			    <div id="collapse_<?php echo $key ?>" class="collapse show" aria-labelledby="heading_<?php echo $key ?>" data-parent="#accordion">
				    <div class="card-body">
				        <div class="row">
				        	<div class="col-md-4">
				        		<b>Fecha inicio: </b> <?php echo date("Y-m-d H:i A",strtotime($binnacle['Binnacle']['date_ini'])) ?>
				        	</div>
				        	<div class="col-md-4">
				        		<b>Fecha fin: </b> <?php echo date("Y-m-d H:i A",strtotime($binnacle['Binnacle']['date_end'])) ?>
				        	</div>
				        	<div class="col-md-12 mt-3">
				        		<h2 class="text-azul">Trabajo realizado:</h2>
				        		<p>
				        			<?php echo h($binnacle['Binnacle']['note']); ?>&nbsp;
				        		</p>
				        	</div>
				        </div>
				    </div>
			    </div>
		    </div>
		<?php endforeach; ?>
	</div>
<?php endif ?>