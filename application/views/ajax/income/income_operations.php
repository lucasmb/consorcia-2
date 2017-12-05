<script>

$(document).ready(function() {
    
    $('#close_period_button').click(function(){
        var answer = confirm("¿Está seguro que desea Cerrar el periodo actual?");
        if (answer){
            $(this).showLoadingView();
            building_id = $(this).val();
            
            if (building_id != ""){
                $.post('index.php/ajax/buildings/close_period', {building_id : building_id}, function(rta){

                    $(this).closeLoadingView();
                    $('#building').trigger('change');
                    $.fancybox({
                        'content' : rta
                    });
                });
            }
        }
    });
    
    $('#reopen_period_button').click(function(){
        var answer = confirm("¿Está seguro que desea Reabrir el periodo anterior?");
        if (answer){
            $(this).showLoadingView();
            building_id = $(this).val();
            
            if (building_id != ""){
                $.post('index.php/ajax/buildings/reopen_period', {building_id : building_id}, function(rta){
                    
                    $(this).closeLoadingView();
                    $('#building').trigger('change');
                    $.fancybox({
                            'content' : rta
                    });
                    
                });
            }
        }
    });
});

</script>

<div class="col income_center_title">
	Periodo Actual: <?= $building->actual_period->format("Y-m") ?> (<?= $building->type_expense_period->type_name?>)
</div>

<div class="col">
	<div class="col-2">
		<button class="submit" id="close_period_button" value="<?= $building->id ?>">Cerrar Periodo</button>		
	</div>
	<div class="col-2">
		<button class="submit" id="reopen_period_button" value="<?= $building->id ?>">Abrir Periodo Anterior</button>		
	</div>
</div>