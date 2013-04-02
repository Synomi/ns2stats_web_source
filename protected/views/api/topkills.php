<?php
header('Content-type: text/plain');
$html ="\n#TOP KILLS\n";
$html .= "Steam name,  kills\n";
foreach ($data as $key => $value)
{
    $html.= $value['steam_name'] . ", " . $value['kills'] . "\n";
}

echo $html;
?>
