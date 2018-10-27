<?php
/**
 * Created by PhpStorm.
 * User: qiankaihua
 * Date: 25/10/2018
 * Time: 4:00 PM
 */

namespace App;

/**
 * Class Helper
 * @package App
 */
class Helper
{
    /**
     * @param string $string
     * @return string
     */
    public static function sha256(String $string)
    {
        return hash('sha256', $string);
    }

    /**
     * @param integer $min
     * @param integer $max
     * @return string
     */
    public static function generateChinese(int $min = 2, int $max = 4)
    {
        $zh = '';
        $len = rand($min, $max);
        for ($i = 0; $i < $len; $i++) {
            $zh .= '&#' . rand(19968, 40869) . ';';
        }
        return mb_convert_encoding($zh, 'UTF-8', 'HTML-ENTITIES');
    }

    /**
     * @param string $value
     * @return array
     */
    public static function stringToArray(string $value)
    {
        $arr = [];
        for ($i = strlen($value) - 1; $i >= 0; --$i) {
            $arr[] = $value[$i];
        }
        return $arr;
    }
    /**
     * @param int $len
     * @param string $charset
     * @return string
     */
    public static function generateVerifyCode(int $len = 6, string $charset = '0123456789')
    {
        $charset = Helper::stringToArray($charset);
        $code = '';
        for ($i = 0; $i < $len; ++$i) {
            $code .= $charset[array_rand($charset)];
        }
        return $code;
    }
}