
<html>
    <head></head>
    <body>
        <center>
            <img src="https://pagoagil.appspot.com/img/logopagoagil44.png" height="44" width="104" /><br />
            You will redirect to gateway PagoAgil
        </center>
        <script type="text/javascript">
            function abrirPagina() {
                window.open("https://www.pagoagil.net/onestep/");
            }
        </script>
        <meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" /><title></title>
        <form id="formid" method="post" action="https://www.pagoagil.net/onestep/">
            <input type="hidden" name="uuid" value="%7B36a44301-ec3f-45a1-b40c-ff182e045367%7D" />
            <input type="hidden" name="codigo" value="" />
            <input type="hidden" name="terminal" value="" />
            <input type="hidden" name="nombre_entidad" value="" />
            <input type="hidden" name="estado_transaccion" value="" />
            <input type="hidden" name="codigo_respuesta" value="" />
            <input type="hidden" name="mensaje_respuesta" value="" />
            <input type="hidden" name="autorizacion" value="" />
            <input type="hidden" name="recibo" value="" />
            <input type="hidden" name="cuotas" value="" />
            <input type="hidden" name="marca" value="" />
            <input type="hidden" name="codigo_marca" value="" />
            <input type="hidden" name="tarjeta" value="" />
            <input type="hidden" name="cuenta" value="" />
            <input type="hidden" name="referencia" value="<?php echo ("COTCRMZ".$quotation_id) ?>" />
            <input type="hidden" name="fecha" value="" />
            <input type="hidden" name="hora" value="" />
            <input type="hidden" name="descripcion_pago" value="Pago <?php echo !is_null($abono) ? "(por abono)" : "" ?> de la cotización - <?php echo $datosQuation["Quotation"]["name"]  ?> | Código: <?php echo $datosQuation["Quotation"]["codigo"] ?>" />
            <input type="hidden" name="monto" value="<?php echo ($totalFinal) ?>" />
            <input type="hidden" name="monto_inicial" value="" />
            <input type="hidden" name="otros_valores" value="" />
            <input type="hidden" name="iva" value="<?php echo ($totalIVa) ?>" />
            <input type="hidden" name="iac" value="" />
            <input type="hidden" name="base_devolucion" value="<?php echo$totalFinal ?>" />
            <input type="hidden" name="nombres" value="<?php echo mb_strtoupper($this->Utilities->name_prospective($datos['ProspectiveUser']['id'])); ?>" />
            
            <input type="hidden" name="documento" value="" />
            <input type="hidden" name="tipo_documento" value="" />
            <input type="hidden" name="provincia" value="" />
            <input type="hidden" name="pais" value="CO" />

            <?php if ($datos['ProspectiveUser']['contacs_users_id'] > 0){ ?>
                <input type="hidden" name="apellidos" value="<?php echo $this->Utilities->name_bussines($datosC['ContacsUser']['clients_legals_id']); ?>" />
                <?php if ($datosC['ContacsUser']['telephone'] != ''): ?>
                    <input type="hidden" name="telefono" value="<?php echo $datosC['ContacsUser']['telephone'] ?>" />
                <?php endif ?>
                <input type="hidden" name="correo" value="<?php echo $datosC['ContacsUser']['email'] ?>" />
                <input type="hidden" name="ciudad" value="<?php echo $datosC['ContacsUser']['city'] ?>" />
            <?php } else { ?>

                <?php if ($datosC['ClientsNatural']['telephone'] != ''): ?>
                    <input type="hidden" name="telefono" value="<?php echo $datosC['ClientsNatural']['telephone'] ?>" />
                <?php endif ?>
                <input type="hidden" name="correo" value="<?php echo $datosC['ClientsNatural']['email'] ?>" />
                <input type="hidden" name="ciudad" value="<?php echo $datosC['ClientsNatural']['city'] ?>" />
            <?php } ?>

            <input type="hidden" name="celular_destinatario" value="" />
            <input type="hidden" name="correo_destinatario" value="" />
            <input type="hidden" name="codigo_postal" value="" />
            <input type="hidden" name="codigo_postal_destinatario" value="" />
            <input type="hidden" name="ip" value="" />
            <input type="hidden" name="banco" value="" />
            <input type="hidden" name="codigo_banco" value="" />
            <input type="hidden" name="codigo_retorno" value="" />
            <input type="hidden" name="idioma" value="es" />
            <input type="hidden" name="moneda" value="COP" />
            <input type="hidden" name="token" value="" />
            <input type="hidden" name="url_ipn" value="<?php echo Router::url("/",true)."quotations/payments_ipn/"?><?php echo $this->Utilities->encryptString("COTCRMZ__".$quotation_id) ?>" />
            <input type="hidden" name="prueba" value="0" />
            <input type="hidden" name="tipo" value="onestep" />
            <input name="url_respuesta" value="https://almacendelpintor.pagoagil.co/" type="hidden">
        </form>
        <script type="text/javascript">
            document.getElementById("formid").submit();
        </script>
    </body>
</html>
