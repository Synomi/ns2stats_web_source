<?php

class RoundController extends Controller
{

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        
    }

    public function actionIndex()
    {

        $this->render('index', array(
            'player' => $player,
        ));
    }

    public function actionRound($id)
    {
        $round = Round::model()->findByPk($id);
        if (isset($round))
        {
            Highchart::load();
            //Don't update page if parse status has not changed
            if (isset($_GET['parseStatus']))
            {
                if ($round->parse_status == $_GET['parseStatus'])
                    Yii::app()->end();
            }
            //Show page normally if it is completely parsed
            if ($round->parse_status == 0 && !isset($_GET['parseStatus']))
            {
                $this->render('round', array(
                    'round' => $round,
                ));
            }
            else if (isset($_GET['parseStatus']))
            {
                $this->renderPartial('round', array(
                    'round' => $round,
                        ), false, false);
            }
            //Show page partially if it still being parsed
            else
            {
                $this->render('incompleteRound', array(
                    'round' => $round,
                ));
            }
        }
        else
            throw new CHttpException(404, 'Unable to find requested round. Round parsing might have failed.');
    }

    public function actionRounds()
    {
        $this->render('rounds', array(
        ));
    }

    public function actionRoundslist()
    {        
        $this->renderPartial('roundslist', array());
    }

    public function actionRTCountLine($id)
    {
        $rts = Round::getRTCount($id);
        $rts = HighchartData::countLine($rts, 1);
        Json::printJSON($rts);
    }

    public function actionTimeLine($id)
    {
        $timeline = Round::getResourcesUsedToBuildingsAndTech($id);
        $timeline = HighchartData::countLine($timeline, 0);
        Json::printJSON($timeline);
    }

    public function actionKillCountLine($id)
    {
        $kills = Round::getKillCount($id);
        $kills = HighchartData::countLine($kills, 1);
        Json::printJSON($kills);
    }

    public function actionResourcesCountLine($id)
    {
        $kills = Round::getResourcesCount($id);
        $kills = HighchartData::countLine($kills, 1);
        Json::printJSON($kills);
    }

}