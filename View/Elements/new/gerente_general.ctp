<?php if (AuthComponent::user("role") != "Publicidad"): ?>
  

    <ul class="nav side-menu" style="margin-bottom: 50px !important; padding-bottom: 150px !important">
      <li class="bg-naranja"><a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=> "home_adviser" )) ?>"><i class="fa flaticon-menu-1 ml-0"></i> Dashboard</a></li>
      <li class="bg-green mb-2"><a href="<?php echo $this->Html->url(array('controller'=>'carpetas','action'=> "index" )) ?>"><i class="fa fa-book ml-0"></i> Biblioteca</a></li>
      <li class="bg-secondary mb-2">
        <a href="<?php echo $this->Html->url(array('controller'=>'commitments','action'=> "index", "?" => ["calendar" =>time() ] )) ?>"><i class="fa fa-book ml-0"></i> Gestión de  compromisos</a>
      </li>
      <li class="bg-azulclaro"><a><i class="fa flaticon-growth ml-0"></i> Gestión CRM <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li>
              <?php if (in_array(AuthComponent::user("id"), $usersApprovePermission)): ?>            
                <a>Flujos <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                  <li class="sub_menu"><a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>">Gestión </a>
                  </li>
                  <?php if (AuthComponent::user("id") && in_array(AuthComponent::user("email"), ["jotsuar@gmail.com", "gerencia@almacendelpintor.com",'logistica@kebco.co'])): ?>
                     <li>
                        <a href="<?php echo $this->Html->url(array('controller'=>'autorizations','action'=>'index')) ?>">
                          Autorización de abonos 
                        </a>
                    </li>  
                    <li>
                        <a href="<?php echo $this->Html->url(array('controller'=>'times','action'=>'index')) ?>">
                          Configuración de tiempos Chat
                        </a>
                    </li> 
                    
                  <?php endif ?>
                  <?php if (in_array(AuthComponent::user("id"), $usersApprovePermission)): ?>   
                    <li>
                        <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'locked_flows')) ?>">
                          Gestión bloqueos por gestión inicial
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'panel_gest')) ?>">
                          Gestión bloqueos por gestión de cotizaciones
                        </a>
                    </li>             
                    <li>
                        <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'aprovee')) ?>">Aprobaciones <b> <span class="counterAprove" style="font-size: 20px;"><?php echo $this->Utilities->count_cotizaciones_aprobar(); ?></span>  </b></a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'aprovee_cancel')) ?>">Aprob. cancelaciones</a>
                    </li>
                  <?php endif ?>
                </ul>
              <?php else: ?>
                <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>">Flujos</a>
              <?php endif ?>
          </li>
          <?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
             
          <?php endif ?>
          <li><a>Cotizaciones <span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                <li class="sub_menu">
                    <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'quotes_sent')) ?>" >
                      COT generadas <b><?php echo AuthComponent::user("role") == "Asesor Externo" ? "" : $this->Utilities->count_cotizaciones_enviadas(); ?> </b>
                    </a>
                </li>
                <?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
                  
                  <li class="sub_menu">
                      <a href="<?php echo $this->Html->url(array('controller'=>'Quotations','action'=>'tracking')) ?>" >
                        COT sin leer/click </b>
                      </a>
                  </li>
                  <li class="sub_menu">
                      <a href="<?php echo $this->Html->url(array('controller'=>'Templates','action'=>'index')) ?>" >
                        Plantillas
                      </a>
                  </li>
                  <li class="sub_menu">
                    <a href="<?php echo $this->Html->url(array('controller'=>'Notes','action'=>'index')) ?>" >
                      Notas
                    </a>
                  </li>
                  <li class="sub_menu">
                    <a href="<?php echo $this->Html->url(array('controller'=>'Headers','action'=>'index')) ?>" >
                      Banners
                    </a>
                  </li> 
                  <li class="sub_menu">
                    <a href="<?php echo $this->Html->url(array('controller'=>'quotations_marketings','action'=>'index', $this->Utilities->encryptString('bot_kebco'))) ?>" >
                      Cotizaciones BOT KEBCO
                    </a>
                  </li> 
                  
                <?php endif ?>
              </ul>
          </li>
          <li><a> Clientes <span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                <li class="sub_menu">
                  <a href="<?php echo $this->Html->url(array('controller'=>'ClientsLegals','action'=>'index')) ?>" >
                    Empresas
                  </a>
                </li> 
                <li class="sub_menu">
                  <a href="<?php echo $this->Html->url(array('controller'=>'ClientsNaturals','action'=>'index')) ?>" >
                    Naturales
                  </a>
                </li> 
              </ul>
          </li>
          <?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
            <li>
              <a href="<?php echo $this->Html->url(array('controller'=>'pqrs','action'=>'index')) ?>">PQRS</a>
            </li>
          <?php endif ?>
          <?php if (AuthComponent::user("role") == "Gerente General"): ?>
            <li>
              <a href="<?php echo $this->Html->url(array('controller'=>'quizzes','action'=>'index')) ?>">Encuestas</a>
            </li>
            
          <?php endif ?>
          <?php if (in_array(AuthComponent::user("role"), ["Gerente General","Logística","Servicio al Cliente","Asesor Comercial"])): ?>
            <li>
              <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_sinterminar')) ?>">
                Productos no facturados
              </a>
            </li>
            <li>
              <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'facturas')) ?>" class="text-white">
                Gestión de facturas
              </a>
            </li> 
          <?php endif ?>
          <li>
              <a href="<?php echo $this->Html->url(array('controller'=>'customer_requests','action'=>'index')) ?>">Solicitud de creación de clientes</a>
            </li>
        </ul>
      </li>
      <?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
        <li class="bg-danger">
          <a href="javascript:void(0)"><i class="fa fa-file ml-0"></i> Marketing</a>
          <ul class="nav child_menu">
            <li class="sub_menu">
              <a href="<?php echo $this->Html->url(array('controller'=>'quotations_marketings','action'=>'index')) ?>" >
                Cotizaciones para marketing
              </a>
            </li> 
            <li>
                <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'flujo_masivo')) ?>" >
                  Importar masivamente
                </a>
            </li>
             <?php if (in_array(AuthComponent::user("role"), ["Gerente General","Servicio al Cliente"])): ?>
                  <li class="sub_menu">
                    <a href="<?php echo $this->Html->url(array('controller'=>'campaigns','action'=>'index')) ?>" >
                      Remarketing de productos
                    </a>
                  </li>              
                <?php endif ?>
          </ul>
        </li>
      <?php endif ?>
      <?php if (in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])): ?>
        <!-- <li class="bg-warning btnRC"><a href="javascript:void(0)"><i class="fa fa-file ml-0"></i>Cargar RC</a></li> -->
      <?php endif ?>
      <li class="bg-info"><a><i class="fa fa-shopping-bag"></i> Inventario <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
            <li><a href="<?php echo $this->Html->url(array('controller'=>'Brands','action'=>'index')) ?>">Proveedores</a></li>
            <li><a href="<?php echo $this->Html->url(array('controller'=>'Categories','action'=>'index')) ?>">Categorías</a></li>
          <?php endif ?>
          <li><a>Productos <span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                <li class="sub_menu">
                  <a href="<?php echo $this->Html->url(array('controller'=>'Products','action'=>'index')) ?>" >
                    Gestión de productos
                  </a>
                </li> 
                <?php if (in_array(AuthComponent::user("role"), ["Gerente General"])): ?>
                  <li class="sub_menu">
                    <a href="<?php echo $this->Html->url(array('controller'=>'products','action'=>'products_rotation')) ?>" >
                      Inventario de alta rotación
                    </a>
                  </li>
                <?php endif ?>
                <?php if (in_array(AuthComponent::user("role"), ["Gerente General","Logística"])): ?>
                  <li class="sub_menu">
                    <a href="<?php echo $this->Html->url(array('controller'=>'Inventories','action'=>'blocks')) ?>" >
                      Panel de bloqueos de productos
                    </a>
                  </li>  
                  <li class="sub_menu">
                    <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'panel_bloqueos_transito')) ?>" >
                      Panel productos bloqueados en tránsito
                    </a>
                  </li>      
                  <li>
                  <a href="<?php echo $this->Html->url(array('controller'=>'suggested_products','action'=>'index')) ?>" >
                    Productos relacionados o sugeridos
                  </a>
                </li>    
                <li>
                    <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'config_shipping')) ?>">
                      Gestión de tiempos despachos
                    </a>
                </li>      
                <?php endif ?>
              </ul>
              <?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
                <li>
                  <a>Atributos y características <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li class="sub_menu">
                      <a href="<?php echo $this->Html->url(array('controller'=>'features','action'=>'index')) ?>" >
                        Gestión de características
                      </a>
                    </li> 
                    <!-- <li class="sub_menu">
                      <a href="<?php echo $this->Html->url(array('controller'=>'Products','action'=>'index')) ?>" >
                        Gestión de atributos
                      </a>
                    </li>  -->
                  </ul>
                </li>
              <?php endif ?>
          </li>
        </ul>
      </li>
      <?php if (!in_array(AuthComponent::user("role"), ["Gerente General","Logística"])): ?>
        <?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
        <li class="bg-morado"><a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_approved')) ?>"><i class="fa flaticon-logistics-delivery-truck-and-clock ml-0"></i> O.C.</a></li>
        <?php else: ?>
          <li class="bg-morado"><a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_approved')) ?>"><i class="fa flaticon-logistics-delivery-truck-and-clock ml-0"></i> O.C. - Importaciones</a></li>
        <?php endif ?>
      <?php else: ?>
      <li class="bg-morado"><a><i class="fa flaticon-logistics-delivery-truck-and-clock ml-0"></i> O.C. <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li>
              <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'request_import_brands')) ?>">
                Módulo principal de compras nacional
              </a>
            </li>
            <li>
              <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'request_import_brands','internacional')) ?>">
                Módulo principal de compras internacional
              </a>
            </li>
            <li>
              <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_revisions')) ?>">
                Gestión y aprobaciones
              </a>
            </li>
            <li>
              <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_approved')) ?>">
                Órdenes en proceso
              </a>
            </li>
            <li>
              <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'import_ventas')) ?>">
                Reposición de inventario
              </a>
            </li>
            <li>
              <a href="<?php echo $this->Html->url(array('controller'=>'transits','action'=>'index')) ?>">
                Productos solicitados en transito
              </a>
            </li>
        </ul>
      </li>
    <?php endif; ?>
      <?php if (in_array(AuthComponent::user("role"), ["Gerente General","Logística","Contabilidad"])): ?>
        <li class="bg-verde"><a><i class="fa flaticon-money ml-0"></i> Tesoreria <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li>
              <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'compras_nacionales')) ?>" class="text-white">
                Compras de logística
              </a>
            </li>
            <li>
              <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'receipts')) ?>" class="text-white">
                Gestión de recibos de caja
              </a>
            </li> 
            <li>
              <a href="<?php echo $this->Html->url(array('controller'=>'recibos','action'=>'index')) ?>" class="text-white">
                <span>Recibos de caja no aplicados <i class="fa fa-ban d-inline vtc"></i></span>
              </a>
            </li>
            
             
            <?php if (AuthComponent::user("role") != "Logística2"): ?>
              
              <!-- <li>
                <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_comisiones_externas')) ?>" class="text-white">
                  Informe de comisiones externos
                </a>
              </li>  
              <li>
                <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_comisiones_externals_gest')) ?>" class="text-white">
                  Cuentas de cobro externas
                </a>
              </li>--> 
              <li>
                <a href="<?php echo $this->Html->url(array('controller'=>'goals','action'=>'index')) ?>" class="text-white">
                  Metas de facturación
                </a>
              </li>
              <li>
                <a href="<?php echo $this->Html->url(array('controller'=>'collection_goals','action'=>'index')) ?>" class="text-white">
                  Metas de recaudo
                </a>
              </li>
              <li><a>Aprobación de pagos <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li class="sub_menu">
                      <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment')) ?>" >
                      Verificación de Pagos
                    </a>
                    </li> 
                    <li class="sub_menu">
                      <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment_tienda')) ?>" >
                      Verificación de pagos en tienda
                    </a>
                    </li>
                    <li class="sub_menu">
                      <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment_credito')) ?>" >
                      Verificación de Créditos
                    </a>
                    </li>
                    <li class="sub_menu">
                      <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payments_payments')) ?>" >
                      Verificar abonos totales
                    </a>
                    </li>
                  </ul>
              </li>
              <li><a>Historial de pagos <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li class="sub_menu">
                      <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'payment_true')) ?>" >
                      Pagos verificados
                    </a>
                    </li>
                    <li class="sub_menu">
                      <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'payment_false')) ?>" >
                      Pagos rechazados
                    </a>
                    </li>
                    <li class="sub_menu">
                      <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'payment_datafono')) ?>" >
                      Pagos Datáfono
                    </a>
                    </li>
                  </ul>
              </li>
              <li>
                <a href="<?php echo $this->Html->url(array('controller'=>'liquidations','action'=>'index')) ?>" >
                  Liquidación de comisiones
                </a>
              </li>
              <li><a>Informes <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                   <li class="sub_menu">
                      <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'ventas_report',"?"=>array("ini" =>date("Y-m-d"), "end" => date("Y-m-d")  ))) ?>" >
                        Informe de ventas
                      </a>
                    </li>
                    <li class="sub_menu">
                            <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_comisiones')) ?>" >
                        Informe de comisiones
                      </a>
                    </li>
                    <li class="sub_menu">
                        <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_director')) ?>" >
                          Informe director comercial
                        </a>
                    </li>
                    <li class="sub_menu">
                            <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_ventas_tienda')) ?>" >
                        Informe de ventas en tienda
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo $this->Html->url(array('controller'=>'Receipts','action'=>'report',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" >
                        Recibos de caja
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo $this->Html->url(array('controller'=>'accounts','action'=>'report',"?"=>array("ini" =>date("Y-m-d",strtotime("- 30 day")), "end" => date("Y-m-d")  ))) ?>" >
                        Pagos asesores externos
                      </a>
                    </li>
                    
                  </ul>
              </li>
            <?php endif ?>
          </ul>
        </li>
      <?php endif ?>
      <?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
      <li class="bg-cafe"><a><i class="fa flaticon-report-1"></i> Informes <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <?php if (AuthComponent::user("role") == "Gerente General" ): ?>
            <li>
            <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'ventas_report',"?"=>array("ini" =>date("Y-m-d"), "end" => date("Y-m-d")  ))) ?>" >
              Informe real de ventas
            </a>
          </li>
          <li class="sub_menu">
            <a href="<?php echo $this->Html->url(array('controller'=>'pages','action'=>'datos_productos')) ?>" >
              Informe de venta por linéas de negocio
            </a>
          </li>
          <li class="sub_menu">
            <a href="<?php echo $this->Html->url(array('controller'=>'products','action'=>'report_quotations_products')) ?>" >
              Informe de cotizaciones por producto
            </a>
          </li>
          <?php endif ?>
          <li>
            <a href="<?php echo $this->Html->url(array('controller'=>'pages','action'=>'reports',"?"=>array("fecha_consulta" =>date("Y-m-d",strtotime("-1 day"))  ))) ?>" >
              Informe chats
            </a>
          </li>
          <li>
            <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_management',"?"=>array("ini" =>date("Y-m-01"), "end" => date("Y-m-d")  ))) ?>" >
              Informe efectividad
            </a>
          </li>
          <li>
            <a href="<?php echo $this->Html->url(array('controller'=>'Assists','action'=>'report_assists',"?"=>array("ini" =>date("Y-m-01"), "end" => date("Y-m-d")  ))) ?>" >
              Informe de asistencias
            </a>
          </li> 
          <li>
            <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'gestion_flujos',"?"=>array("ini" =>date("Y-m-d",strtotime("-1 day")), "end" => date("Y-m-d")  ))) ?>" >
              Gestión de flujos
            </a>
          </li> 
          <li class="sub_menu">
            <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_mercadeo',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" >
              Informe de linéas de negocio por chat
            </a>
          </li>
          <li>
            <a href="<?php echo $this->Html->url(array('controller'=>'pages','action'=>'datos_clientes')) ?>" >
              Ventas clientes vs Inversion Mercadeo
            </a>
          </li>
          <?php if (AuthComponent::user("role") == "Gerente General"): ?>
            <li>
              <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'cancelaciones_flujo',"?"=>array("ini" =>date("Y-m-d",strtotime("-1 day")), "end" => date("Y-m-d")  ))) ?>" >
                Reporte de cancelaciones de flujos
              </a>
            </li>
            <li>
                <a href="<?php echo $this->Html->url(array('controller'=>'SbTiempos','action'=>'index',"?"=>array("ini" =>date("Y-m-d",strtotime("-1 day")), "end" => date("Y-m-d")  ))) ?>">
                  Reporte de tiempos de conexión chat
                </a>
            </li>
          <?php endif ?>
          
        </ul>
      </li>
      <?php else: ?>
        <li class="bg-cafe"><a><i class="fa flaticon-report-1"></i> Informes <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li class="sub_menu">
                <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'ventas_report',"?"=>array("ini" =>date("Y-m-d"), "end" => date("Y-m-d")  ))) ?>" >
                  Informe de ventas
                </a>
              </li>
              <li class="sub_menu">
                <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_comisiones_externals')) ?>" >
                  Informe de comisiones ganadas sin pagar
                </a>
              </li>
              <li class="sub_menu">
                <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_comisiones_externals_gest')) ?>" >
                  Informe de comisiones gestionadas y/o Pagadas
                </a>
              </li>
          </ul>
        </li>
      <?php endif ?>
      <li class="bg-dark"><a href="<?php echo $this->Html->url(array('controller'=>'shippings','action'=>'index')) ?>"><i class="fa flaticon-logistics-delivery-truck-and-clock"></i> Solicitudes de despachos<span class="fa fa-chevron-down"></span></a>
      </li>
      <li class="bg-aguamarina"><a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'despachos')) ?>"><i class="fa flaticon-logistics-delivery-truck-and-clock"></i> Despachos ANT <span class="fa fa-chevron-down"></span></a>
      </li>
      <?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
      
      
      <li class="bg-rojo"><a><i class="fa flaticon-settings-1"></i> Servicio técnico <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li>
            <a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'index')) ?>" >
              Órdenes en Proceso
            </a>
          </li>
          <li>
            <a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'add')) ?>" >
              Ingreso de Equipos
            </a>
          </li> 
          <li>
            <a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'flujos')) ?>" >
              Negocios desde ST
            </a>
          </li>
          <li>
            <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'reporte_tecnico')) ?>" >
              Reporte de ventas
            </a>
          </li>
        </ul>
      </li>
        <li class="bg-azul"><a><i class="fa flaticon-settings"></i>Configuración general <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
             <li>
                <a href="<?php echo $this->Html->url(array('controller'=>'Manages','action'=>'diary')) ?>" >
                  Mi Agenda
                </a>
            </li>
            <?php if (in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])): ?>
              <li>
                <a href="<?php echo $this->Html->url("/users/index") ?>" >
                  Gestión de Usuarios 
                </a>
              </li>                                                            
            <?php endif ?>
            <?php if (AuthComponent::user("role") == "Gerente General"): ?>
              <li>
                <a href="<?php echo $this->Html->url(array('controller'=>'ManagementNotices','action'=>'index')) ?>" >
                  Avisos Públicos
                </a>
              </li>
              <li>
                <a href="<?php echo $this->Html->url(array('controller'=>'ImportRequests','action'=>'config')) ?>" >
                  Configuración general
                </a>
              </li>
              <li>
                <a href="<?php echo $this->Html->url(array('controller'=>'Headers','action'=>'index')) ?>" >
                  Banners de Cotizaciones
                </a>
              </li> 
            <?php endif ?>
          </ul>
        </li>
        <?php endif ?>
    </ul>

