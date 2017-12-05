
<?
    if($extra_period && $periods_for_pay):

        $args["extra_period"] = $extra_period;        
        $args["periods_for_pay"] = $periods_for_pay;        
        $this->load->view("ajax/income/income_extra_payment",$args);
        
    else:
?>
<script>

function submitAddExtraordinary(){
    
    form = $("#frm_add_extraordinary");
    $.post('index.php/ajax/incomes/add_extraordinary_period', form.serialize() , function(rta){
        
        if (rta.indexOf("success") == -1 ){
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
        
    $( "#add_extraordinary" ).click(function(){
        $.post('index.php/ajax/incomes/add_extraordinary_period', $("#frm_add_extraordinary").serialize(), function(rta){
            if (rta.indexOf("success") == -1){
                $("#div_errors").html("").append(rta);
            }
            else{
                $('#building').trigger('change');
            }
        });
    });    
});    

</script>

<form id="frm_add_extraordinary" method="post" >
    <input type="hidden" name="building" id="building" value="<?= $building->id ?>">
    <div class="div_border">
        <div id="div_errors">
        </div>
        <div class="middle_col">
            <!-- Autocomplete -->
            <div class="field">
                <label for="name">Descripción: </label>
                <input id="name" name="name" value="" type="text" />
            </div>

            <!-- Date from -->
            <div class="field" >
                <label for="fees">Cuotas: </label>
                <input id="fees" name="fees" type="text" >
            </div>

            <!-- Percentage -->
            <div class="field" >
                <label for="tax_percentage">Interés: </label>
                <input id="tax_percentage" name="tax_percentage" type="text" >
            </div>
        </div>
        <div class="middle_col" >
            <!-- Type Income -->
            <div class="field">
                <label for="value">Valor Total: </label>
                <input type="text" id="value" name="value"/>
            </div>

            <!-- Type Expense -->
            <div class="field" >
                <label for="pay_form">Tipo: </label>
                <select name="pay_form" id="pay_form" >
                    <option value="0">Dividido por coeficiente</option>
                    <option value="1">Dividido en partes iguales</option>
                </select>
            </div>
        </div>
        <? if ($extraordinary_properties): ?>
        <table cellpadding="0" cellspacing="0" class="table_properties">
            <thead>
                <tr class="table_header">
                    <th value="functional_unity">UF</th>
                    <th value="floor">Piso</th>
                    <th value="appartment">Depto</th>
                    <th value="owner">Propietario</th>
                    <th>Edicion</th>            
                </tr>
            </thead>
            <tbody>
                <? foreach ($extraordinary_properties as $property): ?>
                <tr class="table_row">
                    <td><?= $property->functional_unity?></td>
                    <td><?= $property->floor?></td>
                    <td><?= $property->appartment?></td>            
                    <td><? if($property->owner->name != ""): echo $property->owner->lastname . ', ' .  $property->owner->name; else: echo $property->owner->lastname; endif;?></td>
                    <td>
                        <input name="property_id" id="property_id" type="hidden" value="<?= $property->id; ?>">

                        <div class="col">
                            <input id="property_extraordinary_add_<?= $property->id ?>" type="checkbox" checked="checked" name="property_extraordinary_add[]" value="<?= $property->id ?>" />
                            <label for="property_extraordinary_add_<?= $property->id ?>">Paga extraordinaria</label>    
                        </div>
                        
                    </td>
                </tr>
                <? endforeach; ?>
            </tbody>
            </table>
        <? endif; ?>
        <button class="submit add_extraordinary" onclick="submitAddExtraordinary(); return false;">Agregar Expensa Extraordinaria</button>

        <!--<button id="add_extraordinary" onclick="return false;;" >Agregar Expensa Extraordinaria</button>-->

    </div>
</form>
<?  endif; ?>