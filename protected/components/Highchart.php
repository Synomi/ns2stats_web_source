<?php

/**
 * Highchart component
 * Usage:
 * 
 */
class Highchart extends CWidget {

    public $options = array();
    public $defaultOptions;
    public $type;
    public $optionsJson;
    //Javascript functions that can be passed to options
    public $tooltipFormatter;
    public $xAxisLabelFormatter;

    public static function load() {
        //Load jQuery
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCoreScript('jquery.ui');
        //Load highcharts library
        Yii::app()->clientScript->registerScriptFile(
                Yii::app()->assetManager->publish(
                        Yii::getPathOfAlias('application.components.highcharts') . '/highcharts.js'
                ), CClientScript::POS_HEAD
        );
        //Load highcharts library wrapper
        Yii::app()->clientScript->registerScriptFile(
                Yii::app()->assetManager->publish(
                        Yii::getPathOfAlias('application.components.highcharts') . '/highchartsWrapper2.js'
                ), CClientScript::POS_HEAD
        );
    }

    public function init() {
        $this->load();
        //Set default settings by type
        if ($this->type == 'pie') {
            $this->defaultOptions = array(
                'chart' => array(
                    'defaultSeriesType' => 'pie',
                    'backgroundColor' => null,
                ),
                'credits' => array(
                    'enabled' => false,
                ),
                'tooltip' => array(
                    'formatter' => '%tooltipFormatter',
                ),
                'plotOptions' => array(
                    'pie' => array(
                        'allowPointSelect' => true,
                        'cursor' => 'pointer',
                        'dataLabels' => array(
                            'enabled' => false,
                        ),
                        'showInLegend' => true,
                    ),
                ),
            );
            if (!isset($this->tooltipFormatter))
                $this->tooltipFormatter = "function() { return this.point.name +': '+ Math.round(this.percentage*100, 4) / 100 + ' % (' + this.y + ')'; }";
        }
        else if ($this->type == 'line') {
            $this->defaultOptions = array(
                'chart' => array(
                    'defaultSeriesType' => 'line',
                    'backgroundColor' => null,
                ),
                'credits' => array(
                    'enabled' => false,
                ),
                'tooltip' => array(
                    'formatter' => '%tooltipFormatter',
                ),
                'xAxis' => array(
                    'type' => 'datetime',
                ),
                'yAxis' => array(
                    'min' => 0,
                ),
                'legend' => array(
                    'enabled' => false,
                ),
            );
            if (!isset($this->tooltipFormatter))
                $this->tooltipFormatter = "function() { return formatTimeLabel(this.x, this.y); }";
        }
        else if ($this->type == 'column') {
            $this->defaultOptions = array(
                'chart' => array(
                    'type' => 'column',
                    'defaultSeriesType' => 'column',
                    'backgroundColor' => null,
                ),
                'credits' => array(
                    'enabled' => false,
                ),
                'tooltip' => array(
                    'formatter' => '%tooltipFormatter',
                ),
                'xAxis' => array(
                    'labels' => array(
                        'enabled' => false,
                        'formatter' => '%xAxisLabelFormatter'
                    ),
                    'minPadding' => 0.1,
                ),
                'yAxis' => array(
                    'min' => 0,
                    'title' => false,
                ),
                'legend' => array(
                    'enabled' => false,
                ),
            );
            if (!isset($this->tooltipFormatter))
                $this->tooltipFormatter = "function() { return this.point.name +': ' + this.y; }";
            if (!isset($this->xAxisLabelFormatter))
                $this->xAxisLabelFormatter = "function() { return this.value; }";
        }
        else if ($this->type == 'area') {
            $this->defaultOptions = array(
                'chart' => array(
                    'defaultSeriesType' => 'area',
                    'backgroundColor' => null,
                    'events' => '%events',
                ),
                'credits' => array(
                    'enabled' => false,
                ),
                'tooltip' => array(
                    'useHTML' => true,
                    'formatter' => '%tooltipFormatter',
                ),
//                'xAxis' => array(
//                    'type' => 'datetime',
//                ),
                'yAxis' => array(
                    'min' => 0,
                    'endOnTick' => false,
                    'startOnTick' => false,
                    'maxPadding' => 0,
                    'minPadding' => 0,
                ),
                'xAxis' => array(
                    'min' => 0,
                    'labels' => array(
                        'formatter' => '%xAxisLabelFormatter'
                    ),
                ),
                'legend' => array(
                    'enabled' => false,
                ),
                'plotOptions' => array(
                    'area' => array(
                        'fillOpacity' => 0.3,
                    ),
                ),
            );
            if (!isset($this->tooltipFormatter))
                $this->tooltipFormatter = "function() { return this.y + ' ' + this.series.name + ' in ' + formatTime(this.x); }";
            if (!isset($this->xAxisLabelFormatter))
                $this->xAxisLabelFormatter = "function() { return this.value; }";
        }

        //Add renderTo to the default options
        if (!isset($this->options['chart']['renderTo']))
            $this->options['chart']['renderTo'] = 'highchart-' . uniqid();
        //Add default options
        if (isset($this->options) && isset($this->defaultOptions))
            $this->options = array_merge_recursive($this->options, $this->defaultOptions);

        $this->optionsJson = json_encode($this->options);
        $this->optionsJson = str_replace('"%tooltipFormatter"', $this->tooltipFormatter, $this->optionsJson);
        $this->optionsJson = str_replace('"%xAxisLabelFormatter"', $this->xAxisLabelFormatter, $this->optionsJson);
        $this->optionsJson = str_replace('"%events"', "{
            load: drawImageLabels
        }", $this->optionsJson);
    }

    public function renderContent() {
        $this->render('highchart');
    }

}