<script>
function submitExpenses(){
    
    form = $("#frm_paid_expenses");
    $.ajaxSetup({async:false});
    $.post('index.php/ajax/incomes/pay_expenses', form.serialize() , function(rta){
        
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
    
    $(".button_pay").click(function(){
        property_id = $(this).val();
        form = $("#form"+property_id);  
        if (property_id != ""){
            $.ajaxSetup({async:false});
            $.post('index.php/ajax/incomes/pay_ordinary_expense', form.serialize() , function(rta){
                if (rta.indexOf("ok") == -1){
                    $.fancybox({
                        'content' : rta
                    });
                }
                else{
                    $('#building').trigger('change');
                }
            });
        }
    });
    
    $('.sort_column').click(function(){
        
        sort_column = this.getAttribute("value");
        order = this.getAttribute("order");
        if (order == null)
            order = "ASC";
        building = $("#building").val();
        /*alert(building);
        $('#div_properties').load('index.php/ajax/properties/order_properties/',{sort_column:sort_column, building_id:building, order:order});*/
    });
    
});
</script>
<div class="tabs">
    <div class="tab active" tab="common_incomes_paid">Propiedades por Pagar</div>
    <div class="tab inactive" tab="common_incomes_unpaid">Propiedades que realizaron el Pago mensual</div>
</div>
<div class="tabs-content">
    
    <div class="properties_div" id="common_incomes_paid">
    <? if ($unpaid_properties):?>    
        <form id="frm_paid_expenses" method="post" action="<?= site_url().'incomes/pay_expenses'?>">
        
        <table cellpadding="0" cellspacing="0" class="table_properties">
        <thead>
            <tr class="table_header">
                <th class="sort_column" <? if ($sort_column == 'functional_unity') echo "order='$order'"; ?> value="functional_unity">UF</th>
                <th class="sort_column" <? if ($sort_column == 'floor') echo "order='$order'"; ?> value="floor">Piso</th>
                <th class="sort_column" <? if ($sort_column == 'appartment') echo "order='$order'"; ?> value="appartment">Depto</th>           
                <th class="sort_column" <? if ($sort_column == 'owner') echo "order='$order'"; ?> value="owner">Propietario</th>
                <th>Saldo</th>            
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
                <td><?= $property->balance + $property->balance_reserve ?></td>
            <? if ($property->building->payment_type != BUILDING_PAYMENT_TYPE_MANUAL): ?>
                <td><?= number_format(round($property->total_to_pay(), 2), 2) ?></td>
            <? else: ?>
                <td><?= number_format(round($property->total_to_pay(), 0 ,PHP_ROUND_HALF_DOWN),2) ?></td>
            <? endif; ?>
                
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
            <? endforeach; ?>
        </tbody>
        </table>
        <button class="submit" onclick="submitExpenses(); return false;">Guardar Expensas </button>
        </form>
    <? endif; ?>
        
    </div>
    <div class="properties_div" id="common_incomes_unpaid" style="display: none">
    <? if ($paid_properties):?>
        <table cellpadding="0" cellspacing="0" class="table_properties">
        <thead>
            <tr class="table_header">
                <th class="sort_column" <? if ($sort_column == 'functional_unity') echo "order='$order'"; ?> value="functional_unity">UF</th>
                <th class="sort_column" <? if ($sort_column == 'floor') echo "order='$order'"; ?> value="floor">Piso</th>
                <th class="sort_column" <? if ($sort_column == 'appartment') echo "order='$order'"; ?> value="appartment">Depto</th>
                <th class="sort_column" <? if ($sort_column == 'owner') echo "order='$order'"; ?> value="owner">Propietario</th>
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
                <td><?= number_format(round($income->value + $income->value_fund, 0 ,PHP_ROUND_HALF_DOWN),2) ?></td>
                <td>
                    <i class="fa fa-window-close fa-lg" onclick="delete_payment(<?= $income->id ?>)"></i>
                </td>
                         
            </tr>
            <? endforeach; ?>
        </tbody>
        </table>
    <? endif; ?>
    </div>
</div>