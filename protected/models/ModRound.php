<?php

/**
 * This is the model class for table "mod_round".
 *
 * The followings are the available columns in table 'mod_round':
 * @property string $id
 * @property string $mod_id
 * @property string $round_id
 *
 * The followings are the available model relations:
 * @property Mod $mod
 * @property Ns2stats.round $round
 */
class ModRound extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ModRound the static model class
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
		return 'mod_round';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mod_id, round_id', 'required'),
			array('mod_id', 'length', 'max'=>11),
			array('round_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, mod_id, round_id', 'safe', 'on'=>'search'),
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
			'mod' => array(self::BELONGS_TO, 'Mod', 'mod_id'),
			'round' => array(self::BELONGS_TO, 'Ns2stats.round', 'round_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'mod_id' => 'Mod',
			'round_id' => 'Round',
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
		$criteria->compare('mod_id',$this->mod_id,true);
		$criteria->compare('round_id',$this->round_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}