<?php

class ApiController extends Controller
{

    public function init()
    {
        Yii::app()->errorHandler->errorAction = 'api/error';
    }

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        
    }

    public function actionIndex()
    {
        
    }

    private function print_r_reverse($in)
    {
        $lines = explode("\n", trim($in));
        if (trim($lines[0]) != 'Array')
        {
            // bottomed out to something that isn't an array
            return $in;
        }
        else
        {
            // this is an array, lets parse it
            if (preg_match("/(\s{5,})\(/", $lines[1], $match))
            {
                // this is a tested array/recursive call to this function
                // take a set of spaces off the beginning
                $spaces = $match[1];
                $spaces_length = strlen($spaces);
                $lines_total = count($lines);
                for ($i = 0; $i < $lines_total; $i++)
                {
                    if (substr($lines[$i], 0, $spaces_length) == $spaces)
                    {
                        $lines[$i] = substr($lines[$i], $spaces_length);
                    }
                }
            }
            array_shift($lines); // Array
            array_shift($lines); // (
            array_pop($lines); // )
            $in = implode("\n", $lines);
            // make sure we only match stuff with 4 preceding spaces (stuff for this array and not a nested one)
            preg_match_all("/^\s{4}\[(.+?)\] \=\> /m", $in, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            $pos = array();
            $previous_key = '';
            $in_length = strlen($in);
            // store the following in $pos:
            // array with key = key of the parsed array's item
            // value = array(start position in $in, $end position in $in)
            foreach ($matches as $match)
            {
                $key = $match[1][0];
                $start = $match[0][1] + strlen($match[0][0]);
                $pos[$key] = array($start, $in_length);
                if ($previous_key != '')
                    $pos[$previous_key][1] = $match[0][1] - 1;
                $previous_key = $key;
            }
            $ret = array();
            foreach ($pos as $key => $where)
            {
                // recursively see if the parsed out value is an array too
                $ret[$key] = $this->print_r_reverse(substr($in, $where[0], $where[1] - $where[0]));
            }
            return $ret;
        }
    }

    public function actionTestSave()
    {
        $liveRound = new LiveRound();
        $liveRound->server_id = 1;
        $sDate = date("Y-m-d H:i:s");
        $liveRound->last_updated = $sDate;
        $liveRound->save();
        echo "saved;";
    }

    public function actionTeststatus()
    {
        $playerCount = 0;
        $dir = Yii::app()->params['logDirectory'] . "status/";
        $_POST = $this->print_r_reverse(file_get_contents($dir . "test/028b5de10bdac44ce739d067a3ca8f81-poststatus-1360171403-41610.txt"));
        $players = json_decode($_POST['players']);
        $server = Server::model()->findByAttributes(array('server_key' => $_POST['key']));
        $sDate = date("Y-m-d H:i:s");
        $server->last_updated = $sDate;
        $server->last_state = intval($_POST['state']);

        if (isset($_POST['map']))
            $map = Map::model()->findByAttributes(array('name' => trim($_POST['map'])));

        if (isset($map))
            $server->last_map = $map->id;
        else
            $server->last_map = null;


        if (isset($server) && isset($players) && is_array($players))
        {
            foreach ($players as $player)
            {
                $dbplayer = Player::model()->findByAttributes(array('steam_id' => '' . $player->steamId));
                if (isset($dbplayer) && $player->dc == false)
                {
                    $playerCount++;
                    echo $dbplayer->steam_name;
                    echo "steam: " . $dbplayer->steam_id;
                    $dbplayer->last_server_id = $server->id;
                    $dbplayer->last_seen = $sDate;
                    $dbplayer->update();
                }
            }
        }
        $server->last_player_count = $playerCount;
        $server->gametime = intval($_POST['gametime']);
        $server->update();
    }

    public function actionGetDevour()
    {
        $dir = Yii::app()->params['logDirectory'] . "status/devourtest.txt";
        $data = file_get_contents($dir);
        echo $data;
    }

    public function actionSendstatusDevour()
    {
        $dir = Yii::app()->params['logDirectory'] . "status/devourtest.txt";
        file_put_contents($dir, $_POST['data']);
        print_r($_POST);
    }

    public function actionSendstatus()
    {
        $playerCount = 0;
        if (isset($_POST['key']))
        {
//            $dir = Yii::app()->params['logDirectory'] . "status/";
            $log = "";
            $log .= "Starting\n";

            if (isset($_POST['players']))
                $players = json_decode($_POST['players']);

            $server = Server::model()->findByAttributes(array('server_key' => $_POST['key']));
            if (!isset($server))
            {
                throw new Exception('Unable to find server.', 404, null);
            }
            $transaction = Yii::app()->db->beginTransaction();
            $log .= "server found\n";
            $sDate = date("Y-m-d H:i:s");
            $server->last_updated = $sDate;
            $server->last_state = intval($_POST['state']);
            $liveRound = LiveRound::model()->findByAttributes(array('server_id' => $server->id));

            $isNewRound = false;
            if (!isset($liveRound))
            { //if first time
                $liveRound = new LiveRound();
                $log .= "liveround not found\n";
                $isNewRound = true;
                $liveRound->setIsNewRecord(true);
            }
            else
                $liveRound->setIsNewRecord(false);

            $liveRound->server_id = $server->id;

            if (isset($players))
            {
                $pAmount = 0;
                foreach ($players as $player)
                {
                    if ($player->dc == false)
                        $pAmount++;
                }

                $liveRound->players = $pAmount;
            }
            else
                $liveRound->players = 0;

            $liveRound->last_updated = $sDate;
            $liveRound->gametime = intval($_POST['gametime']);

            if ($isNewRound)
                $liveRound->save();
            else
                $liveRound->update();

            if (isset($_POST['map']))
                $map = Map::model()->cache(60 * 30)->findByAttributes(array('name' => trim($_POST['map'])));

            if (isset($map))
            {
                $server->last_map = $map->id;
                $log .= "map set . " . $map->name . "\n";
            }
            else
            {
                $log .= "Map not set\n";
                $server->last_map = null;
            }


            if (isset($players) && is_array($players))
            {
                foreach ($players as $player)
                {
                    $dbplayer = Player::model()->findByAttributes(array('steam_id' => '' . $player->steamId));
                    if (isset($dbplayer))
                    {
                        $playerCount++;
                        $dbplayer->last_server_id = $server->id;
                        if ($player->dc == false)
                            $dbplayer->last_seen = $sDate;

                        $livePlayer = LivePlayer::model()->findByAttributes(array('player_id' => $dbplayer->id));
                        $isNewPlayer = false;
                        if (!isset($livePlayer))
                        {
                            $log .= "Live player not found.. adding\n";
                            $livePlayer = new LivePlayer();
                            $isNewPlayer = true;
                        }
                        else
                            $livePlayer->setIsNewRecord(false);

                        $livePlayer->player_id = $dbplayer->id;
                        if ($player->dc == false)
                            $livePlayer->last_updated = $sDate;

                        $livePlayer->json = json_encode($player);
                        $livePlayer->live_round_id = $liveRound->primaryKey;

                        $log .= "Savign liveplayer\n";
                        if ($isNewPlayer)
                            $livePlayer->save();
                        else
                            $livePlayer->update();

                        $log .= "Updating player" . $dbplayer->steam_name . "\n";
                        if (!$dbplayer->update())
                            $log.= 'player update failed' . print_r($dbplayer->getErrors(), true);
                    }
                    else
                        $log .= 'player is not set: ' . $player->steamId;
                }
            }
            $server->last_player_count = $playerCount;
            $server->gametime = intval($_POST['gametime']);
            $log .= "Saving server and live round\n";
            $server->update();

            $transaction->commit();
            //echo $log;
            echo "STATUS_OK";
        }
        else
            echo "NO_KEY";
    }

    public function actionUpdatemapdata()
    {
        print_r($_POST);
        if (!isset($_POST['secret']) || !isset($_POST['mapName']) || !isset($_POST['jsonvalues']) || $_POST['secret'] != "jokukovasalasana")
            throw new CHttpException(401, "Invalid data");

        $map = Map::model()->findByAttributes(array('name' => trim($_POST['mapName'])));

        if (!isset($map))
            throw new CHttpException(404, "Map not found");

        $map->jsonvalues = $_POST['jsonvalues'];
        $map->name = $map->name;
        if ($map->update())
            echo "Map updated";
        else
            echo "Map failed to update";
    }

    public function actionSendLog()
    {
        echo " "; //<< important space (prob) for lua timeout
        if (isset($_POST['last_part']) && $_POST['last_part'] != 1)
            echo '{"info":"LOG_RECEIVED_OK"}';
        ob_flush();

        //Receive log
        $log = '';
        if (isset($_POST['roundlog']))
            $log .= $_POST['roundlog'];
        else
            throw new CHttpException(400, 'Server log missing');
        if (strlen($log) == 0)
            throw new CHttpException(400, 'Server log empty');
        if (!isset($_POST['key']))
            throw new CHttpException(400, 'Server key missing');
        if (!isset($_POST['part_number']))
            throw new CHttpException(400, 'Part number parameter missing');
        $partNumber = intval($_POST['part_number']);
        if (!isset($_POST['last_part']))
            throw new CHttpException(400, 'Last number parameter missing');

        //for DEV output        

        $server = Server::model()->findByAttributes(array('server_key' => $_POST['key']));

        $server->ip = $_SERVER['REMOTE_ADDR'];
        $server->save();
        if (!isset($server))
            throw new CHttpException(401, 'Invalid Server Key');

        //Create log file
        if ($partNumber == 1)
        {

            $logName = "round-log-" . date('d-m-Y-H-i-s') . '-' . $partNumber . '-' . $server->id;
        }
        else
        {
            //Get log file
            $path = Yii::app()->params['logDirectory'] . 'incomplete/';
            $dir = opendir($path);
            $list = array();
            while ($file = readdir($dir))
            {
                if ($file != '.' and $file != '..')
                {
                    // add the filename, to be sure not to
                    // overwrite a array key
                    $ctime = filectime($path . $file) . ',' . $file;
                    $list[$ctime] = $file;
                }
            }
            closedir($dir);
            krsort($list);
            foreach ($list as $fileName)
            {
                $logName = $fileName;
                $fileName = explode('-', $fileName);
                $serverId = array_pop($fileName);
                $previousPartNumber = array_pop($fileName);
                if ($serverId == $server->id)
                    break;
            }
            Yii::log('---------------------', 'info', 'api.sendLog');
            Yii::log('Server IP: ' . $_SERVER['REMOTE_ADDR'], 'info', 'api.sendLog');
            Yii::log('Server ID: ' . $server->id, 'info', 'api.sendLog');
            Yii::log('Server key: ' . $server->server_key, 'info', 'api.sendLog');
            Yii::log('Server name: ' . $server->name, 'info', 'api.sendLog');
            Yii::log('Log name: ' . $logName, 'info', 'api.sendLog');
            Yii::log('Previous part: ' . $previousPartNumber, 'info', 'api.sendLog');
            Yii::log('This part: ' . $partNumber, 'info', 'api.sendLog');
            if ($_POST['last_part'])
                Yii::log('Is last part', 'info', 'api.sendLog');
            else
                Yii::log('Is not last part', 'info', 'api.sendLog');
            if ($previousPartNumber != $partNumber - 1 && $previousPartNumber != $partNumber)
                throw new CHttpException(400, 'Log part missing. Make sure you are using different config_path for each server! Do not copy paste ns2stats_advanced_settings.json file! Server key: ' . $_POST['key']);
            unset($dir);
            unset($list);
        }

        //Save log
        $logPath = Yii::app()->params['logDirectory'] . 'incomplete/' . $logName;
        $fh = fopen($logPath, 'a') or die("can't open file");
        fwrite($fh, $log);
        fclose($fh);
        unset($fh);
        unset($log);

        //Parse log
        if ($_POST['last_part'] == 1)
        {
            $logDirectory = Yii::app()->params['logDirectory'] . 'failed/';
            $logPath = $logName;
            rename(Yii::app()->params['logDirectory'] . 'incomplete/' . $logName, $logDirectory . $logName);
            $logParser = new LogParser();
            $roundId = $logParser->createRound($logDirectory, $logPath, $server->id);
        }
        else
        {
            $newLogName = "round-log-" . date('d-m-Y-H-i-s') . '-' . $partNumber . '-' . $server->id;
            rename(Yii::app()->params['logDirectory'] . 'incomplete/' . $logName, Yii::app()->params['logDirectory'] . 'incomplete/' . $newLogName);
        }
        Yii::log('Execution time: ' . Yii::getLogger()->getExecutionTime(), 'info', 'api.sendLog');
    }

    public function actionParseLog($roundId, $logPath, $serverId)
    {
        if (Yii::app()->user->isSuperAdmin() || $_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR'])
        {
            ignore_user_abort(true);
            $logParser = new LogParser();
//        $logPath = Yii::app()->params['logDirectory'] . 'incomplete/' . $logName;
            $logParser->parse($logPath, $serverId, $roundId);
        }
        else
            throw new CHttpException(401, 'You do not have permission to access this page.');
    }

    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error)
            echo $error['message'];
    }

    public function actionHelp($id, $key)
    {
        $server = Server::model()->findByAttributes(array('server_key' => $key));
        $this->renderPartial('help', array(
            'steamId' => $id,
            'server' => $server,
        ));
    }

    public function actionServerinfo($id, $key)
    {
        $this->renderPartial('serverinfo', array(
            'steamId' => $id,
            'key' => $key,
        ));
    }

    public function actionGenerateKey($s)
    {

        //do something
        if ($s != "7g94389u3r89wujj3r892jhr9fwj")
            return;

        $model = new Server;

        $model->server_key = md5(uniqid(uniqid(), true));
        $model->created = time();

        $model->save();

        $this->renderPartial('generateKey', array(
            'model' => $model,
        ));
    }

    public function actionVerifyServer($id, $key, $s)
    {

        if ($s != "479qeuehq2829")
            return;

        if (isset($key))
            $server = Server::model()->findByAttributes(array('server_key' => $key));

        if (!isset($server))
            return;

        if (isset($id))
            $player = Player::model()->findByAttributes(array('steam_id' => '' . $id));

        if (!isset($player))
        {
            echo "Can't find you in our database, please login in http://ns2stats.org or play game or two.";
            return;
        }

        $server->admin_id = $player->id;
        $server->save();

        $this->renderPartial('adminVerify', array(
            'server' => $server
        ));
    }

    public function actionVerifyKey($key)
    {
        if (isset($key))
            $server = Server::model()->findByAttributes(array('server_key' => $key));

        if (!isset($server))
        {
            $data = array('valid' => false);
        }
        else
            $data = array('valid' => true);

        Json::printJSON($data);
    }

    public function actionMotd($id, $a, $key)
    {
        $server = Server::model()->findByAttributes(array('server_key' => $key));
        $this->renderPartial('motd', array(
            'server' => $server,
            'steamId' => $id,
            'key' => $key,
            'serverVersion' => $a,
        ));
    }

    public function actionFind($id, $a, $b)
    {
        $this->renderPartial('find', array(
            'steamId' => $id,
            'a' => $a,
            'b' => $b,
        ));
    }

    public function actionRank($id)
    {
        $player = Player::model()->findByAttributes(array('steam_id' => '' . $id));
        if (!isset($player))
        {
            echo "Can't find you.";
            return;
        }

        $this->renderPartial('rank', array(
            'player' => $player,
        ));
    }

    public function actionStats($id)
    {
        $player = Player::model()->findByAttributes(array('steam_id' => '' . $id));
        $this->renderPartial('stats', array(
            'player' => $player,
        ));
    }

    public function actionTopKills()
    {
        $connection = Yii::app()->db;
        $command = $connection->createCommand(
                'SELECT
           player.steam_name,
           COUNT(death.id) as kills
           FROM player_round,player,death
           WHERE
           player.id = player_round.player_id and
           player_round.id = death.attacker_id
           GROUP BY player.steam_name
           ORDER BY kills DESC
           LIMIT 10
           ');

        $data = $command->queryAll();
        $this->renderPartial('topkills', array(
            'data' => $data,
        ));
    }

    public function actionPrivate($key)
    {

        $server = Server::model()->findByAttributes(array('server_key' => $key));

        $this->renderPartial('private', array(
            'server' => $server,
        ));
    }

    public function actionLogintest($id, $code)
    {
        if (!is_numeric($id))
            return false;

        $player = Player::model()->findByAttributes(array('steam_id' => '' . $id));


        if (isset($player))
        {
            if (!isset($player->code))
                throw new CHttpException(401, 'Your code is not set, you need to login at www.ns2stats.org and you get it.');

            if ($code == $player->code)
            {
                $this->renderPartial('logintest', array(
                    'player' => $player,
                ));
            }
            else
                throw new CHttpException(401, "Invalid code, use 'stats login your_code' to set your code.");
        }
        else
            throw new CHttpException(404, "Can't find you yet, please play one 4+v4+ round and try again.");
    }

    public function actionHide($id, $code)
    {
        if (!is_numeric($id))
            return false;

        $player = Player::model()->findByAttributes(array('steam_id' => '' . $id));


        if (isset($player))
        {
            if (!isset($player->code))
                throw new CHttpException(401, 'Your code is not set, you need to login at www.ns2stats.org and set it.');

            if ($code == $player->code)
            {

                if ($player->hidden)
                    $player->hidden = false;
                else
                    $player->hidden = true;

                $player->save();

                $this->renderPartial('hide', array(
                    'player' => $player,
                ));
            }
            else
                throw new CHttpException(401, "Invalid code, use 'stats code your_code' to set your code.");
        }
        else
            throw new CHttpException(404, "Can't find you yet, please play one 5+v5+ round and try again.");
    }

    public function actionServer($key)
    {
        $server = Server::model()->findByAttributes(array('server_key' => $key));
        if (isset($server))
        {
            $response = array(
                'id' => $server->id,
                'name' => $server->name,
                'ip' => $server->ip,
                'port' => $server->port,
                'country' => $server->country
            );

            Json::printJSON($response);
        }
        else
        {
            $response = array(
                'error' => 'Invalid server key. Server not found.'
            );
            Json::printJSON($response);
        }
    }

    public function actionPlayer()
    {
        if (isset($_GET['steam_id']))
        {
            $steamId = $_GET['steam_id'];
            $steamId = SteamApi::PublicIdToSteamId($steamId);
            $players = Player::model()->cache(60 * 30)->findByAttributes(array('steam_id' => '' . $steamId));
        }
        else if (isset($_GET['steam_name']))
        {
            $steamName = $_GET['steam_name'];
            $players = Player::model()->cache(60 * 30)->findAllByAttributes(array('steam_name' => $steamName));
        }
        else if (isset($_GET['ns2_id']))
        {
            $steamId = $_GET['ns2_id'];
            $players = Player::model()->cache(60 * 30)->findByAttributes(array('steam_id' => '' . $steamId));
        }
        else
            throw new CHttpException(401, "Missing player name or id.");

        if (!isset($players))
            throw new CHttpException(404, "Player not found");

        if (!is_array($players))
            $players = array($players);
        $response = array();
        foreach ($players as $player)
        {
            $nicknames = array();
            foreach (Player::getNickList($player->id) as $nickname)
            {
                $nicknames[] = $nickname['name'];
            }
            $roundResults = Player::getRoundResults($player->id);
            $marineRoundResults = Player::getRoundResults($player->id, 1);
            $alienRoundResults = Player::getRoundResults($player->id, 2);
            $marineCommanderRoundResults = Player::getLifeformRoundResults($player->id, 'marine_commander');
            $alienCommanderRoundResults = Player::getLifeformRoundResults($player->id, 'alien_commander');
            $response[] = array(
                'name' => $player->steam_name,
                'player_page_id' => $player->id,
                'nationality' => $player->country,
                'kills' => Player::getKillsById($player->id),
                'deaths' => Player::getDeaths($player->id),
                'score' => Player::getScore($player->id),
                'time_played' => Player::getTimePlayed($player->id),
                'highest_kill_streak' => Player::getKillStreak($player->id),
                'longest_survival' => Player::getLongestSurvival($player->id),
                'nicknames' => $nicknames,
                'wins' => $roundResults[0]['count'],
                'losses' => $roundResults[1]['count'],
                'commander' => array(
                    'wins' => $marineCommanderRoundResults[0]['count'] + $alienCommanderRoundResults[0]['count'],
                    'losses' => $marineCommanderRoundResults[1]['count'] + $alienCommanderRoundResults[1]['count'],
                ),
                'marine' => array(
                    'kills' => Player::getKillsById($player->id, 1),
                    'deaths' => Player::getDeaths($player->id, 1),
                    'wins' => $marineRoundResults[0]['count'],
                    'losses' => $marineRoundResults[1]['count'],
                    'elo' => array(
                        'rating' => $player->marine_win_elo,
                        'ranking' => $player->getRanking('marine_win_elo'),
                    ),
                    'commander' => array(
                        'wins' => $marineCommanderRoundResults[0]['count'],
                        'losses' => $marineCommanderRoundResults[1]['count'],
                        'elo' => array(
                            'rating' => $player->marine_commander_elo,
                            'ranking' => $player->getRanking('marine_commander_elo'),
                        ),
                    ),
                ),
                'alien' => array(
                    'kills' => Player::getKillsById($player->id, 2),
                    'deaths' => Player::getDeaths($player->id, 2),
                    'wins' => $alienRoundResults[0]['count'],
                    'losses' => $alienRoundResults[1]['count'],
                    'elo' => array(
                        'rating' => $player->alien_win_elo,
                        'ranking' => $player->getRanking('alien_win_elo'),
                    ),
                    'commander' => array(
                        'wins' => $alienCommanderRoundResults[0]['count'],
                        'losses' => $alienCommanderRoundResults[1]['count'],
                        'elo' => array(
                            'rating' => $player->alien_commander_elo,
                            'ranking' => $player->getRanking('alien_commander_elo'),
                        ),
                    ),
                ),
            );
        }
        Json::printJSON($response);
    }

    public function actionOnePlayer()
    {
        if (isset($_GET['steam_id']))
        {
            $steamId = SteamApi::PublicIdToSteamId($_GET['steam_id']);
            $player = Player::model()->cache(60 * 30)->findByAttributes(array('steam_id' => '' . $steamId));
        }
        else if (isset($_GET['steam_name']))
        {
            $steamName = $_GET['steam_name'];
            $player = Player::model()->cache(60 * 30)->findAllByAttributes(array('steam_name' => $steamName));
        }
        else if (isset($_GET['ns2_id']))
        {
            $steamId = $_GET['ns2_id'];
            $player = Player::model()->cache(60 * 30)->findByAttributes(array('steam_id' => '' . $steamId));
        }
        else
            throw new CHttpException(401, "Missing player name or id.");

        if (!isset($player))
            throw new CHttpException(404, "Player not found");

        if (is_array($player))
        {
            $players = array();
            foreach ($player as $p)
            {

                $p->ip = null;
                $p->code = null;
                unset($p->ip);
                unset($p->code);
                $players[] = $p->attributes;
            }

            Json::printJSON($players);
        }
        else
        {
            $player->ip = null;
            $player->code = null;
            unset($player->ip);
            unset($player->code);
            Json::printJSON($player->attributes);
        }
    }

    public function actionGetDeathsForMapAndBuildJSON($mapName, $build, $offset = 0)
    {

        if (!isset($mapName) || !isset($build) || !is_numeric($build))
            throw new CHttpException(401, "Invalid data");

        $map = Map::model()->cache(60 * 30)->findByAttributes(array('name' => $mapName));
        if (!isset($map))
            throw new CHttpException(404, "Unable to find map.");

        if (!is_numeric($offset))
            throw new CHttpException(401, "Invalid offset");

        $connection = Yii::app()->db;
        $command = $connection->createCommand(
                'SELECT * FROM death
                         INNER JOIN player_round ON player_round.id = death.target_id
                         INNER JOIN round ON round.id = player_round.round_id
                         WHERE round.map_id = ' . $map->id . '
                         AND round.build = ' . intval($build) . '
                         LIMIT ' . intval($offset) . ', 1000;
           ');

        $deaths = $command->queryAll();
        foreach ($deaths as $key => $value)
        {
            //print_r($death);
            if (isset($deaths[$key]['attacker_weapon_id']))
                $deaths[$key]['attacker_weapon_name'] = Weapon::model()->findByPk($deaths[$key]['attacker_weapon_id'])->name;
            if (isset($deaths[$key]['target_weapon_id']))
                $deaths[$key]['target_weapon_name'] = Weapon::model()->findByPk($deaths[$key]['target_weapon_id'])->name;
            if (isset($deaths[$key]['target_lifeform_id']))
                $deaths[$key]['target_lifeform_name'] = Lifeform::model()->findByPk($deaths[$key]['target_lifeform_id'])->name;
            if (isset($deaths[$key]['attacker_lifeform_id']))
                $deaths[$key]['attacker_lifeform_name'] = Lifeform::model()->findByPk($deaths[$key]['attacker_lifeform_id'])->name;
        }

        Json::printJSON($deaths);
    }

    public function actionMap($name)
    {
        if (!isset($name) || $name == '')
            throw new CHttpException(401, "Name is not set");

        $map = Map::model()->cache(60 * 30)->findByAttributes(array('name' => $name));

        if (!isset($map))
            throw new CHttpException(401, "Cannot find map by name");

        echo $map->jsonvalues;
    }

    public function actionPlayers()
    {

        $response = array();
        if (isset($_REQUEST['players']))
        {
            $steamIds = explode(",", $_REQUEST['players']);
            foreach ($steamIds as &$steamId)
                $steamId = '' . $steamId;

            $players = Player::model()->cache(60 * 30)->findAllByAttributes(array('steam_id' => $steamIds));
            foreach ($players as $player)
            {
                $responsePlayer = array(
                    'id' => $player['steam_id'],
                    'steam_name' => $player['steam_name'],
                    'ranking' => $player['ranking'],
                    'rating' => $player['rating'],
                    'win_ELO' => $player['win_elo_rating'],
                    'commander_ELO' => $player['commander_elo_rating'],
                    'marine_ELO' => $player['marine_win_elo'],
                    'alien_ELO' => $player['alien_win_elo'],
                    'marine_commander_ELO' => $player['marine_commander_elo'],
                    'alien_commander_ELO' => $player['alien_commander_elo'],
                );
                $response[] = $responsePlayer;
            }
        }
        else
            throw new CHttpException(401, "Missing player list.");
        Json::printJSON($response);
    }

}
