<script>

function delete_pay_day(pay_day_id){

    if (pay_day_id != ""){
        $.post('index.php/ajax/reports/delete_pay_day', {id : pay_day_id}, function(rta){
            if (rta.indexOf("ok") != -1){
                $('#building_summary').trigger('change');
            }
        });
    }

}    

$(document).ready(function(){
    
    $("#date").datepicker();
    $("#date").datepicker('option', {dateFormat: 'yy-mm-dd'});
    
    $("#button").click(function(){
        
        $.post('index.php/ajax/reports/add_pay_day', $("#frm_add_pay_days").serialize() , function(rta){
            if (rta.indexOf("ok") != -1){
                $('#building_summary').trigger('change');
            }
        });
        
    });
    
});

</script>
<style>
    .div_field{
        float: left;
        width: 100%;
        padding-left: 20%;
    }
    
    .input_time{
        width: 11%;
    }
    
    .input_date{
        width: 25.5%;
    }
    
    .div_field input{
        float: left;
    }
    
    .div_field label{
        float: left;
        text-align: left;
        padding-top: 1%;
    }
    
    .first_label{
        float: left;
        width: 25%; /* change this to whatever you want */
        /* these make it look nicer */
        text-align: left;
    }
    
    #button{
        width: 50%;
    }
 
    .table_properties{
        cellpadding: 0;
        cellspacing: 0;
        border: 1px #000000;
        font: 1.1em 'trebuchet ms',arial,sans-serif;
        margin: 30% 2.5% 2.5% 2.5% ;
        width: 95%;
    }

    .table_properties {border: 1px solid #CCCCCC;}

    .table_properties th{    
        color: #FFF;    
        background: #eddec1 url(assets/img/home/header_table.jpg);
    }

    .table_properties tr td{
        border: 1px solid #F4F4EE;
    }
    
    tr:nth-of-type(odd) td{
        background-color:#F4F4EE
    } 

    tr:nth-of-type(even) td{
        background-color:#FFFFFF
    }

    img{
        cursor:pointer;
        margin-left: 5px;
    } 
    
</style>
<div>
    <div class="div_border">
        <form method="post" id="frm_add_pay_days">
            <input type="hidden" name="building_id" id="building_id" value="<?= $building->id ?>" />
            
            <div class="div_field">
                <label for="date" class="first_label">Fecha</label>
                <input type="text" class="input_date" name="date" id="date" />
            </div>
            
            <div class="div_field">
                <label for="hour_start" class="first_label">Hora Inicio</label>
                <input type="text" maxlength="2" class="input_time" name="hour_start" id="hour_start" />
                
                <label for="minuts_start">:</label>
                <input type="text" maxlength="2" class="input_time" name="minuts_start" id="minuts_start" />
            </div>
            
            <div class="div_field">
                <label for="hour_end" class="first_label">Hora Fin</label>
                <input type="text" maxlength="2" class="input_time" name="hour_end" id="hour_end" />
                
                <label for="minuts_end">:</label>
                <input type="text" maxlength="2" class="input_time" name="minuts_end" id="minuts_end" />
                
                
            </div>
            
            <div class="div_field">
                <button type="button" id="button">Agregar Fecha</button>
            </div>
        </form>
        <? if (isset($actual_pay_days) && $actual_pay_days != null): ?>
        <table cellpadding="0" cellspacing="0" class="table_properties">
            <thead>
                <tr class="table_header">
                    <th>Dia</th>            
                    <th>Hora Inicio</th>            
                    <th>Hora Fin</th>            
                    <th>Edicion</th>            
                </tr>
            </thead>
            
            <tbody>
                <? foreach ($actual_pay_days as $pay_day): ?>
                <tr class="table_row">
                    <td><?= $pay_day->date->format("Y-m-d") ?></td>
                    <td><?= $pay_day->hour_start ." : " .$pay_day->minuts_start ?></td>
                    <td><? if($pay_day->hour_end != "" ): echo $pay_day->hour_end ." : " .$pay_day->minuts_end; endif; ?></td>
                    <td><img src="<?= url_img('/home/delete.png') ?>" onclick="delete_pay_day(<?= $pay_day->id ?>);"></td>
                </tr>
                <? endforeach; ?>
            </tbody>
        </table>

            
        <? endif; ?>
    </div>
</div>
