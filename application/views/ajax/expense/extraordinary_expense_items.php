<? if($expenses): ?>
    <table class="table_properties" cellspacing="0" cellpadding="0">
        <thead>
            <tr class="table_header" >
                <th>Descripcion</th>
                <th>Monto</th>
                <th>Extraordinaria</th>
                <th>Fecha</th>
                <th>Prioridad</th>
                <th>Edicion</th>
            </tr>
        </thead>
        <tbody>
        <? foreach($expenses as $expense): ?>
            <tr class="table_row">
                <td class="aditional_item_descripcion"><?= $expense->expense_tag->name ?></td>
                <td><?= $expense->value ?></td>
                <td><?= $expense->extraordinary_period->name ?></td>
                <td><?= $expense->date ?></td>
                <td><?= $expense->priority ?></td>
                <td>
                    <img src="<?= url_img('/home/delete.png') ?>" onclick="delete_extraordinary_expense(<?= $expense->id ?>)">
                    <img src="<?= url_img('/home/edit.gif') ?>" onclick="edit_extraordinary_expense(<?= $expense->id ?>)">
                    <img src="<?= url_img('/home/plus.png') ?>" onclick="increment_extraordinary_priority(<?= $expense->id ?>)">
                    <img src="<?= url_img('/home/minus.png') ?>" onclick="decrement_extraordinary_priority(<?= $expense->id ?>)">
                </td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>    
<? endif; ?>