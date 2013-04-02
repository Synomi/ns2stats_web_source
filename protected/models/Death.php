<?php

/**
 * This is the model class for table "death".
 *
 * The followings are the available columns in table 'death':
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
 * @property string $target_lifetime
 *
 * The followings are the available model relations:
 * @property PlayerRound $attacker
 * @property PlayerRound $target
 * @property Weapon $attackerWeapon
 * @property Lifeform $attackerLifeform
 * @property Lifeform $targetLifeform
 * @property Weapon $targetWeapon
 */
class Death extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Death the static model class
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
        return 'death';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('target_id, target_weapon_id, target_lifeform_id, time, target_x, target_y, target_z, target_lifetime', 'required'),
            array('time, attacker_team, target_team, attacker_armor, attacker_health', 'numerical', 'integerOnly' => true),
            array('attacker_id, target_id, attacker_weapon_id, target_weapon_id, attacker_lifeform_id, target_lifeform_id, target_lifetime', 'length', 'max' => 10),
            array('attacker_x, attacker_y, attacker_z, target_x, target_y, target_z', 'length', 'max' => 9),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, attacker_id, target_id, attacker_weapon_id, target_weapon_id, attacker_lifeform_id, target_lifeform_id, time, attacker_team, target_team, attacker_armor, attacker_health, attacker_x, attacker_y, attacker_z, target_x, target_y, target_z, target_lifetime', 'safe', 'on' => 'search'),
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
            'target_lifetime' => 'Target Lifetime',
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
        $criteria->compare('target_lifetime', $this->target_lifetime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function getLatestsDeathsForMap($mapName, $offset = 0)
    {
        //error_reporting(0);        

        $map = Map::model()->findByAttributes(array('name' => $mapName));
        if (!isset($map))
            throw new CHttpException(404, "Unable to find map.");

        if (!is_numeric($offset))
            throw new CHttpException(401, "Invalid offset");

        $connection = Yii::app()->db;
        $command = $connection->createCommand(
                        'SELECT * FROM death
                         INNER JOIN player_round ON player_round.player_id = death.target_id
                         INNER JOIN round ON round.id = player_round.round_id
                         WHERE round.map_id = ' . $map->id . '                         
                         ORDER BY round.end DESC LIMIT ' . intval($offset) . ', 8000;
           ');

        $deaths = $command->queryAll();
//        foreach ($deaths as $key => $value)
//        {
//            //print_r($death);
//            if (isset($deaths[$key]['attacker_weapon_id']))
//                $deaths[$key]['attacker_weapon_name'] = Weapon::model()->findByPk($deaths[$key]['attacker_weapon_id'])->name;
//            if (isset($deaths[$key]['target_weapon_id']))
//                $deaths[$key]['target_weapon_name'] = Weapon::model()->findByPk($deaths[$key]['target_weapon_id'])->name;
//            if (isset($deaths[$key]['target_lifeform_id']))
//                $deaths[$key]['target_lifeform_name'] = Lifeform::model()->findByPk($deaths[$key]['target_lifeform_id'])->name;
//            if (isset($deaths[$key]['attacker_lifeform_id']))
//                $deaths[$key]['attacker_lifeform_name'] = Lifeform::model()->findByPk($deaths[$key]['attacker_lifeform_id'])->name;
//        }

        return $deaths;
    }

}