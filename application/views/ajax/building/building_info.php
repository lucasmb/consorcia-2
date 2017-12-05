<script>

function view_owner(owner_id){
    if (owner_id != ""){
        $.post('index.php/ajax/properties/view_owner', {id : owner_id, view: true}, function(rta){
            $.fancybox({
                'content' : rta
            });

        });
    }
}

function edit_property(property_id){
    if (property_id != ""){
        $.post('index.php/ajax/properties/edit_property', {id : property_id}, function(rta){
            $.fancybox({
                'content' : rta
            });
        });
    }
}

function delete_property(property_id){
    var answer = confirm("¿Está seguro que desea eliminar esta propiedad?. Tenga en cuenta que se eliminará tambien el historial de propietarios de esta propiedad.");
    if (answer){
        if (property_id != ""){
        $.post('index.php/ajax/properties/delete_property', {id : property_id}, function(rta){
            if (rta.indexOf("ok") != -1){
                $('#building').trigger('change');
                alert("Se ha eliminado la propiedad de manera correcta");
            }            
        });
    }
    }    
}


$(document).ready(function() {

    $('.add_property_button').click(function(){
        id = $("#building").val();
        if (id != ""){
            $.post('index.php/ajax/properties/add_property', {id:id}, function(rta){
                $.fancybox({
                    'content' : rta
                });

            });
        }
    });
});
    
</script>
<div class='div_border'>
    <div class='adv_col dynamic_col'>
        <div class="field">
            <label for="ciudad">Ciudad : </label>
            <div id="ciudad"><?= $building->city->zone->name ?>, <?= ' '.$building->city->name ?> </div>
        </div>
        <div class="field">
            <label for="calle">Calle : </label>
            <div id="calle"><?= $building->street ?></div>
        </div>
        <div class="field">
            <label for="numero">Numero : </label>
            <div id="numero"><?= $building->number ?></div>
        </div>
    </div>
    <div class='adv_col dynamic_col'>
        <div class="field">
            <label for="earning_monthly">Expensas Mensuales : </label>
            <div id="earning_monthly"><?= $building->earning_monthly ?></div>
        </div>
        <div class="field">
            <label for="cuit">CUIT : </label>
            <div id="cuit"><?= $building->cuit ?></div>
        </div>        
    </div>
</div>
<div class='div_border' id="div_properties">
<?
    if($building->properties):

        $args["properties"] = $building->properties;
        $args["sort_column"] = "floor";
        $args["order"] = "ASC";
        $this->load->view("ajax/building/properties_table",$args);

?>
<? else: ?>

    <button class="add_property_button" id="add_property_button" type="button">Agregar Propiedad</button> 

<? endif; ?>
</div>