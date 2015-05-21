<?php

namespace GedasTheEvil\LaravelTranslationCheck\Formatter;

class ConsoleColor
{
    const END = "\033[0m\n";
    const FG_BLACK = "\033[0;30m";
    const BG_RED = "\033[41m";
    const BG_GREEN = "\033[42m";
    const BG_BLUE = "\033[45m";

    public static function red($text)
    {
        echo self::FG_BLACK . self::BG_RED . $text . self::END;
    }

    public static function green($text)
    {
        echo self::BG_GREEN . $text . self::END;
    }

    public static function blue($text)
    {
        echo self::FG_BLACK . self::BG_BLUE . $text . self::END;
    }
}
