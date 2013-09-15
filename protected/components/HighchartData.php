<?php

class HighChartData {

    public static $timeDistributionFactor = 5;

    public static function pie($data, $key = 'name', $value = 'count', $itemLimit = 8) {

        $rows = array();
        $i = 1;
        foreach ($data as $row) {
            if ($i < $itemLimit)
                $rows[] = array($row[$key], (int) $row[$value]);
            else if ($i == $itemLimit)
                $rows[] = array('Others', (int) $row[$value]);
            else
                $rows[$itemLimit - 1][1] += (int) $row[$value];
            $i++;
        }
        return array('series' =>
            array(
                array(
                    'data' => $rows
                )
            ),
        );
    }

    public static function column($data, $key = 'name', $value = 'count', $serieName = 'serie') {
//        echo '<pre>';
//        var_dump($data);
//        die();

        $categories = array();
        foreach ($data as $row) {
            $categories[$row[$key]] = $row[$key];
        }
        $categories = array_values($categories);
        sort($categories);
        $categoryNumbers = array_flip($categories);
        $rows = array();
        $i = 1;
        $previousSerie = '';
        $columnNumber = 0;
        foreach ($data as $rowNumber => $row) {

            if ($previousSerie != $row[$serieName]) {
                if (isset($serie)) {
                    $rows[] = $serie;
                    unset($serie);
                }
                $columnNumber = 0;
            }
            if (!isset($serie))
                $serie = array(
                    'name' => $row[$serieName],
                    'data' => array(
                    ),
                );
            while ($columnNumber < $categoryNumbers[$row[$key]]) {
                $serie['data'][] = 0;
                $columnNumber++;
            }
            $serie['data'][] = (int) $row[$value];
            $columnNumber++;
            $previousSerie = $row[$serieName];

            $i++;
        }
        if (isset($serie))
            $rows[] = $serie;
        return array(
            'series' => $rows,
            'categories' => $categories,
        );
    }

    public static function getTimeDistributionPie($data, $key = 'name', $value = 'count', $time = 'time', $itemLimit = 12, $timeDistributionFactor = null) {
        if ($timeDistributionFactor == null)
            $timeDistributionFactor = self::$timeDistributionFactor;
        $categories = array();
        foreach ($data as $row) {
            $categories[$row[$time]] = $row[$time];
        }
        $categories = array_values($categories);
        sort($categories);
        $timeCategories = array();
        $itemLimit++;
        foreach ($categories as $category) {
            if ($category == 0) {
                
            }
            if ($category == 1)
                $timeCategories[] = 'Less than ' . $timeDistributionFactor * ($category + 1) . ' min';
            else if ($category < $itemLimit - 1)
                $timeCategories[] = $category * $timeDistributionFactor . ' - ' . $timeDistributionFactor * ($category + 1) . ' min';
            else if ($category == $itemLimit - 1)
                $timeCategories[] = 'More than ' . $timeDistributionFactor * ($category) . ' min';
        }
        $categories = $timeCategories;
        $categoryNumbers = array_keys($categories);
        $i = 0;
        $result = array();
        $previousSerie = '';
        $columnNumber = 1;
        foreach ($data as $row) {
            if ($previousSerie !== $row[$key]) {
                if (isset($serie)) {
                    while (count($serie['data']) < count($categoryNumbers)) {
                        $serie['data'][] = 0;
                    }
                    $result[] = $serie;
                    unset($serie);
                }
                $columnNumber = 1;
            }
            if (!isset($serie)) {
                $serie = array(
                    'name' => $row[$key],
                    'data' => array(
                    ),
                );
            }
            if (isset($categoryNumbers[$row[$time]]))
                while ($columnNumber < $categoryNumbers[$row[$time]]) {
                    $serie['data'][] = 0;
                    $columnNumber++;
                }
            if ($columnNumber < $itemLimit)
                $serie['data'][] = (int) $row[$value];
            $columnNumber++;
            $previousSerie = $row[$key];
            $i++;
        }
        if (isset($serie))
            $result[] = $serie;
        return
                array(
                    'series' => $result,
                    'categories' => $categories,
        );
    }

    public static function line($data, $key = 'date', $value = 'count') {
        $rows = array();
        $categories = array();
        $groupTime = 3600;
        if (isset($data[0])) {
            $filter = new Filter();
            $filter->loadFromSession();
            $filter->loadDefaults();
            if (strtotime($filter->endDate) - strtotime($filter->startDate) > 7 * 24 * $groupTime) {
                $groupTime *= 24;
            }
            $current = strtotime($filter->startDate) + $groupTime;
            $i = 0;
            //2013-09-15 changed: strtotime($filter->endDate) TO strtotime($filter->endDate . ' -1 days')
            //to hide last day
            
            while ($current < strtotime($filter->endDate . ' -1 days')  + 2 * 86400) {
                if (isset($data[$i])) {
                    if ($data[$i][$key] <= $current) {
                        $rows[] = array($current * 1000, (double) $data[$i][$value]);
                        $i++;
                    }
                    else
                        $rows[] = array($current * 1000, 0);
                }
                else
                    $rows[] = array($current * 1000, 0);
                $current += $groupTime;
            }
        }
        return array(
            'series' =>
            array(
                array(
                    'data' => $rows
                )
            )
        );
    }

    public static function countLine($data, $startValue = 0, $name = 'name', $key = 'time', $valueName = 'value', $symbol = 'symbol') {
        $values = array();
        $series = array();
        if (isset($data[0])) {
            $value = self::getStartValue($data[0], $startValue);
            $values[] = array(0, $value);

            $serieName = $data[0][$name];
            $i = 0;


            foreach ($data as $row) {
                if ($serieName != $row[$name] && $i > 0) {
                    $values[] = array((int) $row['round_length'], (int) $value);
                    $series[] = array(
                        'name' => $serieName,
                        'data' => $values,
                    );
                    $serieName = $row[$name];
                    $values = array();
                    $value = self::getStartValue($data[$i], $startValue);
                    $values[] = array(0, $value);
                }
                if ($row[$key] != $row['round_length']) {
                    $values[] = array(
                        (int) $row[$key],
                        (int) $value,
                    );
                    $value += $row[$valueName];
                    if (isset($row[$symbol]))
                        $values[] = array(
                            'x' => (int) $row[$key],
                            'y' => (int) $value,
                            'text' => $row[$symbol],
                            'icon' => Yii::app()->createAbsoluteUrl('images/icons/' . $row[$symbol] . '.png'),
                            'marker' => array(
                                'enabled' => true,
                                'symbol' => 'url(' . Yii::app()->createAbsoluteUrl('images/icons/' . $row[$symbol] . '.png') . ')'
                            ),
                        );
                    else
                        $values[] = array(
                            (int) $row[$key],
                            (int) $value,
                        );
                }
                $i++;
            }
            $values[] = array((int) $row['round_length'], (int) $value);
            $series[] = array(
                'name' => $serieName,
                'data' => $values,
            );
        }
        return array('series' => $series);
    }

    private static function getStartValue($row, $startValue) {
        if ($row['time'] != 0)
            return $startValue;
        else
            return 0;
    }

}
