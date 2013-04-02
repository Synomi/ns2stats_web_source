<?php

/**
 * This is the model class for table "live_player".
 *
 * The followings are the available columns in table 'live_player':
 * @property string $id
 * @property string $player_id
 * @property string $last_updated
 *
 * The followings are the available model relations:
 * @property Player $player
 */
class LivePlayer extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return LivePlayer the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'live_player';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('player_id, last_updated', 'required'),
            array('player_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, player_id, last_updated', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'player' => array(self::BELONGS_TO, 'Player', 'player_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'player_id' => 'Player',
            'last_updated' => 'Last Updated',
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
        $criteria->compare('player_id', $this->player_id, true);
        $criteria->compare('last_updated', $this->last_updated, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function getStructureDamage($weapons) {
        //:[{"time":52,"name":"bite","miss":0,"player_damage":75,"player_hit":1,"structure_hit":9,"structure_damage":650}],
        $damage = 0;
        if (count($weapons) == 0)
            return 0;
        
        foreach ($weapons as $weapon) {
            $damage += intval($weapon['structure_damage']);
        }
        return $damage;
    }

    public static function getPlayerDamage($weapons) {
        //:[{"time":52,"name":"bite","miss":0,"player_damage":75,"player_hit":1,"structure_hit":9,"structure_damage":650}],
        $damage = 0;
        if (count($weapons) == 0)
            return 0;
        
        foreach ($weapons as $weapon) {
            $damage += intval($weapon['player_damage']);
        }
        return $damage;

    }

}