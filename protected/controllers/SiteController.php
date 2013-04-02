<?php

class SiteController extends Controller {

    public $layout = 'text';

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        
    }

    public function actionChangelog() {
        $this->redirect('about', true, 301);
    }

    public function actionProgress() {
        $this->redirect('about', true, 301);
    }

    public function actionAbout() {
        $this->render('about');
    }

    public function actionInstall() {
        $this->render('install');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionSteamLogin() {
        $loid = Yii::app()->loid->load();
        if (!empty($_GET['openid_mode'])) {
            if ($_GET['openid_mode'] == 'cancel') {
                $err = Yii::t('core', 'Authorization cancelled');
            } else {
                try {
                    $loid->validate();
                    $communityId = substr($loid->identity, 36);
                    $steamId = SteamApi::CommunityIdToSteamId($communityId);
                    $playerId = Player::getIdBySteamId($steamId);
                    $player = Player::model()->findByPk($playerId);
                    $identity = new UserIdentity($player->id, $player->steam_name);
                    $duration = 3600 * 24 * 30; // 30 days
                    Yii::app()->user->login($identity, $duration);
                    Yii::app()->request->redirect(Yii::app()->createUrl('player/player', array('id' => $playerId)));
                } catch (Exception $e) {
                    $err = Yii::t('core', $e->getMessage());
                }
            }
            if (!empty($err))
                echo $err;
        } else {
            $loid->identity = "http://steamcommunity.com/openid"; //Setting identifier
            $loid->required = array('namePerson/friendly', 'contact/email'); //Try to get info from openid provider
            $loid->realm = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
            $loid->returnUrl = $loid->realm . $_SERVER['REQUEST_URI']; //getting return URL
            if (empty($err)) {
                try {
                    $url = $loid->authUrl();
                    $this->redirect($url);
                } catch (Exception $e) {
                    $err = Yii::t('core', $e->getMessage());
                }
            }
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionLogs() {
        $this->render('logs');
    }

    public function actionLog($filename) {
        echo '<pre>';
        $file = "protected/data/round-logs/" . $filename;
        $handle = @fopen($file, "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                echo $buffer;
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }
    }

    public function actionGetSteamData() {
        $players = Player::model()->findAll('steam_name IS null');
        foreach ($players as $player)
            $this->actionGetSteamDataById($player->id);
    }

    public function actionGetSteamDataById($id) {
        $player = Player::model()->findByPk($id);
        $player->getSteamApiData();
        $player->save();
    }

}