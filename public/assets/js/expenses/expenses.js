function delete_expense(id){
    
    if (id != ""){
        $.ajaxSetup({async:false});    
        $.fancybox.showLoading({'modal':true});
        $.post('/ajax/expenses/delete_expense', {id:id}, function(rta){
            if (rta.indexOf("ok") != -1)
                $('#building').trigger('change');            
            else{
                $.fancybox({
                    'content' : rta
                });
            }
        });
        $.fancybox.close();
    }
}

function edit_expense(expense_id){
    if (expense_id != ""){
        $.post('/ajax/expenses/edit_expense', {id : expense_id}, function(rta){
            $.fancybox({
                'content' : rta
            });
        });
    }
}

function increment_priority(expense_id){
    if (expense_id != ""){
        $.post('/ajax/expenses/increment_priority', {id : expense_id},function(){
            $('#building').trigger('change');
        });
        
    }
}

function decrement_priority(expense_id){
    if (expense_id != ""){
        $.post('/ajax/expenses/decrement_priority', {id : expense_id},function(){
            $('#building').trigger('change');
        });
    }
}

function delete_extraordinary_expense(id){
    
    if (id != ""){
        $.ajaxSetup({async:false});    
        $.fancybox.showLoading({'modal':true});
        $.post('/ajax/expenses/delete_extraordinary_expense', {id:id}, function(rta){
            if (rta.indexOf("ok") != -1)
                $('#building_extra').trigger('change');            
            else{
                $.fancybox({
                    'content' : rta
                });
            }
        });
        $.fancybox.close();
    }
}

function edit_extraordinary_expense(expense_id){
    if (expense_id != ""){
        $.post('/ajax/expenses/edit_extraordinary_expense', {id : expense_id}, function(rta){
            $.fancybox({
                'content' : rta
            });
        });
    }
}

function increment_extraordinary_priority(expense_id){
    if (expense_id != ""){
        $.post('/ajax/expenses/increment_extraordinary_priority', {id : expense_id},function(){
            $('#building_extra').trigger('change');
        });
        
    }
}

function decrement_extraordinary_priority(expense_id){
    if (expense_id != ""){
        $.post('/ajax/expenses/decrement_extraordinary_priority', {id : expense_id},function(){
            $('#building_extra').trigger('change');
        });
    }
}

function delete_special_expense(id){
    
    if (id != ""){
        $.ajaxSetup({async:false});    
        $.fancybox.showLoading({'modal':true});
        $.post('/ajax/expenses/delete_special_expense', {id:id}, function(rta){
            if (rta.indexOf("ok") != -1)
                $('#building_special').trigger('change');            
            else{
                $.fancybox({
                    'content' : rta
                });
            }
        });
        $.fancybox.close();
    }
}

function edit_special_expense(expense_id){
    if (expense_id != ""){
        $.post('/ajax/expenses/edit_special_expense', {id : expense_id}, function(rta){
            $.fancybox({
                'content' : rta
            });
        });
    }
}

function increment_special_priority(expense_id){
    if (expense_id != ""){
        $.post('/ajax/expenses/increment_special_priority', {id : expense_id},function(){
            $('#building_special').trigger('change');
        });
        
    }
}

function decrement_special_priority(expense_id){
    if (expense_id != ""){
        $.post('/ajax/expenses/decrement_special_priority', {id : expense_id},function(){
            $('#building_special').trigger('change');
        });
    }
}

function delete_estimative_expense(id){
    
    if (id != ""){
        $.ajaxSetup({async:false});    
        $.fancybox.showLoading({'modal':true});
        $.post('/ajax/expenses/delete_estimative_expense', {id:id}, function(rta){
            if (rta.indexOf("ok") != -1)
                $('#building_estimative').trigger('change');            
            else{
                $.fancybox({
                    'content' : rta
                });
            }
        });
        $.fancybox.close();
    }
}

function edit_estimative_expense(expense_id){
    if (expense_id != ""){
        $.post('/ajax/expenses/edit_estimative_expense', {id : expense_id}, function(rta){
            $.fancybox({
                'content' : rta
            });
        });
    }
}

function increment_estimative_priority(expense_id){
    if (expense_id != ""){
        $.post('/ajax/expenses/increment_estimative_priority', {id : expense_id},function(){
            $('#building_estimative').trigger('change');
        });
        
    }
}

function decrement_estimative_priority(expense_id){
    if (expense_id != ""){
        $.post('/ajax/expenses/decrement_estimative_priority', {id : expense_id},function(){
            $('#building_estimative').trigger('change');
        });
    }
}

function refreshExtraordinaryPeriods(){
    var building_id = $('#building_extra').val();
    if(building_id != "")
        $('#extraordinary_period').load('/ajax/expenses/get_extraordinary_period/'+building_id);
    else
        $('#extraordinary_period').html('');
}

