<?php

class Apiv1Controller extends Controller
{

    private $models = array(
        'ChatMessage',
        'Death',
        'Hit',
        'Lifeform',
        'LivePlayer',
        'LiveRound',
        'Map',
        'Mod',
        'ModRound',
        'Pickable',
        'Player',
        'PlayerLifeform',
        'PlayerRound',
        'PlayerTeam',
        'PlayerWeapon',
        'Resources',
        'Round',
        'RoundStructure',
        'RoundUpgrade',
        'Server',
        'Team',
        'Upgrade',
        'Weapon'
    );

    public function init()
    {
        Yii::app()->errorHandler->errorAction = 'apiv1/error';
    }

    public function actionError()
    {

        if ($error = Yii::app()->errorHandler->error)
            Json::printJSON(array('error' => $error['message']), $error['code']);
        else
            Json::printJSON(array('error' => 'General error'), 400);
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array();
    }

    private function handleRequest()
    {
        $model = null;
        foreach ($this->models as $allowed_model)
        {
            if (strtolower(Yii::app()->controller->action->id) == strtolower($allowed_model))
                $model = $allowed_model;
        }

        if ($model == null)
        {
            Json::printJSON(array('error' => 'Model not found'), 404);
            return;
        }

        //find by field id
        if (isset($_GET['field']) && strlen($_GET['field']) > 1 && isset($_GET['value']) && $_GET['field'] != 'code' && $_GET['field'] != 'ip')
        {
            $fieldModel = new $model;
            foreach ($fieldModel->attributes as $key => $value)
            {
                if ($_GET['field'] == $key)
                {
                    $data = $model::model()->cache(60*30)->findByAttributes(array($_GET['field'] => $_GET['value']));
                    if (isset($data))
                    {
                        if (isset($data->ip) && $model != 'Server')
                            $data->ip = null;
                        if (isset($data->code))
                            $data->code = null;
                        if (isset($data->server_key))
                            $data->server_key = null;
                        
                        Json::printJSON($data->attributes, 200);
                    }
                    else
                        Json::printJSON(array('error' => $_GET['field'] . ':' . $value . ' does not return any data.'), 200);

                    return;
                }
            }

            Json::printJSON(array('error' => $_GET['field'] . ' field does not exist in ' . $model . '.'), 404);
            return;
        }
        else if (isset($_GET['id']))
        {
            $data = $model::model()->cache(60*30)->findByPk($_GET['id']);

            if (isset($data))
            {
                if (isset($data->ip) && $model != 'Server')
                    $data->ip = null;
                if (isset($data->code))
                    $data->code = null;
                if (isset($data->server_key))
                    $data->server_key = null;

                Json::printJSON($data->attributes, 200);
            }
            else
                Json::printJSON(array('error' => $model . ' not found.'), 404);
        }
        else
        {
            $criteria = new CDbCriteria( );

            $offset = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $offset = $_GET['offset'] : 0;
            $limit = (isset($_GET['limit']) && is_numeric($_GET['limit'])) ? $limit = $_GET['limit'] : 100;
            if (isset($_GET['order']) && strpos($_GET['order'], ' ') && strlen($_GET['order']) > 5)
            {
                $parts = explode(' ', $_GET['order']);

                $parts[0] = preg_replace('/[^a-z\_0-9]/i', '', $parts[0]);

                $parts[1] = preg_replace('/[^a-z\_0-9]/i', '', $parts[1]);
                if (count($parts) != 2 || strlen($parts[0]) < 2 || strlen($parts[0]) > 20 || (strtolower($parts[1]) != 'desc' && strtolower($parts[1]) != 'asc') || strtolower($parts[0]) == 'code')
                {
                    Json::printJSON(array('error' => 'Invalid order type. Use <field name> (space) <ASC or DESC>'), 400);
                    return;
                }
                $order = implode(' ', $parts);
                $criteria->order = $order;
            }


            if ($limit > 100)
                $limit = 100;
            $criteria->limit = $limit;
            $criteria->offset = $offset;


            try
            {
                $rows = $model::model()->cache(60*30)->findAll($criteria);
            }
            catch (Exception $ex)
            {
                Json::printJSON(array('error' => $ex), 400);
                return;
            }
            if (isset($rows))
            {
                $models = array();
                foreach ($rows as $row)
                {
                    if (isset($row->ip) && $model != 'Server')
                        $row->ip = null;
                    if (isset($row->server_key))
                        $row->server_key = null;
                    if (isset($row->code))
                        $row->code = null;


                    $models[] = $row->attributes;
                }

                Json::printJSON($models, 200);
            }
            else
                Json::printJSON(array('error' => $model . ' has no data.'), 200);
        }
    }

    public function actionList()
    {
        Json::printJSON($this->models, 200);
    }

    public function actionChatMessage()
    {
        $this->handleRequest();
    }

    public function actionDeath()
    {
        $this->handleRequest();
    }

    public function actionHit()
    {
        $this->handleRequest();
    }

    public function actionLifeform()
    {
        $this->handleRequest();
    }

    public function actionLivePlayer()
    {
        $this->handleRequest();
    }

    public function actionLiveRound()
    {
        $this->handleRequest();
    }

    public function actionMap()
    {
        $this->handleRequest();
    }

    public function actionMod()
    {
        $this->handleRequest();
    }

    public function actionModRound()
    {
        $this->handleRequest();
    }

    public function actionPickable()
    {
        $this->handleRequest();
    }

    public function actionPlayer()
    {
        $this->handleRequest();
    }

    public function actionRound()
    {
        $this->handleRequest();
    }

    public function actionPlayerLifeform()
    {
        $this->handleRequest();
    }

    public function actionPlayerRound()
    {
        $this->handleRequest();
    }

    public function actionPlayerTeam()
    {
        $this->handleRequest();
    }

    public function actionPlayerWeapon()
    {
        $this->handleRequest();
    }

    public function actionResources()
    {
        $this->handleRequest();
    }

    public function actionRoundStructure()
    {
        $this->handleRequest();
    }

    public function actionRoundUpgrade()
    {
        $this->handleRequest();
    }

    public function actionTeam()
    {
        $this->handleRequest();
    }

    public function actionUpgrade()
    {
        $this->handleRequest();
    }

    public function actionWeapon()
    {
        $this->handleRequest();
    }

    public function actionServer()
    {
        $this->handleRequest();
    }

}
