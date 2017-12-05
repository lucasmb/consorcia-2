$(document).ready(function(){    

    // $("#building_estimate").change(function(){
    //     id_building = $("#building_estimate").val();
    //     if (id_building != ""){
            
    //         $.get('index.php/ajax/adjustments/get_building_base', { id: id_building }, function(rta){
                
    //             $("#new_base_estimate").val(rta);
    //             $("#adjustment_estimate").html("")
                 
    //         });

    //         $.get('index.php/ajax/adjustments/get_building_estimate', { id: id_building } , function(rta){
                
    //             $("#adjustment_estimate").html("").append(rta);
    //             $("#adjustment_estimate").show("300");
                 
    //         });
            
    //     }
    //     else{
    //         $("#adjustment_estimate").html("<div>Debe seleccionar un edificio</div>");
    //     }
            
    // });

    $( "#tabs" ).tabs();
    
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


    $("#frm_upload_roela_file_bank_payments").submit(function(evt){  
        evt.preventDefault();
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: 'index.php/ajax/bankPayment/upload_roela_file_bank_payments',
            type: 'POST',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function (rta) {

                $("#payment_info").html("").append(rta);
                $("#payment_info").show("300");
                
            }
        });

        return false;

    });

    $("#frm_upload_roela_file_bank_expenses").submit(function(evt){  
        evt.preventDefault();
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: 'index.php/ajax/bankPayment/upload_roela_file_bank_expenses',
            type: 'POST',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function (rta) {

                $("#expenses_info").html("").append(rta);
                $("#expenses_info").show("300");
                
            }
        });

        return false;

    });

});
