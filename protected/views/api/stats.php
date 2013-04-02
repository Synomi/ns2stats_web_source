<?php

header('Content-type: text/plain');
if (isset($player)) {
    echo "\nScore: " . Player::getScore($player->id) . ", ";
    echo "Score per Death: " . round(Player::getSD($player->id), 2) . ", ";
    echo "Score per Minute: " . round(Player::getSM($player->id), 2) . "\n";
    echo "Kills: " . Player::getKillsById($player->id) . ", ";
    echo "Deaths: " . Player::getDeaths($player->id) . ", ";
    echo "Kills per Death: " . round(Player::getKD($player->id), 2) . "\n";
    echo "Best Kill Streak: " . Player::getKillStreak($player->id) . ", ";
    echo "Time Played: " . Helper::secondsToTime(Player::getTimePlayed($player->id)) . ", ";
    echo "Longest Survival: " . Helper::secondsToTime(Player::getLongestSurvival($player->id)) . "\n";
    echo "You can find more stats at ns2stats.org";
}
else
    echo "No stats found yet";