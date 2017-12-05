<div id="site_content">

    <h1>Ajustes de Base</h1>
    <div>
        <form id="form_building_selection" action="<?= site_url().'reports/report_extraordinary_expenses_bill' ?>" method="post">
            <div>
                <label for="building_estimate" >Edificio : </label>
                <select name="building_estimate" id="building_estimate">
                    <?= get_select_building(set_value('building'));?>
                </select>
                <input id="new_base_estimate" type="text" value="" />
                <button id="btn_modify_base" onclick="return false;;">Modificar base</button>  
            </div>
            <div>
                <button id="btn_calculate_base" onclick="return false;;">Calcular nueva base</button>  
            </div>
        </form>
        <div id="adjustment_estimate">
        </div>
    </div>
</div>