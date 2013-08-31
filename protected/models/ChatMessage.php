<?php

/**
 * This is the model class for table "chat".
 *
 * The followings are the available columns in table 'chat':
 * @property string $id
 * @property string $message
 * @property string $player_round_id
 * @property string $team_number
 * @property integer $to_team
 * @property string $player_name
 * @property integer $gametime
 */
class ChatMessage extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ChatMessage the static model class
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
		return 'chat';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('player_round_id, team_number, to_team, player_name, gametime', 'required'),
			array('to_team, gametime', 'numerical', 'integerOnly'=>true),
			array('message', 'length', 'max'=>512),
			array('player_round_id, team_number', 'length', 'max'=>10),
			array('player_name', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, gametim, message, player_round_id, team_number, to_team, player_name', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'message' => 'Message',
			'player_round_id' => 'Player Round',
			'team_number' => 'Team Number',
			'to_team' => 'To Team',
			'player_name' => 'Player Name',
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
		$criteria->compare('message',$this->message,true);
		$criteria->compare('player_round_id',$this->player_round_id,true);
		$criteria->compare('team_number',$this->team_number,true);
		$criteria->compare('to_team',$this->to_team);
		$criteria->compare('player_name',$this->player_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}