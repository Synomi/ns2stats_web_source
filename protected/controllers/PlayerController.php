<?php

class PlayerController extends Controller
{

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        
    }

    public function actionIndex()
    {
        $player = new Player();
        $player->attributes = Yii::app()->request->getQuery('Player');
        $this->render('index', array(
            'player' => $player,
        ));
    }

    public function actionCalculateRanks()
    {
        die('disabled');
        if (Yii::app()->user->isSuperAdmin() || $_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR'])
        {
            //calculate ranks

            $connection = Yii::app()->db; //,COUNT(death.id) AS kills
            $command = $connection->createCommand(
                    'SELECT id
                FROM player
                ORDER BY rating DESC
            ');

            $players = $command->queryAll();

            $command = $connection->createCommand(
                    'ALTER TABLE player
               DISABLE KEYS
               '
            );
            $command->execute();

            $transaction = Yii::app()->db->beginTransaction();
            try
            {
                $n = count($players);
                for ($i = 0; $i < $n; $i++)
                {
                    $command = $connection->createCommand(
                            'UPDATE player
                SET ranking=' . ($i + 1) .
                            ' WHERE id=' . $players[$i]['id']
                    );
                    $command->execute();
                }
                $transaction->commit();
            }
            catch (Exception $e)
            {
                $transaction->rollback();
            }
            $command = $connection->createCommand(
                    'ALTER TABLE player
               ENABLE KEYS
               '
            );
            $command->execute();

            $this->renderPartial('calculateranks', array(
            ));
        }
        else
            throw new CHttpException(401, 'You do not have permission to access this page.');
    }

    public function actionPlayer($id)
    {

        $player = Player::model()->findByPk($id);
        $hidden = false;
        if (isset(Yii::app()->user->id))
        {
            if ($player->id != Yii::app()->user->id && $player->hidden)
                $hidden = true;
        }
        else if ($player->hidden)
            $hidden = true;


        if (isset($player))
        {
            Highchart::load();
            $this->render('player', array(
                'player' => $player,
                'hidden' => $hidden,
            ));
        }
        else
            throw new CHttpException(404, 'Unable to fing requested player.');
    }

    public function actionLifeforms($id)
    {
        Json::printJSON(
                array('series' =>
                    array(
                        array(
                            'data' => array(
                                array('Skulk', 3),
                                array('Skulk', 2),
                                array('Skulk', 5.7),
                            )
                        )
                    )
                )
        );
    }

    public function actionMapsPie($id)
    {
        $maps = Player::getMaps($id);
        $maps = HighchartData::pie($maps);
        Json::printJSON($maps);
    }

    public function actionRoundResultsPie($id)
    {
        $roundResults = Player::getRoundResults($id);
        $roundResults = HighchartData::pie($roundResults);
        Json::printJSON($roundResults);
    }

    public function actionLifeformRoundResultsPie($id, $lifeform)
    {
        $roundResults = Player::getLifeformRoundResults($id, $lifeform);
        $roundResults = HighchartData::pie($roundResults);
        Json::printJSON($roundResults);
    }

    public function actionTimePlayedByLifeformPie($id)
    {
        $lifeforms = Player::getTimePlayedByAlienLifeform($id);
        $lifeforms = HighchartData::pie($lifeforms);
        Json::printJSON($lifeforms);
    }

    public function actionTimePlayedAlienCommanderPie($id)
    {
        $lifeforms = Player::getTimePlayedByAlienLifeform($id, true);
        $commanderTime = array(
            array('name' => 'Not Commander', 'count' => 0),
        );
        foreach ($lifeforms as $lifeform)
        {
            if ($lifeform['name'] == 'alien_commander')
                $commanderTime[] = $lifeform;
            else
                $commanderTime[0]['count'] += $lifeform['count'];
        }

        $lifeforms = HighchartData::pie($commanderTime);
        Json::printJSON($lifeforms);
    }

    public function actionTimePlayedMarineCommanderPie($id)
    {
        $lifeforms = Player::getTimePlayedByMarineLifeform($id, true);
        $commanderTime = array(
            array('name' => 'Not Commander', 'count' => 0),
        );
        foreach ($lifeforms as $lifeform)
        {
            if ($lifeform['name'] == 'marine_commander')
                $commanderTime[] = $lifeform;
            else
                $commanderTime[0]['count'] += $lifeform['count'];
        }

        $lifeforms = HighchartData::pie($commanderTime);
        Json::printJSON($lifeforms);
    }

    public function actionWeaponsPie($id, $team)
    {
        $weapons = Player::getWeapons($id, $team);
        $weapons = HighchartData::pie($weapons);
        Json::printJSON($weapons);
    }

    public function actionKillsByWeaponPie($id, $team)
    {
        $kills = Player::getKillsByWeapon($id, $team);
        $kills = HighchartData::pie($kills);
        Json::printJSON($kills);
    }

    public function actionTeamsPie($id)
    {
        $lifeforms = Player::getTeams($id);
        $lifeforms = HighchartData::pie($lifeforms);
        Json::printJSON($lifeforms);
    }

    public function actionTeamsTimePie($id)
    {
        $lifeforms = Player::getTeamsTime($id);
        $lifeforms = HighchartData::pie($lifeforms);
        Json::printJSON($lifeforms);
    }

    public function actionRoundsPlayedLine($id)
    {
        $roundResults = Player::getRoundsPlayedPerDay($id);
        $roundResults = HighchartData::line($roundResults);
        Json::printJSON($roundResults);
    }

    public function actionHiveUpgradesPie($id)
    {
        $hiveUpgrades = Player::getHiveUpgrades($id);
        $hiveUpgrades = HighchartData::pie($hiveUpgrades);
        Json::printJSON($hiveUpgrades);
    }

    public function actionKilledLifeformsPie($id, $team)
    {
        $killedLifeforms = Player::getKilledLifeforms($id, $team);
        $killedLifeforms = HighchartData::pie($killedLifeforms);
        Json::printJSON($killedLifeforms);
    }

    public function actionOverview($id)
    {
        $this->renderPartial('overview', array(
            'player' => Player::model()->findByPk($id),
        ));
    }

    public function actionMarine($id)
    {
        $this->renderPartial('marine', array(
            'player' => Player::model()->findByPk($id),
        ));
    }

    public function actionAlien($id)
    {
        $this->renderPartial('alien', array(
            'player' => Player::model()->findByPk($id),
        ));
    }

    public function actionMarineComm($id)
    {
        $this->renderPartial('marinecomm', array(
            'player' => Player::model()->findByPk($id),
        ));
    }

    public function actionAlienComm($id)
    {
        $this->renderPartial('aliencomm', array(
            'player' => Player::model()->findByPk($id),
        ));
    }

    public function actionGetSteamData()
    {
        
    }

    public function actionUpdateprofile()
    {
        if (isset(Yii::app()->user->id))
        {
            $this->layout = 'account';
            $player = Player::model()->findByPk(Yii::app()->user->id);
            $player->getSteamApiData();
            $player->update();
            $this->render('updateprofile', array(
                'message' => "Your profile information has been updated from Steam pages.",
            ));
        }
        else
            throw new CHttpException(401, 'You need to login to access this page.');
    }

    public function actionToggleHidden()
    {
        if (isset(Yii::app()->user->id))
        {
            $this->layout = 'account';
            $player = Player::model()->findByPk(Yii::app()->user->id);
            if ($player->hidden)
            {
                $player->hidden = false;
                $message = "Your stats are now available for public.";
            }
            else
            {
                $player->hidden = true;
                $message = "Your stats are now hidden from public and your personal stats page can be only seen by you while logged in.";
            }

            $player->update();
            $this->render('updatehidden', array(
                'message' => $message,
            ));
        }
        else
            throw new CHttpException(401, 'You need to login to access this page.');
    }

    public function actionUpdateNationality()
    {
        if (isset(Yii::app()->user->id))
        {
            $this->layout = 'account';
            $player = Player::model()->findByPk(Yii::app()->user->id);

            $sql = 'SELECT DISTINCT country FROM player';
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $nationalities = $command->queryAll();
            
            foreach ($nationalities as $nat)
            {
                if ($nat['country'] == $_POST['nationality'])
                {
                    $player->country = $_POST['nationality'];
                    $player->update();
                    $this->render('nationalityupdated', array(
                        'message' => "Your nationality is now set to: " . $player->country,
                    ));
                    return;
                }
            }

            throw new CHttpException(404, 'Nationality not found');
        }
        else
            throw new CHttpException(401, 'You need to login to access this page.');
    }

    public function actionAccount()
    {
        if (isset(Yii::app()->user->id))
        {
            $this->layout = 'account';

            $player = Player::model()->findByPk(Yii::app()->user->id);

            //check if code is set
            //if not: generate
            if ($player->code == null)
            {
                $num = rand(1, 8999);
                $num += 1000;
                $player->code = intval($num);
                $player->save();
            }



            $this->render('account', array(
                'player' => $player,
            ));
        }
        else
            throw new CHttpException(401, 'You need to login to access this page.');
    }

    public function actionInGameNicks($id)
    {
        $player = Player::model()->findByPk($id);
        $this->renderPartial('ingamenicks', array(
            'player' => $player,
        ));
    }

    public function actionGeneral($id)
    {
        $player = Player::model()->findByPk($id);
        $this->renderPartial('general', array(
            'player' => $player,
        ));
    }

    public function actionRecentRounds($id)
    {
        $player = Player::model()->findByPk($id);
        $this->renderPartial('recentrounds', array(
            'player' => $player,
        ));
    }

    public function actionPlayerList()
    {
        $namePhrase = Yii::app()->request->getPost('s', '');
        $this->renderPartial('playerlist', array(
            'namePhrase' => $namePhrase,
        ));
    }

}