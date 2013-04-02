<?php

/**
 * This is the model class for table "mod".
 *
 * The followings are the available columns in table 'mod':
 * @property string $id
 * @property string $name
 * @property string $workshop_id
 *
 * The followings are the available model relations:
 * @property ModRound[] $modRounds
 */
class Mod extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Mod the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'mod';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'length', 'max' => 128),
            array('workshop_id', 'length', 'max' => 32),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, workshop_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'modRounds' => array(self::HAS_MANY, 'ModRound', 'mod_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'workshop_id' => 'Workshop',
        );
    }

    public static function getIdByName($name) {
        $mod = Mod::model()->findByAttributes(array('name' => $name));
        if (isset($mod))
            return $mod->id;
        else {
            $mod = new Mod();
            $mod->name = $name;
        }
        if ($mod->save())
            return Yii::app()->db->getLastInsertID();
    }

}