        <?php
        echo CHtml::tag('h2', array('style' => 'margin-top:-80px;z-index:-1;'), 'All recent rounds <span style="font-size:9px">(ignores filters)</span>');

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
                    'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("server/server/", array("id" => $data["server_id"]))), Helper::truncate($data["server_name"], 20))',
                ),
            ),
            'rows' => All::getRoundsList(),
                )
        );
        ?>