<?php

/**
 * OBSOLETE FOR NOW
 */
class Stats {

    protected static function query($sql, $conditions) {
        if (isset($conditions)) {
            $i = 0;
            foreach ($conditions as $key => $value) {
                if (isset($sql['where']))
                    $sql['where'] .= ' AND ';
                else
                    $sql['where'] = '';
                $sql['where'] .= $key . ' :Param' . $i;
                $i++;
            }
        }
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $i = 0;
        if (isset($conditions))
            foreach ($conditions as $condition) {
                $command->bindParam(':Param' . $i, $condition);
                $i++;
            }
        return $command->queryAll();
    }

    public static function getMaps($conditions = null) {
        $sql = array();
        $sql['select'] = 'map.id, map.name, COUNT(map.id) AS count';
        $sql['from'] = 'player';
        $sql['join'] = '
            LEFT JOIN player_round ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id
            LEFT JOIN map ON round.map_id = map.id';
        $sql['group'] = 'map.id';
        return self::query($sql, $conditions);
    }

    public static function getRoundResults($conditions = null) {
        $sql = array();
        $sql['select'] = '"Wins" AS name, COUNT(round.id) AS count';
        $sql['from'] = 'player';
        $sql['join'] = '
            LEFT JOIN player_round ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id';
//        $sql['group'] = 'player.steam_id';

        //WHERE player.steam_id = :steam_id AND round.winner != player_round.team
//            UNION
//            SELECT "Losses" AS name, COUNT(round.id) AS count FROM player
//            LEFT JOIN player_round ON player.id = player_round.player_id
//            LEFT JOIN round ON player_round.round_id = round.id
//            WHERE player.steam_id = :steam_id AND round.winner = player_round.team
//            GROUP BY player.steam_id
//            ';
        return self::query($sql, $conditions);
    }

}
