<?php

/**
 * Balance stats
 */
class Balance {

    public static function getRoundResults() {
        $sql = '
            SELECT build AS name, IF(winner = 1, "Marines", "Aliens") AS serie, COUNT(round.id) AS count FROM round 
            WHERE 1=1 ' . Filter::addFilterConditions(false, false) . '
            GROUP BY winner, name
            ORDER BY winner, name
            ';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }

//    public static function getWeapons() {
//        $sql = 'SELECT build AS name, weapon.name AS serie, SUM(player_weapon.time) AS count FROM weapon
//            LEFT JOIN player_weapon ON player_weapon.weapon_id = weapon.id
//            LEFT JOIN player_round ON player_weapon.player_round_id = player_round.id
//            LEFT JOIN player ON player.id = player_round.player_id
//            LEFT JOIN round ON player_round.round_id = round.id
//            WHERE weapon.name != "none" ' . Filter::addFilterConditions(true) . '
//            GROUP BY weapon.id 
//            ORDER BY count DESC';
//        $connection = Yii::app()->db;
//        $command = $connection->createCommand($sql);
//        return $command->queryAll();
//    }

    public static function getKillsByTeam() {
        $sql = 'SELECT build AS name, IF(attacker_team = 1, "Marines", "Aliens") AS serie, COUNT(death.id) AS count
            FROM death          
            LEFT JOIN player_round ON death.attacker_id = player_round.id    
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            LEFT JOIN weapon ON death.attacker_weapon_id = weapon.id
            WHERE attacker_team IS NOT NULL ' . Filter::addFilterConditions(false, false) . '
            GROUP BY attacker_team, name
            ORDER BY attacker_team, name';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getTimePlayedByAlienLifeform() {
        $sql = '
                SELECT build AS name, lifeform.name AS serie, SUM(player_lifeform.end - player_lifeform.start) AS count FROM lifeform
                LEFT JOIN player_lifeform ON player_lifeform.lifeform_id = lifeform.id
                LEFT JOIN player_round ON player_lifeform.player_round_id = player_round.id
                LEFT JOIN player ON player.id = player_round.player_id
                LEFT JOIN round ON player_round.round_id = round.id
                WHERE player_lifeform.start < player_lifeform.end AND ' . Player::getAlienLifeforms() . ' ' . Filter::addFilterConditions(false, false) . '
                GROUP BY serie, name
                ORDER BY serie, name';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }
    
    public static function getAverageLifetimeByAlienLifeform() {
        $sql = 'SELECT build AS name, lifeform.name AS serie, AVG(death.target_lifetime) AS count
            FROM death          
            LEFT JOIN player_round ON death.attacker_id = player_round.id    
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            LEFT JOIN lifeform ON death.target_lifeform_id = lifeform.id
            WHERE attacker_team IS NOT NULL AND ' . Player::getAlienLifeforms() . ' ' . Filter::addFilterConditions(false, false) . '
            GROUP BY serie, name
            ORDER BY serie, name';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }
}