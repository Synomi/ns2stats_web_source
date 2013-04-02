<?php
$this->widget('FilterForm', array(
    'builds' => All::getBuilds(),
    'filter' => $filter,
));
?>
<?php
$this->widget('FilterPanel', array(
    'url' => 'team/teamlist',
        )
);
?>

