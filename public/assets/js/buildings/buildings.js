function refreshBuildings(id){
    if (isNaN(id))
        $("#building").load('/ajax/buildings/refresh_buildings');
    else
        $("#building").load('/ajax/buildings/refresh_buildings/'+id);
};

$(document).ready(function() {
    
    $('#button_deactivate').click(function(){
        id = $("#building").val();
        if (id != ""){
            $.post('/ajax/buildings/deactivate_building', {id:id}, function(rta){
                $.fancybox({
                    'content' : rta
                });
                refreshBuildings();
                $('#building').trigger('change');
            });
        }
    });

    $('#button').click(function(){
        id = $("#building").val();
        if (id != ""){
            $.post('/ajax/buildings/edit_building', {id:id}, function(rta){
                $.fancybox({
                    'content' : rta
                });

            });
        }
        else{
            $.post('/ajax/buildings/add_building', { }, function(rta){
                $.fancybox({
                    'content' : rta
                });

            });
        }            
    });
    
    $('#building').change(function(){
        id = $(this).val();
        if (id != ""){
            $(this).showLoadingView();
            $.post('/ajax/buildings/get_building_data', {id:id}, function(rta){
                $("#building_info").html("").append(rta);
                $(this).closeLoadingView();
            });
            $("#button").html("Editar Edificio");
            $("#button_deactivate").attr("style", "display:inline-block");

        }
        else{
            $("#building_info").html("");
            $("#button_deactivate").attr("style", "display:none");
            $("#button").html("Agregar Edificio");
        }
    });

});
