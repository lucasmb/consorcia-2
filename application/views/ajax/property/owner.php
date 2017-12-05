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
        margin-bottom: 0px;
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

<div class='div_border div_property' id="newOwnerDiv">
    <div class='adv_col'>
        <div class="field_property">
            <label for="name">Nombre : </label>
            <input type="text" name="name" id="name" <? if (isset($owner->name) && !is_null($owner->name)) echo "value='$owner->name' readonly='readonly' ";?> >
        </div>                
        <div class="field_property">
            <label for="phone">Telefono : </label>
            <input type="text" name="phone" id="phone" <? if (isset($owner->phone) && !is_null($owner->phone)) echo "value='$owner->phone' readonly='readonly' ";?> >
        </div>                
        <div class="field_property">
            <label for="street">Calle : </label>
            <input type="text" name="street" id="street" <? if (isset($owner->street) && !is_null($owner->street)) echo "value='$owner->street' readonly='readonly' ";?> >
        </div>                
        <div class="field_property">
            <label for="floor_owner">Piso : </label>
            <input type="text" name="floor_owner" id="floor_owner" <? if (isset($owner->floor) && !is_null($owner->floor)) echo "value='$owner->floor' readonly='readonly' ";?> >
        </div>
        <div class="field_document">
            <div class="field_divided">
                <label for="type_document">Tipo Doc. : </label>
                <select id="type_document" name="type_document" <? if(isset($owner->type_document_id) && !is_null($owner->type_document_id)) echo "disabled='disabled' " ?> >
                    <? if(isset($owner->type_document_id) && !is_null($owner->type_document_id)) echo get_select_type_document($owner->type_document_id); else echo get_select_type_document();?>
                </select>
            </div>                
            <div class="field_divided">
                <label for="document">N. Documento : </label>
                <input type="text" name="document" id="document" <? if (isset($owner->document) && !is_null($owner->document)) echo "value='$owner->document' readonly='readonly' ";?> >
            </div>
        </div>
    </div>
    <div class='adv_col'>
        <div class="field_property">
            <label for="lastname">Apellido : </label>
            <input type="text" name="lastname" id="lastname" <? if (isset($owner->lastname) && !is_null($owner->lastname)) echo "value='$owner->lastname' readonly='readonly' ";?> >
        </div>
        <div class="field_property">
            <label for="alternative_phone">Tel. Alternativo : </label>
            <input type="text" name="alternative_phone" id="alternative_phone" <? if (isset($owner->alternative_phone) && !is_null($owner->alternative_phone)) echo "value='$owner->alternative_phone' readonly='readonly' ";?> >
        </div>
        <div class="field_property">
            <label for="number_street">Numero : </label>
            <input type="text" name="number_street" id="number_street" <? if (isset($owner->number_street) && !is_null($owner->number_street)) echo "value='$owner->number_street' readonly='readonly' ";?> >
        </div>
        <div class="field_property">
            <label for="appartment_owner">Departamento : </label>
            <input type="text" name="appartment_owner" id="appartment_owner" <? if (isset($owner->appartment) && !is_null($owner->appartment)) echo "value='$owner->appartment' readonly='readonly' ";?> >
        </div>
        <div class="field_property">
            <label for="cuit_owner">Cuit : </label>
            <input type="text" name="cuit_owner" id="cuit_owner" <? if (isset($owner->cuit) && !is_null($owner->cuit)) echo "value='$owner->cuit' readonly='readonly' ";?> >
        </div>
    </div>
</div>