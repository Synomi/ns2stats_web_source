<?php

class LogParser
{

    private $log = array(); //Json log
    private $playerRounds = array(); //All player round objects
    private $playerLifeforms = array(); //All player lifeform objects
//    private $playerWeapons = array();
    private $roundStructures = array();
    private $lifeforms = array();
    private $weapons = array();
    private $structures = array();
    private $upgrades = array();
    private $roundId; //Id of the round
    private $serverId;
    private $round;
    private $pickables = array();
    private $message;
    private $startTime = null;
    private $logRandomId = 0;

    function parseStarted()
    {
        //log speed
        $gentime = microtime();
        $gentime = explode(' ', $gentime);
        $gentime = $gentime[1] + $gentime[0];
        $this->startTime = $gentime;
    }

    function addTimeStamp($text)
    {
        return;
        //end of your page
        $gentime = microtime();
        $gentime = explode(' ', $gentime);
        $gentime = $gentime[1] + $gentime[0];
        $pg_end = $gentime;
        $totaltime = ($pg_end - $this->startTime);
        $showtime = number_format($totaltime, 4, '.', '');
        $tmp = $showtime . " s \t: " . $text . "\n";

        $filename = Yii::app()->params['logDirectory'] . "parselogs/" . "log-" . $this->serverId . "-" . $this->logRandomId;

        $fp = fopen($filename, "a");
        fwrite($fp, $tmp);
        fclose($fp);
    }

