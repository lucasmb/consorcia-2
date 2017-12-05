<script>
function delete_extraordinary(id_extraordinary){
    var answer = confirm("¿Está seguro que desea eliminar esta expensa extraordinaria?. Tenga en cuenta que se eliminará todos los pagos sobre la misma y puede dejar inconsistente el balance del edificio");
    if (answer){
        $.post('index.php/ajax/incomes/delete_extraordinary', {id:id_extraordinary}, function(rta){
        if (rta.indexOf("ok") != -1 ){
            
            $('#building').trigger('change');
            $.fancybox({
                'content' : "<h2>Se ha eliminado el pago de forma correcta</h2>"
            });
        }
        else
            $.fancybox({
                'content' : rta
            });
    
        });            
    }
}

function close_extraordinary(id_extraordinary){
    var answer = confirm("¿Está seguro que desea CERRAR esta expensa extraordinaria?");
    if (answer){
        $.post('index.php/ajax/incomes/close_extraordinary', {id:id_extraordinary}, function(rta){
        if (rta.indexOf("ok") != -1 ){
            
            $('#building').trigger('change');
            $.fancybox({
                'content' : "<h2>Se ha cerrado correctamente</h2>"
            });
        }
        else
            $.fancybox({
                'content' : rta
            });
    
        });            
    }
}

function delete_extraordinary_transaction(id_extraordinary_transaction){

    $.post('index.php/ajax/incomes/delete_extra_payment', {id:id_extraordinary_transaction}, function(rta){
    if (rta.indexOf("ok") != -1 ){

        $('#building').trigger('change');
        
    }
    else
        $.fancybox({
            'content' : rta
        });

    });            
    
}



function submitExtraIncomes(button){
    
    
    form = $("#frm_paid_extra_expenses_" + $(button).val());
    $.ajaxSetup({async:false});
    $.post('index.php/ajax/incomes/pay_extra_incomes', form.serialize() , function(rta){
        
        if (rta.indexOf("ok") == -1 ){
            $.fancybox({
                'content' : rta
            });
        }
        else{
            $('#building').trigger('change');
        }
    });
}



$(document).ready(function() {
    
    $(".button").click(function(){
        if ($(this).hasClass("gray")){
            $(".properties_div").slideToggle("slow");
            $(".white").addClass("gray");
            $(".white").removeClass("white");
            $(this).addClass("white");
            $(this).removeClass("gray");
        }
    });
    
    
});
</script>
<!-- <? 
    if ($extra_period): 
?>    
<div class="div_border">
    <h4>Extraordinarias</h4>
    <table cellpadding="0" cellspacing="0" class="table_properties">
    <thead>
        <tr class="table_header">
            <th>Descripcion</th>
            <th>Cuotas</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Forma Pago</th>
            <th>Interés</th>
            <th>Valor Total</th>            
            <th>Estado</th>            
            <th>Edicion</th>            
        </tr>
    </thead>
    <tbody>
        <? foreach ($extra_period as $period): ?>
        <tr class="table_row">
            <td><?= $period->name ?></td>
            <td><?= $period->fees ?></td>
            <td><?= $period->date_from->format("Y-m-d") ?></td>
            <td><?= $period->date_to->format("Y-m-d") ?></td>
            <td><? if ($period->pay_form == DIVIDE_COEFICCIENT): echo DIVIDE_COEFICCIENT_LABEL; else: echo DIVIDE_EQUALS_LABEL; endif; ?></td>
            <td><?= $period->tax_percentage ?>%</td>
            <td><?= $period->value ?></td>
            <td><?= $period->period_state_name() ?></td>
            <td>
                <i class="fa fa-window-close fa-lg" onclick="delete_extraordinary(<?= $period->id ?>)"></i>
            </td>            
        </tr>
        <? endforeach; ?>
    </tbody>
    </table>
</div>
<?
    endif;
?> -->

<? 
    if($periods_for_pay):
?>    
    <div class="tabs">
        <div class="tab active" tab="extra_incomes_paid">Propiedades por Pagar</div>
        <div class="tab inactive" tab="extra_incomes_unpaid">Propiedades que realizaron el Pago mensual</div>
    </div>
    <div class="tabs-content">
<?
        foreach($periods_for_pay as $period):
            $unpaid = $period->get_current_unpaid_properties();
            $paid = $period->get_current_paid_properties();
        
