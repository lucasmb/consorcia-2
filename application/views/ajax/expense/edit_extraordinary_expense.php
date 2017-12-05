<script>
$(document).ready(function() {

    
    $('.expense_tags_extraordinary').autocomplete({
     source: function(request, response) {
            $.ajaxSetup({async:true});    
            $.ajax({
                url: "index.php/ajax/expenses/get_expense_tags",
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
    
    
    $('#close').click(function(){
        parent.$.fancybox.close();
    });
       
    $('#submit').click(function(){
        $.post('index.php/ajax/expenses/save_edit_extraordinary_expense' , $("#frm_edit_extraordinary_expense").serialize(), function(rta){
            if(rta.indexOf("success") == -1)
                $("#div_errors_edit_extra").html("").append(rta);
            else{
                alert("Cambios realizados con exito.");
                parent.$.fancybox.close();
                $('#building_extra').trigger('change');
            }
        });
        
    });
});
</script>
<style>
    .contenedor{
        width: 600px;
    }
</style>

<div class="contenedor">
    <h2>Editar Egreso Extraordinario</h2>
    <form id="frm_edit_extraordinary_expense" method="post" >
        <input name="expense_id" id="expense_id" type="hidden" value="<?= $expense->id; ?>">
        <input name="building_extra" id="building_extra" type="hidden" value="<?= $expense->building_id; ?>">
        <div class="div_border">
            <div>
                <div class="field">
                    <label for="extraordinary_period" >Extraordinaria : </label>
                    <select name="extraordinary_period" id="extraordinary_period">
                        <?= get_select_extraordinary_periods($expense->building_id, $expense->extraordinary_period_id); ?>
                    </select>
                </div>       
            </div>       
        </div>
        
        <div class="div_border">
            <div id="div_errors_edit_extra">
            </div>
            
            <div class="middle_col">
                <!-- Autocomplete -->
                <div class="field">
                    <label for="expense_tags_extraordinary">Descripción: </label>
                    <input id="expense_tags_extraordinary" class="expense_tags_extraordinary" name="expense_tags_extraordinary" value="<?= $expense->expense_tag->name ?>" class="ui-autocomplete-input" type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
                </div>

                <!-- Date from -->
                <div class="field" >
                    <label for="date_extraordinary">Fecha : </label>
                    <input id="date_extraordinary" value="<?= $expense->date ?>"name="date_extraordinary" type="text" >
                </div>
            </div>
            
            <div class="middle_col" >
                <!-- Priority -->
                <div class="field" >
                    <label for="priority_extraordinary">Prioridad : </label>
                    <input id="priority_extraordinary" name="priority_extraordinary" value="<?= $expense->priority ?>" type="text" >
                </div>
                
                <!-- Type Income -->
                <div class="field">
                    <label for="value_extraordinary">Valor: </label>
                    <input type="text" id="value_extraordinary" value="<?= $expense->value ?>" name="value_extraordinary"/>
                </div>
            </div>
            <!--<button id="add_expense" onclick="return false;;" >Modificar Egreso</button>-->

        </div>
        <button name="cancel" class="cancel" id="close" value="true" type="button">Cancelar</button> 
        <button name="submit" class="submit" id="submit" value="true" type="button">Modificar Egreso</button> 
    </form>
</div>