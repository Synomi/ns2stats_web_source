<?php

class UpdateController extends Controller {

    /**
     * Resolves the country of a players who don't yet have country by IP
     * @throws CHttpException
     */
    public function actionGetPlayerCountry() {
        ignore_user_abort(1);
        if (Yii::app()->user->isSuperAdmin() || $_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR']) {
            $players = Player::model()->findAll('country IS null AND ip IS NOT null');
            $i = 0;
            $ipInfoDB = new IpInfoDB();
            echo '<pre>';
            foreach ($players as $player) {
                $player->country = $ipInfoDB->getCountry($player->ip);
                $player->save();
                if ($i > 970)
                    break;
                $i++;
            }
        }
        else
            throw new CHttpException(401, 'You do not have permission to access this page.');
    }

    public function actionGetServerCountry() {
        ignore_user_abort(1);
        if (Yii::app()->user->isSuperAdmin() || $_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR']) {
            $servers = Server::model()->findAll('country IS null AND ip IS NOT null');
            $i = 0;
            $ipInfoDB = new IpInfoDB();
            echo '<pre>';
            foreach ($servers as $server) {
                $server->country = $ipInfoDB->getCountry($server->ip);
                $server->save();
                if ($i > 30)
                    break;
                $i++;
            }
        }
        else
            throw new CHttpException(401, 'You do not have permission to access this page.');
    }

    public function actionStartParseLog() {
//        ignore_user_abort(1);
        set_time_limit(55);
        while (1 == 1) {
            $rounds = Round::model()->findAll('parse_status = 1');
            foreach ($rounds as $round) {
                $this->startParse($round->id);
            }
            die();
            sleep(15);
        }
    }

    protected function startParse($roundId) {
        $round = Round::model()->findByPk($roundId);
        $command = 'elinks "' . Yii::app()->createAbsoluteUrl('api/parselog', array('logPath' => Yii::app()->params['logDirectory'] . 'failed' . '/' . $round->log_file, 'roundId' => $round->id, 'serverId' => $round->server_id)) . '"  --config-file ' . Yii::app()->basePath . '/config/elinks.conf > /dev/null';
//        var_dump($command);
        exec($command);
    }

}