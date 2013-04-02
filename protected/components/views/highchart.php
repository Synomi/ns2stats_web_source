<script type="text/javascript" >
    //<![CDATA[
    highcharts.push(<?php echo $this->optionsJson; ?>);
    //]]>
</script>
<?php
echo CHtml::tag('div', array('id' => $this->options['chart']['renderTo']), '');