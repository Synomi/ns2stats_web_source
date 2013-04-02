<?php
if (isset($server->motd) && $server->motd!=" ")
{
    $rows =explode("!LB", str_replace("!V", $serverVersion, $server->motd)) ;

    for ($i=0;$i<count($rows);$i++)
        echo $rows[$i] . "\n";
}
else
    echo "Welcome! Server is running NS2 Stats version " . $serverVersion . " beta.";

?>
