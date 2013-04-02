<?php
function countFiles($directory)
{
    if (glob($directory . "*") != false)   
        return count(glob($directory . "*"));         
    else
        return 0;
}

echo CHtml::tag('div',array(),"Completed logs : " . countFiles(Yii::app()->params['logDirectory'] . 'completed/'));
echo CHtml::tag('div',array(),"Incompleted logs : " . countFiles(Yii::app()->params['logDirectory'] . 'incomplete/'));
echo CHtml::tag('div',array(),"Failed logs : " . countFiles(Yii::app()->params['logDirectory'] . 'failed/'));

?>