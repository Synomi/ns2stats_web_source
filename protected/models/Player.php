<?php

/**
 * This is the model class for table "player".
 *
 * The followings are the available columns in table 'player':
 * @property string $id
 * @property string $steam_id
 * @property string $steam_name
 * @property string $steam_url
 * @property string $steam_image
 * @property integer $group
 * @property string $bio
 * @property string $ip
 * @property boolean $hidden
 * @property string $code
 * @property string $ranking
 * @property string $rating
 * @property integer $kill_elo_rating
 * @property integer $win_elo_rating
 * @property integer $commander_elo_rating
 * @property integer $marine_win_elo
 * @property integer $alien_win_elo
 * @property integer $marine_commander_elo
 * @property integer $alien_commander_elo
 * 
 * The followings are the available model relations:
 * @property PlayerRound[] $playerRounds
 * @property Server[] $servers
 */
class Player extends CActiveRecord
{

    public $score;
    public $rounds_played;
    public $kills;
    public $commander;
    public $name;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Player the static model class
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
        return 'player';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
        return array(
            array('steam_id', 'length', 'max' => 100),
            array('steam_name', 'length', 'max' => 45),
            array('country', 'length', 'max' => 2),
            array('ip', 'length', 'max' => 50),
            array('steam_url, steam_image', 'length', 'max' => 256),
            array('bio', 'safe'),
            array('hidden', 'boolean'),
            array('group, code, kill_elo_rating, win_elo_rating, commander_elo_rating, marine_win_elo, alien_win_elo, marine_commander_elo, alien_commander_elo', 'length', 'max' => 6),
            array('ranking, rating, kill_elo_rating, win_elo_rating,  commander_elo_rating, marine_win_elo, alien_win_elo, marine_commander_elo, alien_commander_elo', 'numerical', 'integerOnly' => true),
            array('rating', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
// Please remove those attributes that should not be searched.
            array('id, steam_id, steam_name, steam_url, steam_image, group, bio, hidden, code, ranking, rating', 'safe', 'on' => 'search'),
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
            'playerRounds' => array(self::HAS_MANY, 'PlayerRound', 'player_id'),
            'servers' => array(self::HAS_MANY, 'Server', 'admin_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'steam_id' => 'Steam',
            'steam_name' => 'Steam Name',
            'steam_url' => 'Steam Url',
            'steam_image' => 'Steam Image',
            'group' => 'Group',
            'bio' => 'Bio',
            'hidden' => 'Hidden',
            'code' => 'code',
            'ranking' => 'ranking',
            'rating' => 'rating',
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
        $criteria->compare('steam_id', $this->steam_id, true);
        $criteria->compare('steam_name', $this->steam_name, true);
        $criteria->compare('steam_url', $this->steam_url, true);
        $criteria->compare('steam_image', $this->steam_image, true);
        $criteria->compare('group', $this->group);
        $criteria->compare('bio', $this->bio, true);
        $criteria->compare('hidden', $this->hidden, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('ranking', $this->ranking, true);
        $criteria->compare('rating', $this->rating, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function getGroupName($group)
    {
        switch ($group)
        {
            case 0:
                return "Player";
                break;
            case 1:
                return "Referee";
                break;
            case 2:
                return "RefereeAdmin";
                break;
            case 3:
                return "Moderator";
                break;
            case 4:
                return "Admin";
                break;
            case 100:
                return "SuperAdmin";
                break;
            default:
                return "Undefined";
                break;
        }
    }

    public static function getIdBySteamId($steamId, $ip = null)
    {
        $steamId = round($steamId);
        if (!is_numeric($steamId))
            return false;
        $player = Player::model()->findByAttributes(array('steam_id' => '' . $steamId));
        if (isset($player))
            return $player->id;
        else
        {
            $player = new Player();
            $player->steam_id = $steamId;
            $player->getSteamApiData();
        }
        $player->save();
        return Yii::app()->db->getLastInsertID();
    }

    public function getSteamApiData()
    {
        $steamData = SteamApi::getPlayerSummary($this->steam_id);
        $this->steam_name = $steamData['personaname'];
        $this->steam_url = $steamData['profileurl'];
        $this->steam_image = $steamData['avatarfull'];
    }

    public static function getPlayers($namePhrase)
    {
        if (!isset($namePhrase) || $namePhrase == '')
            return array();
        $sql = '
            SELECT 
            player.steam_name AS name, 
            player.id            
            FROM player
            LEFT JOIN player_round ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id            
            WHERE player.steam_name LIKE :namePhrase2 OR player_round.name LIKE :namePhrase2
            GROUP BY player.id
            ORDER BY name DESC
            LIMIT 10';
        /* kestää yli 5 sekuntia pitkään tää kysely:
          $sql = '
          SELECT
          player.steam_name AS name,
          player.id,
          SUM(DISTINCT player_round.score) AS score,
          COUNT(DISTINCT kills.id) AS kills,
          COUNT(DISTINCT deaths.id) AS deaths,
          COUNT(DISTINCT kills.id) / COUNT(DISTINCT deaths.id) AS kpd
          FROM player
          LEFT JOIN player_round ON player.id = player_round.player_id
          LEFT JOIN round ON player_round.round_id = round.id
          LEFT JOIN death AS kills ON player_round.id = kills.attacker_id
          LEFT JOIN death AS deaths ON player_round.id = deaths.target_id
          WHERE player.steam_name LIKE :namePhrase2 OR player_round.name LIKE :namePhrase2
          GROUP BY player.id
          ORDER BY name DESC
          LIMIT 35'; */
        //$connection = Yii::app()->db;
        //$command = $connection->createCommand($sql);
        $namePhrase = '%' . $namePhrase . '%';
        //$command->bindParam(':namePhrase2', $namePhrase);

        $rows = Yii::app()->db->cache(60 * 60, null)->createCommand($sql)->bindParam(':namePhrase2', $namePhrase)->queryAll();
        //$rows = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryAll();
        return $rows;
    }

    public static function getMaxRank()
    {
        $sql = 'SELECT max(ranking) as m FROM player';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        return $result[0]["m"];
    }

    public static function getMaps($id)
    {
        $sql = 'SELECT map.id, map.name, COUNT(map.id) AS count FROM player
            LEFT JOIN player_round ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id
            LEFT JOIN map ON round.map_id = map.id
            WHERE player.id = :id ' . Filter::addFilterConditions(true) . '
            GROUP BY map.id 
            ORDER BY count DESC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getRoundResults($id, $team = null)
    {
        $sql = '
            SELECT "Wins" as name, COUNT(count) AS count FROM (
            SELECT COUNT(round.id) AS count, 
            SUM(player_round.end - player_round.start) AS playertime,
            SUM(round.end - round.start) AS roundtime
            FROM round
            LEFT JOIN player_round ON round.id = player_round.round_id
            LEFT JOIN player ON player_round.player_id = player.id
            WHERE player.id = :id AND round.winner = player_round.team ';
        if ($team)
            $sql .= ' AND player_round.team = :team';
        $sql .= Filter::addFilterConditions(true) . '
            GROUP BY round.id) AS wins
            WHERE playertime > 0.5 * roundtime
            UNION
            SELECT "Losses" as name, COUNT(count) AS count FROM (
            SELECT COUNT(round.id) AS count, 
            SUM(player_round.end - player_round.start) AS playertime,
            SUM(round.end - round.start) AS roundtime
            FROM round
            LEFT JOIN player_round ON round.id = player_round.round_id
            LEFT JOIN player ON player_round.player_id = player.id
            WHERE player.id = :id AND round.winner != player_round.team ';
        if ($team)
            $sql .= ' AND player_round.team = :team';
        $sql .= Filter::addFilterConditions(true) . '
            GROUP BY round.id) AS losses
            WHERE playertime > 0.5 * roundtime';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        if ($team)
            $command->bindParam(':team', $team);
        return $command->queryAll();
    }

    public static function getTimePlayedByAlienLifeform($id, $includeCommander = false)
    {
        $sql = 'SELECT lifeform.name, SUM(player_lifeform.end - player_lifeform.start) AS count FROM lifeform
            LEFT JOIN player_lifeform ON player_lifeform.lifeform_id = lifeform.id
            LEFT JOIN player_round ON player_lifeform.player_round_id = player_round.id
            LEFT JOIN player ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id ' . Filter::addFilterConditions(true) . ' AND (' . self::getAlienLifeforms();
        if ($includeCommander)
            $sql .= ' OR lifeform.name = "alien_commander") ';
        else
            $sql .= ') ';
        $sql .= 'GROUP BY lifeform.id 
            ORDER BY count DESC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getTimePlayedByMarineLifeform($id, $includeCommander = false)
    {
        $sql = 'SELECT lifeform.name, SUM(DISTINCT player_lifeform.end - player_lifeform.start) AS count FROM lifeform
            LEFT JOIN player_lifeform ON player_lifeform.lifeform_id = lifeform.id
            LEFT JOIN player_round ON player_lifeform.player_round_id = player_round.id
            LEFT JOIN player ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player_lifeform.end > player_lifeform.start AND player.id = :id ' . Filter::addFilterConditions(true) . ' AND (' . self::getMarineLifeforms();

        if ($includeCommander)
            $sql .= ' OR lifeform.name = "marine_commander") ';
        else
            $sql .= ') ';
        $sql .= 'GROUP BY lifeform.id 
            ORDER BY count DESC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getWeapons($id, $team)
    {
        $sql = 'SELECT weapon.name, SUM(player_weapon.time) AS count FROM weapon
            LEFT JOIN player_weapon ON player_weapon.weapon_id =weapon.id
            LEFT JOIN player_round ON player_weapon.player_round_id = player_round.id
            LEFT JOIN player ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id AND player_round.team = :team AND weapon.name != "none" ' . Filter::addFilterConditions(true) . '
            GROUP BY weapon.id 
            ORDER BY count DESC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        $command->bindParam(':team', $team);
        return $command->queryAll();
        return array();
    }

    public static function getKillsByWeapon($id, $team)
    {
        $sql = 'SELECT weapon.name, COUNT(DISTINCT death.id) AS count FROM death
            LEFT JOIN player_round ON death.attacker_id = player_round.id
            LEFT JOIN player_weapon ON player_weapon.player_round_id = player_round.id
            LEFT JOIN weapon ON death.attacker_weapon_id = weapon.id
            LEFT JOIN player ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id AND player_round.team = :team AND weapon.name != "none" ' . Filter::addFilterConditions(true) . '
            GROUP BY weapon.id 
            ORDER BY count DESC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        $command->bindParam(':team', $team);
        return $command->queryAll();
    }

    public static function getTeams($id)
    {
        $sql = '            
            SELECT "Marines" as name, COUNT(id) AS count FROM (
            SELECT round.id, SUM(player_round.end - player_round.start) AS playertime,
            SUM(round.end - round.start) AS roundtime
            FROM round
            LEFT JOIN player_round ON round.id = player_round.round_id
            LEFT JOIN player ON player_round.player_id = player.id
            WHERE player.id = :id AND player_round.team = 1 ' . Filter::addFilterConditions(true) . '
            GROUP BY round.id) AS marines
            WHERE playertime > 0.5 * roundtime
            UNION
            SELECT "Aliens" as name, COUNT(id) AS count FROM (
            SELECT round.id, SUM(player_round.end - player_round.start) AS playertime,
            SUM(round.end - round.start) AS roundtime
            FROM round
            LEFT JOIN player_round ON round.id = player_round.round_id
            LEFT JOIN player ON player_round.player_id = player.id
            WHERE player.id = :id AND player_round.team = 2 ' . Filter::addFilterConditions(true) . ' 
            GROUP BY round.id) AS aliens
            WHERE playertime > 0.5 * roundtime';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getTeamsTime($id)
    {
        $sql = 'SELECT "Marines" AS name, SUM(player_round.end - player_round.start) AS count FROM player_round
            LEFT JOIN player ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id AND player_round.team = 1 ' . Filter::addFilterConditions(true) . '
            GROUP BY player.id 
            UNION
            SELECT "Aliens" AS name, SUM(player_round.end - player_round.start) AS count FROM player_round
            LEFT JOIN player ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id AND player_round.team = 2 ' . Filter::addFilterConditions(true) . '
            GROUP BY player.id';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getRoundsPlayedPerDay($id)
    {
        $filter = new Filter();
        $filter->loadFromSession();
        $filter->loadDefaults();
        $sql = 'SELECT COUNT(round.id) AS count, round.end AS date FROM player
            LEFT JOIN player_round ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id ' . Filter::addFilterConditions(true) . '
            GROUP BY ';
        if (strtotime($filter->endDate) - strtotime($filter->startDate) <= 7 * 24 * 3600)
            $sql .= 'HOUR(FROM_UNIXTIME(round.end)), ';
        $sql .= 'DAYOFYEAR(FROM_UNIXTIME(round.end)), YEAR(FROM_UNIXTIME(round.end))
            ORDER BY date';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getRoundsList($id)
    {
        $sql = 'SELECT server.id AS server_id, round.id, server.name AS server_name, round.end, round.end - round.start AS length, round.added
            FROM round
            LEFT JOIN server ON server.id = round.server_id
            LEFT JOIN player_round ON round.id = player_round.round_id
            WHERE parse_status IN(0,1,3,4) AND player_round.player_id = :id ' . Filter::addFilterConditions(true) . '
            ORDER BY round.added DESC,round.id DESC
            LIMIT 10';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    /**
     * Round results for a player who has been one type of lifeform over 50 % of the game. Useful for getting commander stats.
     * @param type $id
     * @param type $lifeform
     * @return type 
     */
    public static function getLifeformRoundResults($id, $lifeform)
    {
        $sql = '
            SELECT "Wins" as name, COUNT(count) AS count FROM (
            SELECT 1 AS count, SUM(player_lifeform.end - player_lifeform.start) AS lifeformtime,
            round.end - round.start AS roundtime
            FROM lifeform
            LEFT JOIN player_lifeform ON player_lifeform.lifeform_id = lifeform.id            
            LEFT JOIN player_round ON player_lifeform.player_round_id = player_round.id    
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON round.id = player_round.round_id
            WHERE player.id = :id AND round.winner = player_round.team AND 
            lifeform.name = :lifeform' . Filter::addFilterConditions(true) . '
            GROUP BY round.id) AS wins
            WHERE lifeformtime > 0.5 * roundtime
            UNION
            SELECT "Losses" as name, COUNT(count) AS count FROM (
            SELECT 1 AS count, SUM(player_lifeform.end - player_lifeform.start) AS lifeformtime,
            round.end - round.start AS roundtime
            FROM lifeform
            LEFT JOIN player_lifeform ON player_lifeform.lifeform_id = lifeform.id            
            LEFT JOIN player_round ON player_lifeform.player_round_id = player_round.id    
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON round.id = player_round.round_id
            WHERE player.id = :id AND round.winner != player_round.team AND 
            lifeform.name = :lifeform' . Filter::addFilterConditions(true) . '
            GROUP BY round.id) AS losses
            WHERE lifeformtime > 0.5 * roundtime';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        $command->bindParam(':lifeform', $lifeform);
        return $command->queryAll();
    }

    public static function getRoundsPlayed($id)
    {
        $sql = 'SELECT COUNT(DISTINCT id) AS count FROM (
            SELECT round.id, SUM(player_round.end - player_round.start) AS playertime,
            round.end - round.start AS roundtime
            FROM player
            LEFT JOIN player_round ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id' . Filter::addFilterConditions(true) . '
            GROUP BY round.id
            ) AS rounds
            WHERE playertime > 0.5 * roundtime
            ';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryScalar();
    }

    public static function getScore($id)
    {
        $sql = 'SELECT SUM(DISTINCT player_round.score) AS count FROM player
            LEFT JOIN player_round ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id' . Filter::addFilterConditions(true) . '';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryScalar();
    }

    public static function getKillsById($id, $team = null)
    {
        $sql = 'SELECT COUNT(death.id) AS count
            FROM death          
            LEFT JOIN player_round ON death.attacker_id = player_round.id    
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            LEFT JOIN weapon ON death.attacker_weapon_id = weapon.id
            WHERE player.id = :id AND weapon.name != "self" AND weapon.name != "naturalcauses"';
        if ($team)
            $sql .= ' AND attacker_team = :team ';
        $sql .= Filter::addFilterConditions(true) . '';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        if ($team)
            $command->bindParam(':team', $team);
        return $command->queryScalar();
    }

    public static function getKillsByPlayerIdAndRoundId($id, $roundId)
    {
        $sql = 'SELECT COUNT(death.id) AS count
            FROM death
            LEFT JOIN player_round ON death.attacker_id = player_round.id
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            LEFT JOIN weapon ON death.attacker_weapon_id = weapon.id
            WHERE player.id = :id and player_round.id = :roundId
            AND weapon.name != "self" AND weapon.name != "naturalcauses"'; //!=self
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        $command->bindParam(':roundId', $roundId);
        return $command->queryScalar();
    }

    public static function getDeaths($id, $team = null)
    {
        $sql = 'SELECT COUNT(death.id) AS count
            FROM death          
            LEFT JOIN player_round ON death.target_id = player_round.id    
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id';
        if ($team)
            $sql .= ' AND attacker_team = :team ';
        $sql .= Filter::addFilterConditions(true);
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        if ($team)
            $command->bindParam(':team', $team);
        return $command->queryScalar();
    }

    public static function getDeathsByPlayerIdAndRoundId($id, $roundId)
    {
        $sql = 'SELECT COUNT(death.id) AS count
            FROM death
            LEFT JOIN player_round ON death.target_id = player_round.id
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            LEFT JOIN weapon ON death.attacker_weapon_id = weapon.id
            WHERE player.id = :id and player_round.id = :roundId';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        $command->bindParam(':roundId', $roundId);
        return $command->queryScalar();
    }

    public static function getKD($id)
    {
        $sql = '
            SELECT SUM(kills) / SUM(deaths) AS kd FROM (
            SELECT COUNT(death.id) AS kills, 0 AS deaths
            FROM death          
            LEFT JOIN player_round ON death.attacker_id = player_round.id    
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            LEFT JOIN weapon ON death.attacker_weapon_id = weapon.id
            WHERE player.id = :id AND weapon.name != "self" AND weapon.name != "naturalcauses"' . Filter::addFilterConditions(true) . '
            UNION
            SELECT 0 AS kills, COUNT(death.id) AS deaths
            FROM death          
            LEFT JOIN player_round ON death.target_id = player_round.id    
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id' . Filter::addFilterConditions(true) . ') AS kd';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryScalar();
    }

    public static function getSD($id)
    {
        $sql = '
            SELECT SUM(score) / SUM(deaths) AS sd FROM (
            SELECT SUM(DISTINCT player_round.score) AS score, 0 AS deaths
            FROM  player_round  
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id' . Filter::addFilterConditions(true) . '
            UNION
            SELECT 0 AS score, COUNT(DISTINCT death.id) AS deaths
            FROM death          
            LEFT JOIN player_round ON death.target_id = player_round.id    
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id' . Filter::addFilterConditions(true) . ') AS kd';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryScalar();
    }

    public static function getSM($id)
    {
        $sql = '
            SELECT SUM(DISTINCT score) / SUM(player_round.end - player_round.start) * 60
            FROM player_round  
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id' . Filter::addFilterConditions(true);
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryScalar();
    }

    public static function getTimesPlayedByAlienLifeform($id)
    {
        $sql = 'SELECT lifeform.name AS name, COUNT(player_round.id) AS count FROM player
            LEFT JOIN player_round ON player.id = player_round.player_id
            LEFT JOIN player_lifeform ON player_lifeform.player_round_id = player_round.id
            LEFT JOIN lifeform ON player_lifeform.lifeform_id = lifeform.id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id ' . Filter::addFilterConditions(true) . ' AND ' . self::getAlienLifeforms() . 'GROUP BY lifeform.id';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return self::toAssociativeArray($command->queryAll());
    }

//    public static function getScoreByLifeform($id) {
//        $sql = 'SELECT DISTINCT lifeform.name AS name, SUM(player_round.score) AS count FROM player
//            LEFT JOIN player_round ON player.id = player_round.player_id
//            LEFT JOIN player_lifeform ON player_lifeform.player_round_id = player_round.id
//            LEFT JOIN lifeform ON player_lifeform.lifeform_id = lifeform.id
//            LEFT JOIN round ON player_round.round_id = round.id
//            WHERE player.id = :id AND ' . self::getAlienLifeforms() . '
//            GROUP BY lifeform.id';
//        $connection = Yii::app()->db;
//        $command = $connection->createCommand($sql);
//        $command->bindParam(':id', $id);
//        return self::toAssociativeArray($command->queryAll());
//    }

    public static function getKillsByLifeform($id)
    {
        $sql = 'SELECT lifeform.name AS name, COUNT(DISTINCT death.id) AS count
            FROM death          
            LEFT JOIN player_round ON death.attacker_id = player_round.id
            LEFT JOIN player_lifeform ON player_lifeform.player_round_id = player_round.id
            LEFT JOIN lifeform ON player_lifeform.lifeform_id = lifeform.id
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            LEFT JOIN weapon ON death.attacker_weapon_id = weapon.id
            WHERE player.id = :id AND weapon.name != "self" AND weapon.name != "naturalcauses"' . Filter::addFilterConditions(true) . ' AND ' . self::getAlienLifeforms() . '
            GROUP BY lifeform.id';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return self::toAssociativeArray($command->queryAll());
    }

    public static function getDeathsByLifeform($id)
    {
        $sql = 'SELECT lifeform.name AS name, COUNT(DISTINCT death.id) AS count
            FROM death          
            LEFT JOIN player_round ON death.target_id = player_round.id 
            LEFT JOIN player_lifeform ON player_lifeform.player_round_id = player_round.id
            LEFT JOIN lifeform ON player_lifeform.lifeform_id = lifeform.id
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id ' . Filter::addFilterConditions(true) . ' AND ' . self::getAlienLifeforms() . '
            GROUP BY lifeform.id';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return self::toAssociativeArray($command->queryAll());
    }

    public static function getKDByLifeform($id)
    {
        $sql = '
            SELECT name AS name, SUM(kills) / SUM(deaths) AS count FROM (
            SELECT lifeform.name AS name, COUNT(DISTINCT death.id) AS kills, 0 AS deaths
            FROM death          
            LEFT JOIN player_round ON death.attacker_id = player_round.id
            LEFT JOIN player_lifeform ON player_lifeform.player_round_id = player_round.id
            LEFT JOIN lifeform ON player_lifeform.lifeform_id = lifeform.id
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id ' . Filter::addFilterConditions(true) . ' AND ' . self::getAlienLifeforms() . '
            GROUP BY lifeform.id
            UNION
            SELECT lifeform.name AS name, 0 AS kills, COUNT(DISTINCT death.id) AS deaths
            FROM death          
            LEFT JOIN player_round ON death.target_id = player_round.id   
            LEFT JOIN player_lifeform ON player_lifeform.player_round_id = player_round.id
            LEFT JOIN lifeform ON player_lifeform.lifeform_id = lifeform.id
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id ' . Filter::addFilterConditions(true) . ' AND ' . self::getAlienLifeforms() . '
            GROUP BY lifeform.id) AS kd
            GROUP BY name';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return self::toAssociativeArray($command->queryAll());
    }

//    public static function getSDByLifeform($id) {
//        $sql = '
//            SELECT name AS name, SUM(score) / SUM(deaths) AS count FROM (
//            SELECT lifeform.name AS name, SUM(player_round.score) AS score, 0 AS deaths
//            FROM  player_round  
//            LEFT JOIN player ON player_round.player_id = player.id
//            LEFT JOIN player_lifeform ON player_lifeform.player_round_id = player_round.id
//            LEFT JOIN lifeform ON player_lifeform.lifeform_id = lifeform.id
//            WHERE player.id = :id AND ' . self::getAlienLifeforms() . '
//            GROUP BY lifeform.id
//            UNION
//            SELECT lifeform.name AS name, 0 AS score, COUNT(death.id) AS deaths
//            FROM death          
//            LEFT JOIN player_round ON death.target_id = player_round.id
//            LEFT JOIN player_lifeform ON player_lifeform.player_round_id = player_round.id
//            LEFT JOIN lifeform ON player_lifeform.lifeform_id = lifeform.id
//            LEFT JOIN player ON player_round.player_id = player.id
//            WHERE player.id = :id AND ' . self::getAlienLifeforms() . '
//            GROUP BY lifeform.id) AS sd
//            GROUP BY name';
//        $connection = Yii::app()->db;
//        $command = $connection->createCommand($sql);
//        $command->bindParam(':id', $id);
//        return self::toAssociativeArray($command->queryAll());
//    }
//
//    public static function getSMByLifeform($id) {
//        $sql = '
//            SELECT lifeform.name AS name, SUM(score) / SUM(player_round.end - player_round.start) * 60 AS count
//            FROM player_round  
//            LEFT JOIN player ON player_round.player_id = player.id
//            LEFT JOIN player_lifeform ON player_lifeform.player_round_id = player_round.id
//            LEFT JOIN lifeform ON player_lifeform.lifeform_id = lifeform.id
//            WHERE player.id = :id AND ' . self::getAlienLifeforms() . '
//            GROUP BY lifeform.id';
//        $connection = Yii::app()->db;
//        $command = $connection->createCommand($sql);
//        $command->bindParam(':id', $id);
//        return self::toAssociativeArray($command->queryAll());
//    }

    public static function getNickList($id)
    {
        $sql = 'SELECT DISTINCT player_round.name
            FROM player_round
            LEFT JOIN player ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player_round.player_id = :id ' . Filter::addFilterConditions(true) . '
            ORDER BY round.end DESC
            LIMIT 10';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        $nicks = $command->queryAll();
        foreach ($nicks as &$nick)
        {
            $nick['name'] = htmlspecialchars($nick['name']);
        }
        return $nicks;
    }

    public static function getTimePlayed($id)
    {
        $sql = 'SELECT SUM(player_round.end - player_round.start) AS count FROM player_round
            LEFT JOIN player ON player.id = player_round.player_id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id' . Filter::addFilterConditions(true) . '
            GROUP BY player.id';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryScalar();
    }

    public static function getKillStreak($id)
    {
        $sql = '
            SELECT * FROM (            
            SELECT death.id AS player_id, player_round.id, death.target_id, death.time
            FROM death 
            LEFT JOIN player_round ON death.attacker_id = player_round.id    
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            LEFT JOIN weapon ON death.attacker_weapon_id = weapon.id
            LEFT JOIN mod_round ON mod_round.round_id = round.id
            WHERE player.id = :id AND weapon.name != "self" AND weapon.name != "naturalcauses"' . Filter::addFilterConditions(true) . '
            UNION
            SELECT death.id AS player_id, player_round.id, death.target_id, death.time
            FROM death          
            LEFT JOIN player_round ON death.target_id = player_round.id    
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            LEFT JOIN mod_round ON mod_round.round_id = round.id
            WHERE player.id = :id' . Filter::addFilterConditions(true) . ') AS data
            ORDER BY id ASC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        $deaths = $command->queryAll();
        if (isset($deaths[0]))
        {
            $playerRound = $deaths[0]['id'];
            $streak = 0;
            $longestStreak = 0;
            foreach ($deaths as $death)
            {
                if ($death['id'] == $playerRound && $death['target_id'] != $death['id'])
                    $streak++;
                else
                    $streak = 0;
                if ($streak > $longestStreak)
                    $longestStreak = $streak;
                $playerRound = $death['id'];
            }
            return $longestStreak;
        }
        else
            return 0;
    }

    public static function getLongestSurvival($id)
    {
        $sql = '
            SELECT id, died, born, comm_start, comm_end, player_round_start, player_round_end FROM (
            SELECT player_lifeform.id AS player_lifeform_id, player_round.id, player_lifeform.start AS died, player_lifeform.end AS born, 0 as comm_start, 0 as comm_end, player_round.start AS player_round_start, player_round.end AS player_round_end
            FROM player_round   
            LEFT JOIN player_lifeform ON player_lifeform.player_round_id = player_round.id
            LEFT JOIN lifeform ON player_lifeform.lifeform_id = lifeform.id
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id AND lifeform.name = "dead"' . Filter::addFilterConditions(true) . '
            UNION
            SELECT player_lifeform.id AS player_lifeform_id, player_round.id, 0 AS died, 0 AS born, player_lifeform.start AS comm_start, player_lifeform.end AS comm_end, player_round.start AS player_round_start, player_round.end AS player_round_end
            FROM player_round   
            LEFT JOIN player_lifeform ON player_lifeform.player_round_id = player_round.id
            LEFT JOIN lifeform ON player_lifeform.lifeform_id = lifeform.id
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id ' . Filter::addFilterConditions(true) . ' AND (lifeform.name = "alien_commander" OR lifeform.name = "marine_commander")) data
            ORDER BY id, player_lifeform_id';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        $deaths = $command->queryAll();
        $playerRound = 0;
        $survival = 0;
        $longestSurvival = 0;
        $born = 0;
        foreach ($deaths as $death)
        {
            //Same round
//            echo '<pre>';
            if ($death['id'] == $playerRound)
            {
                if ($death['born'])
                {
                    $survival = $survival + $death['died'] - $born;
                    $born = $death['born'];
                }
                else if ($death['comm_start'])
                {
                    $survival = $survival - ($death['comm_end'] - $death['comm_start']);
                }
            }
            //New round
            else
            {
                $playerRound = $death['id'];
                if ($death['player_round_start'] == 0)
                    $survival = $death['died'];
                $born = $death['born'];
//                $survival = $death['player_round_end'] - $born;
            }
            if ($survival > $longestSurvival)
                $longestSurvival = $survival;
            if ($death['id'] == $playerRound)
                if ($death['born'])
                    $survival = 0;
        }
        return $longestSurvival;
    }

    public static function getHiveUpgrades($id)
    {

        $sql = '
            SELECT name, COUNT(round_id) AS count FROM (
            SELECT round.id AS round_id, upgrade.name, upgrade.id AS upgrade_id, MIN(round_upgrade.time) AS time FROM round_upgrade 
            LEFT JOIN player_round ON round_upgrade.commander_id = player_round.id
            LEFT JOIN upgrade ON round_upgrade.upgrade_id = upgrade.id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE round_upgrade.commander_id = player_round.id AND player_round.player_id = :id ' . Filter::addFilterConditions(true) . ' AND (
                upgrade.name = "UpgradeCarapaceShell" OR 
                upgrade.name = "UpgradeRegenerationShell" OR 
                upgrade.name = "UpgradeSilenceVeil" OR
                upgrade.name = "UpgradeCamouflageVeil" OR
                upgrade.name = "UpgradeFeintVeil" OR
                upgrade.name = "UpgradeAdrenalineSpur" OR
                upgrade.name = "UpgradeCeleritySpur" OR
                upgrade.name = "UpgradeHyperMutationSpur"
            )
            GROUP BY round.id
            ) AS rounds
            GROUP BY upgrade_id
            ORDER BY count DESC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getKilledLifeforms($id, $team)
    {
        $sql = 'SELECT lifeform.name AS name, COUNT(DISTINCT death.id) AS count
            FROM death          
            LEFT JOIN player_round ON death.attacker_id = player_round.id
            LEFT JOIN lifeform ON death.target_lifeform_id = lifeform.id
            LEFT JOIN player ON player_round.player_id = player.id
            LEFT JOIN round ON player_round.round_id = round.id
            WHERE player.id = :id AND attacker_team = :team ' . Filter::addFilterConditions(true) . '
            GROUP BY lifeform.id';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        $command->bindParam(':team', $team);
        return $command->queryAll();
    }

    public static function getPlayedBuilds($id)
    {
        $sql = 'SELECT round.build FROM round
            LEFT JOIN player_round ON round.id = player_round.round_id
            LEFT JOIN player ON player_round.player_id = player.id
            WHERE player.id = :id
            GROUP BY round.build
            ORDER BY round.build DESC';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    public static function getPlayedServers($id)
    {
        $sql = 'SELECT server.id, server.name FROM server
            LEFT JOIN round ON round.server_id = server.id
            LEFT JOIN player_round ON round.id = player_round.round_id
            LEFT JOIN player ON player_round.player_id = player.id
            WHERE player.id = :id';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

    private static function toAssociativeArray($rows, $keyName = 'name', $valueName = 'count')
    {
        $array = array();
        foreach ($rows as $row)
        {
            $array[$row[$keyName]] = $row[$valueName];
        }
        return $array;
    }

    public static function getAlienLifeforms()
    {
        return '(
                lifeform.name = "Skulk" OR 
                lifeform.name = "Gorge" OR
                lifeform.name = "Lerk" OR
                lifeform.name = "Fade" OR
                lifeform.name = "Onos"
            )';
    }

    public static function getMarineLifeforms()
    {
        return '(
                lifeform.name = "marine" OR 
                lifeform.name = "jetpackmarine" 
            )';
    }

    public function beforeSave()
    {
        return true;
    }

    public function getName()
    {
        return htmlspecialchars($this->name);
    }

    public static function getCurrentActivePlayers()
    {
        $players = Player::model()->findAll(array(
            'condition' => 'now()-300<=last_seen and hidden=0',
            'order' => 'steam_name ASC'
        ));

        return $players;
    }

    public function getRanking($attribute)
    {
        Yii::beginProfile('getRanking');
        if ($this->$attribute == 1500)
            return '-';
        $rank = Yii::app()->cache->get('rank-' . $this->id . '-' . $attribute);
        if ($rank)
            return $rank;
        if (isset($this->$attribute))
        {
            $criteria = new CDbCriteria();
            $sql = 'SELECT ' . $attribute . ' AS rating FROM player WHERE ' . $attribute . ' != 1500 ORDER BY rating DESC';
            $players = Yii::app()->db->createCommand($sql)->queryAll();
            $rank = 1;
            foreach ($players as $player)
            {
                if ($player['rating'] == $this->$attribute)
                {
                    Yii::app()->cache->set('rank-' . $this->id . '-' . $attribute, $rank);
                    return $rank;
                }
                $rank++;
            }
        }
        Yii::beginProfile('getRanking');
    }

}
