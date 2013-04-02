<?php

/**
 * This is the model class for table "upgrade".
 *
 * The followings are the available columns in table 'upgrade':
 * @property string $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property RoundUpgrade[] $roundUpgrades
 */
class Upgrade extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Upgrade the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'upgrade';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max' => 45),
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
            'roundUpgrades' => array(self::HAS_MANY, 'RoundUpgrade', 'upgrade_id'),
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public static function getIdByName($name) {
        $upgrade = Upgrade::model()->findByAttributes(array('name' => $name));
        if (isset($upgrade))
            return $upgrade->id;
        else {
            $upgrade = new Upgrade();
            $upgrade->name = $name;
        }
        if ($upgrade->save())
            return Yii::app()->db->getLastInsertID();
    }

}