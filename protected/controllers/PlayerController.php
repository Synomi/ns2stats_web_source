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
                if (isset($playerImages) && count($playerImages) > 22)
                    throw new CHttpException(500, 'You have maxium of 3 images created. Remove previous images to make new.');
                if (!isset($_SESSION['signature']))
                    throw new CHttpException(404, 'Signature data not available. Unable to continue.');

                $signature = new SignatureForm();
                $signature->attributes = $_SESSION['signature'];



                //(w x h)
//Call in another picture
                
                $playerImage = new PlayerImage();
                if (isset($_SESSION['signature']['background_image_meta'])) //custom background?
                {
                    $ext = pathinfo($_SESSION['signature']['background_image_meta'], PATHINFO_EXTENSION);
                    echo $ext;

                    $dbimage = file_get_contents($_SESSION['signature']['background_image']);
                    if ($ext == 'png')
                        $image = imagecreatefrompng($_SESSION['signature']['background_image']);
                    else if ($ext == 'jpg')
                        $image = imagecreatefromjpeg($_SESSION['signature']['background_image']);
                    else
                        throw new CHttpException(500, 'Image type not supported. Jpg and png are supported.');

                    @unlink($signature->background_image);
                    //$image = imagecreatefromstring($signature->background_image);
                    $playerImage->background_image = $dbimage;
                    //$image = imagecreatefrompng();    
                }
                else//use predefined background
                {
                    //choises
                    echo 'using predefined background';
                    $image = imagecreatetruecolor($signature->width, $signature->height);
                }


                /*
                 * Steam image
                 */

                $steam_image = imagecreatefromjpeg($player->steam_image);
                // get current width/height
                $steam_image_width = imagesx($steam_image);
                $steam_image_height = imagesy($steam_image);

                $background_image_width = imagesx($steam_image);
                $background_image_height = imagesy($image);




                imagecopy($image, $steam_image, 10, $background_image_height - 10 - $steam_image_height, 0, 0, $steam_image_width, $steam_image_height);


                $white = imagecolorallocate($image, 255, 255, 255);
                $font = 'css/OptimusPrincepsSemiBold.ttf';

                //make explodeable if not.
                if (strpos($signature->data, PHP_EOL) === false)
                    $signature->data .=' ' . PHP_EOL . ' ';

                $rows = explode(PHP_EOL, $this->findValues($signature->data, $player));
                if (is_array($rows))
                {
                    $x = 0;
                    $size = 10;
                    foreach ($rows as $row)
                    {
                        $x+=$size + intval($size / 5) + 1;
                        imagettftext($image, $size, 0, 0, $x, $white, $font, str_replace(' ', '  ', $row));
                    }
                    
                    imagettftext($image, $size, 0, $background_image_width-300, $background_image_height-20, $white, $font, 'ns2stats.com');
                    
                }
                else
                    throw new CHttpException(500, 'Values field failed to parse-');





                $tmpFilePath = '/tmp/signature_' . $player->id . '.png';
                imagepng($image, $tmpFilePath);
                $imageData = file_get_contents($tmpFilePath);

                $playerImage->image = $imageData;
                $playerImage->player_id = $player->id;

                $playerImage->save();

                unlink($tmpFilePath);
                unset($tmpFilePath);
                unset($imageData);
                unset($image);
                unset($_SESSION['signature']);
                $this->redirect(array('player/signature'));

//                header("Content-type: image/png");
////tells the browser it is a png picture
//                imagepng($image);
////displays the png image "image"
//                imagedestroy($image);
//removes the image from memory
//                
            }
            else
                throw new CHttpException(404, 'Player not found');
        }
        else
            throw new CHttpException(401, 'You need to login to access this page.');
    }

    public function actionGetSignature($id)
    {
        header("Content-type: image/png");
        $playerImage = PlayerImage::model()->findByPk($id);
        if (isset($playerImage))
        {

            echo $playerImage->image;
            unset($playerImage);
        }
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
                    $playerImage->delete();
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

    private function findValues($text, $player)
    {

        $searchFor = array();
        $replaceWith = array();

        foreach ($player->attributes as $key => $value)
        {
            if ($key != 'code' && $key != 'ip')
            {
                $searchFor[] = '[' . strtolower($key) . ']';
                $replaceWith[] = $value;
            }
        }

        return str_replace($searchFor, $replaceWith, $text);
    }

}