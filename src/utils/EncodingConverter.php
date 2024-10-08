<?php
/**
 * Created by PhpStorm.
 * User: jean.schmitz
 * Date: 24/05/2023
 * Time: 21:06
 */

 namespace Strolker\CleanArchitecture\utils;

class EncodingConverter
{

    public static function convertStringToIso88591($string) {
//        $encodingOrigem = mb_detect_encoding($string, ['UTF-8', 'ASCII']);
//
//        pre($string);
//        $stringISO = mb_convert_encoding($string, 'ISO-8859-1', $encodingOrigem);
//        pre($stringISO);

        //Feito dessa forma, pq quando usa o array igual feito acima, da algum problema, por algum motivo que eu n�o conhe�o.
        $encodingOrigem = mb_detect_encoding($string, 'UTF-8, ASCII', true);

        switch ($encodingOrigem) {
            case 'UTF-8':
                $stringISO = mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
                break;
            case 'ASCII':
                $stringISO = mb_convert_encoding($string, 'ISO-8859-1', 'ASCII');
                break;
            default:
                $stringISO = $string;
                break;
        }

        return $stringISO;
    }

    public static function convertNestedArrayToUtf8($data){
        $typeConversion = 'utf8';
        if(is_array($data)) {
            return self::convertNestedArrays($data, $typeConversion);
        }else{
            return self::encodingValue($data, $typeConversion);
        }
    }

    public static function convertNestedArrayToIso88591($data){
        $typeConversion = 'iso88591';
        if(is_array($data)) {
            return self::convertNestedArrays($data, $typeConversion);
        }else{
            return self::encodingValue($data, $typeConversion);
        }
    }

    private static function convertNestedArrays(array $data, $typeConversion)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::convertNestedArrays($value, $typeConversion);
            }
        }

        return self::convertArray($data, $typeConversion);
    }

    private static function convertArray(array $data, $typeConversion)
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = self::encodingValue($value, $typeConversion);
            }
        }

        return $data;
    }

    private static function encodingValue($value, $typeConversion){
        switch ($typeConversion){
            case 'utf8' :
                return utf8_encode($value);
            case 'iso88591' :
                return utf8_decode($value);
        }

        return $value;
    }
}