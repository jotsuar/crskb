<div class="blockwhite">
  <h3 class="text-center m-4">
    Atención de flujos del usuario: <b><?php echo $user["User"]["name"] ?></b>
  </h3>
  <table cellpadding="0" cellspacing="0" class="myTable table table-hover table_resultados table-inbox hidden">
    <thead>
      <tr>
        <th class="cliente">Cliente</th>
        <th class="requerimiento">Requerimiento</th>
        <th class="asignado">Asignado</th>
        <th class="contactado text-center">Contactado</th>
        <th class="cotizado text-center">Cotizado</th>
        <th class="negocido text-center">Negociado</th>
        <th class="pagado text-center">Pagado</th>
        <th class="despachado text-center">Despachado</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($datos as $value): ?>
      <tr>
        
        <td class="uppercase">
          <?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['AtentionTime']['prospective_users_id']), 25,array('ellipsis' => '...','exact' => false)); ?>
        </td>
        <td class="uppercase">
          <?php echo $this->Text->truncate($this->Utilities->find_reason_prospective($value['AtentionTime']['prospective_users_id']), 25,array('ellipsis' => '...','exact' => false)); ?>
        </td>        
        <td>
          <?php echo $this->Utilities->data_null_date($value['AtentionTime']['asignado_date'].' '.$value['AtentionTime']['asignado_time'],'',$value['AtentionTime']['prospective_users_id']); ?>
        </td>
        <td class="text-center">
          <?php echo $this->Utilities->data_null_date($value['AtentionTime']['contactado_date'].' '.$value['AtentionTime']['contactado_time'],Configure::read('variables.nombre_flujo.flujo_asignado'),$value['AtentionTime']['prospective_users_id']); ?>
        </td>
        <td class="text-center">
          <?php echo $this->Utilities->data_null_date($value['AtentionTime']['cotizado_date'].' '.$value['AtentionTime']['cotizado_time'],Configure::read('variables.nombre_flujo.flujo_contactado'),$value['AtentionTime']['prospective_users_id']); ?>
        </td>
        <td class="text-center">
          <?php echo $this->Utilities->data_null_date($value['AtentionTime']['negociado_date'].' '.$value['AtentionTime']['negociado_time'],'',$value['AtentionTime']['prospective_users_id']); ?>
        </td>
        <td class="text-center">
          <?php echo $this->Utilities->data_null_date($value['AtentionTime']['pagado_date'].' '.$value['AtentionTime']['pagado_time'],'',$value['AtentionTime']['prospective_users_id']); ?>
        </td>
        <td class="text-center">
          <?php echo $this->Utilities->data_null_date($value['AtentionTime']['despachado_date'].' '.$value['AtentionTime']['despachado_time'],'',$value['AtentionTime']['prospective_users_id']); ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
