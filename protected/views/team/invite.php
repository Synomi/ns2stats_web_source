<script type="text/javascript" >
    var filterpanels = new Array();
    function checkKey(event) {
        if(event.keyCode == 13)
            return false;
        else 
            return true;
    }
    
    var previousInput = '';
    function loadPlayers() {
        if($('#s').val() != previousInput) {
            for (i in filterpanels) {
                if(filterpanels[i].request)
                    filterpanels[i].request.abort()
                $("#" + filterpanels[i].id).html('<img class="loading" src="<?php echo Yii::app()->baseUrl ?>/images/loading.gif" alt="loading" />');
                filterpanels[i].request = $.ajax({
                    url: filterpanels[i].url,
                    data: $('#filter-form').serialize(),
                    type: 'POST',
                    success: function(result, textStatus, jqXHR) {
                        for(i in filterpanels) {
                            filterpanels[i] = filterpanels[i];
                            if(filterpanels[i].url == this.url) {
                                $("#" + filterpanels[i].id).html(result);
                            }
                        }
                        jQuery("#" + filterpanels[i].id + " a.timeago").timeago();
                    }
                });
            }
        }
        previousInput = $('#s').val();
    }
    window.setInterval("loadPlayers()", 500);
</script>
<div class="box" >
<?php
echo CHtml::tag('h1', array(), 'Players');


echo CHtml::beginForm('', 'post', array(
    'id' => 'filter-form',
));
echo CHtml::label('Search', 's', array('class' => 'label'));
echo CHtml::textField('s', '', array('onkeypress' => 'checkKey(event)'));
echo CHtml::endForm();

$this->widget('FilterPanel', array(
    'url' => 'team/invitelist',
    'params' => array(
         'id' => $team->id
    ),
        )
);
?>
</div>