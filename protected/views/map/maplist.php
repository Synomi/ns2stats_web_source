<div class="span-30">
    <div class="box">
        <?php
        echo CHtml::tag('h2', array(), 'Maps');

        $this->widget('StatsTable', array(
            'columns' => array(
                array(
                    'title' => 'Name',
                    'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("map/map/", array("id" => $data["map_id"]))), $data["map_name"])',
                ),
                array(
                    'title' => 'Last Played',
                    'value' => 'CHtml::tag("a", array("class" => "timeago", "title" => date("c", $data["round_added"]), "href" => Yii::app()->createUrl("round/round/", array("id" => $data["round_id"]))), $data["round_end"])',
                ),
                array(
                    'title' => 'Times Played',
                    'value' => '$data["times_played"]',
                ),
            ),
            'rows' => Map::getList(),
                )
        );
        ?>
    </div>
</div>
