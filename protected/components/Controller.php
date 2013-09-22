<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    public $description = '';

    public function beforeAction($action)
    {
        $this->description = Yii::app()->params['defaultDescription'];
        return true;
    }

    public function afterAction($action)
    {
        Yii::app()->clientScript->registerMetaTag($this->description, 'description', null, array('lang' => 'en'));
        return true;
    }

    public function init()
    {
        if (strpos($_SERVER['HTTP_HOST'], 'ingame.') !== false)
            $this->layout = 'ingame';
        
        if (strpos($_SERVER['HTTP_HOST'], 'ingamedev.') !== false)
            $this->layout = 'ingame';
    }

}