<?php

/**
 * Global stats
 */
class All
{

    public static function getMaps()
    {
        $sql = 'SELECT map.id, map.name, COUNT(DISTINCT round.id, map.id) AS count FROM round
            LEFT JOIN map ON round.map_id = map.id
            LEFT JOIN mod_round ON mod_round.round_id = round.id
            WHERE 1=1 ' . Filter::addFilterConditions() . '
            GROUP BY map.id
            ORDER BY count DESC';

        $command = Yii::app()->db->cache(160 * 60)->createCommand($sql);
        return $command->queryAll();
    }

    public static function getRoundResults()
    {
        //WHERE round.winner = 1 ' . Filter::addFilterConditions() . ' APRIL FOOL CHANGE
        $sql = '
            SELECT "Marines" AS name, COUNT(DISTINCT round.id) AS count FROM round 
            LEFT JOIN mod_round ON mod_round.round_id = round.id
            WHERE round.winner = 1 ' . Filter::addFilterConditions() . '
            UNION
            SELECT "Aliens" AS name, COUNT(DISTINCT round.id) AS count FROM round 
            LEFT JOIN mod_round ON mod_round.round_id = round.id
            WHERE round.winner = 2 ' . Filter::addFilterConditions() . '
            ';

        $command = Yii::app()->db->cache(43 * 60)->createCommand($sql);
        return $command->queryAll();
    }

    public static function getRoundsPlayedPerHour()
    {
        $filter = new Filter();
        $filter->loadFromSession();
        $filter->loadDefaults();
        $sql = 'SELECT COUNT(DISTINCT round.id) AS count, round.end AS date FROM round
            LEFT JOIN mod_round ON mod_round.round_id = round.id
            WHERE 1=1 ' . Filter::addFilterConditions() . '
            GROUP BY ';
        if (strtotime($filter->endDate) - strtotime($filter->startDate) <= 7 * 24 * 3600)
            $sql .= 'HOUR(FROM_UNIXTIME(round.end)), ';
        $sql .= 'DAYOFYEAR(FROM_UNIXTIME(round.end)), YEAR(FROM_UNIXTIME(round.end))
            ORDER BY date';

        $command = Yii::app()->db->cache(56 * 60)->createCommand($sql);
        return $command->queryAll();
    }

    public static function getPlayersPerHour()
    {
        $filter = new Filter();
        $filter->loadFromSession();
        $filter->loadDefaults();
        $sql = 'SELECT COUNT(DISTINCT player_round.player_id) AS count, round.end AS date FROM round
            LEFT JOIN player_round ON round.id = player_round.round_id
            LEFT JOIN mod_round ON mod_round.round_id = round.id
            WHERE 1=1 ' . Filter::addFilterConditions() . '
            GROUP BY ';
        if (strtotime($filter->endDate) - strtotime($filter->startDate) <= 7 * 24 * 3600)
            $sql .= 'HOUR(FROM_UNIXTIME(round.end)), ';
        $sql .= 'DAYOFYEAR(FROM_UNIXTIME(round.end)), YEAR(FROM_UNIXTIME(round.end))
            ORDER BY date';

        $command = Yii::app()->db->cache(54 * 60)->createCommand($sql);
        return $command->queryAll();
    }

    public static function getRoundsList()
    {
        $sql = 'SELECT DISTINCT server.id AS server_id, round.id, server.name AS server_name, round.end, round.end - round.start AS length,round.added
            FROM round
            LEFT JOIN server ON server.id = round.server_id
            LEFT JOIN mod_round ON mod_round.round_id = round.id
            WHERE 1=1 ' . //Filter::addFilterConditions() . '
                'ORDER BY round.added DESC,round.id DESC
            LIMIT 16';

        $command = Yii::app()->db->cache(1 * 60)->createCommand($sql);
        return $command->queryAll();
    }

    /* round / rounds */

    public static function getFullRoundsList()
    {
        if (isset($_GET['searchTags']) && strlen($_GET['searchTags']) > 1)
        {            
            $tagsString = '%' . $_GET['searchTags'] . '%';
            $sql = 'SELECT DISTINCT server.id AS server_id, round.id, server.name AS server_name, round.end, round.end - round.start AS length,round.added, round.tags
            FROM round
            LEFT JOIN server ON server.id = round.server_id
            LEFT JOIN mod_round ON mod_round.round_id = round.id            
            WHERE round.tags IS NOT NULL AND round.tags LIKE :tags AND
            1=1 ' . Filter::addFilterConditions() .
                    ' ORDER BY round.added DESC,round.id DESC
            LIMIT 100';
            $command = Yii::app()->db->cache(1 * 60)->createCommand($sql);
            $command->bindParam(':tags', $tagsString);
             //$command->bindParam(':id', $id); $id was ununsed
        }
        else
        {
            $sql = 'SELECT DISTINCT server.id AS server_id, round.id, server.name AS server_name, round.end, round.end - round.start AS length,round.added,round.tags
            FROM round
            LEFT JOIN server ON server.id = round.server_id
            LEFT JOIN mod_round ON mod_round.round_id = round.id
            WHERE 1=1 ' . Filter::addFilterConditions() .
                    ' ORDER BY round.added DESC,round.id DESC
            LIMIT 100';
            $command = Yii::app()->db->cache(1 * 60)->createCommand($sql);
        }


        return $command->queryAll();
    }

