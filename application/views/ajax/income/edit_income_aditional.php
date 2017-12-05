<script>
$(document).ready(function() {

    
    $('.expense_tags').autocomplete({
     source: function(request, response) {
            $.ajaxSetup({async:true});    
            $.ajax({
                url: "index.php/ajax/expenses/get_expense_tags",
                dataType: "json",
                delay: 100,
                data: {
                    term : request.term,
                    id : $("#building").val()
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        min_length: 3,
        delay: 300
    });
    
    
    $('#close').click(function(){
        parent.$.fancybox.close();
    });
       
    $('#submit').click(function(){
        
        $.post('index.php/ajax/incomes/save_edit_income_aditional' , $("#frm_edit_aditional_income").serialize(), function(rta){
            if(rta.indexOf("success") == -1)
                $("#div_errors_edit").html("").append(rta);
            else{
                alert("Cambios realizados con exito.");
                parent.$.fancybox.close();
                $('#building').trigger('change');
            }
        });
        
    });
});
</script>

<div class="contenedor">
    <h2>Editar Ingreso Adicional</h2>
    <form id="frm_edit_aditional_income" method="post">
        <input name="building" id="building" type="hidden" value="<?= $income->building_id; ?>">
        <input name="income_id" id="income_id" type="hidden" value="<?= $income->id; ?>">
        <div class="div_border_aditional">
            <div id="div_errors_edit">
            </div>
            <div class="middle_col">
                <!-- Autocomplete -->
                <div class="field">
                    <label for="income_tags">Ingreso: </label>
                    <input id="income_tags" name="income_tags" value="<?= $income->income_tag->name ?>" class="ui-autocomplete-input" type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
                </div>

                <!-- Date from -->
                <div class="field">
                    <label for="date">Fecha: </label>
                    <input id="date" name="date" type="text" value="<?= $income->date ?>">
                </div>
            </div>
            <div class="middle_col">
                <!-- Type Income -->
                <div class="field">
                    <label for="value">Valor: </label>
                    <input type="text" id="value" name="value" value="<?= $income->value ?>"/>
                </div>

                <!-- Date to -->
                <div class="field">
                    <label for="priority">Prioridad: </label>
                    <input id="priority" name="priority" type="text" value="<?= $income->priority?>">
                </div>
            </div>
        </div>
        <button name="cancel" class="cancel" id="close" value="true" type="button">Cancelar</button> 
        <button name="submit" class="submit" id="submit" value="true" type="button">Modificar Ingreso</button> 
    </form>
</div>