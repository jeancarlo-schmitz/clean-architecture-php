<?php
namespace Strolker\CleanArchitecture\utils;

class JsonHelper
{
    public static function toJson($data)
    {
        return json_encode($data, true);
    }

    public static function jsonToArray($data)
    {
        return json_decode($data, true);
    }

    public static function jsonToObject($data)
    {
        return json_decode($data);
    }

    public static function toJsonUtf8($data)
    {
        $data = EncodingConverter::convertNestedArrayToUtf8($data);
        return json_encode($data, true);
    }

    public static function toJsonWithoutAccentuation($data)
    {
        $trataString = new TrataString();
        $data = $trataString->removeAcentuacaoDoArray($data);
        return json_encode($data, true);
    }

    public static function toJsonWithoutSlashes($data)
    {
        $trataString = new TrataString();
        $data = $trataString->removeAcentuacaoDoArray($data);
        $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        $data = str_replace('\"', '', $data);
        $data = preg_replace("~[\\\\/*?<>|]~", "", $data);
        $data = preg_replace("~[\\\\'*?<>|]~", "", $data);
        $data = utf8_decode($data);

        return $data;
    }

    public static function toSimpleArray($array) 
    {
        if (array_keys($array) !== range(0, count($array) - 1)) {
            $array = array_values($array);
        }
        return $array;
    }

    public static function toJsonIso($data)
    {
        $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        $data = str_replace('\"', '', $data);
        $data = preg_replace("~[\\\\/*?<>]~", "", $data);
        $data = preg_replace("~[\\\\'*?<>]~", "", $data);
        $data = utf8_decode($data);

        return $data;
    }

    public static function getHeader()
    {
       return 'Content-Type: application/json';
    }
}