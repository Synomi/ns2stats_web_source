<?php

/**
 * This is the model class for table "weapon".
 *
 * The followings are the available columns in table 'weapon':
 * @property string $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Death[] $deaths
 * @property Death[] $deaths1
 * @property PlayerWeapon[] $playerWeapons
 */
class Weapon extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Weapon the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'weapon';
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
            'deaths' => array(self::HAS_MANY, 'Death', 'attacker_weapon_id'),
            'deaths1' => array(self::HAS_MANY, 'Death', 'target_weapon_id'),
            'playerWeapons' => array(self::HAS_MANY, 'PlayerWeapon', 'weapon_id'),
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
        $weapon = Weapon::model()->findByAttributes(array('name' => $name));
        if (isset($weapon))
            return $weapon->id;
        else {
            $weapon = new Weapon();
            $weapon->name = $name;
        }
        if ($weapon->save())
            return Yii::app()->db->getLastInsertID();
    }

    public static function getAccuracyWeapons() {
        return '(
                weapon.name = "rifle" OR 
                weapon.name = "pistol" OR
                weapon.name = "shotgun" OR
                weapon.name = "axe" OR
                weapon.name = "minigun" OR 
                weapon.name = "bite" OR
                weapon.name = "parasite" OR
                weapon.name = "lerkbite" OR
                weapon.name = "spikes" OR 
                weapon.name = "gore" OR
                weapon.name = "swipe" OR
                weapon.name = "spit" 
            )';
    }
}