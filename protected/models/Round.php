<?php

/**
 * This is the model class for table "round".
 *
 * The followings are the available columns in table 'round':
 * @property string $id
 * @property integer $server_id
 * @property string $map_id
 * @property integer $end
 * @property integer $start
 * @property integer $winner
 * @property string $team_1_start
 * @property string $team_2_start
 * @property string $build
 * @property integer $private
 * @property string $team_1
 * @property string $team_2
 * @property integer $parse_status
 * @property string $log_file
 * @property string $added
 * 
 * The followings are the available model relations:
 * @property PlayerRound[] $playerRounds
 * @property Server $server
 * @property Map $map
 */
class Round extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Round the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'round';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('server_id, map_id, end, winner, log_file', 'required'),
            array('server_id, end, start, winner, private, team_1, team_2, parse_status', 'numerical', 'integerOnly' => true),
            array('map_id', 'length', 'max' => 10),
            array('team_1_start, team_2_start', 'length', 'max' => 128),
            array('build', 'length', 'max' => 64),
            array('parse_status', 'length', 'max' => 1),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, server_id, map_id, end, start, winner, team_1_start, team_2_start, build', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'playerRounds' => array(self::HAS_MANY, 'PlayerRound', 'round_id'),
            'server' => array(self::BELONGS_TO, 'Server', 'server_id'),
            'map' => array(self::BELONGS_TO, 'Map', 'map_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'server_id' => 'Server',
            'map_id' => 'Map',
            'end' => 'end',
            'start' => 'start',
            'winner' => 'Winner',
            'added' => 'Added',
            'team_1_start' => 'Team 1 Start',
            'team_2_start' => 'Team 2 Start',
            'build' => 'Build',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('server_id', $this->server_id);
        $criteria->compare('map_id', $this->map_id, true);
        $criteria->compare('end', $this->end);
        $criteria->compare('start', $this->start);
        $criteria->compare('winner', $this->winner);
        $criteria->compare('team_1_start', $this->team_1_start, true);
        $criteria->compare('team_2_start', $this->team_2_start, true);
        $criteria->compare('build', $this->build, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function getPlayersFromRound($id, $team)
    {

        $connection = Yii::app()->db; //,COUNT(death.id) AS kills
        $command = $connection->createCommand(
                'SELECT DISTINCT
           player.id as "id",
           player.steam_name as "steam_name",
           player_round.name as "name",
           player.steam_image as "steam_image",
           player_round.team as "team",
           player_round.end - player_round.start AS playtime,
           player_round.commander,
           ip,
           score,
           assists,
           country,
           COUNT(DISTINCT kills.id) AS kills,
           COUNT(DISTINCT deaths.id) AS deaths,
           player_round.id AS prid,
           player.hidden as hidden
           FROM player
           LEFT JOIN player_round ON player.id = player_round.player_id
           LEFT JOIN round ON player_round.round_id = round.id
           LEFT JOIN death AS kills ON player_round.id = kills.attacker_id
           LEFT JOIN death AS deaths ON player_round.id = deaths.target_id
           WHERE 
           round.id = :id AND
           player_round.team = :team
           GROUP BY player.id
           ORDER BY score DESC
            ');

        $command->bindParam(':id', $id);
        $command->bindParam(':team', $team);
        
        return $command->queryAll(); 
    }

    static function getMapNameByRoundId($id)
    {
        $connection = Yii::app()->db; //,COUNT(death.id) AS kills
        $command = $connection->createCommand(
                'SELECT map.name
           FROM round
           LEFT JOIN map ON map.id = round.map_id
           WHERE
           round.id = :id
            ');

        $command->bindParam(':id', $id);

        $result = $command->queryAll();

        return $result[0]['name'];
    }

    function getPlayersOnRoundForMinimap($id)
    {
        $connection = Yii::app()->db; //,COUNT(death.id) AS kills
        $command = $connection->createCommand(
                'SELECT 
               steam_name,steam_id,player.id,
               attacker_id,attacker_x,attacker_y,attacker_z,
               target_x,target_y,target_z,target_id
            FROM round
            LEFT JOIN player_round ON player_round.round_id = round_id
            LEFT JOIN death ON death.target_id = player_round.player_id
            LEFT JOIN player ON player.id = player_round.player_id
            WHERE round.id = :id
            '); //TODOO

        $command->bindParam(':id', $id);

        return $command->queryAll();
    }

    public static function getRTCount($id)
    {
        $sql = '
            SELECT time, value, name, round_length FROM (
            SELECT round_structure.build AS time, SUM(1) AS value, CONCAT(structure.name, "s") AS name, round.end - round.start AS round_length FROM round_structure
            LEFT JOIN structure ON round_structure.structure_id = structure.id
            LEFT JOIN round ON round_structure.round_id = round.id
            WHERE round.id = :id AND (structure.name = "Extractor" OR structure.name = "Harvester") AND round_structure.build IS NOT null
            GROUP BY time
            UNION
            SELECT round_structure.destroy AS time, SUM(-1) AS value, CONCAT(structure.name, "s") AS name, round.end - round.start AS round_length FROM round_structure
            LEFT JOIN structure ON round_structure.structure_id = structure.id
            LEFT JOIN round ON round_structure.round_id = round.id
            WHERE round.id = :id AND (structure.name = "Extractor" OR structure.name = "Harvester") AND round_structure.build IS NOT null
            GROUP BY time
            ) AS data
            GROUP BY time
            ORDER BY name ASC, time ASC 
            ';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getUpgradesByTeam($id, $team)
    {
        $sql = '
            SELECT upgrade.name, round_upgrade.time FROM round_upgrade
            LEFT JOIN upgrade ON round_upgrade.upgrade_id = upgrade.id
            LEFT JOIN round ON round_upgrade.round_id = round.id
            WHERE round.id = :id AND team = :team
            ORDER BY round_upgrade.time ASC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        $command->bindParam(':team', $team);
        return $command->queryAll();
    }

    public static function getResourcesUsedToBuildingsAndTech($id)
    {
        $sql = '
            SELECT team AS name, value, round_length, time, symbol
            FROM (
                SELECT upgrade.name AS symbol, round_upgrade.time AS time , round.end - round.start AS round_length, team, cost AS value FROM round_upgrade
                LEFT JOIN upgrade ON round_upgrade.upgrade_id = upgrade.id
                LEFT JOIN round ON round_upgrade.round_id = round.id
                WHERE 
                round.id = :id AND time IS NOT null AND 
                upgrade.name != "Recycle" AND
                upgrade.name NOT LIKE "%Egg%" AND 
                upgrade.name != "Drifter" AND
                upgrade.name != "ARC" AND 
                upgrade.name != "MAC" AND 
                upgrade.name != "EvolveBombard" 
                UNION
                SELECT structure.name AS symbol, round_structure.build AS time, round.end - round.start AS round_length, team, cost AS value
                FROM round_structure
                LEFT JOIN structure ON round_structure.structure_id = structure.id
                LEFT JOIN round ON round_structure.round_id = round.id
                WHERE round.id = :id AND 
                structure.name != "Cyst" AND 
                structure.name != "Harvester" AND 
                structure.name != "Extractor" AND 
                structure.name != "Sentry" AND 
                structure.name != "Whip" AND
                structure.name != "Crag" AND 
                structure.name != "Shift" AND 
                structure.name != "Shade" AND
                structure.name != "PowerPack" AND
                structure.name != "PhaseGate" AND
                structure.name != "SentryBattery" AND
                structure.name != "NutrientMist" AND
                round_structure.build IS NOT null
            ) as items
            ORDER BY name, time';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getBuildingsAndUpgradesByTeam($id, $team)
    {
        $sql = '
            SELECT name, time FROM (
            SELECT 1 AS ord, upgrade.name, round_upgrade.time FROM round_upgrade
            LEFT JOIN upgrade ON round_upgrade.upgrade_id = upgrade.id
            LEFT JOIN round ON round_upgrade.round_id = round.id
            WHERE round.id = :id AND team = :team AND time IS NOT null AND upgrade.name != "Recycle"
            UNION
            SELECT 2 AS ord, structure.name, round_structure.build AS time FROM round_structure
            LEFT JOIN structure ON round_structure.structure_id = structure.id
            LEFT JOIN round ON round_structure.round_id = round.id
            WHERE round.id = :id AND team = :team AND structure.name != "Cyst" AND round_structure.build IS NOT null) as items
            GROUP BY ord, time
            ORDER BY ord ASC, time ASC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        $command->bindParam(':team', $team);
        return $command->queryAll();
    }

    public static function getKillCount($id)
    {
        $sql = '
            SELECT ord, time, value, name, round_length FROM (
            SELECT 1 AS ord, death.time, 1 AS value, "Kills for Marines" AS name, round.end - round.start AS round_length FROM death
            LEFT JOIN player_round ON player_round.id = death.attacker_id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE round.id = :id AND death.attacker_team = 1 AND death.target_team = 2
            UNION
            SELECT 2 AS ord, death.time, 1 AS value, "Kills for Aliens" AS name, round.end - round.start AS round_length FROM death
            LEFT JOIN player_round ON player_round.id = death.attacker_id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE round.id = :id AND death.attacker_team = 2 AND death.target_team = 1
            ) AS data
            GROUP BY ord, time
            ORDER BY ord ASC, time ASC ';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        $command->bindParam(':team', $team);
        return $command->queryAll();
    }

    public static function getResourcesCount($id)
    {
        $sql = '
            SELECT ord, time, value, name, round_length FROM (
            SELECT 1 AS ord, resources.time, gathered AS value, "Resources for Marines" AS name, round.end - round.start AS round_length FROM resources
            LEFT JOIN round ON resources.round_id = round.id
            WHERE round.id = :id AND team = 1
            UNION
            SELECT 2 AS ord, resources.time, gathered AS value, "Resources for Aliens" AS name, round.end - round.start AS round_length FROM resources
            LEFT JOIN round ON resources.round_id = round.id
            WHERE round.id = :id AND team = 2
            ) AS data
            GROUP BY ord, time
            ORDER BY ord ASC, time ASC ';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        $command->bindParam(':team', $team);
        return $command->queryAll();
    }

    public static function getResourceDistributionCount($id, $team)
    {
        $sql = '
            SELECT name, time FROM (
            SELECT 1 AS ord, upgrade.name, round_upgrade.time FROM round_upgrade
            LEFT JOIN upgrade ON round_upgrade.upgrade_id = upgrade.id
            LEFT JOIN round ON round_upgrade.round_id = round.id
            WHERE round.id = :id AND team = :team AND time IS NOT null AND upgrade.name != "Recycle"
            UNION
            SELECT 2 AS ord, structure.name, round_structure.build AS time FROM round_structure
            LEFT JOIN structure ON round_structure.structure_id = structure.id
            LEFT JOIN round ON round_structure.round_id = round.id
            WHERE round.id = :id AND team = :team AND structure.name != "Cyst" AND round_structure.build IS NOT null) as items
            GROUP BY ord, time
            ORDER BY ord ASC, time ASC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        $command->bindParam(':team', $team);
        return $command->queryAll();
    }

    public function beforeDelete()
    {
        RoundUpgrade::model()->deleteAllByAttributes(array('round_id' => $this->id));
        RoundStructure::model()->deleteAllByAttributes(array('round_id' => $this->id));
        Resources::model()->deleteAllByAttributes(array('round_id' => $this->id));
        ModRound::model()->deleteAllByAttributes(array('round_id' => $this->id));
        $playerRounds = PlayerRound::model()->findAllByAttributes(array('round_id' => $this->id));
        foreach ($playerRounds as $playerRound)
        {
            PlayerWeapon::model()->deleteAllByAttributes(array('player_round_id' => $playerRound->id));
            PlayerLifeform::model()->deleteAllByAttributes(array('player_round_id' => $playerRound->id));
            Death::model()->deleteAllByAttributes(array('attacker_id' => $playerRound->id));
            Death::model()->deleteAllByAttributes(array('target_id' => $playerRound->id));
            RoundStructure::model()->deleteAllByAttributes(array('builder_id' => $playerRound->id));
            RoundStructure::model()->deleteAllByAttributes(array('attacker_id' => $playerRound->id));
            Hit::model()->deleteAllByAttributes(array('attacker_id' => $playerRound->id));
            Hit::model()->deleteAllByAttributes(array('target_id' => $playerRound->id));
            Pickable::model()->deleteAllByAttributes(array('commander_id' => $playerRound->id));
            $playerRound->delete();
        }
        return true;
    }

}