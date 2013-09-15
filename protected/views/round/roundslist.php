
<div class="page_content">    
        <?php
        echo CHtml::tag('h2', array('style' => ''), 'Found rounds');

        $this->widget('StatsTable', array(
            'columns' => array(
                array(
                    'title' => 'Ended',
                    'value' => 'CHtml::tag("a", array("class" => "timeago", "title" => date("c", $data["added"]), "href" => Yii::app()->createUrl("round/round/", array("id" => $data["id"]))), $data["end"])',
                ),
                array(
                    'title' => 'Length',
                    'value' => 'Helper::secondsToTime($data["length"])',
                ),
                array(
                    'title' => 'Server',
                    'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("server/server/", array("id" => $data["server_id"]))), Helper::truncate($data["server_name"], 40))',
                ),
                array(
                    'title' => 'Tags',
                    'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("round/rounds/?searchTags=" . htmlspecialchars($data["tags"]), array())), Helper::truncate($data["tags"], 60))',
                ),
            ),
            'rows' => All::getFullRoundsList(),
                )
        );        
        ?>
</div>