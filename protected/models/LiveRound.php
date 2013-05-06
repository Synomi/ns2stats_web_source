<?php

/**
 * This is the model class for table "live_round".
 *
 * The followings are the available columns in table 'live_round':
 * @property string $id
 * @property string $server_id
 * @property string $last_updated
 *
 * The followings are the available model relations:
 * @property Server $server
 */
class LiveRound extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return LiveRound the static model class
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
        return 'live_round';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('server_id, last_updated', 'required'),
            array('server_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, server_id, last_updated', 'safe', 'on' => 'search'),
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
            'server' => array(self::BELONGS_TO, 'Server', 'server_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'server_id' => 'Server',
            'last_updated' => 'Last Updated',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('server_id', $this->server_id, true);
        $criteria->compare('last_updated', $this->last_updated, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function getPlayersForLiveRound($id, $team)
    {

        $cacheId = "liveplayers$id.$team";
        $players = Yii::app()->cache->get($cacheId);
        
        if ($players === false)
        {
            $livePlayers = LivePlayer::model()->findAll(array(
                        'condition' => 'now()-300<=last_updated AND live_round_id=:id',
                        'order' => 'id DESC',
                        'params' => array('id' => $id), 
                    ));

            $players = null;
            foreach ($livePlayers as $livePlayer)
            {
                $data = json_decode($livePlayer->json, true);
                if ($data['dc'] == true)
                    $data['team'] = 5;

                if ($data['teamnumber'] == $team || ($team == 5 && $data['teamnumber'] != 1 && $data['teamnumber'] != 2))
                {
                    $data['player'] = Player::model()->findByAttributes(array('steam_id' => $data['steamId']));

                    if ($data['isCommander'] == true)
                        $data['player']['commander'] = true;
                    else
                        $data['player']['commander'] = false;
                    
                    if (!$data['player']['hidden'])
                        $players[] = $data;
                }
            }

            if (count($players) > 0)
            {
                usort($players, function($a, $b)
                        {
                            if ($a['score'] == $b['score'])
                                return 0;
                            if ($a['score'] > $b['score'])
                                return -1;
                            else
                                return 1;
                        });
            }
            else
                $players = array();

            Yii::app()->cache->set($cacheId, $players, 300);
        }

        return $players;
    }

}