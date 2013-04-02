<?php

/**
 * This is the model class for table "lifeform".
 *
 * The followings are the available columns in table 'lifeform':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Death[] $deaths
 * @property Death[] $deaths1
 * @property PlayerLifeform[] $playerLifeforms
 */
class Lifeform extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Lifeform the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'lifeform';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max' => 20),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'deaths' => array(self::HAS_MANY, 'Death', 'killer_lifeform'),
            'deaths1' => array(self::HAS_MANY, 'Death', 'killed_lifeform'),
            'playerLifeforms' => array(self::HAS_MANY, 'PlayerLifeform', 'lifeform_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public static function getIdByName($name) {
        $lifeform = Lifeform::model()->findByAttributes(array('name' => $name));
        if (isset($lifeform))
            return $lifeform->id;
        else {
            $lifeform = new Lifeform();
            $lifeform->name = $name;
        }
        if ($lifeform->save())
            return Yii::app()->db->getLastInsertID();
    }

}