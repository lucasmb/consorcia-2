<script type="text/javascript">
    
</script>
<style type="text/css">
    .half_container{
        width: 50%;
        height: 400px;
        
        float: left;
    }
</style> 
<div id="site_content">

<h1>Editar Tags</h1>
<div>
    <div class="div_border">
        <center>
            <label for="building_tag" >Edificio : </label>
            <select name="building_tag" id="building_tag">
                <?= get_select_building(set_value('building'));?>
            </select>
        </center>
    </div>
    <div>
        <div class="half_container" id="building_tags">
        </div>
        <div class="half_container" id="balance_tags">
        </div>
    </div>
    
</div>