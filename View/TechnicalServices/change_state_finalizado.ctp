<p class="copiealert">
	Por favor evita pegar texto en los dos siguientes campos, esto puede dañar el informe que reciba el cliente, si necesita pegar contenido por favor haga clic derecho y seleccione la opción "PEGAR TEXTO SIN FORMATO"
</p>

<?php echo $this->Form->create('TechnicalService',array('id'=>'formFinishService','enctype'=>'multipart/form-data')); ?>
	<?php
		echo $this->Form->input('report',array('type' => 'textarea','rows'=>'8','label' => 'Informe','placeholder' => 'Por favor ingresa el informe del servicio'));
		echo $this->Form->input('observation',array('type' => 'textarea','rows'=>'3','label' => 'Observación','placeholder' => 'Por favor ingresa la observación del servicio'));
		echo $this->Form->hidden('id',array('value'=> $modelo_id));
	?>

	<div class="dataequipoimg">
		<div class="form-row aqthis">
			<div class="form-group col-md-11">
				<?php echo $this->Form->input('img1',array('type' => 'file','label' => 'Imagen del servicio técnico')); ?>
			</div>
			<div class="form-group col-md-1 text-center">
				<i class="fa fa-lg fa-plus" id="icon_add_imagenes"></i>
			</div>
		</div>

		<div class="form-row">
			<div class="divImagenes col-md-12">
				<div class="form-row aqthis">
					<div class="col-md-12">
						<?php echo $this->Form->input('img2',array('type' => 'file','label' => 'Segunda imagen del servicio técnico')); ?>
					</div>
				</div>
				<div class="form-row aqthis">
					<div class="col-md-12">
						<?php echo $this->Form->input('img3',array('type' => 'file','label' => 'Tercera imagen del servicio técnico')); ?>
					</div>
				</div>
				<div class="form-row aqthis">
					<div class="col-md-12">
						<?php echo $this->Form->input('img4',array('type' => 'file','label' => 'Cuarta imagen del servicio técnico')); ?>
					</div>
				</div>
				<div class="form-row aqthis">
					<div class="col-md-12">
						<?php echo $this->Form->input('img5',array('type' => 'file','label' => 'Quinta imagen del servicio técnico')); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<button class="btn" type="button" id="btn_servicio_tecnico_cotizacion_true">
		Finalizar y cotizar
	</button>
	<button class="btn" type="button" id="btn_servicio_tecnico_cotizacion_false">
		Finalizar sin cotizar por el momento
	</button>
</form>