<div class="stat-list">
    <div>
        <div class="stat-label">Marine Ranking:</div>
        <div class="stat-value"><?php echo $player->getRanking('marine_win_elo') . ' (' . $player->marine_win_elo . ')'; ?></div>
    </div>
    <div>
        <div class="stat-label">Alien Ranking:</div>
        <div class="stat-value"><?php echo $player->getRanking('alien_win_elo') . ' (' . $player->alien_win_elo . ')'; ?></div>
    </div>
    <div>
        <div class="stat-label">Marine Commander Ranking:</div>
        <div class="stat-value"><?php echo $player->getRanking('marine_commander_elo') . ' (' . $player->marine_commander_elo . ')'; ?></div>
    </div>
    <div>
        <div class="stat-label">Alien Commander Ranking:</div>
        <div class="stat-value"><?php echo $player->getRanking('alien_commander_elo') . ' (' . $player->alien_commander_elo . ')'; ?></div>
    </div>
    <div>
        <div class="stat-label">Rating:</div>
        <div class="stat-value"><?php echo $player->rating . " (might reset)" ?></div>
    </div>
    <div>
        <div class="stat-label">Ranking:</div>
        <div class="stat-value"><?php echo $player->ranking . " of " . Player::getMaxRank() ?></div>
    </div>
    <div>
        <div class="stat-label">NS2ID:</div>
        <div class="stat-value"><?php echo $player->steam_id; ?></div>
    </div>
    <div>
        <div class="stat-label">Rounds Played:</div>
        <div class="stat-value"><?php echo Player::getRoundsPlayed($player->id) ?></div>
    </div>
    <div>
        <div class="stat-label">Time Played:</div>
        <div class="stat-value"><?php echo Helper::secondsToTime(Player::getTimePlayed($player->id)) ?></div>
    </div>
    <div>
        <div class="stat-label">Longest Survival:</div>
        <div class="stat-value"><?php echo Helper::secondsToTime(Player::getLongestSurvival($player->id)) ?></div>
    </div>
    <div>
        <div class="stat-label">Score:</div>
        <div class="stat-value"><?php echo Player::getScore($player->id) ?></div>
    </div>
    <div>
        <div class="stat-label">Kills:</div>
        <div class="stat-value"><?php echo Player::getKillsById($player->id) ?></div>
    </div>
    <div>
        <div class="stat-label">Deaths:</div>
        <div class="stat-value"><?php echo Player::getDeaths($player->id) ?></div>
    </div>
    <div>
        <div class="stat-label">Best Kill Streak:</div>
        <div class="stat-value"><?php echo Player::getKillStreak($player->id) ?></div>
    </div>
    <div>
        <div class="stat-label">Kills / Deaths:</div>
        <div class="stat-value"><?php echo round(Player::getKD($player->id), 2) ?></div>
    </div>
    <div>
        <div class="stat-label">Score / Deaths:</div>
        <div class="stat-value"><?php echo round(Player::getSD($player->id), 2) ?></div>
    </div>
    <div>
        <div class="stat-label">Score / Minute:</div>
        <div class="stat-value"><?php round(Player::getSM($player->id), 2) ?></div>
    </div>

    <div>
        <div class="stat-label">Score / Deaths:</div>
        <div class="stat-value"><?php echo round(Player::getSD($player->id), 2) ?></div>
    </div>
    <div>
        <div class="stat-label">Last seen:</div>
        <div class="stat-value"><a class="timeago" style="text-decoration: none;color:white;" href="#" title="<?php echo isset($player->last_seen) ? date("c", strtotime($player->last_seen )):"" ?>"></a></div>
    </div>
</div>
<?php
//echo CHtml::tag('div', array(), 'User level: ' . Player::getGroupName($player->group));
?>