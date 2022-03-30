<?php


namespace Dengpju\PhpScanner;


class Util
{
    /**
     * @param string $project
     * @param string $class
     * @param string $name
     * @return string
     */
    public static function md5(string $project, string $class, string $name): string
    {
        return md5($project . '+' . $class . '@' . $name);
    }

    /**
     * @param string $docComment
     * @return string
     */
    public static function parse(string $docComment): string
    {
        preg_match_all("/(?<=(\@Message\(\")).*?(?=(\"\)))/", $docComment, $doc);
        if ($doc) {
            if (isset($doc[0]) && isset($doc[0][0]) && !empty($doc[0][0])) {
                return trim($doc[0][0], '"');
            }
        }
        return "";
    }

    /**
     * @param int $length
     * @return bool|string
     */
    public static function randomNumbser($length = 8)
    {
        $chars = [];
        for ($i = 0; $i <= 100; $i++) {
            $chars[] = $i;
        }
        $keys = array_rand($chars, $length);
        shuffle($keys);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[$keys[$i]];
        }
        $random = date("YmdHis") . (date("YmdHis") + (int)$str);
        $random = str_shuffle((string)$random);
        list($usec, $sec) = explode(' ', microtime());
        $micro = str_replace('0.', '', $usec);
        $random = bcadd((int)substr((string)$random, 0, $length), (bcmul($micro, rand(100000, 999999))));
        $random = substr((string)$random, 0, $length);
        return $random;
    }
}