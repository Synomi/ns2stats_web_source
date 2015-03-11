<?php

class LiveController extends Controller
{

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        
    }

    public function actionLivestats($id)
    {
        return 'Currently disabled';
//        $server = Server::model()->findByPk($id);
//        if (!isset($server))
//            throw new CHttpException(404, "Unknown server");
//
//        $widget = $this->widget('Livestats', array(
//            'server' => $server,
//        ));
    }

    public function actionScoreboard($id)
    {
        return 'Currently disabled';
//        $server = Server::model()->findByPk($id);
//        if (!isset($server))
//            throw new CHttpException(404, "Unknown server");
//        
//        $this->layout='ingame';
//        $this->render('scoreboard', array('server' => $server));
    }

}

?>
