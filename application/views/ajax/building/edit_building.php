<style>     
    .ui-widget input , .ui-widget select{
        background: none repeat scroll 0 0 #FFFFFF;
        border: 1px solid #CCCCCC;
        border-radius: 3px 3px 3px 3px;
        color: #5D5D5D;
        font: 1.0em 'trebuchet ms',arial,sans-serif;
        padding: 5px;
    }
    
    .checkbox{
        margin: 8px;
        float: left;
    }
    
    
    .ui-state-active a, .ui-state-active a:link, .ui-state-active a:visited{
        color: #ff8832;
    }

    .ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited{
        color: #FFFFFF; 
    }
    
    #form_building_edit{
        font: 1.0em 'trebuchet ms',arial,sans-serif;
    }

    #form_building_edit h3{
        background: #635B53 no-repeat 0 0;
    }
    
</style>

<script>

function refreshCities(){
    var zone_id = $('#zone').val();
    if(zone_id != "")
        $('#city').load('index.php/ajax/localization/get_cities/'+zone_id);
    else
        $('#city').html('');
}


$(document).ready(function(){    
    
    $("#accordion").accordion();
    $("#accordion").accordion("option", "animated", 'bounceslide');
    
    $('#zone').unbind('change').change(function(){
        refreshCities();
    });
       
    $('#has_reserve_fund').click(function(){
        $("#reserve_fund_field").toggle();
        $("#reserve_percentage_field").toggle();
        $("#earn_static_fund_field").toggle();
        $("#put_reserve_value_manually_field").toggle();
        
        if ($('#has_reserve_fund').val() == 0){
            $('#has_reserve_fund').val(1);     
            if($("#put_reserve_value_manually").val() == 0){
                if($("#earn_static_fund").val() == 1)
                    $("#earn_static_fund_value_field").toggle();
                else
                    $("#earn_percentage_value_field").toggle();
            }
            else{
                $('#earn_static_fund_field').toggle();
            }    
            
        }
        else{
            $('#has_reserve_fund').val(0);
            if($("#put_reserve_value_manually").val() == 0){
                if($("#earn_static_fund").val() == 1)
                    $("#earn_static_fund_value_field").toggle();                

                else
                    $("#earn_percentage_value_field").toggle();
            }
            else{
                $('#earn_static_fund_field').toggle();
            }

        }
        
        $.fancybox.update();
    });
    
    $('#put_reserve_value_manually').click(function(){
        if ($("#put_reserve_value_manually").val() == 1)
            $("#put_reserve_value_manually").val(0);
        else
            $("#put_reserve_value_manually").val(1);
        $("#earn_static_fund_field").toggle();
        if ($('#earn_static_fund').val() == 1)
            $("#earn_static_fund_value_field").toggle();
        else
            $("#earn_percentage_value_field").toggle();
        $.fancybox.update();
    });
    
    $('#earn_static_fund').click(function(){
        $("#earn_static_fund_value_field").toggle();
        $("#earn_percentage_value_field").toggle();
        if ($('#earn_static_fund').val() == 0){
            $('#earn_static_fund').val(1);                 
        }
        else
            $('#earn_static_fund').val(0);        
    });
       
    $('#close').click(function(){
        parent.$.fancybox.close();
    });
       
    $('#submit').click(function(){
        $.post('index.php/ajax/buildings/save_edit_building' , $("#form_building_edit").serialize(), function(rta){
            if(rta.indexOf("success") == -1)
                $("#div_errors").html("").append(rta);
            else{
                arr = rta.split(":");
                refreshBuildings(arr[1]);                
                alert("Cambios realizados con exito.");
                parent.$.fancybox.close();
                $('#building').trigger('change');
            }
        });
        
    });
    
});

