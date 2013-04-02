<?php
/**
 * Overload of CWebUser to set some more methods.
 */
class WebUser extends CWebUser
{
    public $player = null;

    private function loadPlayer()
    {
        if (isset(Yii::app()->user->id))
            $this->player = Player::model()->findByPk(Yii::app()->user->id);
    }
    
    public function isSuperAdmin()
    {        
        if (!isset ($this->player))
                $this->loadPlayer ();

        if (isset ($this->player) && $this->player->group > 9)        
            return true;        
        else        
            return false;        
    }
    
}

?>
