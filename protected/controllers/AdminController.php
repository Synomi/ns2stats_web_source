<?php

class AdminController extends Controller
{

    public $layout = 'admin';

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        if (Yii::app()->user->isSuperAdmin())
            $this->render('index');
        else
            throw new CHttpException(401, 'You do not have permission to access this page.');
    }

    public function actionLogs($directory)
    {
        if (Yii::app()->user->isSuperAdmin())
        {
            $path = Yii::app()->params['logDirectory'] . $directory . '/';
            $dir = opendir($path);
            $list = array();
            while ($file = readdir($dir))
            {
                if ($file != '.' and $file != '..')
                {
                    // add the filename, to be sure not to
                    // overwrite a array key
                    $ctime = filectime($path . $file) . ',' . $file;
                    $list[$ctime] = $file;
                }
            }
            closedir($dir);
            krsort($list);
            $this->render('logs', array(
                'list' => $list,
            ));
        }
        else
            throw new CHttpException(401, 'You do not have permission to access this page.');
    }

    public function actionLog($file, $directory)
    {
        if (Yii::app()->user->isSuperAdmin())
        {
            echo '<pre>';
            $path = Yii::app()->params['logDirectory'] . $directory . '/' . $file;
            $handle = @fopen($path, "r");
            if ($handle)
            {
                while (($buffer = fgets($handle, 4096)) !== false)
                {
                    echo $buffer;
                }
                if (!feof($handle))
                {
                    echo "Error: unexpected fgets() fail\n";
                }
                fclose($handle);
            }
        }
        else
            throw new CHttpException(401, 'You do not have permission to access this page.');
    }

    public function actionParseLog($file, $directory)
    {
        if (Yii::app()->user->isSuperAdmin())
        {
            defined('YII_DEBUG') or define('YII_DEBUG', true);
            ini_set('memory_limit', '512M');
            $log = array();
            $logDirectory = Yii::app()->params['logDirectory'] . $directory . '/';
            $path = $logDirectory . $file;
            $handle = @fopen($path, "r");
            $regex = <<<'END'
/
  (
    (?: [\x00-\x7F]                 # single-byte sequences   0xxxxxxx
    |   [\xC0-\xDF][\x80-\xBF]      # double-byte sequences   110xxxxx 10xxxxxx
    |   [\xE0-\xEF][\x80-\xBF]{2}   # triple-byte sequences   1110xxxx 10xxxxxx * 2
    |   [\xF0-\xF7][\x80-\xBF]{3}   # quadruple-byte sequence 11110xxx 10xxxxxx * 3 
    ){1,100}                        # ...one or more times
  )
| .                                 # anything else
/x
END;
            if ($handle)
            {
                while (($buffer = fgets($handle)) !== false)
                {
                    //$log[] = json_decode($buffer, true);
                    $row = json_decode(preg_replace($regex, '$1', $buffer), true);                    
                    if ($row =='' || $row == null)
                        die('json_decode of line: <br />\n' . $buffer . '<br />\n has FAILED!');
                    
                    $log[] = $row;
                    //$log[] = json_decode(preg_replace('/[^(\x20-\x7F)]*/', '', $buffer), true);
                }
                if (!feof($handle))
                {
                    echo "Error: unexpected fgets() fail\n";
                }
                fclose($handle);
            }
            $logParser = new LogParser();
            $fn = explode('-', $file);
            $serverId = array_pop($fn);
            //Get end time
            for ($i = count($log) - 1; $i > 0; $i--)
            {
                $logRow = $log[$i];
                if ($logRow['action'] == 'game_ended')
                {
                    $end = round($logRow['time']);
                    break;
                }
            }
            $rounds = Round::model()->findAllByAttributes(array('end' => $end, 'server_id' => $serverId));
            foreach ($rounds as $round)
                $round->delete();
            Yii::beginProfile('createRound');
            $roundId = $logParser->createRound($logDirectory, $file, $serverId);

            Yii::endProfile('createRound');
            Yii::beginProfile('parse');
            unset($logParser);
            $logParser = new LogParser();

            $logParser->parse($path, $serverId, $roundId);
            Yii::endProfile('parse');
//            $logParser->startParse($file, $serverId, $roundId);
//            rename(Yii::app()->params['logDirectory'] . $directory . '/' . $file, Yii::app()->params['logDirectory'] . "completed/". $file);
        }
        else
            throw new CHttpException(401, 'You do not have permission to access this page.');
    }

    public function actionParseLogs($directory)
    {
        if (Yii::app()->user->isSuperAdmin())
        {
            if ($dirhandle = opendir(Yii::app()->params['logDirectory'] . $directory . '/'))
            {

                while (false !== ($file = readdir($dirhandle)))
                {
                    if ($file != '.' && $file != '..')
                        try
                        {
                            $this->actionParseLog($file, $directory);
                        }
                        catch (Exception $e)
                        {
                            
                        }
                }
                closedir($dirhandle);
            }
        }
        else
            throw new CHttpException(401, 'You do not have permission to access this page.');
    }

    public function actionPlayersTest()
    {
        $this->render('playerstest');
    }

}