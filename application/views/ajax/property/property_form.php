<style>
     
    .ui-widget input, .ui-widget select{
        background: none repeat scroll 0 0 #FFFFFF;
        border: 1px solid #CCCCCC;
        border-radius: 3px 3px 3px 3px;
        color: #5D5D5D;
        font: 1.0em 'trebuchet ms',arial,sans-serif;
        padding: 5px;
    }
    
    .ui-state-active a, .ui-state-active a:link, .ui-state-active a:visited{
        color: #ff8832;
    }

    .ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited{
        color: #FFFFFF; 
    }

    #form_property_add{
        font: 1.0em 'trebuchet ms',arial,sans-serif;
    }

    #form_property_add h3{
        background: #635B53 no-repeat 0 0;
    }
    
    .adv_col div{
        font: 0.9em 'trebuchet ms',arial,sans-serif;
        width: 100%
    }
    
    .adv_col input{
        width:60%;
        float: none;
    }
    
    .adv_col select{
        width:63%;
        font: 1em 'trebuchet ms',arial,sans-serif;
        float: none;            
    }
    
    .field_property label{
        position: absolute;            
        margin-top: 8px;
    }
    
    .field_property input{
        width:60%;
        float:right;
        margin-right: 15px;
    }
    
    .field_property select{
        width:63%;
        float:right;
        font: 1em 'trebuchet ms',arial,sans-serif;
        margin-right: 15px;
    }
    
    .field_document div{
        width:50%;
    }
   
    .field_divided{
        width: 50%;
        float: left;
        font: 1em 'trebuchet ms',arial,sans-serif;
    }
    
    .field_divided select{
        float: right;
        margin-right: 15px;
        width: 45%;
    }
    
    .field_divided input{
        float: right;
        margin-right: 15px;
        width: 45%;
    }
    
    .field_divided label{
        position: absolute;
    }
    
    
    
</style>
    
<script>

function add_property(){
    $.post('index.php/ajax/properties/save_property', $("#form_property_add").serialize(), function(rta){
        if(rta.indexOf("success") == -1){
            $("#div_errors").html("").append(rta).show().delay(4000);
            $("#div_errors").hide("slow");
        }
        else{
            arr = rta.split(":");
            refreshBuildings(arr[1]);                
            alert("Cambios realizados con exito.");
            parent.$.fancybox.close();
            $('#building').trigger('change');
        }
    });
}

function modify_property(){
    $.post('index.php/ajax/properties/modify_property', $("#form_property_add").serialize(), function(rta){
        if(rta.indexOf("success") == -1){
            $("#div_errors").html("").append(rta).show().delay(4000);
            $("#div_errors").hide("slow");
        }
        else{
            arr = rta.split(":");
            refreshBuildings(arr[1]);                
            alert("Cambios realizados con exito.");
            parent.$.fancybox.close();
            $('#building').trigger('change');
        }
    });
}

$(document).ready(function(){    

    $('#cancel').click(function(){
        parent.$.fancybox.close();        
    });
    
    $("#accordion").accordion();
    $("#accordion").accordion("option", "animated", 'bounceslide');

   
    $('#newOwnerButton').click(function(){
        $('#owner').val("");
        $('#owner').trigger('change');
        $('#oldOwnerDiv').slideUp("slow");
        $("#accordion").accordion( "option", "active", 1 );
    });

    $("#owner").change(function(){
        owner_id = $("#owner").val();
        $.post('index.php/ajax/properties/view_owner', {id : owner_id}, function(rta){
            $("#div_owner").html("").append(rta)
        });
    });

    
});
</script>

