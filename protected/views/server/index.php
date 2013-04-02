<?php
$this->widget('FilterForm', array(
    'builds' => All::getBuilds(),
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

