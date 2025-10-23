<div class="databussiness">
	<table class="table table-striped table-bordered">
		<tr>
			<th>Valor</th>
			<th>Negocio</th>
			<th>Asesor</th>
			<th>Fecha</th>
		</tr>
		<?php foreach ($negocios_realizados as $value): ?>
			<tr>
				<td>
					$<?php echo number_format((int)h($value['FlowStage']['valor']),0,",","."); ?>&nbsp;
				</td>
				<td>
					<?php if ($this->Utilities->exist_file(WWW_ROOT.'/files/flujo/cotizado/'.$this->Utilities->find_id_document_quotation_send($value['FlowStage']['prospective_users_id']))) { ?>
						<a target="_blank" class="nameuppercase" href="<?php echo $this->Html->url('/files/flujo/cotizado/'.$this->Utilities->find_id_document_quotation_send($value['FlowStage']['prospective_users_id'])) ?>">
							<?php echo $this->Utilities->find_name_document_quotation_send($value['FlowStage']['prospective_users_id']) ?>
						</a>
					<?php } else { ?>
						<a target="_blank" class="nameuppercase" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($this->Utilities->find_id_document_quotation_send($value['FlowStage']['prospective_users_id'])))) ?>">
							<?php echo $this->Utilities->find_name_document_quotation_send($value['FlowStage']['prospective_users_id']) ?>
						</a>
					<?php } ?>
				</td>
				<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']); ?></td>
				<td><?php echo $this->Utilities->date_castellano(h($value['FlowStage']['created'])); ?></td>
			</tr>
		<?php endforeach ?>
	</table>
</div>