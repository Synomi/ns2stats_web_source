<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-5" id="menu-column">
    <div id="sidebar" class="menu">
        <?php
        $this->widget('zii.widgets.CMenu', array(
            'encodeLabel' => false,
            'items' => array(
                array('label' => CHtml::tag('div', array('class' => 'menu-button-top', 'alt' => ''), 'Home'), 'url' => array('/admin/index')),
                array('label' => CHtml::tag('div', array('class' => 'menu-button', 'alt' => ''), 'Completed Logs'), 'url' => array('/admin/logs', 'directory' => 'completed')),
                array('label' => CHtml::tag('div', array('class' => 'menu-button', 'alt' => ''), 'Other Logs'), 'url' => array('/admin/logs', 'directory' => 'other')),
                array('label' => CHtml::tag('div', array('class' => 'menu-button', 'alt' => ''), 'Failed Logs'), 'url' => array('/admin/logs', 'directory' => 'failed')),
                array('label' => CHtml::tag('div', array('class' => 'menu-button', 'alt' => ''), 'Parse info Logs'), 'url' => array('/admin/logs', 'directory' => 'parselogs')),
                array('label' => CHtml::tag('div', array('class' => 'menu-button', 'alt' => ''), 'Incomplete Logs'), 'url' => array('/admin/logs', 'directory' => 'incomplete')),
                array('label' => CHtml::tag('div', array('class' => 'menu-button', 'alt' => ''), 'Failed Logs (older)'), 'url' => array('/admin/logs', 'directory' => 'failed_old')),
            ),
        ));
        ?>
    </div><!-- sidebar -->
</div>
<div id="content-column" class="span-25 last">
    <div id="content">
        <?php echo $content; ?>
    </div><!-- content -->
</div>
<?php $this->endContent(); ?>