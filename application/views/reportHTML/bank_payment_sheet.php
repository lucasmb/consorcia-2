<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title> Planilla de registro de pagos mensual </title>
        <link rel="stylesheet" type="text/css" href="<?= url_css('/reportsHTML/monthly_capitulation_building.css') ?>" />
        <link rel="stylesheet" type="text/css" href="<?= url_css('/reportsHTML/bank_payment_sheet.css') ?>" />
        <script type="text/javascript" src="<?= url_js('/home/jquery.js')?>"></script>
        <script type="text/javascript" src="<?= url_js('/reportsHTML/font_resizer.js')?>"></script>
        
    </head>
    <style type="text/css" media="print">
        @page { size: portrait; }
    </style>
<body>
    <div class="no-print">
        <button id="increaseFont"> + </button>
        <button id="decreaseFont"> - </button>
        <button id="resetFont"> Reset </button>
        <button id="print"> Print</button>
    </div>
    <div class="content">
    <? $iterations = 0; ?>
    <? foreach ($buildings as $building): ?>
        <? $iterations++; ?>
        <div class="page">
            <h2><?= $building->name ?> - Expensas Ordinarias <h2>
            <table class="table_properties" cellpadding="0"  cellspacing="0">
                <thead>
                    <tr class="table_header">
                        <th>UF</th>
                        <th>Piso</th>
                        <th>Depto</th>
                        <th>Propietario</th>
                        <th><div class="to_center">Identificador de Pago</div></th>
                        <th>Monto a Pagar</th>
                        <th>Pagó</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($building->properties as $property): ?>
                    <tr class="table_row">
                        <td><?= $property->functional_unity?></td>
                        <td><?= $property->floor?></td>
                        <td><?= $property->appartment?></td>            
                        <td><? if($property->owner->name != ""): echo $property->owner->lastname; else: echo $property->owner->lastname; endif;?></td>
                        <? $payment_code = $property->get_current_payment_code(); ?>
                        <td class="identifier_col">
                            <div class="to_center">
                                <?= substr($payment_code, 0, 5) ?><span class="highlight"><?= substr($payment_code, 5, 8) ?></span><?= substr($payment_code, 13, 48) ?>
                            </div>
                        </td>
                        <td><div class="to_right"><?= number_format($property->total_to_pay(),2) ?></div></td>
                        <td>
                            <? if (IncomeTransaction::exist_bank_payment_by_code($property->get_current_payment_code())): ?>
                                <span class="highlight">SI</span>
                            <? endif; ?>
                        </td>
                    </tr>
                    <? endforeach; ?>
                </tbody>
            </table>

            <? if ($building->has_actives_extraordinaries_period()): ?>
            <h2><?= $building->name ?> - Expensas Extraordinarias <h2>
            <table class="table_properties" cellpadding="0"  cellspacing="0">
                <thead>
                    <tr class="table_header">
                        <th>UF</th>
                        <th>Piso</th>
                        <th>Depto</th>
                        <th>Propietario</th>
                        <th><div class="to_center">Identificador de Pago</div></th>
                        <th>Monto a Pagar</th>
                        <th>Pagó</th>
                    </tr>
                </thead>
                <tbody>
                    <? 
                        $periods = $building->get_actives_extraordinaries_period();
                        foreach ($periods as $period):    
                            foreach ($building->properties as $property):    
                                if ($property->has_extraordinary_period_active()):
                    ?>
                    <tr class="table_row">
                        <td><?= $property->functional_unity?></td>
                        <td><?= $property->floor?></td>
                        <td><?= $property->appartment?></td>            
                        <td><? if($property->owner->name != ""): echo $property->owner->lastname; else: echo $property->owner->lastname; endif;?></td>
                        <? $payment_code = $property->get_current_extraordinary_payment_code(); ?>
                        <td class="identifier_col">
                            <div class="to_center">
                                <?= substr($payment_code, 0, 5) ?><span class="highlight"><?= substr($payment_code, 5, 8) ?></span><?= substr($payment_code, 13, 48) ?>
                            </div>
                        </td>
                        <td><div class="to_right"><?= number_format($property->value_to_pay_extraordinary($period),2) ?></div></td>
                        <td>
                            <? if (ExtraordinaryTransaction::exist_bank_payment($property->get_current_extraordinary_payment_font_code())): ?>
                                <span class="highlight">SI</span>
                            <? endif; ?>
                        </td>
                    </tr>
                    <?  
                                endif;
                            endforeach;
                        endforeach;
                    ?>
                </tbody>
            </table>
            <? endif; ?>

        </div>
        <? if ($iterations < count($buildings)): ?>
            <div class="line_break " style="visibility: none"></div>
        <? endif; ?>
    <? endforeach; ?>
    </div>
</body>
</html>