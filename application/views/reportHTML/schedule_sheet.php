<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title> Horarios de Visita </title>
    
        <link rel="stylesheet" type="text/css" href="<?= url_css('/reportsHTML/short_monthly_balance_capitulation.css') ?>" />
        <script type="text/javascript" src="<?= url_js('/home/jquery.js')?>"></script>
        <script type="text/javascript" src="<?= url_js('/reportsHTML/font_resizer.js')?>"></script>
        
    </head>
    <style type="text/css" media="print">
        @page { size: landscape; }
    </style>

<body>
    <div class="no-print">
        <button id="increaseFont"> + </button>
        <button id="decreaseFont"> - </button>
        <button id="resetFont"> Reset </button>
        <button id="print"> Print</button>
    </div>
    <div class="content">
        <table class="table_properties">
            <!-- Periods -->
            <thead>
                <th>Dia / Edificio</th>
                <? foreach ($buildings as $building): ?>
                <th>
                    <?= $building->street ?><br />
                    Nº <?= $building->number ?>
                </th>
                <? endforeach; ?>
            </thead>
            
            <tbody>
                <? 
                    $month_dates = get_month_dates_from_date($date_time);
                    foreach($month_dates as $date):

                ?>
                <tr>
                    <td> <?= $date->format('d') ?> - <?= week_day_name($date->format("Y-m-d")) ?> </td>
                    <? foreach ($buildings as $building): ?>
                        <? if ($building_pay_date = $building->have_schedule_date_for_date($date)): ?>
                            <td> <?= $building_pay_date->hour_start ?>:<?= $building_pay_date->minuts_start ?> </td>
                        <? else: ?>
                            <td></td>
                        <? endif; ?>
                    <? endforeach; ?>
                </tr>
                <? endforeach; ?>
            </tbody>
                
        </table>
        
        
    </div>
</body>
</html>