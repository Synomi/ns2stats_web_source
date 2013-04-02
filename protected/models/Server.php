<?php

/**
 * This is the model class for table "server".
 *
 * The followings are the available columns in table 'server':
 * @property string $id
 * @property string $name
 * @property string $ip
 * @property string $port
 * @property string $admin_id
 * @property string $server_key
 * @property integer $created
 * @property integer $stats_version
 * @property string $motd
 * @property integer $private
 *
 * The followings are the available model relations:
 * @property Round[] $rounds
 * @property Player $admin
 */
class Server extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Server the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'server';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('server_key, created', 'required'),
            array('created, private', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 128),
            array('ip', 'length', 'max' => 50),
            array('port', 'length', 'max' => 5),
            array('admin_id', 'length', 'max' => 10),
            array('server_key', 'length', 'max' => 32),
            array('stats_version', 'length', 'max' => 5),
            array('country', 'length', 'max' => 2),
            array('motd', 'length', 'max' => 240),
            array('id', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, ip, port, admin_id, server_key, motds, created', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'rounds' => array(self::HAS_MANY, 'Round', 'server_id'),
            'admin' => array(self::BELONGS_TO, 'Player', 'admin_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'ip' => 'Ip',
            'port' => 'Port',
            'admin_id' => 'Admin',
            'server_key' => 'Server Key',
            'created' => 'Created',
            'motd' => 'Message of the day',
            'stats_version' => 'Stats Version',
            'private' => 'Tournament Mode',
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
        $criteria->compare('ip', $this->ip, true);
        $criteria->compare('port', $this->port, true);
        $criteria->compare('admin_id', $this->admin_id, true);
        $criteria->compare('server_key', $this->server_key, true);
        $criteria->compare('motd', $this->motd, true);
        $criteria->compare('created', $this->created);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public static function getListByAdmin($id) {
        $criteria = new CDbCriteria;
        $criteria->select = 'COALESCE(name, "Unknown") AS name, 
            COALESCE(ip, "Unknown") AS ip,
            COALESCE(port, "Unknown") AS port,
            COALESCE(id, 0) AS id,
            COALESCE(stats_version, "Unknown") AS stats_version,            
            server_key,
            IF(private>0, "Enabled", "Disabled") AS private';
        $criteria->condition = 'admin_id = :admin_id';
        $criteria->order = 'created ASC';
        $criteria->params = array(
            ':admin_id' => $id,
        );

        return new CActiveDataProvider('Server', array(
                    'criteria' => $criteria,
                ));
    }

    public static function getRoundsList($id) {
        $sql = 'SELECT DISTINCT server.id AS server_id, round.id, server.name AS server_name, round.end, round.end - round.start AS length
            FROM round
            LEFT JOIN server ON server.id = round.server_id
            LEFT JOIN player_round ON round.id = player_round.round_id
            WHERE server.id = :id ' . Filter::addFilterConditions() . '
            ORDER BY end DESC
            LIMIT 10';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getPlayersPerHour($id) {
        $sql = '
            SELECT ROUND(AVG(count)) AS count, date FROM ( 
            SELECT COUNT(DISTINCT player_round.player_id) AS count, round.end AS date, round.id FROM round
            LEFT JOIN player_round ON round.id = player_round.round_id
            WHERE server_id = :server_id AND player_round.finished = 1 ' . Filter::addFilterConditions() . '
            GROUP BY round.id, HOUR(FROM_UNIXTIME(round.end)), DAYOFYEAR(FROM_UNIXTIME(round.end)), YEAR(FROM_UNIXTIME(round.end))
            ORDER BY date) as rounds
            GROUP BY HOUR(FROM_UNIXTIME(date)), DAYOFYEAR(FROM_UNIXTIME(date)), YEAR(FROM_UNIXTIME(date))
            ORDER BY date
            ';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':server_id', $id);
        return $command->queryAll();
    }

    public static function getMaps($id) {
        $sql = 'SELECT map.id, map.name, COUNT(map.id) AS count FROM round
            LEFT JOIN map ON round.map_id = map.id
            WHERE server_id = :server_id ' . Filter::addFilterConditions() . '
            GROUP BY map.id
            ORDER BY count DESC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':server_id', $id);
        return $command->queryAll();
    }

    public static function getList() {
        $sql = '
            SELECT 
            server.id, server.name, server.ip, server.port,
            UNIX_TIMESTAMP(server.last_updated) as lastGame,
            last_map,last_player_count,
            round.id AS round_id, round.end AS round_end, 
            map.id AS map_id, map.name AS map_name,
            player.id AS admin_id, player.steam_name AS admin_name, server.country
            FROM server
            LEFT JOIN round ON round.server_id = server.id
            LEFT JOIN map ON round.map_id = map.id
            LEFT JOIN player ON server.admin_id = player.id
            WHERE 1=1 ' . Filter::addFilterConditions() . '            
            GROUP BY server.id
            ORDER BY server.last_updated DESC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }

    public static function getPlayedBuilds($id) {
        $sql = 'SELECT round.build FROM round
            LEFT JOIN player_round ON round.id = player_round.round_id
            LEFT JOIN player ON player_round.player_id = player.id
            WHERE round.server_id = :id
            GROUP BY round.build
            ORDER BY round.build DESC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

}