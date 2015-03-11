<?php

class Filter extends CModel
{

    public $build;
    public $startDate;
    public $endDate;
    public $list;
    public $server;
    public $round;
    public $private;
    public $team;
    public $mod;
    public $alltime;
    public $gamemode;

    public function attributeNames()
    {
        return array(
            'build' => 'Build',
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
            'list' => 'List',
            'server' => 'Server',
            'round' => 'Round',
            'mod' => 'Mod',
            'gamemode' => 'Gamemode',
        );
    }

    public function loadDefaults()
    {


        if (!$this->private)
            $this->private = array(0);
//        if (!$this->build)
//            $this->build = array(229);
        if (!$this->gamemode)
            $this->gamemode = 'ns2';
        if (!$this->startDate)
            $this->startDate = date('d.m.Y', strtotime('-14 days'));
        if (!$this->endDate)
            $this->endDate = date('d.m.Y', strtotime('now'));


        if (isset($_GET['alltime']))
        {
            $this->alltime = 1;
            $this->saveToSession();
        }
        if (isset(Yii::app()->controller->action->id) && strpos(Yii::app()->controller->action->id, 'general') !== false && !isset($_GET['alltime']))
        {
            $this->alltime = 0;
            $this->saveToSession();
        }
    }

    public function loadFromSession()
    {
        $filters = Yii::app()->user->getState('filter');

        if (isset($filters['alltime']) && $filters['alltime'])
            $this->alltime = $filters['alltime'];
        if ($filters['build'])
            $this->build = $filters['build'];
        if ($filters['gamemode'])
            $this->gamemode = $filters['gamemode'];
        if ($filters['startDate'])
            $this->startDate = $filters['startDate'];
        if ($filters['endDate'])
            $this->endDate = $filters['endDate'];
        if ($filters['list'])
            $this->list = $filters['list'];
        if ($filters['server'])
            $this->server = $filters['server'];
        if ($filters['round'])
            $this->round = $filters['round'];
        if ($filters['private'])
            $this->private = $filters['private'];
        if ($filters['team'])
            $this->team = $filters['team'];
        if (isset($filters['mod']))
            if ($filters['mod'])
            {
                $this->mod = $filters['mod'];
            }
    }

    public function saveToSession()
    {
        Yii::app()->user->setState('filter', array(
            'alltime' => $this->alltime,
            'build' => $this->build,
            'gamemode' => $this->gamemode,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'list' => $this->list,
            'server' => $this->server,
            'round' => $this->round,
            'private' => $this->private,
            'team' => $this->team,
            'mod' => $this->mod,
        ));
    }

    public static function getFilterStringForCache()
    {
        $filterString = Filter::addFilterConditions();
        $filterString = preg_replace('#\W#', '', $filterString);
        return $filterString;
    }

    public static function addFilterConditions($teamFilterEnabled = false, $buildFilterEnabled = true)
    {
//        if (isset($_GET['alltime']) && Yii::app()->controller->id == 'player')
//        {
//            echo '<span style="color:gold">';
//            return ' AND 1=1';
//        }
        if (isset($_GET['alltime_signature']) && Yii::app()->controller->id == 'player')
        {
            return ' AND 1=1';
        }

        if (Yii::app()->controller->id != 'api')
        {
            $filter = new Filter();
            $filter->loadFromSession();
            $filter->loadDefaults();
            //alltime
            if (isset($filter->alltime) && $filter->alltime == 1 && Yii::app()->controller->id == 'player')
            {
                if (strpos(Yii::app()->controller->action->id, 'general') !== false)
                    echo '<span style="color:gold">';

                return ' AND 1=1';
            }

            $filterInput = Yii::app()->request->getPost('Filter');
            if (isset($filterInput))
            {
//            if (!isset($_POST['Filter_server_all']))
                if (isset($filterInput['server']))
                    $filter->server = $filterInput['server'];
//            if (!isset($_POST['Filter_build_all']))
                if (isset($filterInput['build']))
                    $filter->build = $filterInput['build'];
                if (isset($filterInput['gamemode']))
                    $filter->gamemode = $filterInput['gamemode'];

//            if (!isset($_POST['Filter_private_all']))
                if (isset($filterInput['private']))
                    $filter->private = $filterInput['private'];
//            if (!isset($_POST['Filter_team_all']))
                if (isset($filterInput['team']))
                    $filter->team = $filterInput['team'];
                if (isset($filterInput['mod']))
                    $filter->mod = $filterInput['mod'];
                if (isset($filterInput['startDate']))
                    $filter->startDate = $filterInput['startDate'];
                if (isset($filterInput['endDate']))
                    $filter->endDate = $filterInput['endDate'];
                if (Yii::app()->request->getPost('changed', 0))
                    $filter->saveToSession();
            }
            $sql = '';
            //Server
            if (isset($filter->server))
                if (is_array($filter->server))
                    $sql .= ' AND round.server_id IN (' . implode(', ', $filter->server) . ') ';
                else
                    $sql .= ' AND 1 = 1';
            //Build
            if ($buildFilterEnabled)
                if (isset($filter->build))
                    if (is_array($filter->build))
                        $sql .= ' AND round.build IN (' . implode(', ', $filter->build) . ') ';
                    else
                        $sql .= ' AND 1 = 1';

            if (isset($filter->gamemode))
                if (is_array($filter->gamemode))
                    $sql .= ' AND round.gamemode LIKE "' . addslashes($filter->gamemode) . '"';
                else
                    $sql .= ' AND 1 = 1';
            //Start Date
            $sql .= ' AND round.end >= ' . strtotime($filter->startDate);
            //End Date
            $sql .= ' AND round.end <= ' . ((strtotime($filter->endDate)) + 86400);
            //Private
            if (isset($filter->private))
                if (is_array($filter->private))
                    $sql .= ' AND round.private IN (' . implode(', ', $filter->private) . ') ';
                else
                    $sql .= ' AND 1 = 1';
            //Teams
//        if ($teamFilterEnabled)
//            if (isset($filter->team)) {
//                if (is_array($filter->team)) {
//                    if (count($filter->team) > 0) {
//                        $sql .= ' AND ((player_round.team = 1 AND round.team_1 IN (' . implode(', ', $filter->team) . ') OR (player_round.team = 2 AND round.team_2 IN (' . implode(', ', $filter->team) . ')))';
//                        if (in_array(0, $filter->team))
//                            $sql .= ' OR (player_round.team = 1 AND round.team_1 IS null) OR (player_round.team = 2 AND round.team_2 IS null))';
//                        else
//                            $sql .= ')';
//                    }
//                }
//            }
            //Mods
            if (Yii::app()->controller->id == 'all')
                if (isset($filter->mod))
                    if (is_array($filter->mod))
                        $sql .= ' AND COALESCE(mod_round.mod_id, 0) IN (' . implode(', ', $filter->mod) . ') ';
                    else
                        $sql .= ' AND 1 = 1';
            return $sql;
        }
    }

}
