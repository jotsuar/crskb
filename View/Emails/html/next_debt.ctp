<tr>
  <td align="left" style="Margin:0;padding-bottom:20px;padding-left:20px;padding-right:20px;padding-top:30px">
   <table cellpadding="0" cellspacing="0" width="100%" role="none" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
     <tr>
      <td align="center" valign="top" style="padding:0;Margin:0;width:560px">
       <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
         <tr>
          <td align="center" style="padding:0;Margin:0"><h1 style="Margin:0;line-height:36px;mso-line-height-rule:exactly;font-family:Manrope, sans-serif;font-size:30px;font-style:normal;font-weight:bold;color:#44465F">Facturas próximas a vencer</h1></td>
         </tr>
         <tr>
          <td align="center" style="padding:0;Margin:0;padding-top:15px"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:Manrope, sans-serif;line-height:21px;color:#073473;font-size:14px">Actualmente tienes las siguientes facturas que venceran en nuestro sistema,&nbsp;</p><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:Manrope, sans-serif;line-height:21px;color:#073473;font-size:14px">realiza oportunamente el pago para poder seguirte prestando el mejor servicio</p></td>
         </tr>
       </table>
      </td>
     </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td class="esdev-adapt-off" align="left" style="padding:20px;Margin:0">
   <table cellpadding="0" cellspacing="0" width="100%" role="none" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
     <tr>
      <td align="center" valign="top" style="padding:0;Margin:0;width:560px">
       <table cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;background-color:#ffffff;border-radius:10px" role="presentation">
         <tr>
          <td align="center" style="padding:0;Margin:0">
           <table border="1" bordercolor="#cccccc" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;height:150px;width:750px" class="es-table" role="presentation">
             <tr style="background-color:#073473;color:#fff">
              <td style="padding:0;Margin:0;font-family:manrope, arial, sans-serif;text-align:center">Factura</td>
              <td style="padding:0;Margin:0;font-family:manrope, arial, sans-serif;text-align:center;font-size:21px">Fecha Vencimiento</td>
              <td style="padding:0;Margin:0;font-family:manrope, arial, sans-serif;text-align:center;font-size:21px">Valor Factura</td>
              <td style="padding:0;Margin:0;font-family:manrope, arial, sans-serif;text-align:center;font-size:21px">Ver Factura</td>
             </tr>
             <?php foreach ($facturas as $key => $value): ?>
               <tr>
              <td style="padding:0;Margin:0;text-align:center;font-family:manrope, arial, sans-serif;font-size:18px">
                <?php echo $value->prefijo ?> <?php echo $value->DocumentoNúmero ?>
              </td>
              <td style="padding:0;Margin:0;text-align:center;font-family:manrope, arial, sans-serif;font-size:18px">
                <?php echo date("Y-m-d",strtotime($value->Vencimiento)) ?>
              </td>
              <td style="padding:0;Margin:0;text-align:center;font-family:manrope, arial, sans-serif;font-size:18px">$<?php echo number_format($value->Saldo) ?></td>
              <td style="padding:0;Margin:0;text-align:center;font-family:manrope, arial, sans-serif;font-size:18px">
                <a href="<?php echo Router::url("/",true) ?>clientes/factura/<?php echo $this->Utilities->encryptString($value->prefijo."-".$value->DocumentoNúmero) ?>" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#073473;font-size:14px">Ver</a>
              </td>
             </tr>
             <?php endforeach ?>
           </table></td>
         </tr>
       </table>
      </td>
     </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td class="esdev-adapt-off" align="left" style="Margin:0;padding-left:10px;padding-top:20px;padding-right:20px;padding-bottom:40px">
   <table cellpadding="0" cellspacing="0" width="100%" role="none" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
     <tr>
      <td align="left" style="padding:0;Margin:0;width:570px">
       <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
         <tr>
          <td align="center" style="padding:0;Margin:0"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:Manrope, sans-serif;line-height:21px;color:#073473;font-size:14px">Agradecemos tu colaboración con el pago de estas factuas</p></td>
         </tr>
       </table>
      </td>
     </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-bottom:40px">
   <table cellpadding="0" cellspacing="0" width="100%" role="none" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
     <tr>
      <td align="center" valign="top" style="padding:0;Margin:0;width:560px">
       <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
         <tr>
          <td align="center" style="padding:0;Margin:0"><!--[if mso]><a href="<?php echo Router::url("/",true) ?>clientes/facturas_mora/<?php echo $this->Utilities->encryptString($value->Identificacion) ?>" target="_blank" hidden>
          	<v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" esdevVmlButton href="<?php echo Router::url("/",true) ?>clientes/facturas_mora/<?php echo $this->Utilities->encryptString($value->Identificacion) ?>" 
                          style="height:44px; v-text-anchor:middle; width:224px" arcsize="34%" stroke="f"  fillcolor="#073473">
          		<w:anchorlock></w:anchorlock>
          		<center style='color:#ffffff; font-family:Manrope, sans-serif; font-size:18px; font-weight:400; line-height:18px;  mso-text-raise:1px'>VER MIS FCTURAS</center>
          	</v:roundrect></a>
          <![endif]--><!--[if !mso]><!-- --><span class="msohide es-button-border" style="border-style:solid;border-color:#073473;background:#073473;border-width:0px;display:inline-block;border-radius:15px;width:auto;mso-hide:all"><a href="<?php echo Router::url("/",true) ?>clientes/facturas_mora/<?php echo $this->Utilities->encryptString($value->Identificacion) ?>" class="es-button" target="_blank" style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;color:#FFFFFF;font-size:20px;padding:10px 20px 10px 20px;display:inline-block;background:#073473;border-radius:15px;font-family:Manrope, sans-serif;font-weight:normal;font-style:normal;line-height:24px;width:auto;text-align:center;mso-padding-alt:0;mso-border-alt:10px solid #073473">VER MIS FCTURAS</a></span><!--<![endif]--></td>
         </tr>
       </table>
      </td>
     </tr>
   </table>
  </td>
 </tr>