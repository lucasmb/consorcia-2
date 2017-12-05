<!DOCTYPE HTML>
<html>
    <head>
        <title>Recibo-Expensas Calle: <?= $building->street ?> Número <?= $building->number ?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="<?= url_css('/reportsHTML/expense_bill.css') ?>" />
        <script type="text/javascript" src="<?= url_js('/home/jquery.js')?>"></script>
        <script>
            $('.one_page').height($(document).height());
        </script>
    </head>
    
    <style type="text/css" media="print">
        @page { size: landscape; }
    </style>

<body onclick="myFunction();">
    <script>
        function myFunction()
        {
            window.print();
        }
    </script>

<div class="one_page">
    <div class="left_column">
        <div class="original_left">
            <div class="title" style="background: url(../assets/img/home/header_report.jpg) no-repeat;">
                <div class="title_center">
                    Recibo de propiedad horizontal ley 13.512 <br>
                    Consorcio de Propietarios <? if($property->building->cuit): ?> CUIT: <?= $property->building->cuit ?> <? endif; ?><br>
                    Calle: <?= $property->building->street ?> Número <?= $property->building->number ?>
                </div>
                <div class="title_right">
                    <p>Original</p>
                    <p>Fecha: ... / <?= date("m",strtotime($building->date_next_period())); ?> / <?= date("Y",strtotime($building->date_next_period())); ?></p>
                </div>
            </div>
            <table cellpadding="0" cellspacing="0" class="table_owner">
                    <tbody>
                        <tr class="table_row">
                            <td id="owner">Recibi de: <?= $property->owner->lastname." ".substr($property->owner->name,0,1).'.'?></td>                           
                            <td>Piso: <?= $property->floor ?></td>
                            <td>Depto: <?= $property->appartment ?></td>
                            <td>UF: <?= $property->functional_unity?></td>                            
                        </tr>
                        <tr class="table_row">
                            <td colspan="4">Por cuenta y orden del consorcio de propietarios Calle <?= $property->building->street ?> Número <?= $property->building->number ?> para aplicar los 
                            conceptos de <?= $bill_concept; ?>. 
                            </td>
                        </tr>
                    </tbody>
            </table>
            <div class="content">
                <div>
                    <table cellpadding="0" cellspacing="0" class="table_properties">
                        <thead>
                            <tr class="table_header">
                                <th>Descripcion</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table_row">
                                <td><?= $bill_description ?></td>
                                <td class="import"><?= number_format(abs($bill_value),2) ?></td>
                            </tr>
                            <tr class="table_row">
                                <td>Total a abonar</td>
                                <td class="import"><?= number_format(round($bill_value, 0 ,PHP_ROUND_HALF_DOWN),2);  ?></td>
                            </tr>                          
                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="sign">Admistración</div>
            <div class="footer">
                    Personas habilitadas para efectuar el cobro: <span>José Omar Hernandez (DNI: 13.667.908)</span> <!--<span>y Mauro Craco(DNI: 32.485.828)</span>-->
            </div>
        </div>
        <div class="duplicate_left">
            <div class="title" style="background: url(../assets/img/home/header_report.jpg) no-repeat;">
                <div class="title_center">
                    Recibo de propiedad horizontal ley 13.512 <br>
                    Consorcio de Propietarios <? if($property->building->cuit): ?> CUIT: <?= $property->building->cuit ?> <? endif; ?><br>
                    Calle: <?= $property->building->street ?> Número <?= $property->building->number ?>
                </div>
                <div class="title_right">
                    <p>Duplicado</p>
                    <p>Fecha: ... / <?= date("m",strtotime($building->date_next_period())); ?> / <?= date("Y",strtotime($building->date_next_period())); ?></p>
                </div>
            </div>
            <table cellpadding="0" cellspacing="0" class="table_owner">
                    <tbody>
                        <tr class="table_row">
                            <td id="owner">Recibi de: <?= $property->owner->lastname." ".substr($property->owner->name,0,1).'.'?></td>                           
                            <td>Piso: <?= $property->floor ?></td>
                            <td>Depto: <?= $property->appartment ?></td>
                            <td>UF: <?= $property->functional_unity?></td>                            
                        </tr>
                        <tr class="table_row">
                            <td colspan="4">Por cuenta y orden del consorcio de propietarios Calle <?= $property->building->street ?> Número <?= $property->building->number ?> para aplicar los 
                            conceptos de <?= $bill_concept; ?>. 
                            </td>
                        </tr>
                    </tbody>
            </table>
            <div class="content">
                <div>
                    <table cellpadding="0" cellspacing="0" class="table_properties">
                        <thead>
                            <tr class="table_header">
                                <th>Descripcion</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table_row">
                                <td><?= $bill_description ?></td>
                                <td class="import"><?= number_format(abs($bill_value),2) ?></td>
                            </tr>
                            <tr class="table_row">
                                <td>Total a abonar</td>
                                <td class="import"><?= number_format(round($bill_value, 0 ,PHP_ROUND_HALF_DOWN),2);  ?></td>
                            </tr>                          
                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="sign">Admistración</div>
            <div class="footer">
                    Personas habilitadas para efectuar el cobro: <span>José Omar Hernandez (DNI: 13.667.908)</span> <!--<span>y Mauro Craco(DNI: 32.485.828)</span>-->
            </div>
        </div>        
    </div>
    <div class="righ_column" />
</div>

</body>
</html>