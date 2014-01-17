<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-5" id="menu-column">
    <div id="sidebar" class="menu">
        <?php
        if (Yii::app()->user->isSuperAdmin())
        {
        $this->widget('zii.widgets.CMenu', array(
            'encodeLabel' => false,
            'items' => array(
                
                array('label' => CHtml::tag('div', array('class' => 'menu-button-top', 'alt' => ''), 'My Account'), 'url' => array('/player/account')),
                array('label' => CHtml::tag('div', array('class' => 'menu-button', 'alt' => ''), 'Administation'), 'url' => array('/admin')),
                array('label' => CHtml::tag('div', array('class' => 'menu-button', 'alt' => ''), 'My Teams'), 'url' => array('/team/admin')),
                array('label' => CHtml::tag('div', array('class' => 'menu-button', 'alt' => ''), 'My Servers'), 'url' => array('/server/admin')),
                array('label' => CHtml::tag('div', array('class' => 'menu-button', 'alt' => ''), 'Signature <span style="color:gold">new!</span>'), 'url' => array('/player/signature')),
                array('label' => CHtml::tag('div', array('class' => 'menu-button-bottom', 'alt' => ''), 'My Stats'), 'url' => array('/player/player', 'id' => Yii::app()->user->id)),                

                ),
            ));
        }
        else
        {
            $this->widget('zii.widgets.CMenu', array(
            'encodeLabel' => false,
            'items' => array(
                array('label' => CHtml::tag('div', array('class' => 'menu-button-top', 'alt' => ''), 'My Account'), 'url' => array('/player/account')),
                array('label' => CHtml::tag('div', array('class' => 'menu-button', 'alt' => ''), 'My Teams'), 'url' => array('/team/admin')),
                array('label' => CHtml::tag('div', array('class' => 'menu-button', 'alt' => ''), 'My Servers'), 'url' => array('/server/admin')),
                array('label' => CHtml::tag('div', array('class' => 'menu-button', 'alt' => ''), 'Signature <span style="color:gold">new!</span>'), 'url' => array('/player/signature')),
                array('label' => CHtml::tag('div', array('class' => 'menu-button-bottom', 'alt' => ''), 'My Stats'), 'url' => array('/player/player', 'id' => Yii::app()->user->id)),
                
                ),
            ));
        }
        ?>
    </div><!-- sidebar -->
</div>
<div id="content-column" class="span-25 last">
    <div id="content">
        <?php echo $content; ?>
    </div><!-- content -->
</div>
<?php $this->endContent(); ?>