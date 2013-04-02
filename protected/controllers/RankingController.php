<?php

class RankingController extends Controller {

    public function actionIndex() {
        $this->render('index');
    }

    public function actionTopMarines() {
        $this->renderPartial('topmarines');
    }

    public function actionTopAliens() {
        $this->renderPartial('topaliens');
    }

    public function actionAlienCommanders() {
        $this->renderPartial('aliencommanders');
    }

    public function actionMarineCommanders() {
        $this->renderPartial('marinecommanders');
    }

    public function actionTopRankings() {
        $this->renderPartial('toprankings');
    }

    public function actionTopRankingsLong() {
        $this->render('toprankingslong');
    }

}