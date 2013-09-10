<?php

$this->widget('FilterForm', array(
    'servers' => All::getServers(),
    'builds' => All::getBuilds(),
    'mods' => All::getMods(),
    'filter' => $filter,
));
?>

<?php

$this->widget('FilterPanel', array(
    'url' => 'server/serverlist',
    "style" => "padding-left:10px;padding-right:10px;",
        )
);
?>

