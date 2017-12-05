<script type="text/javascript">
$(document).ready(function() {

    $(".frm_bank_expense").submit(function(){
        
        form = $(this);
        
        $.ajaxSetup({async:false});
        $.post('index.php/ajax/expenses/add_expense', form.serialize() , function(rta){
            
            if (rta.indexOf("success") == -1){
                form.html("").append(rta);
            }
            else{
                form.html("");
            }

        });
        return false;
    });

});
</script>


<?
    if ($expenses) {
        foreach ($expenses as $expense) {
?>
            
            <form id="frm_bank_expense" class="frm_bank_expense">
                
                <h1><?= $expense->building->name ?></h1>
                
                <input name="building" id="building" type="hidden" value="<?= $expense->building->id; ?>">
                <input name="period_date" id="period_date" type="hidden" value="<?= $expense->period_date; ?>" />
                <input name="priority" id="priority" type="hidden" value="0" />
                <input name="ammount" id="ammount" type="hidden" value="<?= $expense->ammount; ?>" />
                

                <table cellpadding="0" cellspacing="0" class="table_properties">
                    <thead>
                        <tr class="table_header">
                            <th>Descripción</th>
                            <th>Monto</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Edición</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr <? if ($expense->similar_expense): ?> class="table_row_duplicated" <? endif; ?> >
                            <td>
                                <input id="expense_tags" class="expense_tags" name="expense_tags" value="<?= $expense->description; ?>" type="text" >
                            </td>

                            <td>
                                <input id="value" class="value" name="value" type="hidden" readonly value="<?= $expense->value ?>" ><?= $expense->value ?>
                            </td>
                               
                            <td>
                                <select name="type_expense" class="type_expense" id="type_expense" >
                                    <?= get_type_expense($expense->expense_type); ?>
                                </select> 
                            </td>

                            <td>
                                <input id="date" class="date" name="date" type="text" value="<?= $expense->date ?>">
                            </td>

                            <td>
                                <button class="buttonSubmitOlderExpense">+</button>
                            </td>

                        </tr>
                        <? if ($expense->similar_expense): ?>
                        <tr class="table_row_duplicated">
                            <td colspan="5"> Este egreso puede que ya haya sido cargado, si desea puede agregarlo, tenga en cuenta que puede duplicar un egreso. A continuación se muestra el egreso que puede coincidir</td>
                        </tr>
                        <tr class="table_row_duplicated">
                            <td><?= $expense->similar_expense->expense_tag->name; ?></td>
                            <td><?= $expense->similar_expense->value; ?></td>
                            <td><?= $expense->similar_expense->type_expense->name; ?></td>
                            <td colspan="2"><?= $expense->similar_expense->date; ?></td>

                        </tr>
                        <? endif; ?>
                    </tbody>
                </table>
            </form>

<?
        }
    }
?>