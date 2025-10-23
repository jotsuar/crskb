<table cellpadding="0" cellspacing="0" class='table-striped table-bordered'>
	<thead>
		<tr>
			<th>Pagos</th>
			<th>Medios de pagos utilizado</th>
			<th>Comprobantes de pago</th>
			<th>Fecha</th>
		</tr>
	</thead>
	<tbody>
	<?php $resultado = 0; foreach ($history as $value): ?>
		<?php 
			$resultado += $value['Payment']['valor'];
		?>
		<tr>
			<td>$<?php echo number_format((int)h($value['Payment']['valor']),0,",","."); ?>&nbsp;</td>
			<td><?php echo $value['Payment']['payment']; ?></td>
			<td class="comprobanteimgTd">
				<img datacomprobantet="<?php echo $this->Html->url('/img/flujo/pagado/'.$value['Payment']['document']) ?>" src="<?php echo $this->Html->url('/img/flujo/pagado/'.$value['Payment']['document']) ?>" class="reciboT" width="30px">
			</td>
			<td>
				<?php echo $this->Utilities->date_castellano($value['Payment']['created']); ?>
			</td>
		</tr>
	<?php endforeach ?>
	</tbody>
</table>
Total: <?php echo number_format((int)h($resultado),0,",","."); ?>