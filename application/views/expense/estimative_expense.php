<form id="frm_add_estimative_expense" method="post" >
    <div class="div_border">    
        <div class="middle_col_building">
            <div class="field">
                <label for="building_estimative" >Edificio : </label>
                <select name="building_estimative" id="building_estimative">
                    <?= get_select_building();?>
                </select>
            </div>       
        </div>       
    </div>


    <div class="div_border">
        <div id="div_estimative_errors">
        </div>
        <div class="middle_col">
            <!-- Autocomplete -->
            <div class="field">
                <label for="expense_tags_estimative">Descripción: </label>
                <input id="expense_tags_estimative" name="expense_tags_estimative" value="" class="ui-autocomplete-input" type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            </div>

            <!-- Date from -->
            <div class="field" >
                <label for="date">Fecha : </label>
                <input id="date" name="date" type="text" >
            </div>

            <!-- Priority -->
            <div class="field" >
                <label for="priority">Prioridad : </label>
                <input id="priority" name="priority" type="text" >
            </div>
        </div>
        <div class="middle_col" >
            <!-- Type Income -->
            <div class="field">
                <label for="value">Valor: </label>
                <input type="text" id="value" name="value"/>
            </div>

            <!-- Type Expense -->
            <div class="field" >
                <label for="type_expense">Tipo: </label>
                <select name="type_expense" id="type_expense" >
                    <?= get_type_expense(); ?>
                </select>
            </div>
        </div>
        <button id="add_estimative_expense" onclick="return false;;" >Agregar Egreso</button>

    </div>
</form>
<div class="div_border" id="estimative_expenses_info" style="display: none">    
</div>