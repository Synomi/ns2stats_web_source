<?php

/**
 * This is the model class for table "player_round".
 *
 * The followings are the available columns in table 'player_round':
 * @property string $id
 * @property string $player_id
 * @property string $round_id
 * @property string $team
 * @property string $ping
 * @property string $score
 * @property string $assists
 * @property string $name
 * @property integer $start
 * @property integer $end
 * @property string $finished
 * @property integer $commander
 *
 * The followings are the available model relations:
 * @property Death[] $deaths
 * @property Death[] $deaths1
 * @property PlayerLifeform[] $playerLifeforms
 * @property Player $player
 * @property Round $round
 * @property PlayerWeapon[] $playerWeapons
 */
class PlayerRound extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PlayerRound the static model class
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
        return 'player_round';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('player_id, round_id, team, name, start', 'required'),
            array('start, end, commander', 'numerical', 'integerOnly' => true),
            array('player_id, round_id, team, ping, score, assists', 'length', 'max' => 10),
            array('name', 'length', 'max' => 50),
            array('finished, commander', 'length', 'max' => 1),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, player_id, round_id, team, ping, score, assists, name, start, end, finished', 'safe', 'on' => 'search'),
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
            'deaths' => array(self::HAS_MANY, 'Death', 'attacker_id'),
            'deaths1' => array(self::HAS_MANY, 'Death', 'target_id'),
            'playerLifeforms' => array(self::HAS_MANY, 'PlayerLifeform', 'player_round_id'),
            'player' => array(self::BELONGS_TO, 'Player', 'player_id'),
            'round' => array(self::BELONGS_TO, 'Round', 'round_id'),
            'playerWeapons' => array(self::HAS_MANY, 'PlayerWeapon', 'player_in_round_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'player_id' => 'Player',
            'round_id' => 'Round',
            'team' => 'Team',
            'ping' => 'Ping',
            'score' => 'Score',
            'assists' => 'Assists',
            'name' => 'Name',
            'start' => 'Start',
            'end' => 'End',
            'finished' => 'Finished',
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
        $criteria->compare('player_id', $this->player_id, true);
        $criteria->compare('round_id', $this->round_id, true);
        $criteria->compare('team', $this->team, true);
        $criteria->compare('ping', $this->ping, true);
        $criteria->compare('score', $this->score, true);
        $criteria->compare('assists', $this->assists, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('start', $this->start);
        $criteria->compare('end', $this->end);
        $criteria->compare('finished', $this->finished, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function isCommander()
    {
        //Check if commander
        $sql = 'SELECT player.id, SUM(player_lifeform.end - player_lifeform.start) AS commandertime, round.end - round.start AS roundtime
        FROM lifeform
        LEFT JOIN player_lifeform ON player_lifeform.lifeform_id = lifeform.id            
        LEFT JOIN player_round ON player_lifeform.player_round_id = player_round.id    
        LEFT JOIN player ON player_round.player_id = player.id
        LEFT JOIN round ON round.id = player_round.round_id
        WHERE player_lifeform.end > player_lifeform.start AND
        player.id = ' . $this->player_id . ' AND round.id = ' . $this->round_id . ' AND 
        (lifeform.name = "alien_commander" OR lifeform.name = "marine_commander")';
        
        try
        {
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $commanderInfo = $command->queryAll();
            $commanderInfo = $commanderInfo[0];
            if ($commanderInfo['commandertime'] > $commanderInfo['roundtime'] * 0.5)
                return true;
        }
        catch (Exception $ex)
        {            
            error_log('PlayerRound: isCommander(): ' . $ex . ' SQL: ' . $sql);
        }
        
        return false;
    }

}