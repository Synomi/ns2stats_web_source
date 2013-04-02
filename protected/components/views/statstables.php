<script type="text/javascript" >
    //<![CDATA[
    function openPlayerRoundStats(id)
    {
        //showUrlInDialog('player/player/' + id);
        alert("Feature coming soon...")
    }

    function showUrlInDialog(url){
      var tag = $("<div></div>");
      $.ajax({
        url: url,
        success: function(data) {
          tag.html(data).dialog({modal: false,height:500,width:800}).dialog('open');
        }
      });
    }

    //]]>
</script>


<?php
$html="";
$this->test();
switch($type)
{
    case "aliens2nicks":
        echo $this->createTableWith2NicksAndImages($data);
        break;
    case "marine2nicks":
        echo $this->createTableWith2NicksAndImages($data);
        break;





    default:
    $html.="Unable to find requested type: $type";
    break;
}















echo CHtml::tag('div', array('id' => $this->options['general']['renderTo'], 'class' => $type), "$html");