<?php

/**
 * This is the model class for table "team".
 *
 * The followings are the available columns in table 'team':
 * @property string $id
 * @property string $name
 * @property string $website
 * @property string $description
 *
 * The followings are the available model relations:
 * @property PlayerTeam[] $playerTeams
 */
class Team extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Team the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'team';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max' => 62),
            array('website', 'length', 'max' => 1024),
            array('description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, website, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'playerTeams' => array(self::HAS_MANY, 'PlayerTeam', 'team_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'website' => 'Website',
            'description' => 'Description',
        );
    }

    public static function getTeamsByPlayer($id) {
        $sql = 'SELECT team.* FROM team 
            LEFT JOIN player_team ON team.id = player_team.team_id
            WHERE player_team.player_id = :id AND role > 0';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getPlayers($id) {
        $sql = 'SELECT player.*, player.id AS player_id, player_team.role, player_team.id AS player_team_id FROM player_team 
            LEFT JOIN team ON team.id = player_team.team_id
            LEFT JOIN player ON player.id = player_team.player_id
            WHERE team.id = :id';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getList() {
        $sql = '
            SELECT round_id, MAX(round_end) AS round_end, team_id, team_name, COUNT(round_id) AS times_played FROM (
            SELECT 
            round.id AS round_id, round.end AS round_end, 
            team.id AS team_id, team.name AS team_name
            FROM team
            LEFT JOIN round ON round.team_1 = team.id
            WHERE 1=1 ' . Filter::addFilterConditions() . '
            UNION
            SELECT 
            round.id AS round_id, round.end AS round_end, 
            team.id AS team_id, team.name AS team_name
            FROM team
            LEFT JOIN round ON round.team_2 = team.id
            WHERE 1=1 ' . Filter::addFilterConditions() . ') AS data
            GROUP BY team_id
            ORDER BY round_end DESC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }

    public static function getRoundResults($id) {
        $sql = '
            SELECT name, SUM(count) AS count FROM (
            SELECT "Win" AS name, COUNT(round.id) AS count
            FROM team
            LEFT JOIN round ON round.team_1 = team.id
            WHERE team.id = :id AND round.winner = 1' . Filter::addFilterConditions() . '
            UNION
            SELECT "Win" AS name, COUNT(round.id) AS count
            FROM team
            LEFT JOIN round ON round.team_2 = team.id
            WHERE team.id = :id AND round.winner = 2' . Filter::addFilterConditions() . ') as wins
            UNION
            SELECT name, SUM(count) AS count FROM (
            SELECT "Loss" AS name, COUNT(round.id) AS count
            FROM team
            LEFT JOIN round ON round.team_1 = team.id
            WHERE team.id = :id AND round.winner != 1' . Filter::addFilterConditions() . '
            UNION
            SELECT "Loss" AS name, COUNT(round.id) AS count
            FROM team
            LEFT JOIN round ON round.team_2 = team.id
            WHERE team.id = :id AND round.winner != 2' . Filter::addFilterConditions() .') as losses';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getRoundsList($id) {
        $sql = '
            SELECT team_id, id, team_name, end, length FROM (
            SELECT team.id AS team_id, round.id, team.name AS team_name, round.end, round.end - round.start AS length
            FROM round
            LEFT JOIN team ON round.team_1 = team.id
            WHERE team.id = :id' . Filter::addFilterConditions() . '
            UNION
            SELECT team.id AS team_id, round.id, team.name AS team_name, round.end, round.end - round.start AS length
            FROM round
            LEFT JOIN team ON round.team_2 = team.id
            WHERE team.id = :id' . Filter::addFilterConditions() . ') AS rounds
            ORDER BY end DESC
            LIMIT 10';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }
    
        public static function getPlayedBuilds($id) {
        $sql = '
            SELECT build FROM (
            SELECT round.build
            FROM round
            LEFT JOIN team ON round.team_1 = team.id
            WHERE team.id = :id
            UNION
            SELECT round.build
            FROM round
            LEFT JOIN team ON round.team_2 = team.id
            WHERE team.id = :id) AS rounds
            GROUP BY build
            ORDER BY build DESC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getPlayedServers($id) {
        $sql = '
            SELECT name, id FROM (
            SELECT server.name, server.id
            FROM round
            LEFT JOIN team ON round.team_1 = team.id
            LEFT JOIN server ON round.server_id = server.id
            WHERE team.id = :id
            UNION
            SELECT server.name, server.id
            FROM round
            LEFT JOIN team ON round.team_2 = team.id
            LEFT JOIN server ON round.server_id = server.id
            WHERE team.id = :id) AS rounds
            GROUP BY id
            ORDER BY name DESC
        ';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

}