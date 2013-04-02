<?php

/**
 * This is the model class for table "player_weapon".
 *
 * The followings are the available columns in table 'player_weapon':
 * @property string $id
 * @property string $player_round_id
 * @property string $weapon_id
 * @property integer $time
 * @property integer $miss
 * @property integer $player_hit
 * @property integer $player_damage
 * @property integer $structure_hit
 * @property integer $structure_damage
 *
 * The followings are the available model relations:
 * @property PlayerRound $playerRound
 * @property Weapon $weapon
 */
class PlayerWeapon extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PlayerWeapon the static model class
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
		return 'player_weapon';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('player_round_id, weapon_id, time, miss', 'required'),
			array('time, miss, player_hit, player_damage, structure_hit, structure_damage', 'numerical', 'integerOnly'=>true),
			array('player_round_id, weapon_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, player_round_id, weapon_id, time, miss, player_hit, player_damage, structure_hit, structure_damage', 'safe', 'on'=>'search'),
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
			'playerRound' => array(self::BELONGS_TO, 'PlayerRound', 'player_round_id'),
			'weapon' => array(self::BELONGS_TO, 'Weapon', 'weapon_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'player_round_id' => 'Player Round',
			'weapon_id' => 'Weapon',
			'time' => 'Time',
			'miss' => 'Miss',
			'player_hit' => 'Player Hit',
			'player_damage' => 'Player Damage',
			'structure_hit' => 'Structure Hit',
			'structure_damage' => 'Structure Damage',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('player_round_id',$this->player_round_id,true);
		$criteria->compare('weapon_id',$this->weapon_id,true);
		$criteria->compare('time',$this->time);
		$criteria->compare('miss',$this->miss);
		$criteria->compare('player_hit',$this->player_hit);
		$criteria->compare('player_damage',$this->player_damage);
		$criteria->compare('structure_hit',$this->structure_hit);
		$criteria->compare('structure_damage',$this->structure_damage);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


        //id = auto_increment
        //player_round_id = player_round.id
        //types: 1 = player damage, 2 =  structure damage
        public static function getPlayerDamageForRound($player_round_id, $type) {

        $connection = Yii::app()->db;

        if ($type == 1) //player damage
        {
        $command = $connection->createCommand('
            SELECT
                SUM(player_damage) as damage
            FROM
                player_weapon
            WHERE             
                player_round_id = :player_round_id
            ');
        }
        else if($type == 2) //structure damage
        {
            $command = $connection->createCommand('
            SELECT
                SUM(structure_damage) as damage
            FROM
                player_weapon
            WHERE
                player_round_id = :player_round_id
            ');
        }

        
        $command->bindParam(':player_round_id', $player_round_id);
        $result  = $command->queryAll(); 
        return $result[0]["damage"];    
    }

    //only for specific weapons
    //marine : rifle, pistol, shotgun, exo, knife
    //alien: bite, parasite, lerk bite, lerk spikes, gore, swipe, spit,    
     public static function getPlayerAccuracyForRound($player_round_id) {

        $connection = Yii::app()->db;
        
        $command = $connection->createCommand('
            SELECT
                SUM(player_hit) / (SUM(miss) + SUM(player_hit)) * 100 AS accuracy
            FROM
                player_weapon
                LEFT JOIN weapon ON player_weapon.weapon_id = weapon.id
            WHERE
                player_round_id = :player_round_id
                AND ' . Weapon::getAccuracyWeapons());
        
        $command->bindParam(':player_round_id', $player_round_id);
        $result  = $command->queryAll();
        return $result[0]['accuracy'];
    }
}