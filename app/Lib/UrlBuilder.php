<?php 

namespace App\Lib;

class UrlBuilder{

    public function buildURL($url, $params){
        $build = $url.'?';
        foreach ($params as $key => $value) {
            $build = $build . $key;
            if ($value) {
                $build = $build . '=';
                if (gettype($value)=='array') {
                    foreach ($value as $paramkey => $val) {
                        $build = $build . $val;
                        if ($paramkey !== array_key_last($value)) {
                            $build = $build . ',';
                        }
                    }
                }else {
                    $build = $build . $value;
                }
            }
            if ($key !== array_key_last($params)) {
                $build = $build . '&';
            }
        }
        return $build;
    }

}