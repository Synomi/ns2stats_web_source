<?php

/**
 * This is the model class for table "player_lifeform".
 *
 * The followings are the available columns in table 'player_lifeform':
 * @property string $id
 * @property string $player_round_id
 * @property string $lifeform_id
 * @property string $start
 * @property integer $end
 *
 * The followings are the available model relations:
 * @property PlayerRound $playerRound
 * @property Lifeform $lifeform
 */
class PlayerLifeform extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PlayerLifeform the static model class
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
		return 'player_lifeform';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('player_round_id, lifeform_id, start, end', 'required'),
			array('end', 'numerical', 'integerOnly'=>true),
			array('player_round_id, lifeform_id, start', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, player_round_id, lifeform_id, start, end', 'safe', 'on'=>'search'),
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
			'lifeform' => array(self::BELONGS_TO, 'Lifeform', 'lifeform_id'),
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
			'lifeform_id' => 'Lifeform',
			'start' => 'Start',
			'end' => 'End',
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
		$criteria->compare('lifeform_id',$this->lifeform_id,true);
		$criteria->compare('start',$this->start,true);
		$criteria->compare('end',$this->end);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}