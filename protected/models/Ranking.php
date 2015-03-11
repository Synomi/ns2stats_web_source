<?php

class Ranking {

    public static function getMarineList() {
        $sql = 'SELECT country, steam_image, id, steam_name AS name, marine_win_elo, SUM(wins) AS wins, SUM(losses) AS losses FROM(
            SELECT player.country, player.steam_image, player.id, player.steam_name, player.marine_win_elo, COUNT(round.id) AS wins, 0 AS losses
            FROM player_round   
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON round.id = player_round.round_id
            WHERE player_round.team = round.winner AND player_round.team = 1
            GROUP BY player.id
            UNION
            SELECT player.country, player.steam_image, player.id, player.steam_name, player.marine_win_elo, 0 AS wins,  COUNT(round.id) AS losses
            FROM player_round   
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON round.id = player_round.round_id
            WHERE player_round.team != round.winner AND player_round.team = 1
            GROUP BY player.id) as data
            GROUP BY id
            ORDER BY marine_win_elo DESC
            LIMIT 10';
        $command = Yii::app()->db->cache(1117 * 60)->createCommand($sql);
        return $command->queryAll();
    }

    public static function getAlienList() {
        $sql = 'SELECT country, steam_image, id, steam_name AS name, alien_win_elo, SUM(wins) AS wins, SUM(losses) AS losses FROM(
            SELECT player.country, player.steam_image, player.id, player.steam_name, player.alien_win_elo, COUNT(round.id) AS wins, 0 AS losses
            FROM player_round   
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON round.id = player_round.round_id
            WHERE player_round.team = round.winner  AND player_round.team = 2
            GROUP BY id
            UNION
            SELECT player.country, player.steam_image, player.id, player.steam_name, player.alien_win_elo, 0 AS wins,  COUNT(round.id) AS losses
            FROM player_round   
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON round.id = player_round.round_id
            WHERE player_round.team != round.winner  AND player_round.team = 2
            GROUP BY id) as data
            GROUP BY id
            ORDER BY alien_win_elo DESC
            LIMIT 10';
        $command = Yii::app()->db->cache(1261 * 60)->createCommand($sql);
        return $command->queryAll();
    }

    public static function getMarineCommanderList() {
        $sql = 'SELECT country, steam_image, id, steam_name AS name, marine_commander_elo, SUM(wins) AS wins, SUM(losses) AS losses FROM(
            SELECT player.country, player.steam_image, player.id, player.steam_name, player.marine_commander_elo, COUNT(round.id) AS wins, 0 AS losses
            FROM player_round   
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON round.id = player_round.round_id
            WHERE player_round.team = round.winner AND player_round.commander = 1  AND player_round.team = 1
            GROUP BY id
            UNION
            SELECT player.country, player.steam_image, player.id, player.steam_name, player.marine_commander_elo, 0 AS wins,  COUNT(round.id) AS losses
            FROM player_round   
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON round.id = player_round.round_id
            WHERE player_round.team != round.winner AND player_round.commander = 1  AND player_round.team = 1
            GROUP BY id) as data
            GROUP BY id
            ORDER BY marine_commander_elo DESC
            LIMIT 10';
        $command = Yii::app()->db->cache(1520 * 60)->createCommand($sql);
        return $command->queryAll();
    }

    public static function getAlienCommanderList() {
        $sql = 'SELECT country, steam_image, id, steam_name AS name, alien_commander_elo, SUM(wins) AS wins, SUM(losses) AS losses FROM(
            SELECT player.country, player.steam_image, player.id, player.steam_name, player.alien_commander_elo, COUNT(round.id) AS wins, 0 AS losses
            FROM player_round   
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON round.id = player_round.round_id
            WHERE player_round.team = round.winner AND player_round.commander = 1  AND player_round.team = 2
            GROUP BY id
            UNION
            SELECT player.country, player.steam_image, player.id, player.steam_name, player.alien_commander_elo, 0 AS wins,  COUNT(round.id) AS losses
            FROM player_round   
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON round.id = player_round.round_id
            WHERE player_round.team != round.winner AND player_round.commander = 1  AND player_round.team = 2
            GROUP BY id) as data
            GROUP BY id
            ORDER BY alien_commander_elo DESC
            LIMIT 10';
        $command = Yii::app()->db->cache(865 * 60)->createCommand($sql);
        return $command->queryAll();
    }

    public static function getTopRankings() {
        
        $sql1 = 'SET @rank=0;';
        $rconnection = Yii::app()->db;
        $rcommand = $rconnection->createCommand($sql1);
        $rcommand->query();
        
        $sql = '
            SELECT
                player.country, player.steam_image, 
                id,
                steam_name AS name,
                @rank:=@rank+1 AS ranking,
                rating
            FROM `player`            
            ORDER BY rating DESC
            LIMIT 10
            ';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }

    public static function getTopRankingsLong($amount) {
            $sql1 = 'SET @rank=0;';
        $rconnection = Yii::app()->db;
        $rcommand = $rconnection->createCommand($sql1);
        $rcommand->query();
        
        $sql = '            
            SELECT    
                player.country, player.steam_image, 
                id,
                steam_name AS name,
                @rank:=@rank+1 as "ranking",
                rating
            FROM `player`            
            ORDER BY rating DESC
            LIMIT :amount
            ';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':amount', $amount);
        return $command->queryAll();
    }

}