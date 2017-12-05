
function getTags(){
   
    var tag_type = $('input[name=type_tag]:checked', '#formEditTagParameters').val();
    var building_id = $('#building_tag').val();
    var super_tag_id = $('#super_tag').val();

    if (super_tag_id !== "" && super_tag_id !== "-- seleccionar --"){
        
        $('#button_edit_super_tag').show(300);
        $.post('/ajax/editTags/get_tags_of_building', {building_id:building_id,tag_type:tag_type,super_tag_id:super_tag_id}, function(rta){
            $("#multiple_tags").html("").append(rta);
        });
    }
    else{
        $("#multiple_tags").html("").append("");
        $('#button_edit_super_tag').hide(300);
    }
    
}

function refreshTags(){
    
    var tag_type = $('input[name=type_tag]:checked', '#formEditTagParameters').val();
    var building_id = $('#building_tag').val();

    if(building_id !== "" && tag_type !== ""){
        
        $.post('/ajax/editTags/get_balance_tags_for_building', {building_id:building_id,tag_type:tag_type}, function(rta){
                
            $("#super_tag").html("").append(rta);
                 
        });
        
    }
}

function saveSuperTag(){
    
    var tag_type = $('input[name=type_tag]:checked', '#formEditTagParameters').val();
    var building_id = $('#building_tag').val();
    var tag_name = $('#tag_name').val();
    
    if (building_id !== "" && tag_type !== ""){
        
        var super_tag_id = $('#super_tag').val();
        
        var params;
        if (super_tag_id !== ""){
            params = {building_id:building_id, tag_type:tag_type, tag_name:tag_name, super_tag_id:super_tag_id};
        }
        else{
            params = {building_id:building_id, tag_type:tag_type, tag_name:tag_name};
        }
        
        $.post('/ajax/editTags/save_balance_tag', params, function(rta){
 
            if (rta.indexOf("success") !== -1 ){
                $('#building_tag').trigger('change');
            }
            else{
                $.fancybox({
                    'content' : rta
                });
            }
            
        });
    }
}

function saveTags(){
    var tag_type = $('input[name=type_tag]:checked', '#formEditTagParameters').val();
    var building_id = $('#building_tag').val();
    var tags_selected = $('#multiple_tags').val();
    var super_tag_id = $('#super_tag').val();
    
    if (building_id !== "" && tag_type !== "" && super_tag_id !== ""){
        
        var params = {building_id:building_id, tag_type:tag_type, super_tag_id:super_tag_id, tags_selected :tags_selected };
        
        $.post('/ajax/editTags/save_balance_tag_relation', params, function(rta){
 
            if (rta.indexOf("success") !== -1 ){
                $('#building_tag').trigger('change');
            }
            else{
                $.fancybox({
                    'content' : rta
                });
            }
            
        });
    }
}

$(document).ready(function(){   

    $('#my-select').multiSelect();

    $('#income_radio').click(function(){
        refreshTags();
        getTags();
        $('#button_edit_super_tag').hide(300);

    });
    
    $('#income_radio').click(function(){
        refreshTags();
        getTags();
        $('#button_edit_super_tag').hide(300);

    });

    $('#button_add_super_tag').click(function(){
       
        $('#super_tag').val("");
        $('#div_tag_selector').hide("300");
        $('#div_tag_editor').show("300");
       
    });
    
    $('#button_edit_super_tag').click(function(){
        
        $('#tag_name').val($("#super_tag option:selected" ).text());
        $('#div_tag_selector').hide("300");
        $('#div_tag_editor').show("300");
        
    });
    
    $('#button_save_super_tag').click(function(){
        
        saveSuperTag();
        $('#div_tag_editor').hide("300");
        $('#div_tag_selector').show("300");
        
        
    });
    
    $('#button_cancel_save_super_tag').click(function(){
        $('#div_tag_editor').hide("300");
        $('#div_tag_selector').show("300");
    });
    
    $("#super_tag").change(function (){
    
        getTags();
        
    });
    
    $("#building_tag").change(function (){
        
        refreshTags();
        getTags();
        $('#button_edit_super_tag').hide(300);

    });
    
    $('#button_save_tags').click(function(){
        
        saveTags();
        
    });

    $("#multiple_tags").mousedown(function(e){
        e.preventDefault();

        var scroll = this.scrollTop;

        e.target.selected = !e.target.selected;

        this.scrollTop = scroll;

        $(this).focus();
    }).mousemove(function(e){e.preventDefault()});
    
    
});