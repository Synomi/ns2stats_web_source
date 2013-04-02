<?php

class SteamApi extends CComponent {

    public static function steamIdToCommunityId($steamId) {
        return bcadd($steamId, '76561197960265728');
    }

    public static function CommunityIdToSteamId($communityId) {
        return bcsub($communityId, '76561197960265728');
    }

    public static function PublicIdToSteamId($publicId) {
        if (strpos('_', $publicId) !== false) {
            $publicId = strstr($publicId, '_');
            $publicId = substr($steamId, 1);
        }
        $parts = explode(':', $publicId);
        if (!isset($parts[1]) || !isset($parts[2]))
            throw new CHttpException(400, "Invalid Steam ID");
        return $parts[2] * 2 + $parts[1];
    }

    public static function getPlayerSummary($steamId) {
        $communityId = self::steamIdToCommunityId($steamId);
        $response = ApiClient::requestjson('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/', array(
                    'key' => Yii::app()->params['steamApiKey'],
                    'steamids' => $communityId,
                ));
        if (isset($response['response']['players'][0]))
            return $response['response']['players'][0];
    }

}