<script>
    
    
    function edit_income_aditional(income_id){
        if (income_id != ""){
            $.post('/ajax/incomes/edit_aditional_income', {id : income_id}, function(rta){
                $.fancybox({
                    'content' : rta
                });
            });
        }
    }
    
    function delete_income_aditional(id){        
        if (id != ""){
            $.post('/ajax/incomes/delete_aditional_income', { id : id } , function(rta){
                
                $('#building').trigger('change');
            });    
        }
    }
    
    function increment_priority(income_id){
        if (income_id != ""){
            $.post('/ajax/incomes/increment_priority', {id : income_id},function(){
                $('#building').trigger('change');
            });

        }
    }

    function decrement_priority(income_id){
        if (income_id != ""){
            $.post('/ajax/incomes/decrement_priority', {id : income_id},function(){
                $('#building').trigger('change');
            });
        }
    }
    
    $( "#start_date" ).datepicker();
    $( "#end_date" ).datepicker();
    
    $("#income_tags").autocomplete({
     source: function(request, response) {
            $.ajaxSetup({async:true});    
            $.ajax({
                url: "/ajax/incomes/get_income_tags",
                dataType: "json",
                delay: 100,
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        min_length: 3,
        delay: 300
    });
        /*source: "/ajax/incomes/get_income_tags",
        minLength: 3,
        delay: 100,
        select: function(event,ui){
        }
    });*/
    
    $( "#add_aditional_button" ).click(function(){
        $.post('/ajax/incomes/add_aditional_income', $("#frm_aditional_income").serialize(), function(rta){
            if (rta.indexOf("success") == -1){
                $("#div_errors").html("").append(rta);
            }
            else{
                $('#building').trigger('change');
            }
	});
    });
    
    $( ".button_add_aditional_older_income" ).click(function(){
        
        var form = "#frm_add_older_aditional_income" + $(this).val();
        $.post('/ajax/incomes/add_older_expense', $(form).serialize(), function(rta){
            if (rta.indexOf("success") == -1){
                $("#div_errors_older_add").html("").append(rta);
            }
            else{
                $("#div_errors_older_add").html("");
                $('#building').trigger('change');
            }
        });
    });
    
</script>
<style>
    .middle_col{
        width: 50%;
        float: left;
        margin-bottom: 1%;
    }
    

    .middle_col label{
        width: 100px;        
        float: left;
    }
    
    .field{
        width: 100%;        
    }

    .field label{
        font: 1.1em 'trebuchet ms',arial,sans-serif;
        float: left;
        width: 30%;
    }

    .field select{
        font: 1.1em 'trebuchet ms',arial,sans-serif;
        width: 67%;
    }

    .field input{
        font: 1.1em 'trebuchet ms',arial,sans-serif;
        width: 65%;
    }
    
</style>

<h3> Agregar un ingreso adicional</h3>
<div id="div_errors">
</div>
<div class="div_border">
    <form id="frm_aditional_income" action="<?= current_url(); ?>" method="post">
        <input type="hidden" value="<?= $building->id ?>" name="building_id" id="building_id" >
        <div class="middle_col">
            <!-- Autocomplete -->
            <div class="field">
                <label for="income_tags">Ingreso: </label>
                <input id="income_tags" name="income_tags" value="" class="ui-autocomplete-input" type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            </div>

            <!-- Date from -->
            <div class="field">
                <label for="date">Fecha: </label>
                <input id="date" name="date" type="text" >
            </div>
        </div>
        <div class="middle_col">
            <!-- Type Income -->
            <div class="field">
                <label for="value">Valor: </label>
                <input type="text" id="value" name="value"/>
            </div>
            
            <!-- Date to -->
            <div class="field">
                <label for="priority">Prioridad: </label>
                <input id="priority" name="priority" type="text" >
            </div>
        </div>
        <button class="submit" id="add_aditional_button" onclick="return false;;" >Agregar Ingreso</button>
    </form>
</div>

<div class="aditional_list div_border">
<? if($aditional_income): ?>
<table class="table_properties" cellspacing="0" cellpadding="0">
    <thead>
        <tr class="table_header">
            <th>Descripcion</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Prioridad</th>
            <th>Edicion</th>
        </tr>
    </thead>
    <tbody>
    <? foreach ($aditional_income as $income): ?>
        <tr class="table_row">
            <td class="aditional_item_descripcion"><?= $income->income_tag->name ?></td>
            <td><?= $income->value ?></td>
            <td><?= $income->date ?></td>
            <td><?= $income->priority ?></td>
            <td>
                <div id="div_income_editor">
                    <img src="<?= 'assets/img/home/delete.png' ?>" onclick="delete_income_aditional(<?= $income->id ?>)">
                    <img src="<?= 'assets/img/home/edit.gif' ?>" onclick="edit_income_aditional(<?= $income->id ?>)">
                    <img src="<?= 'assets/img/home/plus.png'?>" onclick="increment_priority(<?= $income->id ?>)">
                    <img src="<?= 'assets/img/home/minus.png' ?>" onclick="decrement_priority(<?= $income->id ?>)">
                </div>
            </td>
        </tr>
    <? endforeach; ?>
    </tbody>
</table>    
<? endif; ?>

<? if($older_aditional_income): ?>
    <h3>Expensas del mes anterior</h3>
    <div id="div_errors_older_add">
    </div>
    <div id="header_older_income">
        <div class="div_income_older_tags">Descripcion</div>
        <div class="div_value_older">Monto</div>
        <div class="div_date_older">Fecha</div>
    </div>
    <? foreach($older_aditional_income as $income): ?>

        <form id="frm_add_older_aditional_income<?= $income->id ?>" method="post"> 

            <input type="hidden" id="income_older_priority" name="income_older_priority" value="<?= $income->priority ?>" >
            <input type="hidden" id="income_older_building_id" name="income_older_building_id" value="<?= $building->id ?>" >
            <input id="income_older_tags" class="income_older_tags" name="income_older_tags" value="<?= $income->income_tag->name ?>" class="ui-autocomplete-input" type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input id="value_older" class="value_older" name="value_older" type="value" value="<?= $income->value ?>">
            <input id="date_older" class="date_older" name="date_older" type="text" value="<?= $income->date ?>">
            <button id="button_add_aditional_older_income" class="button_add_aditional_older_income" type="button" value="<?= $income->id ?>" >+</button>

        </form>

    <? endforeach; ?>
<? endif; ?>    

</div>