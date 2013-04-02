<?php
echo CHtml::tag('h1', array(), 'My Servers');
echo CHtml::tag('p', array(), 'Server admins can see their server status here.');

$dataProvider = Server::getListByAdmin($player->id);
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'server-list',
    'dataProvider' => $dataProvider,
    'columns' => array(
        'name',
        'ip',
        'port',
        'stats_version',
        'server_key',              
        'private'
        )
)
);
$this->renderPartial('/site/install');
?>
<div class="clear">

</div>
