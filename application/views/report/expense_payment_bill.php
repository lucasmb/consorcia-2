<!DOCTYPE HTML>
<html>
    <head>
    <title>Factura Expensas</title>
        <link rel="stylesheet" type="text/css" href="<?= url_css('/reports/expense_payment_bill.css') ?>" />
    </head>

<body>
    <h1>
        Propiedades para realizar el pago de Expensas Ordinarias Consorcio Calle: <?= $building->street ?> Número: <?= $building->number ?>
    </h1>
    <h2>
        Periodo: <?= $building->actual_period->format("Y-m") ?>(<?= $building->type_expense_period->type_name?>)
    </h2>
    
    <table cellpadding="0" cellspacing="0" class="table_properties">
    <thead>
        <tr class="table_header">
            <th>UF</th>
            <th>Piso</th>
            <th>Depto</th>           
            <th>Propietario</th>
            <th>Saldo</th>            
            <th>Total al 15</th>            
            <th>Tipo Pago</th>            
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
            <td class="type_pay">

                <input name="property_id" id="property_id" type="hidden" value="<?= $property->id; ?>">

                <input id="account_pay_<?= $property->id ?>" type="checkbox" name="account_pay[]" value="<?= $property->id ?>" />
                <label for="account_pay_<?= $property->id ?>">Pago a cuenta: </label><input class="input_pay" name="input_pay_<?= $property->id ?>" id="input_pay_<?= $property->id ?>" type="text" /><br>

                <input id="second_pay_<?= $property->id ?>" type="checkbox" checked="checked" name="total_pay[]" value="<?= $property->id ?>" />
                <label for="second_pay_<?= $property->id ?>">Pago total al 15</label>

            </td>
        </tr>
        <? endforeach; ?>
    </tbody>
    </table>
</body>
</html>