<div class="contenedor_property">
        
    <div id="div_errors" class="errors">
    </div>
    
    <form method="post" id="form_property_add" >
    <? if(isset($property) && !is_null($property)): ?>
        <input name="id_property" id="id_property" type="hidden" value="<?= $property->id ?>">
    <? endif; ?>
    <input name="id_building" id="id_building" type="hidden" value="<?= $id_building; ?>">
    <div id="accordion">
    <h3><a href="#" class="selectAccordion" tabindex="1">Agregar Unidad Funcional</a></h3>
    <div>
        <div class='div_border div_property'>
            <div class='adv_col'>
                <div class="field_property">
                    <label for="floor" >Piso : </label>
                    <input type="text" name="floor" id="floor" value="<? if (isset($property->floor) && !is_null($property->floor)) echo $property->floor ?>" >
                </div>
                <div class="field_property">
                    <label for="appartment" >Departamento : </label>
                    <input type="text" name="appartment" id="appartment" value="<? if (isset($property->appartment) && !is_null($property->appartment)) echo $property->appartment ?>" >
                </div>
                <div class="field_property">
                    <label for="functional_unity">Unidad funcional : </label>
                    <input type="text" name="functional_unity" id="functional_unity" value="<? if (isset($property->functional_unity) && !is_null($property->functional_unity)) echo $property->functional_unity ?>" >
                </div>
                <div class="field_property">
                    <label for="balance_extraordinary">Saldo Extraordinarias : </label>
                    <input type="text" name="balance_extraordinary" id="balance_extraordinary" value="<? if (isset($property->balance_extraordinary) && !is_null($property->coefficient)) echo $property->balance_extraordinary ?>" >
                </div>
            </div>
            <div class='adv_col'>
                <div class="field_property">
                    <label for="coefficient">Coeficiente : </label>
                    <input type="text" name="coefficient" id="coefficient" value="<? if (isset($property->coefficient) && !is_null($property->coefficient)) echo $property->coefficient ?>" >
                </div>
                <? if($building->put_reserve_value_manually): ?>
                <div class="field_property">
                    <label for="static_reserve_value">Valor Fondo Reserva : </label>
                    <input type="text" name="static_reserve_value" id="static_reserve_value" value="<? if (isset($property->static_reserve_value) && !is_null($property->static_reserve_value)) echo $property->static_reserve_value; else echo 0; ?>" >
                </div>
                <? endif; ?>
                <!--<div class="field_property">
                    <label for="meters">Metros : </label>
                    <input type="text" name="meters" id="meters" value="<? if (isset($property->meters) && !is_null($property->meters)) echo $property->meters ?>" >
                </div>-->
                <div class="field_property">
                    <label for="balance">Saldo cuenta : </label>
                    <input type="text" name="balance" id="balance" value="<? if (isset($property->balance) && !is_null($property->coefficient)) echo $property->balance ?>" >
                </div>
                <div class="field_property">
                    <label for="balance_reserve">Saldo F. de Reserva : </label>
                    <input type="text" name="balance_reserve" id="balance_reserve" value="<? if (isset($property->balance_reserve) && !is_null($property->coefficient)) echo $property->balance_reserve ?>" >
                </div>
                <div class="field_property">
                    <label for="auxiliary_property_of">Propiedad Primaria : </label>
                    <select id="auxiliary_property_of" name="auxiliary_property_of">
                        <? if (isset($auxiliary_property_of) && !is_null($auxiliary_property_of)): echo get_select_property_auxiliary($building->id,$auxiliary_property_of); else: echo get_select_property_auxiliary($building->id); endif; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class='div_border div_property' id="oldOwnerDiv" >
            <div class='adv_col'>
                <div class="field_property">
                    <label for="owner">Propietario : </label>
                    <select id="owner" name="owner">
                        <? if (isset($owner) && !is_null($owner)): echo get_select_owners($owner->id); else: echo get_select_owners(); endif; ?>
                    </select>
                </div>
            </div>
            <div class='adv_col'>
                <button name="newOwner" class="newOwner" id="newOwnerButton" type="button">Nuevo Propietario</button> 
            </div>
        </div>
    </div>
    <h3><a href="#" class="selectAccordion" tabindex="2">Propietario</a></h3>
    <div id="div_owner">
        <? if (isset($owner) && !is_null($owner)):
                $args["owner"] = $owner;
                $this->load->view("ajax/property/owner",$args);
            else:
                $this->load->view("ajax/property/owner");
            endif;
        ?>        
    </div>
    
    </form>
    
    </div>
    <button name="submit" class="submit" id="submit" onclick="<? if(isset($property) && !is_null($property)): echo "modify_property();"; else: echo "add_property();"; endif;?>" type="button"><? if(isset($property) && !is_null($property)): echo "Modificar Propiedad"; else: echo "Agregar Propiedad"; endif;?></button> 
    <button name="cancel" class="cancel" id="cancel" type="button">Cancelar</button>
</div>