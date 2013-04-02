<?php
/* @var $this ServerController */
/* @var $model Server */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'server-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php
        $labels = $model->attributeLabels();
        echo CHtml::tag('div', array('class' => 'label'), $labels['name'] . CHtml::tag('span', array('class' => 'note'),' (Will be detected automatically)'));
        if ($model->name)
            echo CHtml::tag('div', array(), $model->name);
        else
            echo CHtml::tag('div', array(), 'Unknown');
        ?>
    </div>

    <div class="row">
        <?php
        echo CHtml::tag('div', array('class' => 'label'), $labels['ip'] . CHtml::tag('span', array('class' => 'note'),' (Will be detected automatically)'));
        if ($model->name)
            echo CHtml::tag('div', array(), $model->ip);
        else
            echo CHtml::tag('div', array(), 'Unknown');
        ?>
    </div>

    <div class="row">
        <?php echo CHtml::label($labels['port'] . CHtml::tag('span', array('class' => 'note'),' (e.g 27020)'), 'port'); ?>
        <?php echo $form->textField($model, 'port', array('size' => 5, 'maxlength' => 5)); ?>
        <?php echo $form->error($model, 'port'); ?>
    </div>
    <?php if ($model->server_key) { ?>
        <div class="row">
            <?php echo CHtml::tag('div', array('class' => 'label'), $labels['server_key']); ?>
            <?php echo CHtml::tag('div', array(), $model->server_key) ?>
        </div>
    <?php } ?>
    <?php if ($model->stats_version) { ?>
        <div class="row">
            <?php echo CHtml::tag('div', array('class' => 'label'), $labels['stats_version']); ?>
            <?php echo CHtml::tag('div', array(), $model->stats_version) ?>
        </div>
    <?php } ?>
    <div class="row">
        <?php echo CHtml::label('Message of the Day' . CHtml::tag('span', array('class' => 'note'),' (use !LB for line breaks, !V for Stats version) Limit is 240 characters'), 'motd'); ?>
        <?php echo $form->textArea($model, 'motd', array('cols' => 80, 'rows' => 3, 'maxlength' => 240)); ?>
        <?php echo $form->error($model, 'motd'); ?>
    </div>

    <div class="row">
        <?php echo CHtml::label('Tournament mode' . CHtml::tag('span', array('class' => 'note'),' (Check this for PCW, Gather, etc. servers)'), 'private'); ?>
        <?php echo $form->checkBox($model, 'private'); ?>
        <?php echo $form->error($model, 'private'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->