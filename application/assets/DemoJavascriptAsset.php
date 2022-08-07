<?php

/*
 * Класс ассет для ДжаваСкрипт пользовательский
 */

namespace application\assets;
use ItForFree\SimpleAsset\SimpleAsset;
use application\assets\JqueryAsset;

class DemoJavascriptAsset extends SimpleAsset {

    public $basePath = '/';

    public $js = [
        'JS/NewMyJS.js',
//        'JS/newShowContent.js',
//        'JS/showContent.js',
    ];


    public $needs = [
        JqueryAsset::class];

}
