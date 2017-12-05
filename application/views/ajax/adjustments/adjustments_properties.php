<link rel="stylesheet" type="text/css" href="<?= url_css('/reportsHTML/monthly_capitulation_building.css') ?>" />
<div class="content">
<? $colspan = 8; ?>
    <table cellpadding="0" cellspacing="0" class="table_properties">
        <thead>
            <tr class="table_header">
                <th>Propietario</th>
                <th>UF</th>
                <th>Piso</th>
                <th>Dto</th>
                <th>COEF</th>
                <th>Exp comun Actual</th>
        <? 
            if($building->has_reserve_fund): 
        ?>
                <th>Fond Res Actual</th>
        <? endif; ?>
        <? 
            if($building->has_special_expense_last_month()): 
        ?>
                <th>Gastos Esp.</th>
        <? endif; ?>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
<?
foreach($properties as $property):
?>       
                
            <tr class="table_row" >
            <? $total = $property->actual_common_expense(); ?>
                <td id="owner" ><?= $property->owner->lastname." ".substr($property->owner->name,0,1)?></td>
                <td><?= $property->functional_unity?></td>
                <td><?= $property->floor ?></td>
                <td><?= $property->appartment ?></td>
                <td><?= $property->coefficient ?></td>
                <td><?= number_format($property->actual_common_expense(),2) ?></td>
            <? if($building->has_reserve_fund):
                    $total = $total + $property->actual_reserve_fund();
            ?>
                <td><?= number_format(abs($property->actual_reserve_fund()),2) ?></td>
            <? endif; ?>
            <? if($building->has_special_expense_last_month()): 
                    $total = $total + $property->building->get_special_expense_for_property_last_month($property);
            ?>
                <td><?= number_format(abs($property->building->get_special_expense_for_property_last_month($property)),2) ?></td>
            <? endif; ?>
                <!--<td><?//= number_format(round($property->total_to_pay(), 0 ,PHP_ROUND_HALF_DOWN),2) ?></td>-->
                <td><?= number_format(round($total, 0 ,PHP_ROUND_HALF_DOWN),2) ?></td>
            </tr>
<?
endforeach;
?>
                
        </tbody>
    </table>
</div>