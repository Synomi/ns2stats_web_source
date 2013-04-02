<div class="box">
    <?php
    echo CHtml::tag('h1', array(), 'My Teams');
    //echo CHtml::tag('p', array(), 'Server admins can see their server status and add new servers to NS2Stats here.');

    if ($invites) {
        echo CHtml::tag('h2', array(), 'Team Invites');
        $this->widget('StatsTable', array(
            'columns' => array(
                array(
                    'title' => 'Team',
                    'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("team/team/", array("id" => $data["id"]))), $data["name"])',
                ),
                array(
                    'title' => 'Accept',
                    'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("team/acceptinvite/", array("id" => $data["id"]))), "Accept")',
                ),
                array(
                    'title' => 'Decline',
                    'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("team/deleteinvite/", array("id" => $data["id"]))), "Decline")',
                ),
            ),
            'rows' => $invites,
                )
        );
    }

    echo CHtml::link('Create a Team', 'create');
    foreach (Team::getTeamsByPlayer(Yii::app()->user->id) as $team) {
        ?><div class=""><?php
    echo CHtml::tag('h2', array(), CHtml::tag('a', array('title' => 'Click to edit', 'href' => $this->createUrl('team/update', array('id' => $team['id']))), $team['name']));
    echo CHtml::tag('h3', array(), 'Members');
    $playerTeam = PlayerTeam::model()->findByAttributes(array('team_id' => $team['id'], 'player_id' => Yii::app()->user->id));
    $columns = array(
        array(
            'title' => 'Name',
            'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("player/player/", array("id" => $data["id"]))), $data["steam_name"])',
        ),
        array(
            'title' => 'Role',
            'value' => 'PlayerTeam::getRoleString($data["role"])',
        ),
        array(
            'title' => 'Delete',
            'value' => '""; if($data["player_id"] == Yii::app()->user->id)echo CHtml::link("Remove", array("deleteinvite", "id" => $data["player_team_id"]))',
        ),
    );
    if ($playerTeam->role > 1)
        $columns = array_merge($columns, array(
            array(
                'title' => 'Promote / Demote',
                'value' => '"";
                        if($data["role"] == 1)
                            echo CHtml::link("Promote", array("promote", "id" => $data["player_team_id"]));
                        else if($data["role"] == 2)
                            echo CHtml::link("Demote", array("demote", "id" => $data["player_team_id"]));
                        ',
            ),
                )
        );
    $this->widget('StatsTable', array(
        'columns' => $columns,
        'rows' => Team::getPlayers($team['id']),
            )
    );

    if ($playerTeam)
        if ($playerTeam->role >= 2)
            echo CHtml::link('Invite player to ' . $team['name'], array('team/invite', 'id' => $team['id']));
        ?></div><?php
    }
    ?>
</div>