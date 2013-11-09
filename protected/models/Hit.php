<?php

/**
 * This is the model class for table "hit".
 *
 * The followings are the available columns in table 'hit':
 * @property string $id
 * @property string $attacker_id
 * @property string $target_id
 * @property string $attacker_weapon_id
 * @property string $target_weapon_id
 * @property string $attacker_lifeform_id
 * @property string $target_lifeform_id
 * @property integer $time
 * @property integer $attacker_team
 * @property integer $target_team
 * @property integer $attacker_armor
 * @property integer $attacker_health
 * @property string $attacker_x
 * @property string $attacker_y
 * @property string $attacker_z
 * @property string $target_x
 * @property string $target_y
 * @property string $target_z
 * @property string $damage_type
 * @property string $damage
 *
 * The followings are the available model relations:
 * @property PlayerRound $attacker
 * @property PlayerRound $target
 * @property Weapon $attackerWeapon
 * @property Lifeform $attackerLifeform
 * @property Lifeform $targetLifeform
 * @property Weapon $targetWeapon
 */
class Hit extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Hit the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'hit';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('target_id, target_weapon_id, target_lifeform_id, time, target_x, target_y, target_z, damage_type, damage', 'required'),
            array('time, attacker_team, target_team, attacker_armor, attacker_health', 'numerical', 'integerOnly' => true),
            array('attacker_id, target_id, attacker_weapon_id, target_weapon_id, attacker_lifeform_id, target_lifeform_id, damage', 'length', 'max' => 10),
            array('attacker_x, attacker_y, attacker_z, target_x, target_y, target_z', 'length', 'max' => 9),
            array('damage_type', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, attacker_id, target_id, attacker_weapon_id, target_weapon_id, attacker_lifeform_id, target_lifeform_id, time, attacker_team, target_team, attacker_armor, attacker_health, attacker_x, attacker_y, attacker_z, target_x, target_y, target_z, damage_type, damage', 'safe', 'on' => 'search'),
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
            'attacker' => array(self::BELONGS_TO, 'PlayerRound', 'attacker_id'),
            'target' => array(self::BELONGS_TO, 'PlayerRound', 'target_id'),
            'attackerWeapon' => array(self::BELONGS_TO, 'Weapon', 'attacker_weapon_id'),
            'attackerLifeform' => array(self::BELONGS_TO, 'Lifeform', 'attacker_lifeform_id'),
            'targetLifeform' => array(self::BELONGS_TO, 'Lifeform', 'target_lifeform_id'),
            'targetWeapon' => array(self::BELONGS_TO, 'Weapon', 'target_weapon_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'attacker_id' => 'Attacker',
            'target_id' => 'Target',
            'attacker_weapon_id' => 'Attacker Weapon',
            'target_weapon_id' => 'Target Weapon',
            'attacker_lifeform_id' => 'Attacker Lifeform',
            'target_lifeform_id' => 'Target Lifeform',
            'time' => 'Time',
            'attacker_team' => 'Attacker Team',
            'target_team' => 'Target Team',
            'attacker_armor' => 'Attacker Armor',
            'attacker_health' => 'Attacker Health',
            'attacker_x' => 'Attacker X',
            'attacker_y' => 'Attacker Y',
            'attacker_z' => 'Attacker Z',
            'target_x' => 'Target X',
            'target_y' => 'Target Y',
            'target_z' => 'Target Z',
            'damage_type' => 'Damage Type',
            'damage' => 'Damage',
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
        $criteria->compare('attacker_id', $this->attacker_id, true);
        $criteria->compare('target_id', $this->target_id, true);
        $criteria->compare('attacker_weapon_id', $this->attacker_weapon_id, true);
        $criteria->compare('target_weapon_id', $this->target_weapon_id, true);
        $criteria->compare('attacker_lifeform_id', $this->attacker_lifeform_id, true);
        $criteria->compare('target_lifeform_id', $this->target_lifeform_id, true);
        $criteria->compare('time', $this->time);
        $criteria->compare('attacker_team', $this->attacker_team);
        $criteria->compare('target_team', $this->target_team);
        $criteria->compare('attacker_armor', $this->attacker_armor);
        $criteria->compare('attacker_health', $this->attacker_health);
        $criteria->compare('attacker_x', $this->attacker_x, true);
        $criteria->compare('attacker_y', $this->attacker_y, true);
        $criteria->compare('attacker_z', $this->attacker_z, true);
        $criteria->compare('target_x', $this->target_x, true);
        $criteria->compare('target_y', $this->target_y, true);
        $criteria->compare('target_z', $this->target_z, true);
        $criteria->compare('damage_type', $this->damage_type, true);
        $criteria->compare('damage', $this->damage, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function getAllHitsForRound($roundID)
    {
//            $hits = Hit::model()->with('PlayerRound','round')->together()->findAll(array(
//                'condition' => 'round.id = :roundID',
//                'params' => array('roundID' => $roundID),
//            ));
//        $sql = 'SELECT map.id, map.name, COUNT(map.id) AS count FROM player
//            LEFT JOIN player_round ON player.id = player_round.player_id
//            LEFT JOIN round ON player_round.round_id = round.id
//            LEFT JOIN map ON round.map_id = map.id
//            WHERE player.id = :id ' . Filter::addFilterConditions(true) . '
//            GROUP BY map.id
//            ORDER BY count DESC';

        $sql = "SELECT * FROM hit
            INNER JOIN player_round ON attacker_id=player_round.id
            INNER JOIN round ON player_round.round_id = round.id
            WHERE round.id = :roundID"; // AND attacker_weapon_id!=20 AND attacker_weapon_id!=27 AND attacker_weapon_id!=28 AND attacker_weapon_id!=19 AND attacker_weapon_id!=10
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':roundID', $roundID);
        return  $command->queryAll();
        
    }

}