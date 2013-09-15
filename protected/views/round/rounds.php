<?php
$this->pageTitle = 'Rounds - NS2stats';
if (isset($_GET['searchTags']))
    $searchString = htmlspecialchars(strip_tags($_GET['searchTags']));
else
    $searchString = '';

?>
<div class="page_content">
    <h3>Search by tags</h3>
    <form method='get' action='/round/rounds'>
        <input type="text" value="<?php echo $searchString ?>" id="searchTags" name="searchTags" />
        <input type="submit" value="Search" />
    </form>
</div>

<?php
$this->widget('FilterForm', array(
    'servers' => All::getServers(),
    'builds' => All::getBuilds(),   
));

$this->widget('FilterPanel', array(
    'url' => 'round/roundslist',
    "style" => "padding-left:10px;padding-right:10px;",
        )
);
?>