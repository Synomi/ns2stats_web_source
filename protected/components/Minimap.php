<?php

class Minimap extends CWidget
{

    public $type = 0;
    public $round = null;
    public $map = null;
    public $deaths = null;
    public function init()
    {
        if ($this->type == 0)
            $this->render('minimap', array('map' => $this->map, 'round' => $this->round));
        else if ($this->type == 2)
            $this->render('minimap_heatmap', array('map' => $this->map, 'deaths' => $this->deaths));
    }

}

?>
