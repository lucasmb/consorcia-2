
<script>
$(document).ready(function() {

    $( ".buttonSubmitOlderExpense" ).click(function(){
        
        var form = "#frm_add_older_expense" + $(this).val();
        $.post('index.php/ajax/expenses/add_older_expense', $(form).serialize(), function(rta){
            if (rta.indexOf("success") == -1){
                $("#div_errors_older_add").html("").append(rta);
            }
            else{
                $("#div_errors_older_add").html("");
                $('#building').trigger('change');
            }
        });
    });
});    
</script>
<? if($expenses): ?>
    <table class="table_properties" cellspacing="0" cellpadding="0">
        <thead>
            <tr class="table_header" >
                <th>Descripcion</th>
                <th>Monto</th>
                <th>Tipo</th>
                <th>Fecha</th>
                <th>Prioridad</th>
                <th class="th_edition">Edicion</th>
            </tr>
        </thead>
        <tbody>
        <? foreach($expenses as $expense): ?>
            <tr class="table_row">
                <td class="aditional_item_descripcion"><?= $expense->expense_tag->name ?></td>
                <td><?= $expense->value ?></td>
                <td><?= $expense->type_expense->name ?></td>
                <td><?= $expense->date ?></td>
                <td><?= $expense->priority ?></td>
                <td>
                    <i class="fa fa-plus-square fa-lg" onclick="increment_priority(<?= $expense->id ?>)"></i>
                    <i class="fa fa-minus-square fa-lg" onclick="decrement_priority(<?= $expense->id ?>)"></i>
                    <i class="fa fa-pencil-square fa-lg font_edit" onclick="edit_expense(<?= $expense->id ?>)"></i>
                    <i class="fa fa-window-close fa-lg" onclick="delete_expense(<?= $expense->id ?>)"></i>
                </td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>    
<? endif; ?>
<? if($last_month_expenses != null): ?>

    <h3>Expensas del mes anterior</h3>
    <div id="div_errors_older_add">
    </div>
    <div id="header_older_expense">
        <div class="div_expense_older_tags">Descripcion</div>
        <div class="div_value_older">Monto</div>
        <div class="div_type_older_expense">Tipo</div>
        <div class="div_date_older">Fecha</div>
    </div>
        
        <? foreach($last_month_expenses as $expense): ?>
            
                <form id="frm_add_older_expense<?= $expense->id ?>" method="post"> 
                
                    <input type="hidden" id="expense_older_priority" name="expense_older_priority" value="<?= $expense->priority ?>" >
                    <input type="hidden" id="expense_older_building_id" name="expense_older_building_id" value="<?= $building->id ?>" >
                    <input id="expense_older_tags" class="expense_older_tags" name="expense_older_tags" value="<?= $expense->expense_tag->name ?>" class="ui-autocomplete-input" type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
                    <input id="value_older" class="value_older" name="value_older" type="value" value="<?= $expense->value ?>">
                    
                    <select name="type_older_expense" class="type_older_expense" id="type_expense_older" >
                        <?= get_type_expense($expense->type_expense->id); ?>
                    </select>

                    <input id="date_older" class="date_older" name="date_older" type="text" value="<?= $expense->date ?>">
                    <button class="buttonSubmitOlderExpense" type="button" value="<?= $expense->id ?>" >+</button>
                
                </form>
            
        <? endforeach; ?>
<? endif; ?>