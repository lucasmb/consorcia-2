
<script>
$(document).ready(function() {

    $( ".buttonSubmitOlderEstimativeExpense" ).click(function(){
        
        var form = "#frm_add_older_estimative_expense" + $(this).val();
        $.post('index.php/ajax/expenses/add_older_estimative_expense', $(form).serialize(), function(rta){
            if (rta.indexOf("success") == -1){
                $("#div_errors_older_add").html("").append(rta);
            }
            else{
                $("#div_errors_older_add").html("");
                $('#building_estimative').trigger('change');
            }
        });
    });
});    
</script>
<? if($estimatives_expenses): ?>
    <table class="table_properties" cellspacing="0" cellpadding="0">
        <thead>
            <tr class="table_header" >
                <th>Descripcion</th>
                <th>Monto</th>
                <th>Tipo</th>
                <th>Fecha</th>
                <th>Prioridad</th>
                <th>Edicion</th>
            </tr>
        </thead>
        <tbody>
        <? foreach($estimatives_expenses as $expense): ?>
            <tr class="table_row">
                <td class="aditional_item_descripcion"><?= $expense->expense_tag->name ?></td>
                <td><?= $expense->value ?></td>
                <td><?= $expense->type_expense->name ?></td>
                <td><?= $expense->date ?></td>
                <td><?= $expense->priority ?></td>
                <td>
                    <div id="div_expense_editor">
                        <img src="<?= url_img('/home/delete.png') ?>" onclick="delete_estimative_expense(<?= $expense->id ?>)">
                        <img src="<?= url_img('/home/edit.gif') ?>" onclick="edit_estimative_expense(<?= $expense->id ?>)">
                        <img src="<?= url_img('/home/plus.png') ?>" onclick="increment_estimative_priority(<?= $expense->id ?>)">
                        <img src="<?= url_img('/home/minus.png') ?>" onclick="decrement_estimative_priority(<?= $expense->id ?>)">
                    </div>
                </td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>    
<? endif; ?>
<? if($current_expenses != null): ?>

    <h3>Expensas del mes anterior</h3>
    <div id="div_errors_older_add">
    </div>
    <div id="header_older_expense">
        <div class="div_estimative_expense_older_tags">Descripcion</div>
        <div class="div_estimative_value_older">Monto</div>
        <div class="div_estimative_date_older">Fecha</div>
    </div>
        
        <? foreach($current_expenses as $expense): ?>
            
                <form id="frm_add_older_estimative_expense<?= $expense->id ?>" method="post"> 
                
                    <input type="hidden" id="estimative_expense_older_priority" name="estimative_expense_older_priority" value="<?= $expense->priority ?>" >
                    <input type="hidden" id="estimative_expense_older_expense_tag_id" name="estimative_expense_older_expense_tag_id" value="<?= $expense->expense_tag_id ?>" >
                    <input type="hidden" id="estimative_type_older_expense" name="estimative_type_older_expense" value="<?= $expense->type_expense_id ?>" >
                    
                    <input type="hidden" id="estimative_expense_older_building_id" name="estimative_expense_older_building_id" value="<?= $building->id ?>" >
                    <input id="estimative_expense_older_tags" class="estimative_expense_older_tags" name="estimative_expense_older_tags" value="<?= $expense->expense_tag->name ?>" class="ui-autocomplete-input" type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
                    <input id="estimative_value_older" class="estimative_value_older" name="estimative_value_older" type="value" value="<?= $expense->value ?>">

                    <input id="estimative_date_older" class="estimative_date_older" name="estimative_date_older" type="text" value="<?= $expense->date ?>">
                    <button class="buttonSubmitOlderEstimativeExpense" type="button" value="<?= $expense->id ?>" >+</button>
                
                </form>
            
        <? endforeach; ?>
<? endif; ?>