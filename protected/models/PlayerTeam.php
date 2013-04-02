<?php

/**
 * This is the model class for table "player_team".
 *
 * The followings are the available columns in table 'player_team':
 * @property string $id
 * @property string $team_id
 * @property string $player_id
 * @property integer $role
 *
 * The followings are the available model relations:
 * @property Team $team
 * @property Player $player
 */
class PlayerTeam extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PlayerTeam the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'player_team';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('team_id, player_id', 'required'),
            array('role', 'numerical', 'integerOnly' => true),
            array('team_id, player_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, team_id, player_id, role', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'team' => array(self::BELONGS_TO, 'Team', 'team_id'),
            'player' => array(self::BELONGS_TO, 'Player', 'player_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'team_id' => 'Team',
            'player_id' => 'Player',
            'role' => 'Role',
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
        $criteria->compare('team_id', $this->team_id, true);
        $criteria->compare('player_id', $this->player_id, true);
        $criteria->compare('role', $this->role);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public static function getRoles() {
        return array(
            'Invited',
            'Member',
            'Admin',
            'Founder',
        );
    }

    public static function getRoleString($id) {
        $roles = self::getRoles();
        return $roles[$id];
    }

    public static function getInvitesByPlayer($id) {
        $sql = 'SELECT player_team.*, team.name FROM player_team
            LEFT JOIN team ON player_team.team_id = team.id
            WHERE player_team.player_id = :id AND role = 0';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(':id', $id);
        return $command->queryAll();
    }

}