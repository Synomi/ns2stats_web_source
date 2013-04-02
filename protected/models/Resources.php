<?php

/**
 * This is the model class for table "resources".
 *
 * The followings are the available columns in table 'resources':
 * @property string $id
 * @property string $round_id
 * @property string $time
 * @property string $team
 * @property string $gathered
 *
 * The followings are the available model relations:
 * @property Round $round
 */
class Resources extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Resources the static model class
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
		return 'resources';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('round_id, time, team, gathered', 'required'),
			array('round_id, time', 'length', 'max'=>10),
			array('team', 'length', 'max'=>1),
			array('gathered', 'length', 'max'=>5),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, round_id, time, team, gathered', 'safe', 'on'=>'search'),
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
			'time' => 'Time',
			'team' => 'Team',
			'gathered' => 'Gathered',
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
		$criteria->compare('time',$this->time,true);
		$criteria->compare('team',$this->team,true);
		$criteria->compare('gathered',$this->gathered,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}