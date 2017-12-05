<form id="frm_add_special_expense" method="post" >
    <div class="div_border">    
        <div class="middle_col_building">
            <div class="field">
                <label for="building_special" >Edificio : </label>
                <select name="building_special" id="building_special">
                    <?= get_select_building();?>
                </select>
            </div>       
        </div>       
    </div>


    <div class="div_border">
        <div id="div_errors">
        </div>
        <div class="middle_col">
            <!-- Autocomplete -->
            <div class="field">
                <label for="expense_tags_special">Descripción: </label>
                <input id="expense_tags_special" name="expense_tags_special" value="" class="ui-autocomplete-input" type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
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
                <label for="value">Valor Total: </label>
                <input type="text" id="value" name="value"/>
            </div>

            <!-- Type Expense -->
            <div class="field" >
                <label for="type_expense">Tipo: </label>
                <select name="type_expense" id="type_expense" >
                    <?= get_type_expense(); ?>
                </select>
            </div>

            <!-- Type Special Expense -->
            <div class="field" >
                <label for="type_special_expense">Tipo de Pago: </label>
                <select name="type_special_expense" id="type_special_expense" >
                    <?= get_type_special_expense(); ?>
                </select>
            </div>

        </div>
        <button id="add_special_expense" onclick="return false;;" >Agregar Egreso</button>

    </div>
</form>
<div class="div_border" id="special_expenses_info" style="display: none">    
</div>