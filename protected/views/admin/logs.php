<?php
    foreach($list as $entry) {
        if ($entry != '.' && $entry != '..') {
            ?><div><?php

            if ($_GET['directory']!="parselogs")
            {
                echo CHtml::tag('a', array('href' => $this->createUrl('admin/log', array('directory' => $_GET['directory'], 'file' =>   $entry))), $entry);
                echo CHtml::tag('a', array('href' => $this->createUrl('admin/parselog', array('debug' => '0', 'directory' => $_GET['directory'], 'file' =>  $entry))), ' Parse');
            }
            else
                echo CHtml::tag('a', array('href' => $this->createUrl('admin/log', array('directory' => $_GET['directory'], 'file' =>   $entry))), $entry);


            ?></div><?php
        }
}