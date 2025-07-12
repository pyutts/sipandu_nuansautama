<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HelperJS {
    protected static $sections = [];
    protected static $stack = [];

    public static function start($section) {
        self::$stack[] = $section;
        ob_start();
    }

    public static function end() {
        $section = array_pop(self::$stack);
        $content = ob_get_clean();
        if (!isset(self::$sections[$section])) {
            self::$sections[$section] = '';
        }
        self::$sections[$section] .= $content . "\n";
    }

    public static function render($section) {
        echo self::$sections[$section] ?? '';
    }
}
