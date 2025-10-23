<?php 
	$jsTranslations = array(
		'user_id'							=> AuthComponent::user('id'),
		'user_role'							=> AuthComponent::user('role'),
		'user_email'						=> AuthComponent::user('email'),
		'role_servicio_al_cliente' 			=> Configure::read('variables.roles_usuarios.Servicio al Cliente'),
		'role_asesor_comercial'		 		=> Configure::read('variables.roles_usuarios.Asesor Comercial'),
		'role_asesor_tecnico_comercial' 	=> Configure::read('variables.roles_usuarios.Asesor Técnico Comercial'),
		'role_gerencia_pelican' 			=> Configure::read('variables.roles_usuarios.Gerente línea Productos Pelican'),
		'role_gerencia' 					=> Configure::read('variables.roles_usuarios.Gerente General'),
		'role_contabilidad' 				=> Configure::read('variables.roles_usuarios.Contabilidad'),
		'role_logistica' 					=> Configure::read('variables.roles_usuarios.Logística'),
		'role_administracion' 				=> Configure::read('variables.roles_usuarios.Administración'),
		'role_servicio_tecnico' 			=> Configure::read('variables.roles_usuarios.Servicio Técnico'),
		'role_asesor_logitico_comercial' 	=> Configure::read('variables.roles_usuarios.Asesor Logístico Comercial'),
		'base_url'							=> $this->webroot,
		'controller'						=> $this->request->controller,
		'controller_menu'					=> mb_strtoupper($this->request->controller),
		'action'							=> $this->request->action,
		'flujo_asignado'					=> Configure::read('variables.control_flujo.flujo_asignado'),
		'flujo_contactado'					=> Configure::read('variables.control_flujo.flujo_contactado'),
		'flujo_cotizado'					=> Configure::read('variables.control_flujo.flujo_cotizado'),
		'flujo_negociado'					=> Configure::read('variables.control_flujo.flujo_negociado'),
		'flujo_pagado'						=> Configure::read('variables.control_flujo.flujo_pagado'),
		'flujo_despachado'					=> Configure::read('variables.control_flujo.flujo_despachado'),
		'importacion_solicitud' 			=> Configure::read('variables.control_importacion.solicitud_importacion'),
		'importacion_orden' 				=> Configure::read('variables.control_importacion.orden_montada'),
		'importacion_despacho' 				=> Configure::read('variables.control_importacion.despacho_proveedor'),
		'importacion_miami' 				=> Configure::read('variables.control_importacion.llegada_miami'),
		'importacion_amerimpex' 			=> Configure::read('variables.control_importacion.despacho_amerimpex'),
		'importacion_nacionalizacion' 		=> Configure::read('variables.control_importacion.nacionalizacion'),
		'importacion_producto_empresa' 		=> Configure::read('variables.control_importacion.producto_empresa'),
		'nombre_flujo_asignado' 			=> Configure::read('variables.nombre_flujo.flujo_asignado'),
		'nombre_flujo_contactado' 			=> Configure::read('variables.nombre_flujo.flujo_contactado'),
		'btn_action'						=> true,
		'modal_aviso'						=> false,
		'duration_sesion_false'				=> 9123456,
		'codigo_aplicacion_google'			=> Configure::read('variables.codigo_aplicacion_id_gmail'),
		'secret_client_google'				=> Configure::read('variables.codigo_cliente_secreto_gmail'),
		'api_key_cliente_google'			=> Configure::read('variables.api_key_google_aplication'),
		'state_disabled' 					=> Configure::read('variables.state_disabled'),
		'state_enabled' 					=> Configure::read('variables.state_enabled'),
		'state_waiting' 					=> Configure::read('variables.state_waiting'),
		'iva' 								=> Configure::read('variables.iva'),
		'copy_descripcion_productos' 		=> Configure::read('variables.copy_descripcion_productos'),
		'TOKEN_CHAT' 						=> Configure::read('TOKEN_CHAT'),
		'URL_CHAT' 							=> Configure::read('URL_CHAT'),
		'URL_CHAT_CONVERSATION' 			=> Configure::read('URL_CHAT_CONVERSATION'),
		// 'valor_retencion' 					=> Configure::read('variables.valor_retencion')
	);
	if (isset($notices_gerencia[0])) {
      $jsTranslations['modal_aviso'] 	= true;
    }
	// echo $this->Html->scriptBlock("copy_js =".,				array('block' => 'variablesAppScript'));
?>

<script>
	const copy_js = <?php echo json_encode($jsTranslations); ?>;
</script>