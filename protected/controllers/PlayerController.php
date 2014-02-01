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
        if (isset($roundResults))
        {
            $roundResults = HighchartData::pie($roundResults);
            Json::printJSON($roundResults);
        }
        else
            return array();
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

    public function actionLoadSignature($id)
    {
        die('doesnt work yet');
        if (isset(Yii::app()->user->id))
        {
            $this->layout = 'account';
            $player = Player::model()->findByPk(Yii::app()->user->id);

            if (isset($player))
            {
                $playerImage = PlayerImage::model()->findByAttributes(array('id' => $id, 'player_id' => $player->id));
                if (!isset($playerImage))
                    throw new CHttpException(404, 'Signature not found.');

                $playerImages = PlayerImage::model()->findAllByAttributes(array('player_id' => $player->id));

                $model = new SignatureForm();
                $model->attributes = $playerImage->attributes;

                $this->render('signature', array(
                    'player' => $player,
                    'playerImages' => $playerImages,
                    'model' => $model
                ));
            }
            else
                throw new CHttpException(404, 'Player not found');
        }
    }

    public function actionSignature($model = null)
    {
        if (isset(Yii::app()->user->id))
        {
            $this->layout = 'account';

            $player = Player::model()->findByPk(Yii::app()->user->id);

            if (isset($_POST['SignatureForm']))
            {
                $model = new SignatureForm();
                $model->attributes = $_POST['SignatureForm'];

                $background_image = CUploadedFile::getInstance($model, 'background_image');
                if (isset($background_image))
                {
                    $backgroundPath = '/tmp/ns2stats_background.' . $player->id;
                    @unlink($backgroundPath);
                    $background_image->saveAs($backgroundPath);
                    $model->background_image = $backgroundPath;
                    $model->background_image_meta = $background_image->name;
                }
            }

            if (isset($model) && $model->validate())
            {
                $_SESSION['signature'] = $model->attributes;



                $this->redirect(array('player/createsignature'));
            }

            if ($model == null)
                $model = new SignatureForm();

            if (isset($player))
            {
                $playerImages = PlayerImage::model()->findAllByAttributes(array('player_id' => $player->id));

                //means we just created a signature, show last used values (id+images unset)
                if (isset($_SESSION['signature']))
                {
                    $model->attributes = $_SESSION['signature'];
                }

                $this->render('signature', array(
                    'player' => $player,
                    'playerImages' => $playerImages,
                    'model' => $model
                ));
            }
            else
                throw new CHttpException(404, 'Player not found');
        }
        else
            throw new CHttpException(401, 'You need to login to access this page.');
    }

    public function actionCreateSignature()
    {

        if (isset(Yii::app()->user->id))
        {
            $player = Player::model()->findByPk(Yii::app()->user->id);
            if (isset($player))
            {
                $playerImages = PlayerImage::model()->findAllByAttributes(array('player_id' => $player->id));
                if (isset($playerImages) && count($playerImages) > 2)
                    throw new CHttpException(500, 'You have maxium of 3 images created. Remove previous images to make new.');
                if (!isset($_SESSION['signature']))
                    throw new CHttpException(404, 'Signature data not available. Unable to continue.');

                $signature = new SignatureForm();
                $signature->attributes = $_SESSION['signature'];
                //currently fixed width/height or by background 

                if ($signature->width > 900)
                    $signature->width = 900;
                if ($signature->height > 300)
                    $signature->height = 300;
                $signatureWidthLimit = $signature->width;
                $signatureHeightLimit = $signature->height;

                $playerImage = new PlayerImage();
                if (isset($_SESSION['signature']['background_image_meta'])) //custom background?
                {
                    $ext = strtolower(pathinfo($_SESSION['signature']['background_image_meta'], PATHINFO_EXTENSION));

                    if ($ext == 'png')
                        $image = imagecreatefrompng($_SESSION['signature']['background_image']);
                    else if ($ext == 'jpg')
                        $image = imagecreatefromjpeg($_SESSION['signature']['background_image']);
                    else
                        throw new CHttpException(500, 'Image type not supported. Jpg and png are supported.');

                    @unlink($signature->background_image);
                }
                else//use predefined background
                {
                    if ($signature->background_number == 1)
                        $image = imagecreatefromjpeg('images/signature/marine_tab_background.jpg');
                    else if ($signature->background_number == 2)
                        $image = imagecreatefromjpeg('images/signature/alien_tab_background.jpg');
                    else if ($signature->background_number == 3)
                        $image = imagecreatefromjpeg('images/signature/overview_tab_background.jpg');
                }


                $background_image_width = imagesx($image);
                $background_image_height = imagesy($image);
                //attempt to resize
                if ($background_image_height > $signatureHeightLimit || $background_image_width > $signatureWidthLimit)
                {
                    $image = SignatureHelper::resizeImage($image, $background_image_width, $background_image_height, $signatureWidthLimit, $signatureHeightLimit);
                    $background_image_width = imagesx($image);
                    $background_image_height = imagesy($image);
                }

                if (isset($signature->steam_image) && $signature->steam_image == true)
                    SignatureHelper::addSteamImage($image, $player, $signature);

                //add logo with text
                if (isset($signature->logo) && $signature->logo == true)
                    SignatureHelper::addLogo($image);

                //save background before dynamic values
                $tmpBgFilePath = '/tmp/signature_bg_' . $player->id . '.png';
                imagepng($image, $tmpBgFilePath);
                $imageBgData = file_get_contents($tmpBgFilePath);
                $playerImage->background_image = $imageBgData;
                unlink($tmpBgFilePath);

                $playerImage->data = $signature->data;

                //set default is user does not have signatures
                if (!isset($playerImages) || (isset($playerImages) && count($playerImages) == 0))
                    $playerImage->default = 1;
                else
                    $playerImage->default = 0;

                //add dynamic values && save $playerImage
                SignatureHelper::updateDynamicValues($playerImage, $player);

                unset($image);
                $this->redirect(array('player/signature'));
            }
            else
                throw new CHttpException(404, 'Player not found');
        }
        else
            throw new CHttpException(401, 'You need to login to access this page.');
    }

    public function actionGetSignature($id)
    {
        SignatureHelper::displaySignature($id);
    }

    public function actionGetPlayerSignature($id)
    {
        $playerImage = PlayerImage::model()->findByAttributes(array('player_id' => $id, 'default' => 1));
        if (isset($playerImage))
        {
            SignatureHelper::displaySignature($playerImage->id);
        }
        //TODO add ns2stats logo if not found
    }

    public function actionSetDefaultSignature($id)
    {
        if (isset(Yii::app()->user->id))
        {
            $player = Player::model()->findByPk(Yii::app()->user->id);
            if (isset($player))
            {
                $playerImages = PlayerImage::model()->findAllByAttributes(array('player_id' => $player->id));
                if (is_array($playerImages))
                {
                    foreach ($playerImages as $pi)
                    {
                        if ($id == $pi->id)
                            $pi->default = 1;
                        else
                            $pi->default = 0;

                        if (!$pi->update())
                            throw new CHttpException(500, 'Unable to save signature: ' . print_r($pi->getErrors(), true));
                    }
                }
                else
                    throw new CHttpException(404, 'Signatures not found');
            }
            else
                throw new CHttpException(404, 'Player not found');
        }
        else
            throw new CHttpException(401, 'You need to login to access this page.');

        $this->redirect(array('player/signature'));
    }

    public function actionDeleteSignature($id)
    {
        if (isset(Yii::app()->user->id))
        {
            $this->layout = 'account';

            $player = Player::model()->findByPk(Yii::app()->user->id);
            if (isset($player))
            {
                $playerImage = PlayerImage::model()->findByAttributes(array('id' => $id, 'player_id' => $player->id));
                if (isset($playerImage))
                {
                    //if current default, change other image to default
                    if ($playerImage->default == 1)
                        $wasDefault = true;
                    else
                        $wasDefault = false;

                    $playerImage->delete();

                    if ($wasDefault)
                    {
                        $playerImages = PlayerImage::model()->findAllByAttributes(array('player_id' => $player->id));
                        if (isset($playerImages) && count($playerImages) > 0)
                        {
                            foreach ($playerImages as $pi)
                            {
                                $pi->default = 1;
                                $pi->update();
                                break;
                            }
                        }
                    }
                    $this->render('signature_deleted');
                }
                else
                    throw new CHttpException(404, 'Signature not found');
            }
            else
                throw new CHttpException(404, 'Player not found');
        }
        else
            throw new CHttpException(401, 'You need to login to access this page.');
    }

}