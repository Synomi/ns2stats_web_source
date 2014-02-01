<?php

Class SignatureHelper
{

    public static function updateDynamicValues($playerImage, $player)
    {

        $dynamicImage = imagecreatefromstring($playerImage->background_image);
        self::addDynamicValues($dynamicImage, $playerImage->data, $player);

        //test
        //self::addGenericText($dynamicImage, 300, 100, date('Y-M-d H:i:s'));

        $tmpFilePath = '/tmp/signature_' . $player->id . '_' . $playerImage->id . '.png';
        imagepng($dynamicImage, $tmpFilePath);
        $imageData = file_get_contents($tmpFilePath);

        $playerImage->image = $imageData;
        $playerImage->player_id = $player->id;

        $playerImage->save();

        unlink($tmpFilePath);
        unset($tmpFilePath);
        unset($imageData);
    }

    /*
     * @pointer $image
     */

    public static function addSteamImage(&$image, $player, $signature)
    {
        $background_image_width = imagesx($image);
        $background_image_height = imagesy($image);

        $steam_image = imagecreatefromjpeg($player->steam_image);
        if (isset($signature->border) && $signature->border == true)
            self::addBorder($steam_image, 184, 184);

        // get current width/height
        $steam_image_width = imagesx($steam_image);
        $steam_image_height = imagesy($steam_image);



        $sImageNewHeight = $background_image_height * 0.9;
        if ($sImageNewHeight > 184)
            $sImageNewHeight = 184;

        $sImageNewWidth = $sImageNewHeight;

        $sDestX = $background_image_width * 0.02;

        $sDestY = ($background_image_height - $sImageNewHeight) / 2;

        imagecopyresampled($image, //destination image
                $steam_image, //source image
                $sDestX, //destination image x
                $sDestY, // destination image y
                0, //source image x
                0, //source image y
                $sImageNewWidth, //destination image new width
                $sImageNewHeight, // destination image new height
                $steam_image_width, //source image width
                $steam_image_height //source image heigth
        );
    }

    /*
     * @return array('width','heigth')
     */

    public static function getFontWidthAndHeigth($size, $font, $text)
    {
        $sizes = imagettfbbox($size, 0, $font, $text);
        $iWidth = abs($sizes[2] - $sizes[0]);
        $iHeight = abs($sizes[7] - $sizes[1]);

        return array('width' => $iWidth, 'height' => $iHeight);
    }

    /*
     * @return image
     */

    public static function resizeImage($image, $curWidth, $curHeight, $widthLimit, $heightLimit)
    {
        /*
         * Jos leveys 1000px ja korkeus 700px
         * Pitää pienentää kunnes leveys = 900 px ja siitä laskettu korkeus on pienempi kuin 601px
         * Tai pitää pienentää kunnes korkeus on 600px ja siitä laskettu leveys on pienempi kuin 901px
         */
        $sizesOk = false;
        $breaker = 0;

        //image ratio
        $curRatio = $curWidth / $curHeight;
        //$curWidth = $curHeigth * $curRatio
        //$curHeigth = $curwidth / $curratio
        while ($sizesOk === false)
        {
            //lasketaan nykyinen uusi leveys ja uusi korkeus kummallakin tapaa, vähentäen korkeutta ja erikseen vähentäen leveyttä

            $newWidthX = intval($curWidth - $breaker);
            $newHeightX = intval($newWidthX / $curRatio);

            //jos korkeudet ovat ok
            if ($newWidthX <= $widthLimit && $newHeightX <= $heightLimit)
            {
                $newWidth = $newWidthX;
                $newHeight = $newHeightX;
                break;
            }

            $newHeightY = intval($curWidth - $breaker);
            $newWidthY = intval($newHeightY * $curRatio);

            //jos korkeudet ovat ok
            if ($newWidthY <= $widthLimit && $newHeightY <= $heightLimit)
            {
                $newWidth = $newWidthY;
                $newHeight = $newHeightY;
                break;
            }

            $breaker++;
            if ($breaker == 10000)
                throw new CHttpException(500, 'Loop breaker hit, unknown error. Source public static function resizeImage');
        }


        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

        imagecopyresampled($resizedImage, //destination image
                $image, //source image
                0, //destination image x
                0, // destination image y
                0, //source image x
                0, //source image y
                $newWidth, //destination image new width
                $newHeight, // destination image new height
                $curWidth, //source image width
                $curHeight //source image heigth
        );
        unset($image);

        return $resizedImage;
    }

    /*
     * @pointer image
     */

    public static function addLogo(&$image)
    {
        $logo = imagecreatefrompng('images/signature/logo.png');
        $logo_image_width = imagesx($logo);
        $logo_image_height = imagesy($logo);

        $background_image_width = imagesx($image);
        $background_image_height = imagesy($image);

        $sImageNewHeight = $background_image_height * 0.7;
        if ($sImageNewHeight > 100)
            $sImageNewHeight = 100;

        $sImageNewWidth = $sImageNewHeight;

        $sDestX = $background_image_width - $sImageNewWidth - ($background_image_width * 0.05);

        $sDestY = ($background_image_height - $sImageNewHeight) / 2;


        imagecopyresampled($image, //destination image
                $logo, //source image
                $sDestX, //destination image x
                $sDestY, // destination image y
                0, //source image x
                0, //source image y
                $sImageNewWidth, //destination image new width
                $sImageNewHeight, // destination image new height
                $logo_image_width, //source image width
                $logo_image_height //source image heigth
        );

        //add ns2stats.com text
        $size = 10;
        $white = imagecolorallocate($image, 255, 255, 255);
        $font = 'css/OptimusPrincepsSemiBold.ttf';
        $text = 'ns2stats.com';
        $ns2statsTextSize = SignatureHelper::getFontWidthAndHeigth($size, $font, $text);
        //add text at bottom of logo centered
        $textX = $sDestX + ($sImageNewWidth / 2) - ($ns2statsTextSize['width'] / 2) + 3;
        $textY = $sDestY + $sImageNewHeight + $ns2statsTextSize['height'];

        imagettftext($image, $size, 0, $textX, $textY, $white, $font, $text);

        unset($logo);

        return $image;
    }

    /*
     * @pointer $image
     */

    public static function addGenericText(&$image, $x, $y, $text)
    {
        $size = 10;
        $white = imagecolorallocate($image, 255, 255, 255);
        $font = 'css/WHITRABT.TTF';

        imagettftext($image, $size, 0, $x, $y, $white, $font, $text);
    }

    /*
     * @pointer image
     */

    public static function addBorder(&$image, $width, $height)
    {

        $gd = imagecreatetruecolor($width, $height);

        for ($i = 0; $i < $height; $i++)
        {
            // add left border
            imagesetpixel($image, 0, $i, imagecolorallocate($gd, 10, 10, 160));
            // add right border
            imagesetpixel($image, $width - 1, $i, imagecolorallocate($gd, 10, 10, 160));
        }
        for ($j = 0; $j < $width; $j++)
        {
            // add bottom border
            imagesetpixel($image, $j, 0, imagecolorallocate($gd, 10, 10, 200));
            // add top border
            imagesetpixel($image, $j, $height - 1, imagecolorallocate($gd, 10, 10, 160));
        }
    }

    /*
     * @pointer image
     */

    public static function addDynamicValues(&$image, $data, $player)
    {
        $white = imagecolorallocate($image, 255, 255, 255);
        //$font = 'css/OptimusPrincepsSemiBold.ttf';
        $font = 'css/WHITRABT.TTF';

        //make explodeable if not.
        if (strpos($data, PHP_EOL) === false)
            $data .=' ' . PHP_EOL . ' ';

        $rows = explode(PHP_EOL, self::findValues($data, $player));
        if (is_array($rows))
        {
            $y = 7;
            $size = 9;
            foreach ($rows as $row)
            {
                $y+=$size + intval($size / 5) + 1;
                $addedX = 0;

                if (strpos($row, '[SKIPSTEAM]') !== false)
                    $addedX+=161;
                if (strpos($row, '[SKIP100]') !== false)
                    $addedX+=100;
                if (strpos($row, '[SKIP200]') !== false)
                    $addedX+=200;
                if (strpos($row, '[SKIP300]') !== false)
                    $addedX+=300;
                if (strpos($row, '[SKIP400]') !== false)
                    $addedX+=400;
                if (strpos($row, '[SKIP500]') !== false)
                    $addedX+=500;
                if (strpos($row, '[SKIP600]') !== false)
                    $addedX+=600;


                $text = str_replace(array('[SKIPSTEAM]', '[SKIP100]', '[SKIP200]', '[SKIP300]', '[SKIP400]', '[SKIP500]', '[SKIP600]'), '', $row);
                imagettftext($image, $size, 0, $addedX, $y, $white, $font, '' . $text);
            }
        }
        else
            throw new CHttpException(500, 'Values field failed to parse-');
    }

    private static function findValues($text, $player)
    {
        //OVERRIDE
        $_GET['alltime_signature'] = true;
        $searchFor = array();
        $replaceWith = array();

        foreach ($player->attributes as $key => $value)
        {
            if ($key != 'code' && $key != 'ip')
            {
                $searchFor[] = '[' . strtolower($key) . ']';
                $replaceWith[] = $value;
            }
        }
        //customs
        if (strpos($text, '[rank]') !== false)
        {
            $searchFor[] = '[rank]';
            $replaceWith[] = Player::getRankByRating($player->id);
        }
        if (strpos($text, '[ranked_players]') !== false)
        {
            $searchFor[] = '[ranked_players]';
            $replaceWith[] = Player::getMaxRank();
        }
        if (strpos($text, '[rounds_played]') !== false)
        {
            $searchFor[] = '[rounds_played]';
            $replaceWith[] = Player::getRoundsPlayed($player->id);
        }
        if (strpos($text, '[time_played]') !== false)
        {
            $searchFor[] = '[time_played]';
            $replaceWith[] = Helper::secondsToTime(Player::getTimePlayed($player->id));
        }
        if (strpos($text, '[longest_survival]') !== false)
        {
            $searchFor[] = '[longest_survival]';
            $replaceWith[] = Helper::secondsToTime(Player::getLongestSurvival($player->id));
        }
        if (strpos($text, '[score]') !== false)
        {
            $searchFor[] = '[score]';
            $replaceWith[] = Player::getScore($player->id);
        }
        if (strpos($text, '[kills]') !== false)
        {
            $searchFor[] = '[kills]';
            $replaceWith[] = Player::getKillsById($player->id);
        }
        if (strpos($text, '[deaths]') !== false)
        {
            $searchFor[] = '[deaths]';
            $replaceWith[] = Player::getDeaths($player->id);
        }
        if (strpos($text, '[best_kill_streak]') !== false)
        {
            $searchFor[] = '[best_kill_streak]';
            $replaceWith[] = Player::getKillStreak($player->id);
        }
        if (strpos($text, '[kpd]') !== false)
        {
            $searchFor[] = '[kpd]';
            $replaceWith[] = round(Player::getKD($player->id), 2);
        }
        if (strpos($text, '[score_per_death]') !== false)
        {
            $searchFor[] = '[score_per_death]';
            $replaceWith[] = round(Player::getSD($player->id), 2);
        }
        if (strpos($text, '[score_per_minute]') !== false)
        {
            $searchFor[] = '[score_per_minute]';
            $replaceWith[] = round(Player::getSM($player->id), 2);
        }
        if (strpos($text, '[alien_commander_ranking]') !== false)
        {
            $searchFor[] = '[alien_commander_ranking]';
            $replaceWith[] = $player->getRanking('alien_commander_elo');
        }
        if (strpos($text, '[marine_commander_ranking]') !== false)
        {
            $searchFor[] = '[marine_commander_ranking]';
            $replaceWith[] = $player->getRanking('marine_commander_elo');
        }
        if (strpos($text, '[alien_ranking]') !== false)
        {
            $searchFor[] = '[alien_ranking]';
            $replaceWith[] = $player->getRanking('alien_win_elo');
        }
        if (strpos($text, '[marine_ranking]') !== false)
        {
            $searchFor[] = '[marine_ranking]';
            $replaceWith[] = $player->getRanking('marine_win_elo');
        }
        if (strpos($text, '[ns2stats.com_time]') !== false)
        {
            $searchFor[] = '[ns2stats.com_time]';
            $replaceWith[] = date('Y-M-d H:i:s');
        }
        //filled when first used
        $marine_kills = null;
        if (strpos($text, '[kills_by_rifle]') !== false)
        {
            $searchFor[] = '[kills_by_rifle]';
            $replaceWith[] = self::getMarineWeaponKillCount($marine_kills, 'rifle', $player->id);
        }
        if (strpos($text, '[kills_by_shotgun]') !== false)
        {
            $searchFor[] = '[kills_by_shotgun]';
            $replaceWith[] = self::getMarineWeaponKillCount($marine_kills, 'shotgun', $player->id);
        }
        if (strpos($text, '[kills_by_pistol]') !== false)
        {
            $searchFor[] = '[kills_by_pistol]';
            $replaceWith[] = self::getMarineWeaponKillCount($marine_kills, 'pistol', $player->id);
        }
        $alien_kills = null;
        if (strpos($text, '[kills_by_bite]') !== false)
        {
            $searchFor[] = '[kills_by_bite]';
            $replaceWith[] = self::getAlienWeaponKillCount($alien_kills, 'bite', $player->id);
        }
        if (strpos($text, '[kills_by_spit]') !== false)
        {
            $searchFor[] = '[kills_by_spit]';
            $replaceWith[] = self::getAlienWeaponKillCount($alien_kills, 'spit', $player->id);
        }
        if (strpos($text, '[kills_by_swipe]') !== false)
        {
            $searchFor[] = '[kills_by_swipe]';
            $replaceWith[] = self::getAlienWeaponKillCount($alien_kills, 'swipe', $player->id);
        }

        return str_replace($searchFor, $replaceWith, $text);
    }

    private static function getMarineWeaponKillCount(&$marine_kills, $weaponName, $player_id)
    {
        $amount = 0;
        if (!isset($marine_kills))
            $marine_kills = Player::getKillsByWeapon($player_id, 1);


        if (count($marine_kills) > 0)
            foreach ($marine_kills as $weapon)
            {
                if ($weapon['name'] == $weaponName)
                {
                    $amount = $weapon['count'];
                    break;
                }
            }

        return $amount;
    }

    private static function getAlienWeaponKillCount(&$alien_kills, $weaponName, $player_id)
    {
        $amount = 0;
        if (!isset($alien_kills))
            $alien_kills = Player::getKillsByWeapon($player_id, 2);

        if (count($alien_kills) > 0)
            foreach ($alien_kills as $weapon)
            {
                if ($weapon['name'] == $weaponName)
                {
                    $amount = $weapon['count'];
                    break;
                }
            }

        return $amount;
    }

    /*
     * Uses 2 different caches, user cache and internal cache
     * Image is cached in user's browser for 1 hour.
     * Image is dynamicallu updated when loaded every 30 minutes
     */

    public static function displaySignature($id)
    {
        header("Content-type: image/png");
        header("Cache-Control: private, max-age=10800, pre-check=10800");
        header("Pragma: private");
        header("Expires: " . date(DATE_RFC822, strtotime("30 minute")));

        $playerImage = PlayerImage::model()->findByPk($id);
        if (isset($playerImage))
        {
            //if cache is expired, update image
            $cahceId = 'signature_cache_' . $id;
            $CacheValue = Yii::app()->cache->get($cahceId);
            if ($CacheValue === false)
            {
                $player = Player::model()->findByPk($playerImage->player_id);
                self::updateDynamicValues($playerImage, $player);
                Yii::app()->cache->set($cahceId, '', 60 * 20);
            }

            echo $playerImage->image;
            unset($playerImage);
        }

        Yii::app()->end();
    }

}

?>
