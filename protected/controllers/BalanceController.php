<?php

class BalanceController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
        );
    }

    public function actionIndex() {
        $filter = new Filter();
        $filter->private = array(0, 1);
        $filter->startDate = date('d.m.Y', 1344729600);
        $this->render('index', array(
            'filter' => $filter
        ));
    }

    public function actionRoundResultsColumn() {
        $winRates = Balance::getRoundResults();
        $winRates = HighchartData::column($winRates);
        Json::printJSON($winRates);
    }

    public function actionTimePlayedAlienLifeformColumn() {
        $lifeformTimes = Balance::getTimePlayedByAlienLifeform();
        $lifeformTimes = HighchartData::column($lifeformTimes);
        Json::printJSON($lifeformTimes);
    }

    public function actionKillsByTeamColumn() {
        $kills = Balance::getKillsByTeam();
        $kills = HighchartData::column($kills);
        Json::printJSON($kills);
    }

    public function actionaveragelifetimealienlifeformColumn() {
        $lifetimes = Balance::getAverageLifetimeByAlienLifeform();
        $lifetimes = HighchartData::column($lifetimes);
        Json::printJSON($lifetimes);
    }

}