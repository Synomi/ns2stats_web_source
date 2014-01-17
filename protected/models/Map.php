<?php

/**
 * This is the model class for table "map".
 *
 * The followings are the available columns in table 'map':
 * @property string $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Round[] $rounds
 */
class Map extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Map the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'map';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'rounds' => array(self::HAS_MANY, 'Round', 'map_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public static function getIdByName($name) {
        $map = Map::model()->findByAttributes(array('name' => $name));
        if (isset($map))
            return $map->id;
        else {
            $map = new Map();
            $map->name = $name;
        }
        if ($map->save())
            return Yii::app()->db->getLastInsertID();
    }
    public static function getNameById($id) {
        $map = Map::model()->findByAttributes(array('id' => $$id));
        if (isset($map))
            return $map->name;
        else
            return null;
    }

    public static function getRoundsPlayedPerHour($id) {
        $sql = 'SELECT COUNT(round.id) AS count, round.end AS date FROM map
            LEFT JOIN round ON map.id = round.map_id
            WHERE map.id = :id ' . Filter::addFilterConditions() . '
            GROUP BY HOUR(FROM_UNIXTIME(round.end)), DAYOFYEAR(FROM_UNIXTIME(round.end)), YEAR(FROM_UNIXTIME(round.end))
            ORDER BY date';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getRoundResults($id) {
        $sql = '
            SELECT "Marines" AS name, COUNT(round.id) AS count
            FROM map
            LEFT JOIN round ON round.map_id = map.id
            WHERE map.id = :id AND round.winner = 1' . Filter::addFilterConditions() . '
            UNION
            SELECT "Aliens" AS name, COUNT(round.id) AS count
            FROM map
            LEFT JOIN round ON round.map_id = map.id
            WHERE map.id = :id AND round.winner = 2' . Filter::addFilterConditions() . '';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getRoundResultsByStartLocation($id, $team, $startLocation) {
        if ($team == 1 || $team == 2)
            $startLocationField = 'team_' . $team . '_start';
        $sql = '
            SELECT "Wins" AS name, COUNT(round.id) AS count
            FROM map
            LEFT JOIN round ON round.map_id = map.id
            WHERE map.id = :id AND round.winner = :team AND round.' . $startLocationField . ' = :startLocation ' . Filter::addFilterConditions() . '
            UNION
            SELECT "Losses" AS name, COUNT(round.id) AS count
            FROM map
            LEFT JOIN round ON round.map_id = map.id
            WHERE map.id = :id AND round.winner != :team AND round.' . $startLocationField . ' = :startLocation ' . Filter::addFilterConditions();
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        $command->bindParam(':team', $team);
        $command->bindParam(':startLocation', $startLocation);
        return $command->queryAll();
    }

    public static function getStartLocations($id, $team) {
        if ($team == 1 || $team == 2)
            $startLocationField = 'team_' . $team . '_start';
        $sql = '
            SELECT round. ' . $startLocationField . ' AS start_location
            FROM map
            LEFT JOIN round ON round.map_id = map.id
            WHERE map.id = :id ' . Filter::addFilterConditions() . '
            GROUP BY round.' . $startLocationField;

        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getServerList($id) {
        $sql = '
            SELECT server_id, round_id, server_name, round_end, round_added FROM (
            SELECT server.id AS server_id, round.id AS round_id, server.name AS server_name, round.end AS round_end, round.added AS round_added
            FROM round
            LEFT JOIN server ON server.id = round.server_id
            WHERE round.map_id = :id ' . Filter::addFilterConditions() . '
            ORDER BY end DESC) AS data
            GROUP BY server_id
            ORDER BY round_end DESC
            LIMIT 10';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getList() {
        $sql = '
            SELECT round_id, round_end, round_added, map_id, map_name, COUNT(round_id) AS times_played FROM (
                SELECT 
                    round.id AS round_id,
                    round.end AS round_end,
                    round.added AS round_added,
                    map.id AS map_id,
                    map.name AS map_name
                FROM map
                LEFT JOIN round ON round.map_id = map.id
                WHERE 1=1 ' . Filter::addFilterConditions() . '
                    ORDER BY round.added DESC
                    ) 
            AS data
            GROUP BY map_id
            ORDER BY round_added DESC';
        //doesnt work because there are invalid round.added times on db
//        $sql = '
//            SELECT round_id, round_end, round_added, map_id, map_name, COUNT(round_id) AS times_played FROM (
//                SELECT 
//                    round.id AS round_id,
//                    round.end AS round_end,
//                    round.added AS round_added,
//                    map.id AS map_id,
//                    map.name AS map_name
//                FROM map
//                LEFT JOIN round ON round.map_id = map.id
//                WHERE 1=1 ' . Filter::addFilterConditions() . '
//                    ORDER BY round.id DESC
//                    ) 
//            AS data
//            GROUP BY map_id
//            ORDER BY round_added DESC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }

    public static function getRoundLengths($id) {
        $sql = '
            SELECT IF(winner = 1, "Marines", "Aliens") AS name, COUNT(round_length) AS count, floor(round_length / (' . HighchartData::$timeDistributionFactor . ' * 60)) AS time FROM (
            SELECT 
            round.end - round.start AS round_length, winner
            FROM map
            LEFT JOIN round ON round.map_id = map.id
            WHERE map.id = :id' . Filter::addFilterConditions() . ') AS rounds
        GROUP BY winner, floor(round_length / (' . HighchartData::$timeDistributionFactor . ' * 60))';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getPlayedBuilds($id) {
        die('deprecated getPlayedBuilds');
        //too slow replaced with All:getBuilds() DEPRECATED
        $sql = 'SELECT round.build FROM round
            LEFT JOIN player_round ON round.id = player_round.round_id
            LEFT JOIN player ON player_round.player_id = player.id
            WHERE round.map_id = :id
            GROUP BY round.build
            ORDER BY round.build DESC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    //DEPRECATED
    public static function getPlayedServers($id) {
        die('deprecated too slow');
        $sql = 'SELECT server.id, server.name FROM server
            LEFT JOIN round ON round.server_id = server.id
            LEFT JOIN player_round ON round.id = player_round.round_id
            WHERE  round.build>240 AND round.map_id = :id 
            LIMIT 10';
        $connection = Yii::app()->db;
        $command = $connection->cache(60 * 60 * 1)->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

}