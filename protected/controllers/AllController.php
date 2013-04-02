<?php

class AllController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
        );
    }

    public function actionIndex() {
        $filter = new Filter();
        $filter->startDate = date('d.m.Y', strtotime('-14 days'));

        $player = new Player('search');
        $player->unsetAttributes();
        $searchParams = Yii::app()->request->getQuery('Player');
        $player->attributes = Yii::app()->request->getQuery('Player');
        if (isset($searchParams['score']))
            $player->score = $searchParams['score'];
        $this->render('index', array(
            'player' => $player,
            'filter' => $filter,
        ));
    }

    public function actionMapsPie() {
        $maps = All::getMaps();
        $maps = HighchartData::pie($maps);
        Json::printJSON($maps);
    }

    public function actionRoundResultsPie() {
        $roundResults = All::getRoundResults();
        $roundResults = HighchartData::pie($roundResults);
        Json::printJSON($roundResults);
    }

    public function actionRoundsPlayedLine() {
        $rounds = All::getRoundsPlayedPerHour();
        $rounds = HighchartData::line($rounds);
        Json::printJSON($rounds);
    }

    public function actionPlayersLine() {
        $players = All::getPlayersPerHour();
        $players = HighchartData::line($players);
        Json::printJSON($players);
    }

    public function actionMostKills() {
        $this->renderPartial('mostkills');
    }

    public function actionMostScore() {
        $this->renderPartial('mostscore');
    }

    public function actionRecentRounds() {
        $this->renderPartial('recentrounds');
    }

    public function actionRoundResultsLengthColumn() {
        $roundResultsByLength = All::getRoundResultsByTime();
        $roundResultsByLength = HighchartData::getTimeDistributionPie($roundResultsByLength);
        Json::printJSON($roundResultsByLength);
    }

    public function actionRoundLengthColumn() {
        $roundsByLength = All::getRoundLengths();
        $roundsByLength = HighchartData::getTimeDistributionPie($roundsByLength);
        Json::printJSON($roundsByLength);
    }

    public function actionPlayerNationalitiesPie() {
        $nationalities = All::getPlayerNationalities();
        $nationalities = HighchartData::pie($nationalities);
        Json::printJSON($nationalities);
    }

    public function actionElo() {
        $this->render('elo');
    }

}