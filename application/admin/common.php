<?php

function phoneFormat($phone) {
    return substr($phone, 0, 3) . '*****' . substr($phone, -3);
}

function getImageViewUrl($url, $width=120, $height=80) {
    return $url . '?x-oss-process=image/resize,w_'.$width.',h_'.$height;
}