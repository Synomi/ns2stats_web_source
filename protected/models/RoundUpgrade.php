<?php

/**
 * This is the model class for table "round_upgrade".
 *
 * The followings are the available columns in table 'round_upgrade':
 * @property string $id
 * @property string $round_id
 * @property string $upgrade_id
 * @property integer $team
 * @property string $time
 * @property string $cost
 *
 * The followings are the available model relations:
 * @property Round $round
 * @property Upgrade $upgrade
 */
class RoundUpgrade extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RoundUpgrade the static model class
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
		return 'round_upgrade';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('round_id, upgrade_id, team, time, cost', 'required'),
			array('team', 'numerical', 'integerOnly'=>true),
			array('round_id, upgrade_id, time, cost', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, round_id, upgrade_id, team, time, cost', 'safe', 'on'=>'search'),
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
			'upgrade' => array(self::BELONGS_TO, 'Upgrade', 'upgrade_id'),
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
			'upgrade_id' => 'Upgrade',
			'team' => 'Team',
			'time' => 'Time',
			'cost' => 'Cost',
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
		$criteria->compare('upgrade_id',$this->upgrade_id,true);
		$criteria->compare('team',$this->team);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('cost',$this->cost,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}