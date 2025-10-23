<div class="ContacsUser view">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="datajuridica blockwhite">
					<h2>DETALLE DE LA ETAPA "COTIZADO"</h2>
					<div class="datajuridico">
						<b>Cliente: </b>
						<?php echo mb_strtoupper($this->Utilities->name_prospective_contact($datos['FlowStage']['prospective_users_id'])); ?>&nbsp;
						<br>
						<b>Requerimiento inicial: </b>
						<?php echo $this->Utilities->find_reason_prospective($datos['FlowStage']['prospective_users_id']); ?>&nbsp;
						<br>
						<b>Precio:</b> 
						$<?php echo number_format((int)h($datos['FlowStage']['priceQuotation']),0,",","."); ?>
						<br>
						<b>Codigo de la cotización: </b>
						<?php echo $datos['FlowStage']['codigoQuotation'] ?>
						<br>
						<b>Cotización: </b>
						<?php if ($this->Utilities->exist_file(WWW_ROOT.'/files/flujo/cotizado/'.$datos['FlowStage']['document'])) { ?>
							<a target="_blank" href="<?php echo $this->Html->url('/files/flujo/cotizado/'.$datos['FlowStage']['document']) ?>">
								<i class="fa fa-file-text fa-x"></i>
								<?php echo $this->Utilities->find_name_file_quotation($datos['FlowStage']['nameDocument'],$datos['FlowStage']['document']) ?>
							</a>
						<?php } else { ?>
							<a target="_blank" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($datos['FlowStage']['document']))) ?>">
								<i class="fa fa-file-text fa-x"></i>
								<?php echo $this->Utilities->find_name_file_quotation($datos['FlowStage']['nameDocument'],$datos['FlowStage']['document']) ?>
							</a>
						<?php } ?>
						<br>
						<b>Fecha de registro: </b>
						<?php echo $this->Utilities->date_castellano(h($datos['FlowStage']['created'])); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
?>