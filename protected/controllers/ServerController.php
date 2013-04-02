<?php

class ServerController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        
    }

    public function actionIndex() {
        $filter = new Filter();
        $filter->private = array(0, 1);
        $this->render('index', array(
            'filter' => $filter
        ));
    }

    public function actionUpdateServer($id) {
        if (isset(Yii::app()->user->id)) {
            $server = Server::model()->findByAttributes(array('id' => $id));

            if (!isset($server)) {
                throw new CHttpException(404, 'Server does not exist.');
                return;
            }

            if ($server->admin_id != Yii::app()->user->id)
                throw new CHttpException(401, 'You are not allower to edit this server id.');
            else { //allowed to edit 
                if (isset($_POST['motd']))
                    $motd = strip_tags($_POST['motd']);

                if (strlen($motd) > 240)
                    die("Motd is too long, maxium allowed length is 240 characters");

                $server->motd = $motd;
                $server->save();
            }

            echo "saved";
        }
        else
            throw new CHttpException(401, 'You need to login to access this page.');
    }

    public function actionServer($id) {
        $filter = new Filter();
        $filter->private = array(0, 1);
        $server = Server::model()->findByPk($id);
        $this->render('server', array(
            'server' => $server,
            'filter' => $filter
        ));
    }

    public function actionPlayersLine($id) {
        $players = Server::getPlayersPerHour($id);
        $players = HighchartData::line($players);
        Json::printJSON($players);
    }

    public function actionMapsPie($id) {
        $maps = Server::getMaps($id);
        $maps = HighchartData::pie($maps);
        Json::printJSON($maps);
    }

    public function actionAdmin() {
        if (isset(Yii::app()->user->id)) {
            $this->layout = 'account';
            $this->render('admin', array(
                'player' => Player::model()->findByPk(Yii::app()->user->id),
            ));
        }
        else
            throw new CHttpException(401, 'You need to login to access this page.');
    }

    public function actionCreate() {
        $this->layout = 'account';
        $model = new Server;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Server'])) {
            $model->attributes = $_POST['Server'];
            $model->admin_id = Yii::app()->user->id;
            $model->server_key = md5(uniqid(uniqid(), true));
            $model->created = time();
            if ($model->save())
                $this->redirect(array('admin'));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (Yii::app()->user->id == $model->admin_id) {
            $this->layout = 'account';


            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['Server'])) {
                $model->attributes = $_POST['Server'];
                if ($model->save())
                    $this->redirect(array('admin'));
            }

            $this->render('update', array(
                'model' => $model,
            ));
        }
        throw new CHttpException(401, 'Need to owner of the server.');
    }

    public function loadModel($id) {
        $model = Server::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'server-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionServerList() {
        $this->renderPartial('serverlist', array(
        ));
    }

    public function actionRecentRounds($id) {
        $this->renderPartial('recentrounds', array(
            'server' => Server::model()->findByPk($id),
        ));
    }

}
