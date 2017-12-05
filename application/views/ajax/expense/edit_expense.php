<script>
$(document).ready(function() {

    
    $('.expense_tags').autocomplete({
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
        $.post('index.php/ajax/expenses/save_edit_expense' , $("#frm_edit_expense").serialize(), function(rta){
            if(rta.indexOf("success") == -1)
                $("#div_errors_edit").html("").append(rta);
            else{
                alert("Cambios realizados con exito.");
                parent.$.fancybox.close();
                $('#building').trigger('change');
            }
        });
        
    });
});
</script>

<div class="contenedor">
    <h2>Editar Egreso</h2>
    <form id="frm_edit_expense" method="post" >
        <input name="expense_id" id="expense_id" type="hidden" value="<?= $expense->id; ?>">
        <input name="building" id="building" type="hidden" value="<?= $expense->building_id; ?>">
        
        <div class="div_border">
            <div id="div_errors_edit">
            </div>
            <div class="middle_col">
                <!-- Autocomplete -->
                <div class="field">
                    <label for="expense_tags">Descripción: </label>
                    <input id="expense_tags" class="expense_tags" name="expense_tags" value="<?= $expense->expense_tag->name ?>" class="ui-autocomplete-input" type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
                </div>

                <!-- Date from -->
                <div class="field" >
                    <label for="date">Fecha : </label>
                    <input id="date" value="<?= $expense->date ?>"name="date" type="text" >
                </div>

                <!-- Priority -->
                <div class="field" >
                    <label for="priority">Prioridad : </label>
                    <input id="priority" name="priority" value="<?= $expense->priority ?>" type="text" >
                </div>
            </div>
            <div class="middle_col" >
                <!-- Type Income -->
                <div class="field">
                    <label for="value">Valor: </label>
                    <input type="text" id="value" value="<?= $expense->value ?>" name="value"/>
                </div>

                <!-- Type Expense -->
                <div class="field" >
                    <label for="type_expense">Tipo: </label>
                    <select name="type_expense" id="type_expense" >
                        <?= get_type_expense($expense->type_expense_id); ?>
                    </select>
                </div>
            </div>
            <!--<button id="add_expense" onclick="return false;;" >Modificar Egreso</button>-->

        </div>
        <button name="cancel" class="cancel" id="close" value="true" type="button">Cancelar</button> 
        <button name="submit" class="submit" id="submit" value="true" type="button">Modificar Ingreso</button> 
    </form>
</div>