<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_list',
    'template' => '{sorter} {items} <div style="clear:both;"></div> {summary} {pager}',
    'sortableAttributes'=>array(
        'departure_date',
        'fee',
    ),
));
?>