<?php else: ?>


  <ul class="nav side-menu" style="margin-bottom: 50px !important; padding-bottom: 150px !important">
      <li class="bg-naranja"><a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=> $this->Utilities->paint_home_menu_user(AuthComponent::user('role')))) ?>"><i class="fa flaticon-menu-1 ml-0"></i> Dashboard</a></li>
     
        <li class="bg-danger">
          <a href="javascript:void(0)"><i class="fa fa-file ml-0"></i> Marketing</a>
          <ul class="nav child_menu">
            <li class="sub_menu">
              <a href="<?php echo $this->Html->url(array('controller'=>'quotations_marketings','action'=>'index')) ?>" >
                Cotizaciones para marketing
              </a>
            </li> 
            <li>
                <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'flujo_masivo')) ?>" >
                  Importar masivamente
                </a>
            </li>
             <?php if (in_array(AuthComponent::user("role"), ["Gerente General","Servicio al Cliente"])): ?>
                  <li class="sub_menu">
                    <a href="<?php echo $this->Html->url(array('controller'=>'campaigns','action'=>'index')) ?>" >
                      Remarketing de productos
                    </a>
                  </li>              
                <?php endif ?>
          </ul>
        </li>
      
    
      <li class="bg-cafe"><a><i class="fa flaticon-report-1"></i> Informes <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li class="sub_menu">
            <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_mercadeo',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" >
              Informe de mercadeo - landings
            </a>
          </li>
          <li>
            <a href="<?php echo $this->Html->url(array('controller'=>'pages','action'=>'datos_clientes')) ?>" >
              Ventas clientes vs Inversion Mercadeo
            </a>
          </li>
        </ul>
      </li>
     
    </ul>

<?php endif ?>

<style>
  <?php if (AuthComponent::user("role") == "Asesor Externo"): ?>
    .submenu_solicitudes b {
      display: none;
    }
  <?php endif ?>
</style>