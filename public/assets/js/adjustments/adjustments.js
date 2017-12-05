$(document).ready(function(){    

    $("#building_estimate").change(function(){
        id_building = $("#building_estimate").val();
        if (id_building != ""){
            
            $.get('index.php/ajax/adjustments/get_building_base', { id: id_building }, function(rta){
                
                $("#new_base_estimate").val(rta);
                 
            });

            $.get('index.php/ajax/adjustments/get_building_estimate', { id: id_building } , function(rta){
                
                $("#adjustment_estimate").html("").append(rta);
                $("#adjustment_estimate").show("300");
                 
            });
            
        }
        else{
            $("#adjustment_estimate").html("<div>Debe seleccionar un edificio</div>");
        }
            
    });
    
    $("#btn_calculate_base").click(function(){
        id_building = $("#building_estimate").val();
        new_base_building = $("#new_base_estimate").val();
        if (id_building != "" || !isNaN(new_base_building)) {
            $.get('index.php/ajax/adjustments/get_building_estimate', { id: id_building, base: new_base_building } , function(rta){
                
                $("#adjustment_estimate").html("").append(rta);
                $("#adjustment_estimate").show("300");
                 
            });
        }
        else
            alert("Debe seleccionar un Edificio");
    });

    $("#btn_modify_base").click(function(){
        id_building = $("#building_estimate").val();
        new_base_building = $("#new_base_estimate").val();
        if (id_building != "" || !isNaN(new_base_building)) {
            $.post('index.php/ajax/adjustments/set_building_base', { id: id_building, base: new_base_building }, function(rta){
                
                if (rta == 'ok') {
                    alert("Modificaciones correctas");
                }
                 
            });
        }
        else
            alert("Debe seleccionar un Edificio");
    });
   
   
});