?>
        <?  if ($unpaid): ?>
        <div class="properties_div" id="extra_incomes_paid">
            <h4>
                <?= $period->name ?>
                <? if($period->get_number_of_fees($period->building->actual_period) > $period->fees): ?>
                    Cuotas pendientes
                <? else: ?>
                    Cuota: <?= $period->get_number_of_fees($period->building->actual_period) ?> de <?= $period->fees ?>
                <? endif; ?>
            <h4>
            <form id="frm_paid_extra_expenses_<?= $period->id ?>" method="post" action="<?= site_url().'incomes/pay_extra_incomes'?>">
            <input type="hidden" id="extraordinary_period" name="extraordinary_period" value="<?= $period->id ?>">
            <table cellpadding="0" cellspacing="0" class="table_properties">
            <thead>
                <tr class="table_header">
                    <th>UF</th>
                    <th>Piso</th>
                    <th>Depto</th>           
                    <th>Propietario</th>
                    <th>Balance</th>            
                    <th>Total al 15</th>            
                    <th>Tipo Pago</th>            
                </tr>
            </thead>
            <tbody>
                <? 
                    foreach ($unpaid as $property): 
                        $last_transaction = $property->get_last_extraordinary_transaction($period);
                ?>
                <tr class="table_row">
                    <td><?= $property->functional_unity?></td>
                    <td><?= $property->floor?></td>
                    <td><?= $property->appartment?></td>            
                    <td><? if($property->owner->name != ""): echo $property->owner->lastname . ', ' .  $property->owner->name; else: echo $property->owner->lastname; endif;?></td>
                    <td><?= $property->balance_extraordinary ?></td>
                    <td><?= number_format(round($property->value_to_pay_extraordinary($period), 0 ,PHP_ROUND_HALF_DOWN),2) ?></td>
                    <td>
                        <input name="property_id" id="property_id" type="hidden" value="<?= $property->id; ?>">
                        <div class="col">
                            <input id="account_pay_<?= $property->id ?>" type="checkbox" name="account_pay[]" value="<?= $property->id ?>" />
                            <label for="account_pay_<?= $property->id ?>">Pago a cuenta: </label><input class="input_pay" name="input_pay_<?= $property->id ?>" id="input_pay_<?= $property->id ?>" type="text" /><br>    
                        </div>
                        
                        <div class="col">
                            <input id="second_pay_<?= $property->id ?>" type="checkbox" checked="checked" name="total_pay[]" value="<?= $property->id ?>" />
                            <label for="second_pay_<?= $property->id ?>">Pago total al 15</label>
                        </div>

                    </td>
                </tr>
                <? 
                    endforeach; 
                ?>
            </tbody>
            </table>
            <button class="submit" value="<?= $period->id ?>" onclick="submitExtraIncomes(this); return false;">Guardar Expensas </button>
            </form>
        </div>
        <? endif; ?>

    
    
        <? if ($paid):?>
        <div class="properties_div" id="extra_incomes_unpaid" style="display: none">
            <table cellpadding="0" cellspacing="0" class="table_properties">
            <thead>
                <tr class="table_header">
                    <th>UF</th>
                    <th>Piso</th>
                    <th>Depto</th>           
                    <th>Propietario</th>
                    <th>Tipo Pago</th>            
                    <th>Valor</th>
                    <th>Edicion</th>            
                </tr>
            </thead>
            <tbody>
        <? 
            foreach ($paid as $property): 
                $income = $property->get_current_extra_pay_month($period);
        ?>
                <tr class="table_row">
                    <td><?= $property->functional_unity?></td>
                    <td><?= $property->floor?></td>
                    <td><?= $property->appartment?></td>            
                    <td><? if($property->owner->name != ""): echo $property->owner->lastname . ', ' .  $property->owner->name; else: echo $property->owner->lastname; endif;?></td>
                    <td><?= $income->type_pay ?></td>
                    <td><?= number_format(round($income->value, 0 ,PHP_ROUND_HALF_DOWN),2); ?></td>
                    <td>
                        <i class="fa fa-window-close fa-lg" onclick="delete_extraordinary_transaction(<?= $income->id ?>)"></i>
                    </td>

                </tr>
        <? 
            endforeach; 
        ?>
            </tbody>
            </table>
        </div>
        <? 
            endif; 
        ?>
    <? 
        endforeach; 
    ?>
    </div>
<?
    endif; 
?>
