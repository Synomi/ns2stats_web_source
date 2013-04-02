<?php

/**
 * This is the model class for table "pickable".
 *
 * The followings are the available columns in table 'pickable':
 * @property string $id
 * @property string $commander_id
 * @property string $drop
 * @property integer $pick
 * @property integer $destroy
 * @property string $cost
 * @property string $team
 * @property string $name
 * @property string $x
 * @property string $y
 * @property string $z
 * @property string $instant_hit
 *
 * The followings are the available model relations:
 * @property PlayerRound $commander
 */
class Pickable extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Pickable the static model class
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
		return 'pickable';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('commander_id, drop, cost, team, name, x, y, z, instant_hit', 'required'),
			array('pick, destroy', 'numerical', 'integerOnly'=>true),
			array('commander_id, drop', 'length', 'max'=>10),
			array('cost', 'length', 'max'=>2),
			array('team, instant_hit', 'length', 'max'=>1),
			array('name', 'length', 'max'=>32),
			array('x, y, z', 'length', 'max'=>7),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, commander_id, drop, pick, destroy, cost, team, name, x, y, z, instant_hit', 'safe', 'on'=>'search'),
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
			'commander_id' => 'Commander',
			'drop' => 'Drop',
			'pick' => 'Pick',
			'destroy' => 'Destroy',
			'cost' => 'Cost',
			'team' => 'Team',
			'name' => 'Name',
			'x' => 'X',
			'y' => 'Y',
			'z' => 'Z',
			'instant_hit' => 'Instant Hit',
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
		$criteria->compare('commander_id',$this->commander_id,true);
		$criteria->compare('drop',$this->drop,true);
		$criteria->compare('pick',$this->pick);
		$criteria->compare('destroy',$this->destroy);
		$criteria->compare('cost',$this->cost,true);
		$criteria->compare('team',$this->team,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('x',$this->x,true);
		$criteria->compare('y',$this->y,true);
		$criteria->compare('z',$this->z,true);
		$criteria->compare('instant_hit',$this->instant_hit,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}