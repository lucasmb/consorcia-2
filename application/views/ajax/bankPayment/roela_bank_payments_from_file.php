<script type="text/javascript">
$(document).ready(function() {

    $(".frm_bank_payment").submit(function(){
        
        form = $(this);
        
        $.ajaxSetup({async:false});
        $.post('index.php/ajax/bankPayment/make_roela_bank_payment', form.serialize() , function(rta){
            
            form.html("");

        });
        return false;
    });

});
</script>


<?
    if($building) {
        foreach ($building as $building_id => $operation_type_array) {
            $building_model = Building::find($building_id);
            foreach ($operation_type_array as $operation_type_id => $record_bank_payment) {

               var_dump('operation_id');
               var_dump($operation_type_id);

?>
            <form id="frm_bank_payment_<?= $building_id ?>" class="frm_bank_payment">
                <input name="building_id" id="building_id" type="hidden" value="<?= $building_id; ?>">
                <input name="operation_type_id" id="operation_type_id" type="hidden" value="<?= $operation_type_id; ?>">
                <h1><?= $building_model->name ?> - <?= payment_type($operation_type_id) ?></h1>
                <table cellpadding="0" cellspacing="0" class="table_properties">
                    <thead>
                        <tr class="table_header">
                            <th>UF</th>
                            <th>Piso</th>
                            <th>Dto</th>
                            <th>Propietario</th>
                            <th>Fecha de Pago</th>
                            <? if (isset($record->payment_method) && strlen($record->payment_method) > 0): ?>
                                <th>Metodo de Pago</th>
                            <? endif; ?>
                            <th>Monto</th>
                            <th>Tipo Pago</th>
                            <? if (is_extraordinary_operation($operation_type_id)): ?>
                                <th>Extraordiaria</th>
                            <? endif; ?>
                        </tr>
                    </thead>
                    <tbody>
<?
                $records_duplicated = array();
                foreach ($record_bank_payment as $record) {
                    if ((!is_extraordinary_operation($operation_type_id) && IncomeTransaction::exist_bank_payment($record->bank_payment_id)) || (is_extraordinary_operation($operation_type_id) && ExtraordinaryTransaction::exist_bank_payment($record->bank_payment_id))) {
                        $records_duplicated[] = $record;
                    } else {
?>                     
                        <input name="property_id" id="property_id" type="hidden" value="<?= $record->property->id; ?>">
                        <input id="bank_property_paid_<?= $record->property->id ?>" type="hidden" name="bank_property_paid[]" value="<?= $record->property->id ?>" />
                        <input id="input_pay_<?= $record->property->id ?>" type="hidden" name="ammount_paid_<?= $record->property->id ?>" value="<?= $record->payment_ammount ?>" />
                        <input id="paid_date_<?= $record->property->id ?>" type="hidden" name="paid_date_<?= $record->property->id ?>" value="<?= $record->payment_date ?>" />
                        <input id="bank_payment_id_<?= $record->property->id ?>" type="hidden" name="bank_payment_id_<?= $record->property->id ?>" value="<?= $record->bank_payment_id ?>" />
                        <tr class="table_row">
                            <td><?= $record->property->functional_unity ?></td>
                            <td><?= $record->property->floor ?></td>
                            
                            <td><? if (!is_null($record->property->appartment) && strlen($record->property->appartment)>0): ?>
                                    <?= $record->property->appartment ?>
                                <? endif;?>
                            </td>

                            <td><?= $record->property->owner->lastname ?> </td>
                            <td><?= $record->payment_date ?></td>
                            <? if (isset($record->payment_method) && strlen($record->payment_method) > 0): ?>
                                <td><?= $record->payment_method ?></td>
                            <? endif; ?>
                            <td><?= $record->payment_ammount ?></td>

                            <? vd($record); ?>
                            <td><?= $record->property->payment_type_for_ammount($record->payment_ammount, $operation_type_id); ?></td>
                            <? if (is_extraordinary_operation($operation_type_id)): ?>
                                <td>
                                    <select name="extraordinary_period_id_<?= $record->property->id ?>" id="extraordinary_period_id_<?= $record->property->id ?>">
                                        <?= get_select_extraordinary_periods($building_id, $record->property->last_extraodinary_active_id(), EXTRAORDINARY_PERIOD_STATE_ACTIVE); ?>
                                    </select>
                                </td>
                            <? endif; ?>
                        </tr>
<?
                    }
                }
?>
                    </tbody>

<?
                if (count($records_duplicated) > 0) {
?>
                        <tr class="table_header">
                            <th class="duplicated" colspan="8">Registros repetidos que ya han sido procesados</th>
                        </tr>                  
<?
                    foreach ($records_duplicated as $record_duplicated) {
?>
                        <tr class="table_row_duplicated">
                            <td><?= $record_duplicated->property->functional_unity ?></td>
                            <td><?= $record_duplicated->property->floor ?></td>
                            
                            <td><? if (!is_null($record->property->appartment) && strlen($record_duplicated->property->appartment)>0): ?>
                                    <?= $record_duplicated->property->appartment ?>
                                <? endif;?>
                            </td>

                            <td><?= $record_duplicated->property->owner->lastname ?> </td>
                            <td><?= $record_duplicated->payment_date ?></td>
                            <? if (isset($record->payment_method) && strlen($record->payment_method) > 0): ?>
                                <td><?= $record->payment_method ?></td>
                            <? endif; ?>
                            <td><?= $record_duplicated->payment_ammount ?></td>
                            <td><?= $record_duplicated->property->payment_type_for_ammount($record_duplicated->payment_ammount, $operation_type_id); ?></td>
                            <? if (is_extraordinary_operation($operation_type_id)): ?>
                                <td>
                                    <select disabled="disabled" name="extraordinary_period_id_<?= $record->property->id ?>" id="extraordinary_period_id_<?= $record->property->id ?>">
                                        <?= get_select_extraordinary_periods($building_id, $record->property->last_extraodinary_active_id(), EXTRAORDINARY_PERIOD_STATE_ACTIVE); ?>
                                    </select>
                                </td>
                            <? endif; ?>
                        </tr>
<?
                    }
                }
?>                  
                    
                </table>
            <? if (count($records_duplicated) != count($record_bank_payment)): ?>
                <button class="btn_full_width">Guardar ingresos de <?= $building_model->name ?></button>
            <? else: ?>
                <button class="btn_full_width">Quitar tabla</button>
            <? endif; ?>
            </form>
<?
            }
            
        }

    } 
?>