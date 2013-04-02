<?php

/**
 * This is the model class for table "round_structure".
 *
 * The followings are the available columns in table 'round_structure':
 * @property string $id
 * @property string $round_id
 * @property string $structure_id
 * @property integer $drop
 * @property string $build
 * @property string $destroy
 * @property string $cost
 * @property string $team
 * @property string $x
 * @property string $y
 * @property string $z
 * @property string $attacker_id
 * @property string $attacker_lifeform_id
 * @property integer $attacker_team
 * @property string $attacker_weapon_id
 * @property string $builder_id
 * @property string $recycle_res_back
 * @property string $commander_id
 *
 * The followings are the available model relations:
 * @property Round $round
 * @property Structure $structure
 * @property PlayerRound $attacker
 * @property Lifeform $attackerLifeform
 * @property Weapon $attackerWeapon
 * @property PlayerRound $builder
 * @property PlayerRound $commander
 */
class RoundStructure extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RoundStructure the static model class
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
		return 'round_structure';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('round_id, structure_id, drop, cost, team, x, y, z, commander_id', 'required'),
			array('drop, attacker_team', 'numerical', 'integerOnly'=>true),
			array('round_id, structure_id, build, destroy, attacker_id, attacker_lifeform_id, attacker_weapon_id, builder_id, commander_id', 'length', 'max'=>10),
			array('cost', 'length', 'max'=>3),
			array('team, recycle_res_back', 'length', 'max'=>1),
			array('x, y, z', 'length', 'max'=>9),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, round_id, structure_id, drop, build, destroy, cost, team, x, y, z, attacker_id, attacker_lifeform_id, attacker_team, attacker_weapon_id, builder_id, recycle_res_back, commander_id', 'safe', 'on'=>'search'),
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
			'round' => array(self::BELONGS_TO, 'Round', 'round_id'),
			'structure' => array(self::BELONGS_TO, 'Structure', 'structure_id'),
			'attacker' => array(self::BELONGS_TO, 'PlayerRound', 'attacker_id'),
			'attackerLifeform' => array(self::BELONGS_TO, 'Lifeform', 'attacker_lifeform_id'),
			'attackerWeapon' => array(self::BELONGS_TO, 'Weapon', 'attacker_weapon_id'),
			'builder' => array(self::BELONGS_TO, 'PlayerRound', 'builder_id'),
			'commander' => array(self::BELONGS_TO, 'PlayerRound', 'commander_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'round_id' => 'Round',
			'structure_id' => 'Structure',
			'drop' => 'Drop',
			'build' => 'Build',
			'destroy' => 'Destroy',
			'cost' => 'Cost',
			'team' => 'Team',
			'x' => 'X',
			'y' => 'Y',
			'z' => 'Z',
			'attacker_id' => 'Attacker',
			'attacker_lifeform_id' => 'Attacker Lifeform',
			'attacker_team' => 'Attacker Team',
			'attacker_weapon_id' => 'Attacker Weapon',
			'builder_id' => 'Builder',
			'recycle_res_back' => 'Recycle Res Back',
			'commander_id' => 'Commander',
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
		$criteria->compare('round_id',$this->round_id,true);
		$criteria->compare('structure_id',$this->structure_id,true);
		$criteria->compare('drop',$this->drop);
		$criteria->compare('build',$this->build,true);
		$criteria->compare('destroy',$this->destroy,true);
		$criteria->compare('cost',$this->cost,true);
		$criteria->compare('team',$this->team,true);
		$criteria->compare('x',$this->x,true);
		$criteria->compare('y',$this->y,true);
		$criteria->compare('z',$this->z,true);
		$criteria->compare('attacker_id',$this->attacker_id,true);
		$criteria->compare('attacker_lifeform_id',$this->attacker_lifeform_id,true);
		$criteria->compare('attacker_team',$this->attacker_team);
		$criteria->compare('attacker_weapon_id',$this->attacker_weapon_id,true);
		$criteria->compare('builder_id',$this->builder_id,true);
		$criteria->compare('recycle_res_back',$this->recycle_res_back,true);
		$criteria->compare('commander_id',$this->commander_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}