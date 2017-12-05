<script type="text/javascript">
</script>
<script type="text/javascript" src="<?= url_js('/editTags/jquery.multi-select.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?= url_css("/editTags/multi-select.css") ?>" />

<style type="text/css">
    #building_tags{
        width: 100%;
        margin-top: 2%;
    }
    
    #multiple_tags{
        width: 100%;
        height: 300px;
    }
    
    button{
        height: 28px;
    }
    
    #div_tag_editor{
        display: none;
    }
    
    .ms-container{        
        width: 100%;
    }
    
    #button_edit_super_tag{
        display:none;
    }
    
    #tag_name{
        height: 28px !important;
        width: 308px;
    }
    
    .div_parameters{
        width: 60%;
        margin-left: 20%;
    }
    
    .half_container{
        width: 50%;
        height: 400px;
        float: left;
    }
    
    .div_border input{
        padding: 0px !important;
        margin: 0px !important;
        width: auto;
    }
    .div_border label{
        width: auto;
    }
</style> 
<div id="site_content">

<h1>Editar Tags</h1>
<div>
    <div class="div_border">
        <div class="div_parameters">
            
            <div id="formEditTagParameters">
                <label for="expense_radio" >Expensa </label>
                <input type="radio" checked="checked" name="type_tag" id="expense_radio" value="expense">
                <label for="income_radio" >Ingreso</label>
                <input type="radio" name="type_tag" id="income_radio" value="income">    
                <br><br>
                <label for="building_tag" >Edificio : </label>
                <select name="building_tag" id="building_tag">
                    <?= get_select_building(set_value('building'));?>
                </select>
                <br><br>
                <div id="div_tag_selector">
                    <label for="super_tag" >Tag: </label>
                    <select name="super_tag" id="super_tag">
                        <option>-- seleccionar --</option>

                        <?//= get_select_building_tag(set_value('building'));?>
                    </select>
                    <button id="button_add_super_tag" onclick="return false;">+</button>
                    <button id="button_edit_super_tag" onclick="return false;">edit</button>
                </div>
                
                <div id="div_tag_editor">
                    <label for="tag_name" >Tag: </label>
                    <input id="tag_name" type="text" />
                    <button id="button_save_super_tag" onclick="return false;">save</button>
                    <button id="button_cancel_save_super_tag" onclick="return false;">cancel</button>
                </div>
                
            </div>
        </div>

    </div>
    
    <div id="building_tags">
        
        <select multiple="multiple" id="multiple_tags" name="multiple_tags[]">
        </select>
        <button id="button_save_tags" onclick="return false;">Guardar cambios</button>
        
        
    </div>
    
</div>