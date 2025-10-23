<?php echo $this->Form->create('ProspectiveUser',array("id" => "sendMailGest","type" => "file")); ?>
	<div class="row">
		<div class="col-md-12">
			<?php echo $this->Form->input('user_id',array('type' => 'hidden','value'=>AuthComponent::user('id') ));  ?>
			<?php echo $this->Form->input('id',array('type' => 'hidden','value'=>$flowData["ProspectiveUser"]["id"] ));  ?>
		</div>
		<div class="col-md-12">
			<h3 class="text-center">
				Seguimiento a la cotización <b><?php echo $quotationData["Quotation"]["codigo"] ?></b> envíada el día <?php echo $this->Utilities->date_castellano($flowData["ProspectiveUser"]["date_quotation"]) ?> </b>
				<?php echo $this->Form->input('subject',array('type' => 'hidden','value'=> 'Seguimiento a la cotización <b>'.$quotationData["Quotation"]["codigo"].'</b>'  ));  ?>
			</h3>
		</div>
		<div class="col-md-12">
			<div class="form-group">
				<label for="contentText">
					Mensaje a envíar
				</label>

				<?php if (!is_null($res_ai)): ?>
					<textarea name="data[ProspectiveUser][contenido]" id="contenidoGestMail" cols="30" rows="10"><?php echo trim($res_ai,'"') ?></textarea>
				<?php else: ?>
					<textarea name="data[ProspectiveUser][contenido]" id="contenidoGestMail" cols="30" rows="10"><p> Buen día <b><?php echo $customer_data["name"] ?></b>, desde KEBCO SAS esperamos que se encuentre muy bien.</p><p>Le escribo con relación a la cotización en asunto, enviada el pasado <b><?php echo $this->Utilities->date_castellano( date("Y-m-d", strtotime($flowData["ProspectiveUser"]["date_quotation"])) ) ?></b>, en donde le cotizamos con el asunto <b><?php echo $quotationData["Quotation"]["name"] ?></b></p><p>Quisiéramos saber si ha podido revisar la cotización la cual adjunto nuevamente, si tiene alguna duda al respecto o si ha tomado alguna decisión.</p><p>Estamos atentos para seguir asesorándolo</p><p>Feliz día.</p></textarea>
				<?php endif ?>
				
			</div>
		</div>
		<div class="col-md-12">
			<input type="submit" value="Enviar gestión" class="btn btn-success pull-right" >
		</div>
	</div>
</form> 
