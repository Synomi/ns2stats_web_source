<?php

class Ns2stats extends CWebApplication {

    public function init() {
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerScriptFile(
                Yii::app()->assetManager->publish(
                        Yii::getPathOfAlias('application.components') . '/timeago.js'
                ), CClientScript::POS_HEAD
        );

        if (isset(Yii::app()->user->id) && !isset(Yii::app()->user->player))
        {
//            Yii::app()->user->player = new stdClass();
//            Yii::app()->user->player = Player::model()->findByPk(Yii::app()->user->id);
        }
    }

}