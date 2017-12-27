function delete_payment(id_income){
    
    $.post('/ajax/incomes/delete_payment', {id:id_income}, function(rta){
        if (rta.indexOf("ok") != -1 ){
            
            $('#building').trigger('change');
            
        }
        else
            $.fancybox({
                'content' : rta
            });
    });            
}

function close_modal(number_of_request) {
    console.log(number_of_request);
    if (number_of_request == 5) {
        $(this).closeLoadingView();
    }
}

$(document).ready(function() {

    $(".result_div").hide();
    $( "#tabs" ).tabs();
    
    $('#building').change(function(){

        var requests = 0;
        id = $(this).val();
        if (id != ""){
            $(".default_div").hide();            
            $(".result_div").show();       
            $(this).showLoadingView();
            
            $.post('/ajax/incomes/view_income_operations', {id:id}, function(rta){
                $(".operations_div").html("").append(rta);
                requests = requests + 1;
                close_modal(requests);
            });

            $.post('/ajax/incomes/view_income_payment', {id:id}, function(rta){
                $(".payments_div").html("").append(rta);
                requests = requests + 1;
                close_modal(requests);
            });

            $.post('/ajax/incomes/view_income_summary', {id:id}, function(rta){
                $(".summary_div").html("").append(rta);
                requests = requests + 1;
                close_modal(requests);
            });

            $.post('/ajax/incomes/view_income_extra', {id:id}, function(rta){
                $(".extraordinary_div").html("").append(rta);
                requests = requests + 1;
                close_modal(requests);
            });
            /*$.post('/ajax/incomes/view_income_ordinary_and_extraordinary', {id:id}, function(rta){
                $(".extraordinary_div").html("").append(rta);
            });*/
            $.post('/ajax/incomes/view_income_aditional', {id:id}, function(rta){
                $(".aditional_div").html("").append(rta);
                requests = requests + 1;
                close_modal(requests);
            });            

        }
        else{
            $(".result_div").hide();
            $(".default_div").show();            
        }
        
        
    });
});