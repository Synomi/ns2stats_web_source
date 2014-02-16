


<script type = "text/javascript" >
    var filterpanels = new Array();

    function loadFilterPanels()
    {
        for (i in filterpanels)
        {

            if (filterpanels[i].request)
                filterpanels[i].request.abort()
            $("#" + filterpanels[i].id).html('<img class="loading" src="<?php echo Yii::app()->baseUrl ?>/images/loading.gif" alt="loading" />');
            filterpanels[i].request = $.ajax(
                    {
                        url: filterpanels[i].url,
                        data: $('#filter-form').serialize(),
                        type: 'POST',
                        success: function(result, textStatus, jqXHR)
                        {
                            for (i in filterpanels)
                            {
                                filterpanels[i] = filterpanels[i];
                                if (filterpanels[i].url == this.url)
                                {
                                    $("#" + filterpanels[i].id).html(result);
                                }
                            }
                            jQuery("#" + filterpanels[i].id + " a.timeago").timeago();
                        }
                    });
        }
    }

    function filter()
    {
        setTimeout(function()
        {
            $('#changed').val('1');
            if (typeof window.loadFilterPanels == 'function')
                loadFilterPanels();
            if (typeof window.loadHighcharts == 'function')
                loadHighcharts();
        }, 500);
    }

    function showFilters()
    {
        $('#filters').show();
        $('#showFilters').hide();
        $('#hideFilters').show();
    }

    function hideFilters()
    {
        $('#filters').hide();
        $('#showFilters').show();
        $('#hideFilters').hide();
    }

</script>
<?php
if (!isset($_GET['alltime']))
{
    ?>

    <div class = "content-box">
        <div id = "showFilters" class = "left">
            <button class = "filterButton" onclick = "showFilters();
                return false;">More Filters</button>
        </div>
        <div id = "hideFilters" class = "left">
            <button class = "filterButton" onclick = "hideFilters();
                return false;">Hide Filters</button>
        </div>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'filter-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        ));
        echo CHtml::hiddenField('changed', '0');

        $datePickerOptions = array(
            'model' => $this->filter,
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'dd.mm.yy',
            ),
            'htmlOptions' => array(
                'class' => '',
                'onchange' => 'filter();',
            ),
        );
        $startDateOptions = $datePickerOptions;
        $startDateOptions['options']['minDate'] = date('d.m.Y', 1344745005);
        $startDateOptions['options']['maxDate'] = date('d.m.Y', strtotime('today'));
        $startDateOptions['value'] = $this->filter->startDate;
        $startDateOptions['name'] = 'Filter[startDate]';
        ?> <div class="left filterList"> <?php
        echo CHtml::tag('h3', array('class' => 'filterHeading'), 'Start Date');
        $this->widget('zii.widgets.jui.CJuiDatePicker', $startDateOptions);
        echo $form->error($this->filter, 'startDate');

        $endDateOptions = $datePickerOptions;
        $endDateOptions['options']['maxDate'] = date('d.m.Y', strtotime('today'));
        $endDateOptions['value'] = $this->filter->endDate;
        $endDateOptions['name'] = 'Filter[endDate]';

        echo CHtml::tag('h3', array('class' => 'filterHeading'), 'End Date');
        $this->widget('zii.widgets.jui.CJuiDatePicker', $endDateOptions);
        echo $form->error($this->filter, 'startDate');
        ?> 
        </div> 
        <?php
        if (Yii::app()->controller->id == 'player' && isset($_GET['id']))
            echo '<a href="' . Yii::app()->params['siteurl'] . '/player/player/' . intval($_GET['id']) . '?alltime=true" style="color:gold;padding-left:10px">Show all-time stats</a>';
        ?>

        <div class="clear"></div>
        <div id="filters">
            <?php
            if (count($this->servers) > 0)
            {
                ?> <div class="filterList"> <?php
                echo CHtml::tag('h3', array(), 'Servers');
                echo $form->checkBoxList($this->filter, 'server', $this->servers, array(
                    'checkAll' => 'Select All',
                    'onchange' => 'filter();'
                ));
                ?> </div> <?php
            }
            ?> <div class="left filterList"> <?php
                echo CHtml::tag('h3', array(), 'Builds');
                echo $form->checkBoxList($this->filter, 'build', $this->builds, array(
                    'checkAll' => 'Select All',
                    'onchange' => 'filter();',
                ));
                ?> </div> <?php
                ?> <div class="left filterList"> <?php
            echo CHtml::tag('h3', array(), 'Mode');
            echo $form->checkBoxList($this->filter, 'private', $this->private, array(
                'onchange' => 'filter();',
            ));
            ?> </div> <?php
            if (count($this->teams) > 0)
            {
                ?> <div class="left filterList"> <?php
                    echo CHtml::tag('h3', array(), 'Teams');
                    echo $form->checkBoxList($this->filter, 'team', $this->teams, array(
                        'checkAll' => 'Select All',
                        'onchange' => 'filter();',
                    ));
                    ?> </div> <?php
            }
            if (count($this->mods) > 0)
            {
                ?> <div class="left filterList"> <?php
                    echo CHtml::tag('h3', array(), 'Mods');
                    echo $form->checkBoxList($this->filter, 'mod', $this->mods, array(
                        'checkAll' => 'Select All',
                        'onchange' => 'filter();',
                    ));
                    ?> </div> <?php
            }
            $this->endWidget();
            ?>       
            <div class="clear"></div>
        </div>
    </div>
    <?php
}
else //showing all time stats
{
    echo '<div style="padding:10px;padding-left:20px;"><a href="' . Yii::app()->params['siteurl'] . '/player/player/' . intval($_GET['id']) . '" style="color:yellow;">Use filters</a></div>';
}
?>