    protected function loadLog($logPath)
    {
        $this->log = array();
        Yii::beginProfile('loadLog');
        //Load the whole log
        $logParser = new LogParser();
        $handle = @fopen($logPath, "r");
        if ($handle)
        {
//            $start_memory = memory_get_usage();

            while (($buffer = fgets($handle)) !== false)
            {
                $this->log[] = json_decode($buffer, true);
//                if (!is_array($this->log[count($this->log)-1]))
//                    die("Not array: " . print_r($this->log[count($this->log)-1],true));
//                echo "mem usage: " . ((memory_get_usage() - $start_memory)/1024/1024) . " M<br />";
//                echo "counT:" . count($this->log);
            }
            if (!feof($handle))
            {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }
        Yii::endProfile('loadLog');
    }

    public function createRound($logDirectory, $logFile, $serverId)
    {
        $this->message = new stdClass();
        $this->loadLog($logDirectory . $logFile);
        $round = new Round();
        //Round start
        Yii::beginProfile('roundStart');
        foreach ($this->log as $logRow)
        {
            if ($logRow['action'] == 'game_start')
            {
                //2013-06-14 16:32:01
                $dateInfo = date_parse_from_format('Y-M-d H:i:s', $logRow['time']);                
                $unixTimestamp = mktime(
                        $dateInfo['hour'], $dateInfo['minute'], $dateInfo['second'], $dateInfo['month'], $dateInfo['day'], $dateInfo['year'], $dateInfo['is_dst']
                );
                $round->start = $unixTimestamp; //save as temporary time
                break;
            }
        }
        Yii::endProfile('roundStart');
        //Round end
        Yii::beginProfile('roundEnd');
        foreach ($this->log as $logRow)
        {
            if ($logRow['action'] == 'game_ended')
            {
                $this->checkVersion($logRow['statsVersion']);
                $server = Server::model()->findByPk($serverId);
                $server->name = $logRow['serverName'];

                if (isset($logRow['tags']) && $logRow['tags'] != null)
                    $server->tags = json_encode($logRow['tags']);

                $server->stats_version = $logRow['statsVersion'];

                if ($logRow['private'] == true) //change in b227, tournament mode is now set in game/config
                    $server->private = 1;
                else
                    $server->private = 0;

                //serverInfo by players
                $tmp = explode(":", $logRow['serverInfo']["IP"]);
                if (isset($tmp) && count($tmp) == 2)
                {
                    $server->ip = $tmp[0];
                    $server->port = $tmp[1];

                    //password available:
                    //$logRow['serverInfo']["password"]
                }

                $server->save();
                $round->private = $server->private;
                $round->server_id = $serverId;
                $round->map_id = Map::getIdByName($logRow['map']);

                $dateInfo = date_parse_from_format('Y-M-d H:i:s', $logRow['time']);                
                $unixTimestamp = mktime(
                        $dateInfo['hour'], $dateInfo['minute'], $dateInfo['second'], $dateInfo['month'], $dateInfo['day'], $dateInfo['year'], $dateInfo['is_dst']
                );
                $round->end =  $unixTimestamp;
                $round->added = strtotime(date('Y-m-d H:i:s'));
                $round->winner = $logRow['winner'];
                $round->team_1_start = $logRow['start_location1'];
                $round->team_2_start = $logRow['start_location2'];
                $round->build = $logRow['version'];
                $round->log_file = $logFile;
                break;
            }
        }
        Yii::endProfile('roundEnd');
        if ($round->start == 0)
            throw new CHttpException(404, "Log is not finished");
        die($round->start . " end: " . $round->end);
        if ($round->save())
        {
            //print messages
            $id = Yii::app()->db->getLastInsertID();
            $this->message->link = "/round/round/" . $id;
            echo json_encode($this->message);
            ob_flush();
            
            return $id;
        }
        else
            throw new Exception("saving of round failed" . print_r($round->getErrors(), true), 401, null);
    }

    protected function checkVersion($version)
    {
        $versionNumber = preg_replace("/[^1234567890]/", "", $version);
        if ($versionNumber < Yii::app()->params['minimumStatsVersion'])
        {
            $this->message->other = 'NS2Stats mod is too old version. Stats are not saved before you update NS2Stats!';
            die();
        }
        //Not latest version
        if ($version != Yii::app()->params['currentStatsVersion'])
            $this->message->other = 'There is a new version of NS2Stats mod available. Please update!';
    }

    public function parse($logPath, $serverId, $roundId)
    {
        set_time_limit(5 * 60);
        ini_set('memory_limit', '512M');
        $this->loadLog($logPath);
        $this->serverId = $serverId;
        $this->logRandomId = rand(1, 999999);
        $this->parseStarted();
        $this->addTimeStamp("PARSE_STARTED");

        $this->roundId = $roundId;
        $this->round = Round::model()->findByPk($roundId);
        $this->round->parse_status = 2;
        $this->round->save();
//        $this->roundId = $this->createRound();
        $started = 0;
        /* First loop
         * The First loop saves everything needed to display the end round scoreboard
         */
        Yii::beginProfile('firstLoop');
        $transaction = Yii::app()->db->beginTransaction();
        try
        {
            foreach ($this->log as $logRow)
            {
//            if (Yii::app()->request->getQuery('debug')) {
//                echo json_encode($logRow) . '<br />';
//                ob_flush();
//            }
                //Create starting players 
                if ($logRow['action'] == 'player_list_start' && $started == 0)
                {
                    $this->createPlayerRounds($logRow['list']);
                    $started = 1;
                }

                if ($started)
                {
                    //Create death
                    if ($logRow['action'] == 'death')
                    {
                        if ($logRow['attacker_weapon'] == 'natural causes')
                            $this->createDeath($logRow);
                        else if (isset($logRow['attacker_steamId']))
                            $this->createDeath($logRow);
                    }
                    //Join team
                    if ($logRow['action'] == 'player_join_team')
                    {
                        if (is_numeric($logRow['steamId']) && $logRow['steamId'] > 0)
                        {
                            $this->joinTeam($logRow);
                        }
                    }
                    //Create disconnects
                    if ($logRow['action'] == 'disconnect')
                    {
                        if (is_numeric($logRow['steamId']) && $logRow['steamId'] > 0)
                        {
                            $this->leaveTeam($logRow);
                        }
                    }
                    //Player list
                    if ($logRow['action'] == 'player_list_end')
                    {
                        $this->endRound($logRow);
                    }
                    //Save mods
                    if ($logRow['action'] == 'game_ended')
                    {
                        if (isset($logRow['mods']))
                            $this->saveMods($logRow['mods']);
                    }
                }
            }
            $transaction->commit();
        }
        catch (Exception $e)
        {
            $transaction->rollback();
        }
        Yii::endProfile('firstLoop');
        $playerRounds = PlayerRound::model()->findAllByAttributes(array('round_id' => $this->roundId));
        $this->deleteByPlayerCount(9.5, $playerRounds);
        if ($this->round->private)
            $this->findTeams($playerRounds);

        $this->round->parse_status = 3;
        $this->round->save();
        $started = 0;
        /*
         * Second loop
         * The seconds loop saves rest of the data displayed in the end round page
         */
        Yii::beginProfile('secondLoop');
        $transaction = Yii::app()->db->beginTransaction();
        try
        {
            foreach ($this->log as $logRow)
            {
                // if (Yii::app()->request->getQuery('debug')) {
                //    echo json_encode($logRow) . '<br />';
                //    ob_flush();
                //}
                if ($logRow['action'] == 'game_start')
                    $started = 1;


                if ($started)
                {
                    //Create lifeform change
                    if ($logRow['action'] == 'lifeform_change')
                    {
                        if (is_numeric($logRow['steamId']) && $logRow['steamId'] > 0)
                        {
                            $this->createLifeformChange($logRow);
                        }
                    }

                    //Create upgrade
                    if ($logRow['action'] == 'upgrade_started')
                    {
                        $this->createUpgrade($logRow);
                    }
                    //Create upgrade
                    if ($logRow['action'] == 'upgrade_finished')
                    {
                        $this->endUpgrade($logRow);
                    }
                    if ($logRow['action'] == 'structure_dropped')
                    {
                        $this->dropStructure($logRow);
                    }
                    if ($logRow['action'] == 'ghost_destroy')
                    {
                        $this->destroyStructure($logRow, 0, 0, 1);
                    }
                    //Create structure
                    if ($logRow['action'] == 'structure_built')
                    {
                        $this->buildStructure($logRow);
                    }
                    //Kill structure
                    if ($logRow['action'] == 'structure_killed')
                    {
                        $this->destroyStructure($logRow, 1);
                    }
                    //Suicide structure
                    if ($logRow['action'] == 'structure_suicide')
                    {
                        $this->destroyStructure($logRow);
                    }
                    //Recycle structure
                    if ($logRow['action'] == 'structure_recycle')
                    {
                        $this->destroyStructure($logRow, 0, 1);
                    }
                    //Resources gathered
                    if ($logRow['action'] == 'resources_gathered')
                    {
                        $this->resourcesGathered($logRow);
                    }
                }
            }
            $transaction->commit();
        }
        catch (Exception $e)
        {
            $transaction->rollback();
        }
        Yii::endProfile('secondLoop');
        $this->round->parse_status = 4;
        $this->round->save();

        foreach ($this->playerRounds as $playerRound)
        {
            $this->endLifeForm($playerRound->player->steam_id, round($this->round->end - $this->round->start));
        }
        $this->saveELOs();

        $started = 0;
        /*
         * Third loop
         * The third loop saves data not displayed in the end round page
         */
        Yii::beginProfile('thirdLoop');
        $transaction = Yii::app()->db->beginTransaction();
        try
        {
            foreach ($this->log as $logRow)
            {
                //if (Yii::app()->request->getQuery('debug')) {
                //  echo json_encode($logRow) . '<br />';
                //   ob_flush();
                // }
                if ($logRow['action'] == 'game_start')
                    $started = 1;

                if ($started)
                {
                    //Weapon hit
                    if ($logRow['action'] == 'hit_player' || $logRow['action'] == 'structure')
                        $this->hit($logRow);
                    //Drop Pickable
                    if ($logRow['action'] == 'pickable_ability_dropped' || $logRow['action'] == 'pickable_item_dropped')
                        $this->dropPickable($logRow);
                    //Pick Pickable
                    if ($logRow['action'] == 'pickable_ability_picked' || $logRow['action'] == 'pickable_item_picked')
                        $this->pickPickable($logRow);
                    //Destroy Pickable
                    if ($logRow['action'] == 'pickable_ability_destroyed' || $logRow['action'] == 'pickable_item_destroyed')
                        $this->destroyPickable($logRow);

                    //End round
                    if ($logRow['action'] == 'player_list_end')
                    {
                        foreach ($this->roundStructures as $roundStructure)
                        {
                            if (isset($roundStructure->attributes))
                            {
                                $roundStructure->destroy = round($logRow['gametime']);
                                if (isset($roundStructure->attributes))
                                    $roundStructure->save();
                            }
                        }
                        break;
                    }
                }
            }
            $transaction->commit();
        }
        catch (Exception $e)
        {
            $transaction->rollback();
        }
        Yii::endProfile('thirdLoop');
        $this->round->parse_status = 0;
        $this->round->save();
        rename($logPath, Yii::app()->params['logDirectory'] . 'completed/' . $this->round->log_file);
        $this->addTimeStamp("PARSE_FINISHED");
    }

    protected function createPlayerRounds($playerList)
    {
        Yii::beginProfile('createPlayerRounds');
        $this->addTimeStamp("CREATE_PLAYER_ROUNDS");
        foreach ($playerList as $player)
        {
            if (is_numeric($player['steamId']) && $player['steamId'] > 0 && ($player['teamnumber'] == 1 || $player['teamnumber'] == 2))
            {
                Yii::beginProfile('createPlayerRoundIteration');
                $playerId = Player::getIdBySteamId($player['steamId'], $player['ipaddress']);
                $playerModel = Player::model()->findByPk($playerId);
                $playerModel->ip = $player['ipaddress'];
                $playerModel->save();
                $playerRound = new PlayerRound();
                $playerRound->player_id = $playerId;
                $playerRound->round_id = $this->roundId;
                $playerRound->team = $player['teamnumber'];
                $playerRound->score = 0;
                $playerRound->name = $player['name'];
                $playerRound->start = 0;
                $playerRound->save();
                //Set starting lifeform
                $playerRound->id = Yii::app()->db->getLastInsertID();
                $this->playerRounds[$player['steamId']] = $playerRound;
                $startingLifeFormData = array(
                    'steamId' => $player['steamId'],
                    'gametime' => 0,
                    'lifeform' => $player['lifeform'],
                );
                $this->createLifeformChange($startingLifeFormData);
                Yii::endProfile('createPlayerRoundIteration');
            }
        }
        Yii::endProfile('createPlayerRounds');
    }

    protected function createDeath($logRow)
    {
        $this->addTimeStamp("CREATE_DEATH");
        $death = new Death();
        //Has attacker
        if (isset($logRow['attacker_steamId']))
        {
            $death->attacker_team = $logRow['attacker_team'];
            $death->attacker_armor = $logRow['attacker_armor'];
            $death->attacker_health = round($logRow['attacker_hp']);
            $death->attacker_x = $logRow['attackerx'];
            $death->attacker_y = $logRow['attackery'];
            $death->attacker_z = $logRow['attackerz'];
            //Attacker is not a bot
            if (is_numeric($logRow['attacker_steamId']) && $logRow['attacker_steamId'] > 0)
            {
//            if (!isset($this->playerRounds[$logRow['attacker_steamId']])) {
//                foreach ($this->playerRounds as $player)
//                    var_dump($player->attributes);
//                var_dump($logRow);
//                die();
//            }
                $attacker = $this->playerRounds[$logRow['attacker_steamId']];
                $death->attacker_id = $attacker->id;
                $death->attacker_weapon_id = $this->getWeaponIdByName($logRow['attacker_weapon']);
                $death->attacker_lifeform_id = $this->getLifeformIdByName($logRow['attacker_lifeform']);

                if (is_numeric($logRow['target_steamId']) && $logRow['target_steamId'] > 0)
                {
                    Yii::beginProfile('calculateKillEloRank');
                    $target = $this->playerRounds[$logRow['target_steamId']];
                    $eloCalculator = new EloCalculator($attacker->player->kill_elo_rating, $target->player->kill_elo_rating, 1, 0);
                    $eloResult = $eloCalculator->getNewRatings();
                    $attacker->player->kill_elo_rating = round($eloResult['a']);
                    $target->player->kill_elo_rating = round($eloResult['b']);
                    Yii::endProfile('calculateKillEloRank');
                }
            }
        }
        if (is_numeric($logRow['target_steamId']) && $logRow['target_steamId'] > 0)
        {
            $target = $this->playerRounds[$logRow['target_steamId']];
            $death->target_id = $target->id;
            $death->target_weapon_id = $this->getWeaponIdByName($logRow['target_weapon']);
            $death->target_lifeform_id = $this->getLifeformIdByName($logRow['target_lifeform']);
            $death->target_team = $logRow['target_team'];
            $death->target_x = $logRow['targetx'];
            $death->target_y = $logRow['targety'];
            $death->target_z = $logRow['targetz'];
            $death->target_lifetime = round($logRow['target_lifetime']);
            $death->time = round($logRow['gametime']);
            $death->save();
        }
    }

    protected function createLifeformChange($logRow)
    {
        $this->addTimeStamp("CREATE_LIFEFORM_CHANGE");
        //End old lifeform
        $this->endLifeForm($logRow['steamId'], $logRow['gametime']);
        //Save new lifeform
        if (isset($this->playerRounds[$logRow['steamId']]))
        {
            $playerRound = $this->playerRounds[$logRow['steamId']];
            $playerLifeform = new PlayerLifeform();
            $playerLifeform->player_round_id = $playerRound->id;
            //Separate Alien and Marine Commanders
            if ($logRow['lifeform'] == 'Commander' && $playerRound->team == 1)
                $logRow['lifeform'] = 'Marine Commander';
            if ($logRow['lifeform'] == 'Commander' && $playerRound->team == 2)
                $logRow['lifeform'] = 'Alien Commander';
            $playerLifeform->lifeform_id = $this->getLifeformIdByName($logRow['lifeform']);
            $playerLifeform->start = round($logRow['gametime']);
            $this->playerLifeforms[$logRow['steamId']] = $playerLifeform;
        }
    }

    protected function endLifeForm($steamId, $gameTime)
    {

//        $this->endWeapon($steamId, $gameTime);
        if (!empty($this->playerLifeforms[$steamId]))
        {
            $playerLifeform = $this->playerLifeforms[$steamId];
            $playerLifeform->end = round($gameTime);
//            $pr = PlayerRound::model()->findByPk($playerLifeform->player_round_id);
//            var_dump($playerLifeform->player_round_id);
//            if(!$pr)
//                die();
            $playerLifeform->save();
        }
    }

    protected function joinTeam($logRow)
    {
        $this->addTimeStamp("JOIN_TEAM");
        //End old player round
        $this->leaveTeam($logRow);
        //Join marines or aliens
        if ($logRow['team'] == 1 || $logRow['team'] == 2)
        {
            //Create new player round
            $playerId = Player::getIdBySteamId($logRow['steamId']);
            $playerRound = new PlayerRound();
            $playerRound->player_id = $playerId;
            $playerRound->round_id = $this->roundId;
            $playerRound->team = $logRow['team'];
            $playerRound->score = 0;
            $playerRound->name = $logRow['name'];
//            $playerRound->name = ' asa';
            $playerRound->start = round($logRow['gametime']);
            $playerRound->save();
            $playerRound->id = Yii::app()->db->getLastInsertID();
            $this->playerRounds[$logRow['steamId']] = $playerRound;
        }
    }

    protected function leaveTeam($logRow)
    {
        $this->addTimeStamp("LEAVE_TEAM");
        $this->endLifeForm($logRow['steamId'], $logRow['gametime']);
        //End old player round
        if (!empty($this->playerRounds[$logRow['steamId']]))
        {
            $playerRound = $this->playerRounds[$logRow['steamId']];
            $playerRound->end = round($logRow['gametime']);
            if (isset($logRow['score']))
                $playerRound->score = $logRow['score'];
            $playerRound->save();
//            $this->playerRounds[$logRow['steamId']] = null;
        }
    }

    protected function endRound($logRow)
    {
        $this->addTimeStamp("END_ROUND");
        $playerList = $logRow['list'];

        foreach ($playerList as $player)
        {
            if (is_numeric($player['steamId']) && $player['steamId'] > 0)
            {
                if (isset($this->playerRounds[$player['steamId']]))
                {
                    $playerRound = $this->playerRounds[$player['steamId']];

                    if ($playerRound->team == 1 || $playerRound->team == 2)
                    {

                        $playerRound->score = $player['score'];
                        $playerRound->assists = $player['assists'];
                        $playerRound->finished = 1;
                        $playerRound->end = round($logRow['gametime']);
                        if (isset($playerRound->attributes))
                            $playerRound->save();

                        if (isset($playerRound->player_id))
                        {
                            $playerRound->player->ip = $player['ipaddress'];
                            //rating>
                            $ratingChange = 0;
                            //win+lose
                            if ($this->round->winner == $playerRound->team)
                                $ratingChange += 30;
                            else
                                $ratingChange -= 15;

                            //kills + deaths + assits, kills/deaths done with another way
                            $ratingChange += $player['kills'] * 2;
                            $ratingChange -= $player['deaths'];
                            $ratingChange += $playerRound->assists;

                            //commander bonus (TODO) 
                            //Score bonus (TODO)
                            //modifiers (TODO)
                            //apply rating
                            $playerRound->player->rating += $ratingChange;
                            //rating<
                            $playerRound->player->save();
                        }
                    }


                    foreach ($player['weapons'] as $weapon)
                    {
                        $playerWeapon = new PlayerWeapon();
                        $playerWeapon->player_round_id = $playerRound->id;
                        $playerWeapon->weapon_id = $this->getWeaponIdByName($weapon['name']);
                        $playerWeapon->time = round($weapon['time']);
                        $playerWeapon->miss = round($weapon['miss']);
                        $playerWeapon->player_hit = round($weapon['player_hit']);
                        $playerWeapon->player_damage = round($weapon['player_damage']);
                        $playerWeapon->structure_hit = round($weapon['structure_hit']);
                        $playerWeapon->structure_damage = round($weapon['structure_damage']);
                        $playerWeapon->save();
                    }
                }
            }
        }
    }

    protected function createUpgrade($logRow)
    {
        if (isset($logRow['commander_steamid']))
        {
            $roundUpgrade = new RoundUpgrade();
            $roundUpgrade->round_id = $this->roundId;
            if (isset($logRow['name']))
                $name = $logRow['name'];
            else
                $name = $logRow['upgrade_name'];
            $roundUpgrade->upgrade_id = $this->getUpgradeIdByName($name);
            $roundUpgrade->team = $logRow['team'];

            $roundUpgrade->cost = $logRow['cost'];
            $commander = $this->playerRounds[$logRow['commander_steamid']];
            $roundUpgrade->commander_id = $commander->id;
            $this->upgrades[$logRow['structure_id'] . '-' . $logRow['upgrade_name']] = $roundUpgrade;
        }
    }

    protected function endUpgrade($logRow)
    {
        $roundUpgrade = $this->upgrades[$logRow['structure_id'] . '-' . $logRow['upgrade_name']];
        $roundUpgrade->time = round($logRow['gametime']);
        $roundUpgrade->save();
    }

    protected function dropStructure($logRow)
    {
        if ($logRow['structure_name'] == 'PowerPoint')
            return;
        $roundStructure = new RoundStructure();
        $roundStructure->round_id = $this->roundId;
        $roundStructure->structure_id = $this->getStructureIdByName($logRow['structure_name']);
        $roundStructure->team = $logRow['team'];
        $roundStructure->drop = round($logRow['gametime']);
        $roundStructure->cost = $logRow['structure_cost'];
        $roundStructure->x = $logRow['structure_x'];
        $roundStructure->y = $logRow['structure_y'];
        $roundStructure->z = $logRow['structure_z'];
        if ($logRow['steamId'] > 0)
        {
            $commander = $this->playerRounds[$logRow['steamId']];
            $roundStructure->commander_id = $commander->id;
        }
        if (round($logRow['gametime']) == 0)
            $roundStructure->build = 0;
        $this->roundStructures[$logRow['id']] = $roundStructure;
    }

    protected function buildStructure($logRow)
    {
        if (!in_array($logRow['id'], array_keys($this->roundStructures)))
            return;
        if ($logRow['structure_name'] == 'Egg')
            return;
        $roundStructure = $this->roundStructures[$logRow['id']];
        if (isset($roundStructure->attributes))
        {
            if ($logRow['structure_name'] == 'Hydra')
                if ($roundStructure == null)
                {
                    $this->dropStructure($logRow);
                    $roundStructure = $this->roundStructures[$logRow['id']];
                }
            $roundStructure->build = round($logRow['gametime']);
            if ($logRow['steamId'] > 0)
            {
                $builder = $this->playerRounds[$logRow['steamId']];
                $roundStructure->builder_id = $builder->id;
            }
            $this->roundStructures[$logRow['id']] = $roundStructure;
        }
    }

    protected function destroyStructure($logRow, $killed = 0, $recycled = 0, $ghost = 0)
    {
        if (!in_array($logRow['id'], array_keys($this->roundStructures)))
            return;
        if ($this->roundStructures[$logRow['id']] == null)
            return;
        if ($logRow['structure_name'] == 'MAC' || $logRow['structure_name'] == 'ARC' || $logRow['structure_name'] == 'Drifter')
            return;
        if ($logRow['structure_name'] == 'Egg')
            return;
        $roundStructure = $this->roundStructures[$logRow['id']];
        if ($killed && $logRow['killer_steamId'] > 0)
        {
            $attacker = $this->playerRounds[$logRow['killer_steamId']];
            $roundStructure->attacker_id = $attacker->id;
            $roundStructure->attacker_lifeform_id = $this->getLifeformIdByName($logRow['killer_lifeform']);
            $roundStructure->attacker_weapon_id = $this->getWeaponIdByName($logRow['killerweapon']);
        }
        if ($recycled)
        {
            $roundStructure->recycle_res_back = round($logRow['givenback']);
        }
        if ($ghost)
        {
            $roundStructure->recycle_res_back = $roundStructure->cost;
        }
        $roundStructure->destroy = round($logRow['gametime']);
        if (isset($roundStructure->attributes))
            $roundStructure->save();
        $this->roundStructures[$logRow['id']] = null;
    }

    protected function hit($logRow)
    {
        $hit = new Hit();
        //Has attacker
        if (isset($logRow['attacker_steamId']))
        {
            $hit->attacker_team = $logRow['attacker_team'];
            $hit->attacker_armor = $logRow['attacker_armor'];
            $hit->attacker_health = round($logRow['attacker_hp']);
            $hit->attacker_x = round($logRow['attackerx'], 4);
            $hit->attacker_y = round($logRow['attackery'], 4);
            $hit->attacker_z = round($logRow['attackerz'], 4);
            //Attacker is not a bot
            if (is_numeric($logRow['attacker_steamId']) && $logRow['attacker_steamId'] > 0)
            {
                $attacker = $this->playerRounds[$logRow['attacker_steamId']];
                $hit->attacker_id = $attacker->id;
                $hit->attacker_weapon_id = $this->getWeaponIdByName($logRow['attacker_weapon']);
                $hit->attacker_lifeform_id = $this->getLifeformIdByName($logRow['attacker_lifeform']);
            }
        }
        if ($logRow['action'] == 'hit_player')
            if (isset($logRow['target_steamId']))
            {
                if (is_numeric($logRow['target_steamId']) && $logRow['target_steamId'] > 0)
                {
                    $target = $this->playerRounds[$logRow['target_steamId']];
                    $hit->target_id = $target->id;
                }
            }
        $hit->target_weapon_id = $this->getWeaponIdByName($logRow['target_weapon']);
        $hit->target_lifeform_id = $this->getLifeformIdByName($logRow['target_lifeform']);
        $hit->target_team = $logRow['target_team'];
        if ($logRow['action'] == 'hit_player')
        {
            $hit->target_x = round($logRow['targetx'], 4);
            $hit->target_y = round($logRow['targety'], 4);
            $hit->target_z = round($logRow['targetz'], 4);
        }
        if ($logRow['action'] == 'hit_structure')
        {
            $roundStructure = $this->roundStructures[$logRow['id']];
            $hit->structure_id = $roundStructure->id;
            $hit->target_x = round($logRow['structure_x'], 4);
            $hit->target_y = round($logRow['structure_y'], 4);
            $hit->target_z = round($logRow['structure_z'], 4);
        }
        $hit->damage_type = $logRow['damageType'];
        $hit->damage = $logRow['damage'];
        $hit->time = round($logRow['gametime']);

        $hit->save();
    }

    protected function dropPickable($logRow)
    {
        if ($logRow['name'] == 'nanoshield')
            return;
        $pickable = new Pickable();
        $pickable->drop = round($logRow['gametime']);
        $pickable->cost = $logRow['cost'];
        $pickable->name = $logRow['name'];
        $pickable->team = $logRow['team'];
        $pickable->x = round($logRow['x'], 4);
        $pickable->y = round($logRow['y'], 4);
        $pickable->z = round($logRow['z'], 4);
        if ($logRow['commander_steamid'] > 0)
        {
            $commander = $this->playerRounds[$logRow['commander_steamid']];
            $pickable->commander_id = $commander->id;
        }
        if (isset($logRow['instanthit']))
            if ($logRow['instanthit'])
                $pickable->instant_hit = 1;
            else
                $pickable->instant_hit = 0;
        $this->pickables[$logRow['id']] = $pickable;
    }

    protected function pickPickable($logRow)
    {
        if ($logRow['name'] == 'nanoshield')
            return;
        $pickable = $this->pickables[$logRow['id']];
        $pickable->pick = round($logRow['gametime']);
        $pickable->save();
    }

    protected function destroyPickable($logRow)
    {
        if ($logRow['name'] == 'nanoshield')
            return;
        $pickable = $this->pickables[$logRow['id']];
        $pickable->destroy = round($logRow['gametime']);
        $pickable->save();
    }

    protected function resourcesGathered($logRow)
    {
        $resources = new Resources();
        $resources->time = round($logRow['gametime']);
        $resources->team = $logRow['team'];
        $resources->gathered = $logRow['amount'];
        $resources->round_id = $this->roundId;
        $resources->save();
    }

    /* Cache functions */

    protected function getWeaponIdByName($name)
    {
        if (!isset($this->weapons[$name]))
        {
            $weaponId = Weapon::getIdByName($name);
            $this->weapons[$name] = $weaponId;
        }
        return $this->weapons[$name];
    }

    protected function getLifeformIdByName($name)
    {
        if (!isset($this->lifeforms[$name]))
        {
            $lifeformId = Lifeform::getIdByName($name);
            $this->lifeforms[$name] = $lifeformId;
        }
        return $this->lifeforms[$name];
    }

    protected function getUpgradeIdByName($name)
    {
        if (!isset($this->upgrades[$name]))
        {
            $upgradeId = Upgrade::getIdByName($name);
            $this->upgrades[$name] = $upgradeId;
        }
        return $this->upgrades[$name];
    }

    protected function getStructureIdByName($name)
    {
        if (!isset($this->structures[$name]))
        {
            $structureId = Structure::getIdByName($name);
            $this->structures[$name] = $structureId;
        }
        return $this->structures[$name];
    }

    protected function deleteByPlayerCount($minPlayerCount, $playerRounds)
    {
        if (!YII_DEBUG)
        {
            $timePlayed = 0;
//            var_dump($this->round->attributes);
            foreach ($playerRounds as $playerRound)
            {
//                var_dump($playerRound->attributes);
                $timePlayed += $playerRound->end - $playerRound->start;
            }
            if ($timePlayed <= $minPlayerCount * ($this->round->end - $this->round->start))
            {
                $this->round->delete();
                $this->message->error = 'NOT_ENOUGH_PLAYERS';
                echo json_encode($this->message);
                ob_flush();
                $this->message->error = null;
                $this->addTimeStamp("NOT_ENOUGH_PLAYERS");
                die();
            }
        }
    }

    protected function findTeams($playerRounds)
    {
        $this->addTimeStamp("FIND_TEAMS");
        $team1 = 0;
        $team2 = 0;
        foreach ($playerRounds as $playerRound)
        {
            $playerTeams = PlayerTeam::model()->findAllByAttributes(array('player_id' => $playerRound->player_id));
            $playerTeamList = array();
            foreach ($playerTeams as $playerTeam)
                $playerTeamList[] = $playerTeam->team_id;
            if ($playerRound->team == 1)
                if (is_array($team1))
                    $team1 = array_intersect($team1, $playerTeamList);
                else
                    $team1 = $playerTeamList;
            if ($playerRound->team == 2)
                if (is_array($team2))
                    $team2 = array_intersect($team2, $playerTeamList);
                else
                    $team2 = $playerTeamList;
        }
        if (isset($team1[0]))
            $this->round->team_1 = $team1[0];
        if (isset($team2[0]))
            $this->round->team_2 = $team2[0];


        $this->round->save();
    }

    protected function calculateELOs($playerRounds)
    {
        Yii::beginProfile('calculateElos');
        //Calculate team and commander ELO Ratings
        $team1EloRating = array();
        $marineEloRating = array();
        $team2EloRating = array();
        $alienEloRating = array();
        foreach ($playerRounds as $playerRound)
        {
            if ($playerRound->isCommander())
            {
                if ($playerRound->team == 1)
                {
                    $team1CommanderEloRating = $playerRound->player->commander_elo_rating;
                    $marineCommanderEloRating = $playerRound->player->marine_commander_elo;
                }
                if ($playerRound->team == 2)
                {
                    $team2CommanderEloRating = $playerRound->player->commander_elo_rating;
                    $alienCommanderEloRating = $playerRound->player->alien_commander_elo;
                }
            }
            if ($playerRound->end - $playerRound->start > ($this->round->end - $this->round->start) * 0.5)
            {
                if ($playerRound->team == 1)
                {
                    $team1EloRating[] = $playerRound->player->win_elo_rating;
                    $marineEloRating[] = $playerRound->player->marine_win_elo;
                }
                if ($playerRound->team == 2)
                {
                    $team2EloRating[] = $playerRound->player->win_elo_rating;
                    $alienEloRating[] = $playerRound->player->alien_win_elo;
                }
            }
        }
        $team1EloRating = array_sum($team1EloRating) / count($team1EloRating);
        $team2EloRating = array_sum($team2EloRating) / count($team2EloRating);
        $marineEloRating = array_sum($marineEloRating) / count($marineEloRating);
        $alienEloRating = array_sum($alienEloRating) / count($alienEloRating);

        if (isset($team1CommanderEloRating) && isset($team2CommanderEloRating))
        {
            $team1CommanderEloRating = ($team1CommanderEloRating + $team1EloRating) / 2;
            $team1CommanderEloRating = ($team2CommanderEloRating + $team2EloRating) / 2;
            $marineCommanderEloRating = ($marineCommanderEloRating + $marineEloRating) / 2;
            $alienCommanderEloRating = ($alienCommanderEloRating + $alienEloRating) / 2;
            if ($this->round->winner == 1)
            {
                $eloCalculator = new EloCalculator($team1CommanderEloRating, $team2CommanderEloRating, 1, 0);
                $commanderElos = $eloCalculator->getNewRatings();
                $eloCalculator = new EloCalculator($marineCommanderEloRating, $alienCommanderEloRating, 1, 0);
                $marineAlienCommanderElos = $eloCalculator->getNewRatings();
            }
            if ($this->round->winner == 2)
            {
                $eloCalculator = new EloCalculator($team1CommanderEloRating, $team2CommanderEloRating, 0, 1);
                $commanderElos = $eloCalculator->getNewRatings();
                $eloCalculator = new EloCalculator($marineCommanderEloRating, $alienCommanderEloRating, 0, 1);
                $marineAlienCommanderElos = $eloCalculator->getNewRatings();
            }
        }

        if ($this->round->winner == 1)
        {
            $eloCalculator = new EloCalculator($team1EloRating, $team2EloRating, 1, 0);
            $teamElos = $eloCalculator->getNewRatings();
            $eloCalculator = new EloCalculator($marineEloRating, $alienEloRating, 1, 0);
            $marineAlienElos = $eloCalculator->getNewRatings();
        }
        else if ($this->round->winner == 2)
        {
            $eloCalculator = new EloCalculator($team1EloRating, $team2EloRating, 0, 1);
            $teamElos = $eloCalculator->getNewRatings();
            $eloCalculator = new EloCalculator($marineEloRating, $alienEloRating, 0, 1);
            $marineAlienElos = $eloCalculator->getNewRatings();
        }
        $result = array(
            'team1EloChange' => round($teamElos['a'] - $team1EloRating),
            'team2EloChange' => round($teamElos['b'] - $team2EloRating),
            'marineEloChange' => round($marineAlienElos['a'] - $marineEloRating),
            'alienEloChange' => round($marineAlienElos['b'] - $alienEloRating),
        );
        if (isset($commanderElos))
        {
            $result['team1CommanderEloChange'] = round($commanderElos['a'] - $team1CommanderEloRating);
            $result['team2CommanderEloChange'] = round($commanderElos['b'] - $team2CommanderEloRating);
            $result['marineCommanderEloChange'] = round($marineAlienCommanderElos['a'] - $marineCommanderEloRating);
            $result['alienCommanderEloChange'] = round($marineAlienCommanderElos['b'] - $alienCommanderEloRating);
        }
        Yii::endProfile('calculateElos');
        return $result;
    }

    protected function saveELOs()
    {
        $ELOs = $this->calculateELOs($this->playerRounds);
        foreach ($this->playerRounds as $playerRound)
        {
            if ($playerRound->team == 1 || $playerRound->team == 2)
            {
                //Save win ELO rankings
                if ($playerRound->end - $playerRound->start > ($this->round->end - $this->round->start) * 0.5)
                {
                    if ($playerRound->team == 1)
                    {
                        $playerRound->player->win_elo_rating += $ELOs['team1EloChange'];
                        $playerRound->player->marine_win_elo += $ELOs['marineEloChange'];
                    }
                    if ($playerRound->team == 2)
                    {
                        $playerRound->player->win_elo_rating += $ELOs['team2EloChange'];
                        $playerRound->player->alien_win_elo += $ELOs['alienEloChange'];
                    }
                }

                //Save commander ELO rankings
                if ($playerRound->isCommander())
                {
                    $playerRound->commander = 1;
                    if (isset($ELOs['marineCommanderEloChange']) && isset($ELOs['alienCommanderEloChange']))
                    {
                        if ($playerRound->team == 1)
                        {
                            $playerRound->player->commander_elo_rating += $ELOs['team1CommanderEloChange'];
                            $playerRound->player->marine_commander_elo += $ELOs['marineCommanderEloChange'];
                        }
                        if ($playerRound->team == 2)
                        {
                            $playerRound->player->commander_elo_rating += $ELOs['team2CommanderEloChange'];
                            $playerRound->player->alien_commander_elo += $ELOs['alienCommanderEloChange'];
                        }
                    }
                    $playerRound->save();
                }
                $playerRound->player->save();
            }
        }
    }

    protected function saveMods($mods)
    {
        $mods = explode(',', $mods);
        foreach ($mods as $modName)
        {
            $modName = trim($modName);
            if (strlen($modName) > 0 && strpos($modName, 'NS2Stats') === false)
            {
                $modId = Mod::getIdByName($modName);
                $modRound = new ModRound();
                $modRound->round_id = $this->roundId;
                $modRound->mod_id = $modId;
                $modRound->save();
            }
        }
    }

}
