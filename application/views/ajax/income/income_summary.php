<? if ($extra_period): ?>    
    <div class="div_border">
        <h4>Extraordinarias en curso</h4>
        <table cellpadding="0" cellspacing="0" class="table_properties">
        <thead>
            <tr class="table_header">
                <th>Descripcion</th>
                <th>Cuotas</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Forma Pago</th>
                <th>Interés</th>
                <th>Valor Total</th>            
                <th>Estado</th>            
                <th>Edicion</th>            
            </tr>
        </thead>
        <tbody>
            <? foreach ($extra_period as $period): ?>
            <tr class="table_row">
                <td><?= $period->name ?></td>
                <td><?= $period->fees ?></td>
                <td><?= $period->date_from->format("Y-m-d") ?></td>
                <td><?= $period->date_to->format("Y-m-d") ?></td>
                <td><? if ($period->pay_form == DIVIDE_COEFICCIENT): echo DIVIDE_COEFICCIENT_LABEL; else: echo DIVIDE_EQUALS_LABEL; endif; ?></td>
                <td><?= $period->tax_percentage ?>%</td>
                <td><?= $period->value ?></td>
                <td><?= $period->period_state_name() ?></td>
                <td>
                    <i class="fa fa-trash" onclick="delete_extraordinary(<?= $period->id ?>)"></i>
                </td>
                <td>
                    <i class="fa fa-window-close fa-lg" onclick="close_extraordinary(<?= $period->id ?>)"></i>
                </td>                        
            </tr>
            <? endforeach; ?>
        </tbody>
        </table>
    </div>
<? endif; ?>

<? if ($unpaid_properties):?>    
    <div class="div_border">
        <h4>Propiedades a cobrar</h4>
        <table cellpadding="0" cellspacing="0" class="table_properties">
        <thead>
            <tr class="table_header">
                <th>UF</th>
                <th>Piso</th>
                <th>Depto</th>           
                <th>Propietario</th>
                <th>Balance</th>                            
                <th>Total al 15</th>            
            </tr>
        </thead>
        <tbody>
            <? foreach ($unpaid_properties as $property): ?>
            <tr class="table_row">
                <td><?= $property->functional_unity?></td>
                <td><?= $property->floor?></td>
                <td><?= $property->appartment?></td>            
                <td><? if($property->owner->name != ""): echo $property->owner->lastname . ', ' .  $property->owner->name; else: echo $property->owner->lastname; endif;?></td>
                <td><?= $property->balance + $property->balance_reserve ?></td>
                <td><?= number_format(round($property->total_to_pay(), 0 ,PHP_ROUND_HALF_DOWN),2) ?></td>
                
            </tr>
            <? endforeach; ?>
        </tbody>
        </table>        
    </div>
<? endif; ?>