</script>
<div class="contenedor">
    <h1>Editar Edificio</h1>
    
    <div id="div_errors" class="errors">
    </div>
    
    <form method="post" id="form_building_edit" >
    <div id="accordion">
    <h3><a href="#" class="selectAccordion" tabindex="1">Datos Generales</a></h3>
    <div>
        <input name="building_id" id="building_id" type="hidden" value="<?= $building->id; ?>">
        <div class='div_border'>
            <div class='dynamic_col'>
                <div class="field">
                    <label for="zone" >Provincia : </label>
                    <select id="zone" name="zone">
                        <? echo get_select_zones($building->city->zone_id); ?>
                    </select>
                </div>
                <div class="field">
                    <label for="city">Ciudad : </label>
                    <select id="city" name="city">
                        <? echo get_select_cities($building->city->zone_id,$building->city_id); ?>
                    </select>
                </div>
                <div class="field">
                    <label for="name">Nombre : </label>
                    <input type="text" name="name" id="name" value="<?= $building->name ?>">
                </div>                
                <div class="field">
                    <label for="street">Calle : </label>
                    <input type="text" name="street" id="street" value="<?= $building->street ?>">
                </div>
                <div class="field">
                    <label for="number">Numero : </label>
                    <input type="text" name="number" id="number" value="<?= $building->number ?>">
                </div>
                <div class="field">
                    <label for="cuit">CUIT : </label>
                    <input type="text" name="cuit" id="cuit" value="<?= $building->cuit ?>">
                </div>     
                <div class="field">
                    <label for="total_coefficient">Suma del coeficiente al : </label>
                    <input type="text" name="total_coefficient" id="total_coefficient" value="<?= $building->total_coefficient; ?>">
                </div>
                <div class="field">
                    <label for="balance">Saldo Inicial : </label>
                    <input type="text" name="balance" id="balance" value="<?= $building->balance; ?>">
                </div>
                <div class="field">
                    <label for="balance">Saldo Inicial Extraordinarias: </label>
                    <input type="text" name="balance_extraordinary" id="balance_extraordinary" value="<?= $building->balance_extraordinary; ?>">
                </div>
            </div>
        </div>
    </div>
    <h3><a href="#" class="selectAccordion" tabindex="2">Datos Referidos a Expensas</a></h3>
    <div id="div_owner">
        <div class='div_border'>
            <div class='dynamic_col'>
                <div class="field">
                    <label for="earning_monthly">Expensas Mensuales : </label>
                    <input type="text" name="earning_monthly" id="earning_monthly" value="<?= $building->earning_monthly; ?>">
                </div>
                
                <div class="field">
                    <label for="type_expense_period">Tipo cobro expensas : </label>
                    <select id="type_expense_period" name="type_expense_period" >
                        <? echo get_select_type_expense_period($building->type_expense_period_id); ?>      
                    </select>
                </div>
                
                <div class="field">
                    <label for="payment_type">Forma de pago expensas : </label>
                    <select id="payment_type" name="payment_type" >
                        <? echo get_select_payment_type($building->payment_type); ?>      
                    </select>
                </div>                

                <div class="field">
                    <label for="tax_percentage">Porcentaje interes al proximo vencimiento : </label>
                    <input type="text" name="tax_percentage" id="tax_percentage" value="<?= $building->tax_percentage ?>" >
                </div>                
                <div class="field">
                    <label for="tax_late_percentage">Porcentaje interes a expensas atrasadas : </label>
                    <input type="text" name="tax_late_percentage" id="tax_late_percentage" value="<?= $building->tax_late_percentage ?>" >
                </div>
                <div class="field">
                    <label for="has_reserve_fund">Fondo de Reserva :</label>
                    <input type="checkbox" class="checkbox" id="has_reserve_fund" name="has_reserve_fund" <? if ($building->has_reserve_fund): ?>value="1" checked="checked"<?else:?> value="0" <?endif;?> />
                </div>
                <div class="field" id="reserve_fund_field" <? if (!$building->has_reserve_fund): ?>style="display: none"<?endif;?>>
                    <label for="reserve_fund">Fondo de reserva inicial : </label>
                    <input type="text" name="reserve_fund" id="reserve_fund" value="<?= $building->reserve_fund ?>" >
                </div>
                <div class="field" id="reserve_percentage_field" <? if (!$building->has_reserve_fund): ?>style="display: none"<?endif;?>>
                    <label for="reserve_percentage">Porcentaje interes fondo reserva : </label>
                    <input type="text" name="reserve_percentage" id="reserve_percentage" value="<?= $building->reserve_percentage ?>" >
                </div>
                <div class="field" id="put_reserve_value_manually_field" <? if (!$building->has_reserve_fund): ?>style="display: none"<?endif;?> >
                    <label for="put_reserve_value_manually">Montos de Reserva ingresados Manualmente:</label>
                    <input type="checkbox" class="checkbox" id="put_reserve_value_manually" name="put_reserve_value_manually" <? if ($building->put_reserve_value_manually): ?>value="1" checked="checked"<?else:?> value="0" <?endif;?> />
                </div>
                <div class="field" id="earn_static_fund_field" <? if (!$building->has_reserve_fund  || $building->put_reserve_value_manually): ?>style="display: none"<?endif;?>>
                    <label for="earn_static_fund">Monto de Fondo a recaudar fijo :</label>
                    <input type="checkbox" class="checkbox" id="earn_static_fund" name="earn_static_fund" <? if ($building->earn_static_fund): ?>value="1" checked="checked"<?else:?> value="0"<?endif;?> />
                </div>                
                <div class="field" id="earn_percentage_value_field" <? if ($building->earn_static_fund || !$building->has_reserve_fund || $building->put_reserve_value_manually): ?>style="display: none"<?endif;?>>
                    <label for="earn_percentage_value">Porcentaje E. Ordinarias destinadas al Fondo :</label>
                    <input type="text" name="earn_percentage_value" id="earn_percentage_value" value="<? if($building->earn_percentage_value != null): echo $building->earn_percentage_value; else: echo 0; endif; ?>" />
                </div>
                <div class="field" id="earn_static_fund_value_field" <? if (!$building->earn_static_fund || !$building->has_reserve_fund  || $building->put_reserve_value_manually): ?>style="display: none"<?endif;?>>
                    <label for="earn_static_fund_value">Monto a recaudar del fondo de reserva :</label>
                    <input type="text" name="earn_static_fund_value" id="earn_static_fund_value" value="<? if($building->earn_static_fund_value != null): echo $building->earn_static_fund_value; else: echo 0; endif;  ?>" />
                </div>
            </div>
        </div>
    </div>
    </div>
    <button name="cancel" class="cancel" id="close" value="true" type="button">Cancelar</button> 
    <button name="submit" class="submit" id="submit" value="true" type="button">Modificar Edificio</button> 
        
    </form>
</div>

