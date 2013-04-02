<?php
$this->widget('FilterForm', array(
    'servers' => All::getServers(),
    'builds' => All::getBuilds(),
));
?>
<?php
$this->widget('FilterPanel', array(
    'url' => 'map/maplist',
        )
);
?>