<?php
header('Content-type: text/plain');

function addLine($text)
{
    //ns2 console auto line breaks after 128 characters, so always insert lines with 128 characters
    $line = "";
    for ($i=0;$i<128;$i++)
        if (strlen($text)>$i)                
            $line .= $text[$i];        
        else
            $line .= " ";
    
    return $line;
}
$tmp  = addLine("\n### ::NS2Stats.com::");
$tmp .= addLine("## Available commands (" . $steamId . "):");
$tmp .= addLine("#");
$tmp .= addLine("# stats help : This help");
$tmp .= addLine("# stats rank : Your rank and rating.");
$tmp .= addLine("# stats stats : Your personal stats");
$tmp .= addLine("# stats topkills : Top 10 kill counts");
$tmp .= addLine("# stats login <your code> : Logs you in, required for some actions");
$tmp .= addLine("# stats hide : Hide/show your stat page on website (login required)");
$tmp .= addLine("#- Commands without stats prefix:");
$tmp .= addLine("# ns2stats_settings : shows settings menu, currently able to change which browser check command uses.");
$tmp .= addLine("# votemap : Allows you to vote for map change (if enabled)");
$tmp .= addLine("# players : Lists players with their steam name,ranking and rating (if auto-arrange enabled)");
$tmp .= addLine("# ready : Makes your team ready in tournament mode.");
$tmp .= addLine("# tag : Usage : tag \"Exertus vs Archaea\", Use during game, resets when game starts. Searchable on web. (soon)");
$tmp .= addLine("#- Chat commands:");
$tmp .= addLine("# /stuck or /unstuck: Attempts to free you");
$tmp .= addLine("# ready or rdy: Makes your team ready in tournament mode.");  

echo $tmp;
?>
