<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_list',
    'template' => '{summary} {sorter} {items} <div style="clear:both;"></div> {pager}',
));
?>