$(document).ready(function() {

    $( "#tabs" ).tabs();
    
    $('#building_extra').change(function(){
        refreshExtraordinaryPeriods();
    });
    
    $('#expense_tags').autocomplete({
     source: function(request, response) {
            $.ajaxSetup({async:true});    
            $.ajax({
                url: "/ajax/expenses/get_expense_tags",
                dataType: "json",
                delay: 100,
                data: {
                    term : request.term,
                    id : $("#building").val()
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        min_length: 3,
        delay: 300
    });

    $('#expense_tags_special').autocomplete({
     source: function(request, response) {
            $.ajaxSetup({async:true});    
            $.ajax({
                url: "/ajax/expenses/get_expense_tags",
                dataType: "json",
                delay: 100,
                data: {
                    term : request.term,
                    id : $("#building").val()
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        min_length: 3,
        delay: 300
    });
    
    $('#expense_tags_extraordinary').autocomplete({
     source: function(request, response) {
            $.ajaxSetup({async:true});    
            $.ajax({
                url: "/ajax/expenses/get_expense_tags",
                dataType: "json",
                delay: 100,
                data: {
                    term : request.term,
                    id : $("#building").val()
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        min_length: 3,
        delay: 300
    });

    $('#expense_tags_estimative').autocomplete({
     source: function(request, response) {
            $.ajaxSetup({async:true});    
            $.ajax({
                url: "/ajax/expenses/get_expense_tags",
                dataType: "json",
                delay: 100,
                data: {
                    term : request.term,
                    id : $("#building_estimative").val()
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        min_length: 3,
        delay: 300
    });
    
    $("#building").change(function(){
        id = $(this).val();
        if (id != ""){
            $(this).showLoadingView();     
            $.post('/ajax/expenses/get_building_expenses', {id:id}, function(rta){
                if (rta.indexOf("nok") == -1){
                    $("#expenses_info").html("").append(rta);
                    $("#expenses_info").show("slow");
                }
                else{
                    $("#expenses_info").html("");
                    $("#expenses_info").hide(0);
                }
                $(this).closeLoadingView();     
            });
        }
        else{
            $("#expenses_info").html("");
            $("#expenses_info").hide(0);
        }
    });
    
    $("#building_extra").change(function(){
        id = $(this).val();
        if (id != ""){
            $(this).showLoadingView();       
            $.post('/ajax/expenses/get_building_extraordinary_expenses', {id:id}, function(rta){
                if (rta.indexOf("nok") == -1){
                    $("#extraordinary_expenses_info").html("").append(rta);
                    $("#extraordinary_expenses_info").show("slow");
                }
                else{
                    $("#extraordinary_expenses_info").html("");
                    $("#extraordinary_expenses_info").hide(0);
                }
                $(this).closeLoadingView(); 
            });
        }
        else{
            $("#extraordinary_expenses_info").html("");
            $("#extraordinary_expenses_info").hide(0);
        }
    });


    $("#building_special").change(function(){
        id = $(this).val();
        if (id != ""){
            $(this).showLoadingView();    
            $.post('/ajax/expenses/get_building_special_expenses', {id:id}, function(rta){
                if (rta.indexOf("nok") == -1){
                    $("#special_expenses_info").html("").append(rta);
                    $("#special_expenses_info").show("slow");
                }
                else{
                    $("#special_expenses_info").html("");
                    $("#special_expenses_info").hide(0);
                }
                $(this).closeLoadingView();
            });
        }
        else{
            $("#special_expenses_info").html("");
            $("#special_expenses_info").hide(0);
        }
    });
    
    $("#building_estimative").change(function(){
        id = $(this).val();
        if (id != ""){
            $(this).showLoadingView();    
            $.post('/ajax/expenses/get_building_estimative_expenses', {id:id}, function(rta){
                if (rta.indexOf("nok") == -1){
                    $("#estimative_expenses_info").html("").append(rta);
                    $("#estimative_expenses_info").show("slow");
                }
                else{
                    $("#estimative_expenses_info").html("");
                    $("#estimative_expenses_info").hide(0);
                }
                $(this).closeLoadingView();
            });
        }
        else{
            $("#estimative_expenses_info").html("");
            $("#estimative_expenses_info").hide(0);
        }
    });

    $( "#add_expense" ).click(function(){
        $.post('/ajax/expenses/add_expense', $("#frm_add_expense").serialize(), function(rta){
            if (rta.indexOf("success") == -1){
                $("#div_errors").html("").append(rta);
            }
            else{
                $("#div_errors").html("");
                $('#building').trigger('change');
            }
        });
    });
    
    
    $( "#add_extraordinary_expense" ).click(function(){
        $.post('/ajax/expenses/add_extraordinary_expense', $("#frm_add_expense_extraordinary").serialize(), function(rta){
            if (rta.indexOf("success") == -1){
                $("#div_errors_extra").html("").append(rta);
            }
            else{
                $("#div_errors_extra").html("");
                $('#building_extra').trigger('change');
            }
        });
    });

    $( "#add_special_expense" ).click(function(){
        $.post('/ajax/expenses/add_special_expense', $("#frm_add_special_expense").serialize(), function(rta){
            if (rta.indexOf("success") == -1){
                $("#div_errors").html("").append(rta);
            }
            else{
                $("#div_errors").html("");
                $('#building_special').trigger('change');
            }
        });
    });

    $( "#add_estimative_expense" ).click(function(){
        $.post('/ajax/expenses/add_estimative_expense', $("#frm_add_estimative_expense").serialize(), function(rta){
            if (rta.indexOf("success") == -1){
                $("#div_estimative_errors").html("").append(rta);
            }
            else{
                $("#div_estimative_errors").html("");
                $('#building_estimative').trigger('change');
            }
        });
    });
    
});