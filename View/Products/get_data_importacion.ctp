<?php 
    
    $rolesPermitidos = array(
        "Gerente General", "Logística","Asesor Comercial"
    );

    $reserva  = isset($partsData["Reserva"][$datosProducto["Product"]["part_number"]]) ? $partsData["Reserva"][$datosProducto["Product"]["part_number"]] : 0;

    $cantidad = $partsData[$datosProducto["Product"]["part_number"]] ;

    if (!empty($cantidad) && isset($cantidad[0]["total"])) {
        $cantidad = Set::extract($cantidad,"{n}.total");
        $cantidad = array_sum($cantidad);
    }else{
        $cantidad = 0;
    }

 ?>
<tr id="<?php echo "tr_".$datosProducto['Product']['id'] ?>" data-reference="<?php echo $datosProducto["Product"]["part_number"] ?>" data-quantity="<?php echo $cantidad ?>" class="listado_tabla">
    <td>
        <?php $ruta = $this->Utilities->validate_image_products($datosProducto['Product']['img']); ?>
        <img class="minprods" minprod="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>"  width="40px" >
    </td>
    <td class="nameget <?php echo !is_null($datosProducto["Product"]["notes"]) ? "nota" : "" ?>">
        <?php if (!is_null($datosProducto["Product"]["notes"])): ?>
            <div class="notaproduct">
            <span class="triangle"></span>
            <span class="flechagiro">|</span>
            <div class="text_nota">
                <small class="creadornota"><b></b></small>
                <?php echo $datosProducto["Product"]["notes"] ?>
                <small class="datenota"></small>
            </div>
            </div>
            <?php echo $datosProducto['Product']['name'] ?>
        <?php else: ?>
            <?php echo $datosProducto['Product']['name'] ?>
        <?php endif ?>
        
    </td>
    <td class="descriptionget">
        <?php echo $this->Text->truncate(strip_tags($datosProducto['Product']['description']), 120,array('ellipsis' => '...','exact' => false)); ?>
    </td>
    <td><?php echo $datosProducto['Product']['part_number'] ?></td>
    <td><?php echo $datosProducto['Product']['brand'] ?></td>
    <td class="cantidad">
        <input type="number" id="cantidadproduct" class="cantidadProduct" data-uid="<?php echo $datosProducto['Product']['id'] ?>" name="<?php echo "Cantidad-".$datosProducto['Product']['id'] ?>" min="1" value="1">
    </td>
    <td class="sizenumberQuantity">
        <?php echo $this->element("products_block",["producto" => $datosProducto["Product"], "inventario_wo" => $partsData[$datosProducto["Product"]["part_number"]], "bloqueo" => false, "reserva" => isset($partsData["Reserva"][$datosProducto["Product"]["part_number"]]) ? $partsData["Reserva"][$datosProducto["Product"]["part_number"]] : null ]) ?>
    </td>
    <td>
        <a data-uid="<?php echo $datosProducto['Product']['id'] ?>"  class="deleteProduct">
            <i class="fa fa-remove" data-toggle="tooltip" data-placement="right" title="Eliminar producto"></i>
        </a>
        <?php if ($editProducts): ?>
            <a  data-uid="<?php echo $datosProducto['Product']['id'] ?>" class="editarProduct">
                <i class="fa fa-fw fa-pencil" data-toggle="tooltip" data-placement="right" title="Editar producto"></i>
            </a>
        <?php else: ?>
            <a  data-id="<?php echo $datosProducto['Product']['id'] ?>" class="requestEditProduct">
                <i class="fa fa-fw fa-pencil" data-toggle="tooltip" data-placement="right" title="Solicitar Editar producto"></i>
            </a>
        <?php endif; ?>
        <?php if (in_array(AuthComponent::user("role"), $rolesPermitidos)): ?>
            <a href="#" data-id="<?php echo $datosProducto["Product"]["id"] ?>" class="btn btn-incorrecto d-inline p-2 notesProduct text-white ml-2" data-toggle="tooltip" title="" data-original-title="Gestionar notas del producto">
                <i class="fa fa-comments text-white vtc"></i>
            </a>
        <?php endif ?>
    </td>
</tr>
<script type="text/javascript">
    jQuery("a").mouseenter(function (e) {             
        var posMouse = e.pageX - this.offsetLeft; 
        var textoTooltip = jQuery(this).attr("title"); 
        if (typeof textoTooltip != undefined && textoTooltip != null && textoTooltip.length > 0) {
            jQuery(this).append('<div class="tooltip-os">' + textoTooltip + '</div>');
            jQuery("a > div.tooltip-os").css("left", "" + posMouse - 355 + "px");
            jQuery("a > div.tooltip-os").fadeIn(100);
        }
    });
    jQuery("a").mouseleave(function () {             
        jQuery("a > div.tooltip-os").fadeOut(100).delay(100).queue(function () {
            jQuery(this).remove();
            jQuery(this).dequeue();
        });
    });
</script>