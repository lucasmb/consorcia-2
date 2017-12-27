$(document).ready(function(){    

    $("#generate_report_ordinary").click(function(){
        id_building = $("#building_expense").val();
        if (id_building != ""){
            document.getElementById("frm_report_expense_bill").setAttribute("action","reports/report_expenses_bill");
            $("#frm_report_expense_bill").submit();            
        }
        else
            alert("Debe seleccionar un Edificio");
    });

    $("#generate_report_payment_ordinary").click(function(){
        id_building = $("#building_expense").val();
        if (id_building != ""){
            document.getElementById("frm_report_expense_bill").setAttribute("action","reports/report_expenses_payment_bill");
            $("#frm_report_expense_bill").submit();            
        }
        else
            alert("Debe seleccionar un Edificio");
    });
   
    $("#generate_report_extraordinary").click(function(){
        id_building = $("#building_expense_extra").val();
        if (id_building != ""){
            document.getElementById("frm_report_extraordinary_expense_bill").setAttribute("action","reports/report_extraordinary_expenses_bill");
            $("#frm_report_extraordinary_expense_bill").submit();            
        }
        else
            alert("Debe seleccionar un Edificio");
    });
   
    $("#generate_report_payment_extraordinary").click(function(){
        id_building = $("#building_expense_extra").val();
        if (id_building != ""){
            document.getElementById("frm_report_extraordinary_expense_bill").setAttribute("action","reports/report_extraordinary_expenses_payment_bill");
            $("#frm_report_extraordinary_expense_bill").submit();            
        }
        else
            alert("Debe seleccionar un Edificio");
    });
   
    $("#generate_report_extraordinary_and_ordinary").click(function(){
        id_building = $("#building_expense_extraordinary_and_ordinary").val();
        if (id_building != ""){
                document.getElementById("frm_report_extraordinary_expense_bill").setAttribute("action","reports/report_extraordinary_and_ordinary_expense_bill");
            $("#frm_report_extraordinary_and_ordinary_expense_bill").submit();            
        }
        else
            alert("Debe seleccionar un Edificio");
    });
   
   
    $("#generate_summary_extraordinary_only").click(function(){
        id_building = $("#building_summary_extraordinary").val();
        if (id_building != ""){
            $("#frm_report_summary_extrordinary").submit();            
        }
        else
            alert("Debe seleccionar un Edificio");
    });
    
    $("#generate_summary").click(function(){
        id_building = $("#building_summary").val();
        if (id_building != ""){
            $("#frm_report_summary").submit();            
        }
        else
            alert("Debe seleccionar un Edificio");
    });
    
    $("#building_summary").change(function(){
        id_building = $("#building_summary").val();
        if (id_building != ""){
            $("#pay_days").load('/ajax/buildings/pay_actual_days/'+id_building);
            
        }
        else{
            $("#pay_days").html("");
        }
            
    });
   
    
    $("#generate_capitulation").click(function(){
        id_building = $("#building_capitulation").val();
        if (id_building != ""){
            $("#frm_report_capitulation").submit();            
        }
        else
            alert("Debe seleccionar un Edificio");
    });
   
    $("#generate_capitulation_ordinary").click(function(){
        id_building = $("#building_capitulation_ordinary").val();
        if (id_building != ""){
            $("#frm_report_capitulation_ordinary").submit();            
        }
        else
            alert("Debe seleccionar un Edificio");
    });
   
   
    $("#generate_capitulation_extraordinary").click(function(){
        id_building = $("#building_capitulation_extraordinary").val();
        if (id_building != ""){
            $("#frm_report_capitulation_extraordinary").submit();            
        }
        else
            alert("Debe seleccionar un Edificio");
    });
   
     $("#building_blank_select").change(function (){
        
        var id = $(this).val();

        if (id != ""){
            $.post('/ajax/reports/get_properties_select', {id:id}, function(rta){
                
                $("#properties_blank_select").html("").append(rta);
                 
            });
        }
        else{
            $("#properties_blank_select").html("");
        }
    });
   
    $("#generate_blank_expense").click(function(){
        id_building = $("#building_blank_select").val();
        id_property = $("#properties_blank_select").val();
        if (id_property != ""){
            $("#frm_blank_expense").submit();
        }
        else
            alert("Debe seleccionar una propiedad de un edificio");
    });
   
    $("#send_email_expense").click(function(){
       
        $("#frm_send_email").submit();            
       
    });
    
    $("#generate_balance").click(function(){
       
        $("#frm_report_balance").submit();            
       
    });
    
    $("#building_balance_select").change(function (){
        
        var id = $(this).val();

        if (id != ""){
            $.post('/ajax/reports/get_building_periods', {id:id}, function(rta){
                
                $("#month_building_from").html("").append(rta);
                $("#month_building_to").html("").append(rta);
                $("#div_month_building_from").show("300");
                $("#div_month_building_to").show("300");
                 
            });
        }
        else{
            $("#div_month_building_from").hide("300");
            $("#div_month_building_to").hide("300");
        }
    });

    $("#building_autogenerated_days_select").change(function(){
        id_building = $("#building_autogenerated_days_select").val();
        if (id_building != ""){
            $("#autogenerated_pay_days").load('/ajax/buildings/autogenerated_pay_days/'+id_building);
        }
        else{
            $("#autogenerated_pay_days").html("");
        }
            
    });

    $("#date_schedule_sheet").datepicker();
    $("#date_schedule_sheet").datepicker('option', {dateFormat: 'yy-mm-dd'});

    $( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
    
});
