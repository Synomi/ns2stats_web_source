<?php

class MapController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        
    }

    public function actionIndex() {
        $this->render('index', array(
        ));
    }

    public function actionMap($id) {
        $map = Map::model()->findByPk($id);
        $this->render('map', array(
            'map' => $map,
        ));
    }

    public function actionRoundsPlayedLine($id) {
        $players = Map::getRoundsPlayedPerHour($id);
        $players = HighchartData::line($players);
        Json::printJSON($players);
    }

    public function actionRoundResultsPie($id) {
        $roundResults = Map::getRoundResults($id);
        $roundResults = HighchartData::pie($roundResults);
        Json::printJSON($roundResults);
    }

    public function actionStartLocationRoundResultsPie($id, $team, $startLocation) {
        $roundResults = Map::getRoundResultsByStartLocation($id, $team, $startLocation);
        $roundResults = HighchartData::pie($roundResults);
        Json::printJSON($roundResults);
    }

    public function actionRoundLengthsPie($id) {
        $roundLengths = Map::getRoundLengths($id);
        $roundLengths = HighchartData::getTimeDistributionPie($roundLengths);
        Json::printJSON($roundLengths);
    }

    public function actionMarine($id) {
        $this->renderPartial('marine', array(
            'map' => Map::model()->findByPk($id),
        ));
    }

    public function actionAlien($id) {
        $this->renderPartial('alien', array(
            'map' => Map::model()->findByPk($id),
        ));
    }

//    public function actionPlayersLine($id) {
//        $players = Server::getPlayersPerHour($id);
//        $players = HighchartData::line($players);
//        Json::printJSON($players);
//    }
//
//    public function actionMapsPie($id) {
//        $maps = Server::getMaps($id);
//        $maps = HighchartData::pie($maps);
//        Json::printJSON($maps);
//    }

    public function actionMapList() {
        $this->renderPartial('maplist', array(
        ));
    }

    public function actionServerList($id) {
        $this->renderPartial('serverlist', array(
            'map' => Map::model()->findByPk($id),
        ));
    }

}
