<?php
/* @var $this TeamController */
/* @var $model Team */
?>
<h1>Update Team <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>