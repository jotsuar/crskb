<div class="databussiness contenttableresponsive">
	<table class="table table-striped table-bordered">
		<tr>
		    <th>Nombre</th>
		    <th>Valor</th>
		    <th>Asesor</th>
		    <th>Fecha</th>
		    <th>Ver</th>
		</tr>
	  <?php foreach ($cotizaciones_enviadas as $value): ?>
	  	<?php if (empty($value["ProspectiveUser"]["user_id"])): ?>
	  		
		  	<?php else: ?>
		  	
				<tr>
					<td><?php echo $this->Utilities->find_name_file_quotation($value['FlowStage']['nameDocument'],$value['FlowStage']['document']) ?>&nbsp; </td>
					<td>$<?php echo $this->Utilities->find_valor_quotation_flujo_id($value['FlowStage']['prospective_users_id']) ?></td> 
					<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']) ?></td>
					<td><?php echo $this->Utilities->date_castellano(h($value['FlowStage']['created'])); ?></td>
					<td>
						<?php if ($this->Utilities->exist_file(WWW_ROOT.'/files/flujo/cotizado/'.$value['FlowStage']['document'])) { ?>
							<a target="_blank" href="<?php echo $this->Html->url('/files/flujo/cotizado/'.$value['FlowStage']['document']) ?>">
								<i class="fa fa-eye"></i>
							</a>
						<?php } else { ?>
							<a target="_blank" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($value['FlowStage']['document']))) ?>">
								<i class="fa fa-eye"></i>
							</a>
						<?php } ?>
					</td>
				</tr>
			<?php endif ?>
		<?php endforeach ?>
	</table>
</div>