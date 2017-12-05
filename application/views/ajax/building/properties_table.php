<script>
$(document).ready(function() {
    
    $('.sort_column').click(function(){
        
        sort_column = this.getAttribute("value");
        order = this.getAttribute("order");
        if (order == null)
            order = "ASC";
        building = $("#building").val();
        
        $('#div_properties').load('index.php/ajax/properties/order_properties',{sort_column:sort_column, building_id:building, order:order});
    });
    
});
</script>

<? if($properties): ?>
        <button class="add_property_button" id="add_property_button" type="button">Agregar Propiedad</button> 
        <table cellpadding="0" cellspacing="0" class="table_properties">
        <thead>
            <tr class="table_header">
                <th class="sort_column" <? if ($sort_column == 'functional_unity') echo "order='$order'"; ?> value="functional_unity">UF</th>
                <th class="sort_column" <? if ($sort_column == 'floor') echo "order='$order'"; ?> value="floor">Piso</th>
                <th class="sort_column" <? if ($sort_column == 'appartment') echo "order='$order'"; ?> value="appartment">Departamento</th>
                <th class="sort_column" <? if ($sort_column == 'coefficient') echo "order='$order'"; ?> value="coefficient">Coeficiente</th>
                <th class="sort_column" <? if ($sort_column == 'meters') echo "order='$order'"; ?> value="meters">Metros</th>
                <th class="sort_column" <? if ($sort_column == 'owner') echo "order='$order'"; ?> value="owner">Propietario</th>
                <th>Edicion</th>            
            </tr>
        </thead>
        <tbody>
            <? foreach ($properties as $property): ?>
            <tr class="table_row">
                <td><?= $property->functional_unity?></td>
                <td><?= $property->floor?></td>
                <td><?= $property->appartment?></td>            
                <td><?= $property->coefficient?></td>
                <td><?= $property->meters?></td>
                <td><? if($property->owner->name != ""): echo $property->owner->lastname . ', ' .  $property->owner->name; else: echo $property->owner->lastname; endif;?></td>
                <td>
                    <i class="fa fa-address-card fa-lg" onclick="view_owner(<?= $property->owner_id ?>)"></i>
                    <i class="fa fa-pencil-square fa-lg font_edit" onclick="edit_property(<?= $property->id ?>)"></i>
                    <i class="fa fa-window-close fa-lg" onclick="delete_property(<?= $property->id ?>)"></i>
                </td>
            </tr>
            <? endforeach; ?>
        </tbody>
        </table>
<? endif; ?>