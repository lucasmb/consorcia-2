<div id="site_content">
    
    <h1>Edificios</h1>
    <div class="div_border">    
        <div class="adv_col">
            <label for="building" >Edificio : </label>
            <select name="building" id="building">
                <?= get_select_building(set_value('building'));?>
            </select>
        </div>
        <div class="adv_col">
            <button id="button" type="button">Agregar Edificio</button>
            <button id="button_deactivate" type="button">Desactivar Edificio</button>            
        </div>
    </div>
    <div id="building_info">
    
    </div>
    <div id="building_properties">
    
    </div>
</div>