    public static function getScoreList()
    {
        $sql = 'SELECT DISTINCT player.steam_name AS name, player.id, SUM(DISTINCT player_round.score) AS score
            FROM player
            LEFT JOIN player_round ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE 1=1 ' . Filter::addFilterConditions() . '
            GROUP BY player.id
            ORDER BY score DESC
            LIMIT 10';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }

    public static function getKillsList()
    {
        $sql = 'SELECT player.country, player.steam_image, player.steam_name AS name, player.id, COUNT(DISTINCT death.id) AS kills
            FROM player
            LEFT JOIN player_round ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id
            LEFT JOIN mod_round ON mod_round.round_id = round.id
            LEFT JOIN death ON player_round.id = death.attacker_id
            WHERE 1=1 ' . Filter::addFilterConditions() . '
            GROUP BY player.id
            ORDER BY kills DESC
            LIMIT 10';
        $command = Yii::app()->db->cache(53 * 60)->createCommand($sql);
        return $command->queryAll();
    }

    public static function getBuilds()
    {
        $sql = 'SELECT DISTINCT round.build FROM round
            LEFT JOIN player_round ON round.id = player_round.round_id
            LEFT JOIN player ON player_round.player_id = player.id
            WHERE round.build>244
            ORDER BY round.build DESC';


        $command = Yii::app()->db->cache(60 * 60 * 4)->createCommand($sql);
        return $command->queryAll();
    }

    public static function getMods()
    {
        $sql = 'SELECT DISTINCT * FROM `mod` ORDER BY name DESC';

        $command = Yii::app()->db->cache(143 * 60)->createCommand($sql);
        return $command->queryAll();
    }

    public static function getServers()
    {
        $sql = 'SELECT DISTINCT
            server.id, server.name
            FROM server
            LEFT JOIN round ON round.server_id = server.id
            WHERE round.end > ' . strtotime('-7 days') . '
            ORDER BY name ASC';

        $command = Yii::app()->db->cache(19 * 60)->createCommand($sql);
        return $command->queryAll();
    }

    public static function getRoundResultsByTime()
    {
        $sql = '
            SELECT COUNT(round_length) AS count, IF(winner = 1, "Marines", "Aliens") AS name, floor(round_length / (' . HighchartData::$timeDistributionFactor . ' * 60)) AS time FROM (
            SELECT DISTINCT round.id, round.end - round.start AS round_length, winner
            FROM map
            LEFT JOIN round ON round.map_id = map.id
            LEFT JOIN server ON server.id = round.server_id
            LEFT JOIN mod_round ON mod_round.round_id = round.id
            WHERE 1=1 ' . Filter::addFilterConditions() . ') AS rounds
        GROUP BY winner, floor(round_length / (' . HighchartData::$timeDistributionFactor . ' * 60))';
        $command = Yii::app()->db->cache(121 * 60)->createCommand($sql);
        //$command->bindParam(':id', $id); $id was ununsed
        return $command->queryAll();
    }

    public static function getRoundLengths()
    {
        $sql = '
            SELECT COUNT(round_length) AS count, 1 AS name, floor(round_length / (' . HighchartData::$timeDistributionFactor . ' * 60)) AS time FROM (
            SELECT 
            DISTINCT round.id, round.end - round.start AS round_length
            FROM map
            LEFT JOIN round ON round.map_id = map.id
            LEFT JOIN server ON server.id = round.server_id
            LEFT JOIN mod_round ON mod_round.round_id = round.id
            WHERE 1=1 ' . Filter::addFilterConditions() . ') AS rounds
        GROUP BY floor(round_length / (' . HighchartData::$timeDistributionFactor . ' * 60))';

        $command = Yii::app()->db->cache(122 * 60)->createCommand($sql);

        return $command->queryAll();
    }

    public static function getPlayerNationalities()
    {
        $sql = 'SELECT COUNT(country) AS count, country AS name 
            FROM (
                SELECT DISTINCT player.id, player.country
                FROM player
                LEFT JOIN player_round ON player.id = player_round.player_id
                LEFT JOIN round ON player_round.round_id = round.id
                LEFT JOIN mod_round ON mod_round.round_id = round.id
                WHERE country IS NOT null ' . Filter::addFilterConditions() . '
                GROUP BY player.id
            ) AS players
            GROUP BY country
            ORDER BY COUNT(country) DESC';

        $command = Yii::app()->db->cache(123 * 60)->createCommand($sql);
        return $command->queryAll();
    }

    public static function getTopKillEloRating($limit)
    {
        $sql = '
            SELECT
                id,
                steam_name AS name,
                kill_elo_ranking,
                kill_elo_rating
            FROM `player`
            ORDER BY kill_elo_rating DESC
            LIMIT ' . $limit;

        $command = Yii::app()->db->cache(3600)->createCommand($sql);
        return $command->queryAll();
    }

    public static function getTopWinEloRating($limit)
    {
        $sql = '
            SELECT
                id,
                steam_name AS name,
                win_elo_ranking,
                win_elo_rating
            FROM `player`
            ORDER BY win_elo_rating DESC
            LIMIT ' . $limit;

        $command = Yii::app()->db->cache(3600)->createCommand($sql);
        return $command->queryAll();
    }

    public static function getTopCommanderEloRating($limit)
    {
        $sql = '
            SELECT
                player.country, 
                id,
                steam_name AS name,
                commander_elo_ranking,
                commander_elo_rating
            FROM `player`
            ORDER BY commander_elo_rating DESC
            LIMIT ' . $limit;

        $command = Yii::app()->db->cache(3600)->createCommand($sql);
        return $command->queryAll();
    }

}