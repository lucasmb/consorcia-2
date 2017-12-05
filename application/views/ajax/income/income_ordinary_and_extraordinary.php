<script>
function submitOrdinaryAndExtraordinary(){
    
    form = $("#frm_paid_ordinary_and_extraordinary");
    $.ajaxSetup({async:false});
    $.post('index.php/ajax/incomes/pay_ordinary_and_extraordinary', form.serialize() , function(rta){
        
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
<? if ($building->has_extraordinary_included): ?>
    <div class="button white"> Propiedades a Pagar</div>
    <div class="button gray"> Propiedades que realizaron el Pago mensual</div>
    <? if ($unpaid_properties):?>    
    <div class="properties_div">
        <form id="frm_paid_ordinary_and_extraordinary" method="post" action="<?= site_url().'incomes/pay_ordinary_and_extraordinary'?>">

        <table cellpadding="0" cellspacing="0" class="table_properties">
        <thead>
            <tr class="table_header">
                <th>UF</th>
                <th>Piso</th>
                <th>Depto</th>           
                <th>Propietario</th>
                <th>Saldo</th>
                <th>Saldo Reserva</th>
                <th>Saldo Extraordinarias</th>            
                <th>Total al 15</th>            
                <th>Tipo Pago</th>            
            </tr>
        </thead>
        <tbody>
            <? foreach ($unpaid_properties as $property): ?>
            <tr class="table_row">
                <td><?= $property->functional_unity?></td>
                <td><?= $property->floor?></td>
                <td><?= $property->appartment?></td>            
                <td><? if($property->owner->name != ""): echo $property->owner->lastname . ', ' .  $property->owner->name; else: echo $property->owner->lastname; endif;?></td>
                <td><?= $property->balance ?></td>
                <td><?= $property->balance_reserve ?></td>
                <td><?= $property->balance_extraordinary ?></td>
                <td><?= number_format(round($property->total_to_pay(), 0 ,PHP_ROUND_HALF_DOWN),2) ?></td>
                <td>

                    <input name="property_id" id="property_id" type="hidden" value="<?= $property->id; ?>">

                    <input id="account_pay_<?= $property->id ?>" type="checkbox" name="account_pay[]" value="<?= $property->id ?>" />
                    <label for="account_pay_<?= $property->id ?>">Pago a cuenta: </label><input class="input_pay" name="input_pay_<?= $property->id ?>" id="input_pay_<?= $property->id ?>" type="text" /><br>

                    <input id="second_pay_<?= $property->id ?>" type="checkbox" checked="checked" name="total_pay[]" value="<?= $property->id ?>" />
                    <label for="second_pay_<?= $property->id ?>">Pago total al 15</label>

                </td>
            </tr>
            <? endforeach; ?>
        </tbody>
        </table>
        <button class="submit" onclick="submitOrdinaryAndExtraordinary(); return false;">Guardar Expensas </button>
        </form>
    </div>
    <? endif; ?>
    <? if ($paid_properties):?>
    <div class="properties_div" style="display: none">
        <table cellpadding="0" cellspacing="0" class="table_properties">
        <thead>
            <tr class="table_header">
                <th>UF</th>
                <th>Piso</th>
                <th>Depto</th>
                <th>Propietario</th>
                <th>Tipo de Pago</th>
                <th>Valor</th>
                <th>Edicion</th>            
            </tr>
        </thead>
        <tbody>
            <? 
                foreach ($paid_properties as $property): 
                    $income = $property->get_current_pay_month();
            ?>
            <tr class="table_row">
                <td><?= $property->functional_unity?></td>
                <td><?= $property->floor?></td>
                <td><?= $property->appartment?></td>            
                <td><? if($property->owner->name != ""): echo $property->owner->lastname . ', ' .  $property->owner->name; else: echo $property->owner->lastname; endif;?></td>
                <td><?= $income->type_pay_date ?></td>
                <td><?= number_format(round($income->value + $income->value_fund + $income->value_extraordinary , 0 ,PHP_ROUND_HALF_DOWN),2) ?></td>
                <td><img src="<?= url_img('/home/delete.png') ?>" onclick="delete_ordinary_and_extraordinary(<?= $income->id ?>)"></td>

            </tr>
            <? endforeach; ?>
        </tbody>
        </table>    
    </div>
    <? endif; ?>
<? else: ?>
    <div class="properties_div">
        Solamente se puede ingresar aquellos edificios que tengan incluidas las expensas extraordinarias junto con las ordinarias
    </div>
<? endif; ?>
