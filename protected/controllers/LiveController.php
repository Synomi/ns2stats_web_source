<?php

class LiveController extends Controller
{

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        
    }

    public function actionDevour()
    {
        $this->render('devourtest', array(            
        ));
    }
    public function actionIndex()
    {

//        $liveRounds = LiveRound::model()->cache(15 * 60)->findAll(array(
//            'condition' => 'unix_timestamp(now())-300<=unix_timestamp(last_updated) AND players>0',
//            'order' => 'players DESC'
//        ));
//
//
//        $this->render('index', array(
//            'liveRounds' => $liveRounds,
//        ));
    }

    public function actionLivestats($id)
    {
        $server = Server::model()->findByPk($id);
        if (!isset($server))
            throw new CHttpException(404, "Unknown server");

        $widget = $this->widget('Livestats', array(
            'server' => $server,
        ));
    }

    public function actionScoreboard($id)
    {
        $server = Server::model()->findByPk($id);
        if (!isset($server))
            throw new CHttpException(404, "Unknown server");
        
        $this->layout='ingame';
        $this->render('scoreboard', array('server' => $server));
    }

}

?>
