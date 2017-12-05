<form id="frm_add_expense_extraordinary" method="post" >
    <div class="div_border">    
        <div class="middle_col_building">
            <div class="field">
                <label for="building_extra" >Edificio : </label>
                <select name="building_extra" id="building_extra">
                    <?= get_select_building();?>
                </select>
            </div>       
        </div>       
        <div class="middle_col_building">
            <div class="field">
                <label for="extraordinary_period" >Extraordinaria : </label>
                <select name="extraordinary_period" id="extraordinary_period">
                </select>
            </div>       
        </div>       
    </div>


    <div class="div_border">
        <div id="div_errors_extra">
        </div>
        <div class="middle_col">
            <!-- Autocomplete -->
            <div class="field">
                <label for="expense_tags_extraordinary">Descripción: </label>
                <input id="expense_tags_extraordinary" name="expense_tags_extraordinary" value="" class="ui-autocomplete-input" type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            </div>

            <!-- Date from -->
            <div class="field" >
                <label for="date_extraordinary">Fecha : </label>
                <input id="date_extraordinary" name="date_extraordinary" type="text" >
            </div>
            
        </div>
        <div class="middle_col" >
            <!-- Type Income -->
            <div class="field">
                <label for="value_extraordinary">Valor: </label>
                <input type="text" id="value_extraordinary" name="value_extraordinary"/>
            </div>

            <!-- Priority -->
            <div class="field" >
                <label for="priority_extraordinary">Prioridad : </label>
                <input id="priority_extraordinary" name="priority_extraordinary" type="text" >
            </div>
        </div>
        <button id="add_extraordinary_expense" onclick="return false;;" >Agregar Egreso Extraordinario</button>

    </div>
</form>
<div class="div_border" id="extraordinary_expenses_info" style="display: none">    
</div>