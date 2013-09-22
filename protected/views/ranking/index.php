<?php

?>
<p style="padding-left:30px;">Teams can be found <?php echo CHtml::link('here',array('/team/index'),array()) ?></p>
<div class="span-10">
    <div class="box">
        <?php
        $this->widget('FilterPanel', array(
            'url' => 'ranking/topmarines',
                )
        );
        ?>
    </div>
</div>
<div class="span-10">
    <div class="box">
        <?php
        $this->widget('FilterPanel', array(
            'url' => 'ranking/topaliens',
                )
        );
        ?>
    </div>
</div>
<div class="span-10 last">
    <div class="box">        
        <?php
        $this->widget('FilterPanel', array(
            'url' => 'ranking/toprankings',
                )
        );
        ?>
        <p>Updating rankings list is currently disabled. Rating still updates.</p>
    </div>
</div>
<div class="span-10">
    <div class="box">
        <?php
        $this->widget('FilterPanel', array(
            'url' => 'ranking/marinecommanders',
                )
        );
        ?>
    </div>
</div>
<div class="span-10">
    <div class="box">
        <?php
        $this->widget('FilterPanel', array(
            'url' => 'ranking/aliencommanders',
                )
        );
        ?>
    </div>
</div>