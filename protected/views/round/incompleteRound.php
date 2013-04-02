<script type="text/javascript" >
    var parseStatus = <?php echo $round->parse_status; ?>;
    function updateRound(parseStatusOverride) {
        var sentParseStatus;
        if(parseStatusOverride) {
            sentParseStatus = parseStatusOverride
        }
        else
            sentParseStatus = parseStatus;
        request = $.ajax({
            url: '<?php echo $this->createUrl('round/round', array('id' => $round->id)) ?>',
            data: {
                'parseStatus' : sentParseStatus
            },
            type: 'GET',
            success: function(result, textStatus, jqXHR) {
                if(result.length > 0) {
                    if(!parseStatusOverride) {
                        parseStatus++;
                    }
                    if(parseStatus >= 5) {
                        parseStatus = 0;
                        clearTimeout (updater);
                    }
                    $('#round').html(result);
                    loadHighcharts();
                }
            }
        });
    }

    $(document).ready(function() {
        updateRound(-1);
    });
    var updater = setInterval("updateRound()",5000);
</script>

<div id="round">

</div>
<?
$this->renderPartial('roundScript');