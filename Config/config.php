<?php

$fecha_actual = date("d-m-Y");

$config = array(
	"Informes" => [
		"request_import_brands_email" => "Solicitudes de importación",
		"informe_diario_clientes" => "Creación de clientes diaría",
		"servicios_sin_terminar" => "Servicios técnicos sin terminar",
		"reporte_sin_asignar" => "Servicios técnicos sin asignar técnico",
		"flujos_sin_gestionar" => "Flujos sin gestionar en estado pagado",
		"servicios_sin_pago" => "Servicios técnicos que no tienen pago asociado",
		"informe_sinterminar" => "Informe de productos sin facturar",
	],
	'Application' => array(
		'name' 	  => 'CRM - AlmacenDelPintor.com',
		'version' => 'CAKEPHP v2.10.10',
		'status'  => false,
		'maintenance' => 0,
		'TOKEN_WPP' => '7e44hvujtdekgn89',
		'URL_WPP' => 'https://eu55.chat-api.com/instance135506/',
		'IMAGE_B64' => 'data:image/jpeg;base64,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',
		'IMG_WHATSAPP' => 'data:image/png;base64,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',
	),
	'Meta' => array(
		'title' 	  => '{{title}}',
		'description' => '{{description}}',
		'keywords' 	  => '{{keywords}}',
	),
	
	'Google' => array(
		'analytics'  => '{{analytics}}',
	),
	'TOKEN_CHAT' => '57b9ef2591277ab300134d7a4d18dfb5b8a9b242',
	'URL_CHAT' => 'http://localhost/chatApp/include/api.php',
	'URL_CHAT_CONVERSATION' => 'http://localhost/ChatApp/admin.php?conversation=',
	'PrefijoNAC' => "KREMNAC", 
	'PrefijoINT' => "KREMINT", 
	
	'Email' => array(
		'from_email' 		=> array('Nuevo mensaje' => 'info@kebco.co'),
		'contact_mail' 		=> array('info@kebco.co' => 'Nuevo mensaje'),
		'contact_gerencia' 	=> array('info@kebco.co' => 'Informe Gerencia'),
		'brand_mail' 		=> array('logistica@kebco.co' => 'Nuevo mensaje'),
		'remarketing' 		=> array('mercadeo@almacendelpintor.com' => 'Nuevo mensaje KEBCO')
	),
	"email_shippings" 		=> ["ventas2@almacendelpintor.com","logistica@kebco.co"],
	"TYPE_REQUEST_IMPORT"	=> array(
		"SALES_NO_INVENTORY" => 1,
		"USER_PETITION"		 => 2,
		"SALES_TO_INVENTORY" => 3,
	),
	"DIAS_NOTIFY_ONE" => 3,
	"DIAS_NOTIFY_TWO" => 8,
	"DIAS_PRORROGA_ONE" => 12,
	"DIAS_PRORROGA_TWO" => 15,
	'PQRS' => [
		"FELICITACIÓN" => "FELICITACIÓN",
		"PETICIÓN" => "PETICIÓN",
		"QUEJA" => "QUEJA",
		"RECLAMO" => "RECLAMO",
		"SUGERENCIA" => "SUGERENCIA"

	],
	"Subjects" => [
		"1" => "Solicitud de asociación de uno o más productos",
		"2" => "Solicitud de asociación de uno o más clientes",
		"3" => "Mensaje normal",
	],
	"VALOR_ST" => 0.2,
	"GRUPOS_PRODUCTOS" => array(
		"1" => "Grupo 1",
		"2" => "Grupo 2",
		"3" => "Grupo 3",
		"4" => "Grupo 4",
		"5" => "Grupo 5",
		"6" => "Grupo 6",
		"7" => "Grupo 7",
		"8" => "Grupo 8",
		"9" => "Grupo 9",
		"10" => "Grupo 10",
	),
	"DATOS_DIRECTOR" => [
		"VENTAS" => [
			"BASE" => 1300000,
			"RANGOS" => [
			    ["min" => 260000000, "max" => 300000000, "percent" => 20],
			    ["min" => 300000000, "max" => 330000000, "percent" => 30],
			    ["min" => 330000000, "max" => 360000000, "percent" => 40],
			    ["min" => 360000000, "max" => 390000000, "percent" => 50],
			    ["min" => 390000000, "max" => 420000000, "percent" => 60],
			    ["min" => 420000000, "max" => 450000000, "percent" => 70],
			    ["min" => 450000000, "max" => 480000000, "percent" => 80],
			    ["min" => 480000000, "max" => 510000000, "percent" => 90],
			    ["min" => 510000000, "max" => 540000000, "percent" => 100],
			    ["min" => 540000000, "max" => 570000000, "percent" => 110],
			    ["min" => 570000000, "max" => 600000000, "percent" => 120],
			    ["min" => 600000000, "max" => 2000000000, "percent" => 130],
			],
		],
		"CARTERA" => [
			"BASE" => 200000,
			"RANGOS" => [
			    ["min" => 50000000, "max" =>100000000, "percent" => 0],
			    ["min" => 40000000, "max" =>50000000 , "percent" => 20],
			    ["min" => 30000000, "max" =>40000000 , "percent" => 40],
			    ["min" => 20000000, "max" =>30000000 , "percent" => 60],
			    ["min" => 10000000, "max" =>20000000 , "percent" => 80],
			    ["min" => 0, "max" =>10000000 , "percent" => 100],

			]
 		],
 		"SERVICIO" => [
			"BASE" => 100000,
			"RANGOS" => [
			    ["min" => 7, "max" =>2000 , "percent" => 0],
			    ["min" => 5, "max" =>7 , "percent" => 60],
			    ["min" => 3, "max" =>5 , "percent" => 70],
			    ["min" => 2, "max" =>3 , "percent" => 80],
			    ["min" => 1, "max" =>2 , "percent" => 90],
			    ["min" => 0, "max" =>1 , "percent" => 100],
			]
 		],
 		"MARGEN" => [
			"BASE" => 300000,
			"RANGOS" => [
			    ["min" => -100, "max" => 19.9, "percent" => 0],
			    ["min" => 20, 	"max" =>24.9, "percent" => 60],
			    ["min" => 25, 	"max" =>29.9, "percent" => 70],
			    ["min" => 30, 	"max" =>34.9, "percent" => 80],
			    ["min" => 35, 	"max" =>39.9, "percent" => 90],
			    ["min" => 40, 	"max" =>200, "percent" => 100],
			]
 		],
 		"NUEVOS" => [
			"BASE" => 100000,
			"RANGOS" => [
			    ["min" => 0, 		"max" =>5000000, "percent" => 60],
			    ["min" => 5000000, 	"max" =>10000000, "percent" => 70],
			    ["min" => 10000000,	"max" =>15000000, "percent" => 80],
			    ["min" => 15000000, "max" =>25000000, "percent" => 90],
			    ["min" => 25000000, "max" =>15000000000, "percent" => 100],
			]
 		],
 		"CONTACTADO" => [
 			"BASE" => 20000,
 			"RANGOS" => [
			    ["min" => 1, 		"max" =>10000000, "percent" => 0],
 				["min" => 0, 		"max" =>0, "percent" => 100],
 			]
 		],
 		"COTIZADO" => [
 			"BASE" => 20000,
 			"RANGOS" => [
			    ["min" => 1, 		"max" =>10000000, "percent" => 0],
 				["min" => 0, 		"max" =>0, "percent" => 100],
 			]
 		]
	],

	"COMPANY" => array(
		"NAME" => "KEBCO S.A.S.",
		"NIT" => "900412283 - 0",
		"ADDRESS" => "CALLE 10 # 52A - 18 INT 104",
		"CITY" => "Medellín, Colombia",
		"TELCOMPANY" => "PBX (4) 448 5566",
		"CALL_FREE_NUMBER" => "018000 425700"
	),
	"PAYMENT_TYPE" => array(
		1 => "Contado",
		2 => "50/50 por importación",
		3 => "Crédito 30 días",
		4 => "Crédito 60 días",
		5 => "Crédito 90 días",
		6 => "Debido a la recepción",
        7 => "Cash in advance/ contado anticipado",
	),
	"PAYMENT_USD" => array(
		"CREDIT" => "Crédito",
		"CASH PAYMENT" => "Contado"
	),
	"PAYMENT_COP" => array(
		"Crédito" => "Crédito",
		"Contado" => "Contado"
	),
	"TYPE_REQUEST_IMPORT_DATA"	=> array(
		1 => "VENDIDO EN FLUJO",
		2 => "SOLUCITUD INTERNA",
		3 => "REPOSICIÓN DE INVENTARIO",
		4 => "REPOSICIÓN DE INVENTARIO DE ALTA ROTACIÓN",
	),
	"INVENTORY_TYPE" => [
        "CARGA_INICIAL"             => 1,
        "ENTRADA_MANUAL"            => 2,
        "ENTRADA_IMPORTACION"       => 3,
        "SALIDA_VENTA_NORMAL"       => 4,
        "SALIDA_MANUAL"             => 5
    ], 
    "INVENTORY_TYPE_REASON" => [
        1 => "Carga inicial/masiva de productos"              ,
        2 => "Entrada manual"             ,
        3 => "Entrada por importación de productos"        ,
        4 => "Salida por venta de productos"        ,
        5 => "Salida manual"              
    ], 
    "TYPES_MOVEMENT" => array(
    	"ENTRADA_INVENTARIO" => 1, 
    	"SALIDA_INVENTARIO" => 2, 
    ),
    "TYPES_MOVEMENT_TEXT" => array(
    	1 => "Entrada", 
    	2 => "Salida", 
    ),
    "CUSTOMERS_TYPE" => array(
    	"NATURAL" => 1, 
    	"LEGAL" => 2, 
    ),
    "PAISES" => array(
    	"Colombia" => "Colombia",
    	"Guatemala" => "Guatemala",
    	"Honduras" => "Honduras",
    	"El salvador" => "El salvador",
    	"Nicaragua" => "Nicaragua",
    	"Costa Rica" => "Costa Rica",
    	"Panamá" => "Panamá",
    	"Haití" => "Haití",
    	"Jamaica" => "Jamaica",
    	"Republica Dominicana" => "Republica Dominicana",
    	"Venezuela" => "Venezuela",
    	"Ecuador" => "Ecuador",
    	"Perú" => "Perú",
    	"Bolivia" => "Bolivia",
    	"Paraguay" => "Paraguay",
    	"Uruguay" => "Uruguay",
    	"Argentina" => "Argentina",
    	"Chile" => "Chile",
    	"USA" => "USA",
    	"Brasil" => "Brasil",
    	"Mexico" => "Mexico",
    	"Cuba" => "Cuba",
    	"Puerto Rico" => "Puerto Rico",
    ),
    "MOVEVENTS" => array(
    	"RM" => "Salida",
    	"TR" => "Traslado",
    	"ADD" => "Entrada",
    ),
    "FORMA_PAGO" => array(
    	"Credito 30 Días",
    	"Credito 60 Días",
    	"Credito 90 Días",
    	"Credito 120 Días",
    	"Credito 180 Días",
    	"Contado",
    	"Trasferencia bancaria"
    ),
    "CUSTOMERS_TYPE_TEXT" => array(
    	1 => "Natural", 
    	2 => "Jurídico", 
    ),
    "IMPUESTOS" => array(
    	0 => "NO", 
    	1 => "SI", 
    ),
    "BODEGAS" => array(
    	"Medellín" => "Medellín",
    	"Bogotá" => "Bogotá",    
    	"ST Medellín" => "ST Medellín",    
    	"ST Bogotá" => "ST Bogotá",
    	"Transito" => "Transito"    
    ),
    'entregaProductValidation' 		=> array(
			'Inmediato' 				=> 0,
			'1-2 días hábiles' 			=> 1,
			'2-3 días hábiles' 			=> 2,
			'4-5 días hábiles' 			=> 3,
			'10-12 días hábiles' 		=> 4,
			'12-15 días hábiles' 		=> 5,
			'15-20 días hábiles' 		=> 6,
			'20-30 días hábiles' 		=> 9,
			'30-45 días hábiles' 		=> 7,
			'45-60 días hábiles' 		=> 8,
			'60-90 días hábiles' 		=> 10,
	),
	"entregaImport" => array(
		'2-3 días hábiles' 			=> '2-3 días hábiles',
		'4-5 días hábiles' 			=> '4-5 días hábiles',
		'10-12 días hábiles' 		=> '10-12 días hábiles',
		'12-15 días hábiles' 		=> '12-15 días hábiles',
		'15-20 días hábiles' 		=> '15-20 días hábiles',
		'20-30 días hábiles' 		=> '20-30 días hábiles',
		'30-45 días hábiles' 		=> '30-45 días hábiles',
		'45-60 días hábiles' 		=> '45-60 días hábiles',
		'60-90 días hábiles' 		=> '60-90 días hábiles',
	),


	"NUMBER_PURCHASE" => 1402,

	'variables' 			=> array(
		'password' 							=> 'K3bc02018',
		'hora_inicial_trabajo'				=> '08',
		'hora_fin_trabajo'					=> '17',
		'minutos_fin_trabajo'				=> '30',
		'horas_no_trabajadas' 				=> '15',
		'dias_no_habiles' 					=> array("Sat","Sun"),
		'meta_mes_total_empresa' 			=> 405000000,
		'codigo_aplicacion_id_gmail' 		=> '439071945992-94tj0p4q705fsjmm6danmpno6l669fvm.apps.googleusercontent.com',
		'codigo_cliente_secreto_gmail' 		=> 'yZ-qeexUojilBx3K1OQzsTf-',
		'api_key_google_aplication' 		=> 'AIzaSyAUADJj76f8d1xlrZtV5AuiuBO2EWWb8UE',
		'code_factura' 						=> 'KEB',
		'code_servicio_tecnico' 			=> 'ST',
		'code_importaciones' 				=> 'IMP',
		'code_importaciones_int'			=> 'INT',
		'state_disabled' 					=> '0',
		'state_enabled' 					=> '1',
		'state_waiting'						=> '2',
		'iva' 								=> '19',
		'valor_retencion' 					=> '2.5',
		'copy_descripcion_productos'        => 'En Kebco S.A.S www.almacendelpintor.com solo distribuimos productos, accesorios y repuestos 100% originales, ser distribuidores directos de fábrica de las mejores marcas de Estados Unidos nos permite tener buenos precios con los mejores tiempos de entrega.',
		'emails_defecto'					=> array('ventas@kebco.co'),
		// 'emails_defecto' 				=> array('gerencia@almacendelpintor.com','mercadeo@almacendelpintor.com'),
		// 'emails_defecto' 					=> array('ventasbogota@almacendelpintor.com','mercadeo@almacendelpintor.com'),
		'marcaProduct' 		=> array(
			'N/A' 					=> 'N/A',
			'Graco' 				=> 'Graco',
			'RPB Safety' 			=> 'RPB Safety',
			'Clemco' 				=> 'Clemco',
			'Marco' 				=> 'Marco',
			'PressurePro' 			=> 'PressurePro',
			'DeFelsko' 				=> 'DeFelsko',
			'Aurand' 				=> 'Aurand',
			'Dustless Blasting' 	=> 'Dustless Blasting',
			'Bullard' 				=> 'Bullard',
			'Kebco' 				=> 'Kebco',
			'Honda' 				=> 'Honda',
			'Viper' 				=> 'Viper',
			'Annovi Reverberi' 		=> 'Annovi Reverberi',
			'Eibenstock' 			=> 'Eibenstock',
			'CS Unitec' 			=> 'CS Unitec',
			'Collomix' 				=> 'Collomix',
			'Extech' 				=> 'Extech',
			'Zehntner' 				=> 'Zehntner',
			'Jason' 				=> 'Jason',
			'General Pump' 			=> 'General Pump',
			'ASM' 					=> 'ASM',
			'Paint Care' 			=> 'Paint Care',
			'Holdtight' 			=> 'Holdtight',
			'Amerquip' 				=> 'Amerquip',
			'ALC' 					=> 'ALC',
			'Cat Pump' 				=> 'Cat Pump',
			'Intertape' 			=> 'Intertape',
			'Elcometer' 			=> 'Elcometer',
			'Sullair' 				=> 'Sullair',
			'Redline' 				=> 'Redline',
			'SSCE' 					=> 'SSCE',
			'Gema' 					=> 'Gema',
			'Norgren' 				=> 'Norgren',
			'Pelican' 				=> 'Pelican',
			'Titán' 				=> 'Titán'
		),

		'accesorios_equipo_mantenimiento' => array(
			'Manguera' 							=> 'Manguera',
			'Pistola' 							=> 'Pistola',
			'Portaboquilla' 					=> 'Portaboquilla',
			'Boquilla' 							=> 'Boquilla',
			'Filtro manifold'					=> 'Filtro manifold',
			'Filtro de succión' 				=> 'Filtro de succión',
			'Filtro de pistola' 				=> 'Filtro de pistola',
			'Otros' 							=> 'Otros'
		),

		'estados_equipo_mantenimiento' => array(
			'Satisfactorio' 					=> 'Satisfactorio',
			'Aceptable' 						=> 'Aceptable',
			'Deficiente' 						=> 'Deficiente'
		),

		'type_note_quotation' => array(
			'1' 					=> 'Nota previa',
			'2' 					=> 'Nota descriptiva',
			'3' 					=> 'Condición del negocio',
			'5' 					=> 'Garantía general',
		),

		'fleteEnvio' 		=> array(
			'Contraentrega' 		=> 'Contraentrega',
			'Tienda' 				=> 'Recoge en tienda',
			'Pagado' 				=> 'Pagado'
		),

		'importaciones' 		=> array(
			'proceso'					=> '1', //aprobado
			'finalizadas'				=> '2',
			'solicitud'					=> '3', //pendiente
			'rechazado' 				=> '4'
		),

		'entregaProduct' 		=> array(
			'Inmediato' 				=> 'Inmediato',
			'1-2 días hábiles' 			=> '1-2 días hábiles',
			'2-3 días hábiles' 			=> '2-3 días hábiles',
			'4-5 días hábiles' 			=> '4-5 días hábiles',
			'10-12 días hábiles' 		=> '10-12 días hábiles',
			'12-15 días hábiles' 		=> '12-15 días hábiles',
			'15-20 días hábiles' 		=> '15-20 días hábiles',
			'20-30 días hábiles' 		=> '20-30 días hábiles',
			'30-45 días hábiles' 		=> '30-45 días hábiles',
			'45-60 días hábiles' 		=> '45-60 días hábiles',
			'60-90 días hábiles' 		=> '60-90 días hábiles',
		),

		'entregaProductValues' 		=> array(
			'Inmediato' 				=> 0,
			'1-2 días hábiles' 			=> 2,
			'2-3 días hábiles' 			=> 3,
			'4-5 días hábiles' 			=> 5,
			'10-12 días hábiles' 		=> 12,
			'12-15 días hábiles' 		=> 15,
			'15-20 días hábiles' 		=> 20,
			'20-30 días hábiles' 		=> 30,
			'30-45 días hábiles' 		=> 45,
			'45-60 días hábiles' 		=> 60,
			'60-90 días hábiles' 		=> 90
		),

		'origenContact' 		=> array(
			'Chat' 						=> 'Chat',
			'Whatsapp' 					=> 'Whatsapp',
			'Email' 					=> 'Email',
			'Llamada' 					=> 'Llamada',
			'Redes sociales' 			=> 'Redes sociales',
			'Presencial' 				=> 'Presencial',
			'Referido' 					=> 'Referido',
			'Chat Pelican' 				=> 'Chat Pelican',
			'Whatsapp Kebco USA' 		=> 'Whatsapp Kebco USA',
			'Email Kebco USA' 			=> 'Email Kebco USA',
			'Chat Kebco USA' 			=> 'Chat Kebco USA',
			'Landing' 					=> 'landing',
			'Marketing' 				=> 'Marketing',
			'Feria' 					=> 'Feria',
		),

		'mediosPago' 			=> array(
			'Consignación Bancaria' 		=> 'Consignación Bancaria',
			'Datáfono' 						=> 'Datáfono',
			'Por la página' 				=> 'Por la página',
			'Tarjeta Éxito' 				=> 'Tarjeta Éxito',
			'Efectivo' 						=> 'Efectivo'
		),

		'transportadoras' 		=> array(
			'Interrapidisimo' 					=> 'Interrapidisimo',
			'TCC' 								=> 'TCC',
			'Saferbo' 							=> 'Saferbo',
			'Deprisa' 							=> 'Deprisa',
			'Servientrega' 						=> 'Servientrega',
			'Envia'								=> 'Envia',
			'Terminal de transporte' 			=> 'Terminal de transporte',
			'Coordinadora' 						=> 'Coordinadora',
			'Entrega en Oficina' 				=> 'Entrega en Oficina',
			'Aeropuerto - Aeropuerto' 			=> 'Aeropuerto - Aeropuerto'
		),

		'roles_usuarios' 		=> array(
			'Servicio Técnico' 						=> 'Servicio Técnico',
			'Asesor Comercial' 						=> 'Asesor Comercial',
			'Asesor Técnico Comercial' 				=> 'Asesor Técnico Comercial',
			'Gerente General' 						=> 'Gerente General',
			'Contabilidad' 							=> 'Contabilidad',
			'Servicio al Cliente' 					=> 'Servicio al Cliente',
			'Logística' 							=> 'Logística',
			'Administración' 						=> 'Administración',
			'Publicidad' 							=> 'Publicidad',
			'Gerente línea Productos Pelican' 		=> 'Gerente línea Productos Pelican',
			'Asesor Logístico Comercial' 			=> 'Asesor Logístico Comercial'
		),

		'diasFestivos'		=> array(
									'01-01-2019',
									'07-01-2019',
									'25-03-2019',
									'18-04-2019',
									'19-04-2019',
									'01-05-2019',
									'03-06-2019',
									'24-06-2019',
									'01-07-2019',
									'20-07-2019',
									'07-08-2019',
									'19-08-2019',
									'14-10-2019',
									'04-11-2019',
									'11-11-2019',
									'25-12-2019',
									'01-01-2020',
									'06-01-2020',
									'23-03-2020',
									'09-04-2020',
									'10-04-2020',
									'01-05-2020',
									'25-05-2020',
									'15-06-2020',
									'22-06-2020',
									'29-06-2020',
									'20-07-2020',
									'07-08-2020',
									'17-08-2020',
									'12-10-2020',
									'02-11-2020',
									'16-11-2020',
									'08-12-2020',
									'25-12-2020',
									"2022-01-01","2022-01-10","2022-03-21","2022-04-14","2022-04-15","2022-05-01","2022-05-30","2022-06-20","2022-06-27","2022-07-04","2022-07-20","2022-08-07","2022-08-15","2022-10-17","2022-11-07","2022-11-14","2022-12-08","2022-12-25","2023-01-01","2023-01-06","2023-01-09","2023-03-19","2023-03-20","2023-04-06","2023-04-07","2023-05-01","2023-05-22","2023-06-12","2023-06-19","2023-06-29","2023-07-03","2023-07-20","2023-08-07","2023-08-21","2023-10-16","2023-11-01","2023-11-06","2023-11-11","2023-11-13","2023-12-08","2023-12-25","2024-01-01","2024-01-06","2024-01-08","2024-03-19","2024-03-25","2024-03-28","2024-03-29","2024-05-01","2024-05-13","2024-06-03","2024-06-10","2024-06-29","2024-07-01","2024-07-20","2024-08-07","2024-08-15","2024-08-19","2024-10-12","2024-10-14","2024-11-01","2024-11-04","2024-11-11","2024-12-08","2024-12-25","2025-01-01","2025-01-06","2025-03-19","2025-03-24","2025-04-17","2025-04-18","2025-05-01","2025-06-02","2025-06-23","2025-06-29","2025-06-30","2025-06-30","2025-07-20","2025-08-07","2025-08-15","2025-08-18","2025-10-12","2025-10-13","2025-11-01","2025-11-03","2025-11-11","2025-11-17","2025-12-08","2025-12-25"
		),

		'Gestiones_descripcion' 	=> array(
			'flujo_asignado' 							=> 'Cliente asignado',
			'flujo_contactado' 							=> 'Enviar cotización a',
			'flujo_cotizado' 							=> '',
			'flujo_negociado' 							=> '',
			'flujo_pagado' 								=> '',
			'flujo_despachado' 							=> '',
			'verificar_pago' 							=> 'Han registrado un nuevo pago, por favor verificar',
			'logistica_pedido_gestionado' 				=> 'Han gestionado los productos de un pedido y el asesor no ha seleccionado ningún producto para importación y recuerda que te debes comunicar con el cliente para solicitar los datos para el despacho, ',
			'logistica_pedido_gestionado_importacion' 	=> 'Han gestionado los productos de un pedido, por favor verifica las solicitudes de importación y recuerda que te debes comunicar con el cliente para solicitar los datos para el despacho, ',
			'pago_verificado_asesor' 					=> 'El pago fue APROBADO, por favor valida si en la cotización se encuentran productos para importación, RECUERDA que debes hacer la solicitud',
			'pago_verificado_credito' 					=> 'El pago a crédito fue APROBADO, por favor valida si en la cotización se encuentran productos para importación, RECUERDA que debes hacer la solicitud',
			'pago_no_verificado_abono' 					=> 'El fin del pago en abono fue RECHAZADO, por favor contacta a contabilidad',
			'pago_no_verificado' 						=> 'El pago ha sido RECHAZADO, por favor contacta a contabilidad',
			'pago_abono' 								=> 'El pago en abono ha sido APROBADO, por favor valida si el cliente no a terminado de pagar la cotización, si ya finalizo y en la cotización se encuentran productos para importación, RECUERDA que debes hacer la solicitud',
			'enviar_despacho' 							=> 'Tienes una nueva orden de despacho para',
			'pedido_entregado' 							=> 'Se ha confirmado la entrega del pedido',
			'cancelacion_flujo' 						=> 'El cliente no solicitó cotización',
			'asesor_asignado' 							=> 'Te han asignado un flujo en proceso',
			'verify_pago_abono' 						=> 'El asesor solicita que verifiques el pago en abonos para el flujo'
		),
		
		'Gestiones_horas_habiles' 	=> array(
			'flujo_asignado' 			=> '2',
			'flujo_contactado' 			=> '24',
			'flujo_cotizado' 			=> '48',
			'flujo_negociado' 			=> '0',
			'flujo_pagado' 				=> '0',
			'flujo_despachado' 			=> '0',
			'verificar_pago' 			=> '24',
			'pago_verificado' 			=> '24',
			'enviar_despacho' 			=> '24',
			'envio_confirmado' 			=> '01'
		),

		'control_flujo'			=> array(
			'flujo_asignado' 			=> '1',
			'flujo_contactado' 			=> '2',
			'flujo_cotizado' 			=> '3',
			'flujo_negociado' 			=> '4',
			'flujo_pagado' 				=> '5',
			'flujo_despachado' 			=> '6',
			'flujo_cancelado' 			=> '7',
			'flujo_finalizado' 			=> '8',
			'flujo_no_valido' 			=> '9'
		),

		'control_importacion' 	=> array(
			'solicitud_importacion' 	=> '1',
			'orden_montada' 			=> '2',
			'despacho_proveedor' 		=> '3',
			'llegada_miami' 			=> '4',
			'despacho_amerimpex' 		=> '5',
			'nacionalizacion' 			=> '6',
			'producto_empresa'			=> '7'
		),

		'nombre_flujo'			=> array(
			'flujo_asignado' 			=> 'Asignado',
			'flujo_contactado' 			=> 'Contactado',
			'flujo_cotizado' 			=> 'Cotizado',
			'flujo_negociado' 			=> 'Negociado',
			'flujo_pagado' 				=> 'Pagado',
			'flujo_despachado' 			=> 'Despachado',
			'datos_despacho' 			=> 'Despacho',
			'flujo_cancelado' 			=> 'Cancelado',
			'pago_no_verificado' 		=> 'Pago false',
			'pago_verificado' 			=> 'Pago true',
			'pago_abono' 				=> 'Pago abono',
			'verificar_pago' 			=> 'Verificación pago',
			'pedido_entregado' 			=> 'Pedido entregado',
			'enviar_despacho' 			=> 'Enviar despacho',
			'asignado_flujo_proceso' 	=> 'Asignación de flujo en proceso',
			'pago_credito_true' 		=> 'Pago a credito',
			'flujo_no_valido' 			=> 'Flujo no válido'
		)
	)
);

?>
