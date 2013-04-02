<?php

class TeamController extends Controller {

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->layout = '//layouts/account';
        $model = new Team;

        $this->performAjaxValidation($model);

        if (isset($_POST['Team'])) {
            $model->attributes = $_POST['Team'];
            if ($model->save()) {
                $playerTeam = new PlayerTeam();
                $playerTeam->team_id = Yii::app()->db->getLastInsertID();
                $playerTeam->player_id = Yii::app()->user->id;
                $playerTeam->role = 3;
                $playerTeam->save();
                $this->redirect(array('admin'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $this->layout = '//layouts/account';
        $inviterPlayerTeam = PlayerTeam::model()->findByAttributes(array('team_id' => $id, 'player_id' => Yii::app()->user->id));
        if ($inviterPlayerTeam)
            if ($inviterPlayerTeam->role >= 2) {
                $model = $this->loadModel($id);

                $this->performAjaxValidation($model);

                if (isset($_POST['Team'])) {
                    $model->attributes = $_POST['Team'];
                    if ($model->save()) {
                        $this->redirect(array('admin'));
                    }
                }

                $this->render('update', array(
                    'model' => $model,
                ));
            }
            else
                throw new CHttpException(401, 'Need to be admin or founder of a team to invite a player.');
        else
            throw new CHttpException(401, 'Need to be admin or founder of a team to invite a player.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $filter = new Filter();
        $filter->private = array(0, 1);
        $this->render('index', array(
            'filter' => $filter,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $this->layout = '//layouts/account';
        $model = new Team('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Team']))
            $model->attributes = $_GET['Team'];

        $invites = PlayerTeam::getInvitesByPlayer(Yii::app()->user->id);
        $this->render('admin', array(
            'model' => $model,
            'invites' => $invites,
        ));
    }

    public function actionInvite($id) {
        $this->layout = '//layouts/account';
        $team = Team::model()->findByPk($id);
        $this->render('invite', array(
            'team' => $team
                )
        );
    }

    public function actionInviteList($id) {
        $this->layout = '//layouts/account';
        $team = Team::model()->findByPk($id);
        $namePhrase = Yii::app()->request->getPost('s', '');
        $this->renderPartial('invitelist', array(
            'namePhrase' => $namePhrase,
            'team' => $team,
        ));
    }

    public function actionInvitePlayer($team, $player) {
        $this->layout = '//layouts/account';
        $team = Team::model()->findByPk($team);
        if ($player == Yii::app()->user->id)
            throw new CHttpException(400, "Can't invite yourself.");
        $invitedPlayerTeam = PlayerTeam::model()->findByAttributes(array('team_id' => $team->id, 'player_id' => $player));
        if ($invitedPlayerTeam)
            throw new CHttpException(400, "Player already invited.");

        $inviterPlayerTeam = PlayerTeam::model()->findByAttributes(array('team_id' => $team->id, 'player_id' => Yii::app()->user->id));
        if ($inviterPlayerTeam)
            if ($inviterPlayerTeam->role >= 2) {
                $invitedPlayerTeam = new PlayerTeam();
                $invitedPlayerTeam->team_id = $team->id;
                $invitedPlayerTeam->player_id = $player;
                $invitedPlayerTeam->role = 0;
                $invitedPlayerTeam->save();
                $this->redirect(array('admin'));
            }
        throw new CHttpException(401, 'Need to be admin or founder of a team to invite a player.');
    }

    public function actionAcceptInvite($id) {
        $this->layout = '//layouts/account';
        $invited = PlayerTeam::model()->findByPk($id);
        if ($invited) {
            if ($invited->player_id == Yii::app()->user->id) {
                $invited->role = 1;
                $invited->save();
            }
        }
        $this->redirect(array('admin'));
    }

    public function actionDeleteInvite($id) {
        $this->layout = '//layouts/account';
        $invited = PlayerTeam::model()->findByPk($id);
        $inviter = PlayerTeam::model()->findByAttributes(array('team_id' => $invited->team_id, 'player_id' => Yii::app()->user->id));
        if ($invited) {
            if ($invited->player_id == Yii::app()->user->id) {
                $invited->delete();
            }
        }
        if ($inviter) {
            if ($inviter->role >= 2) {
                $invited->delete();
            }
        }
        $this->redirect(array('admin'));
    }

    public function actionPromote($id) {
        $this->layout = '//layouts/account';
        $playerTeam = PlayerTeam::model()->findByPk($id);
        if ($playerTeam) {
            $admin = PlayerTeam::model()->findByAttributes(array('team_id' => $playerTeam->team_id, 'player_id' => Yii::app()->user->id));
            if ($admin) {
                if ($admin->role >= 2) {
                    $playerTeam->role = 2;
                    $playerTeam->save();
                }
                else
                    throw new CHttpException(401, 'Need to be admin or founder of a team to invite a player.');
            }
            else
                throw new CHttpException(401, 'Need to be admin or founder of a team to invite a player.');
        }

        $this->redirect(array('admin'));
    }

    public function actionDemote($id) {
        $this->layout = '//layouts/account';
        $playerTeam = PlayerTeam::model()->findByPk($id);
        if ($playerTeam) {
            $admin = PlayerTeam::model()->findByAttributes(array('team_id' => $playerTeam->team_id, 'player_id' => Yii::app()->user->id));
            if ($admin) {
                if ($admin->role >= 2) {
                    $playerTeam->role = 1;
                    $playerTeam->save();
                }
                else
                    throw new CHttpException(401, 'Need to be admin or founder of a team to invite a player.');
            }
            else
                throw new CHttpException(401, 'Need to be admin or founder of a team to invite a player.');
        }
        $this->redirect(array('admin'));
    }

    public function actionTeam($id) {
        $filter = new Filter();
        $filter->private = array(0, 1);
        $team = Team::model()->findByPk($id);
        $this->render('team', array(
            'team' => $team,
            'filter' => $filter
        ));
    }

    public function actionRoundResultsPie($id) {
        $roundResults = Team::getRoundResults($id);
        $roundResults = HighchartData::pie($roundResults);
        Json::printJSON($roundResults);
    }

    public function actionRecentRounds($id) {
        $team = Team::model()->findByPk($id);
        $this->renderPartial('recentrounds', array(
            'team' => $team,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Team::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'team-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionTeamList() {
        $this->renderPartial('teamlist', array(
        ));
    }

}
