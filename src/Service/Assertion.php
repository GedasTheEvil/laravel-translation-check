<?php

namespace GedasTheEvil\LaravelTranslationCheck\Service;

use GedasTheEvil\LaravelTranslationCheck\Exceptions\AssertionFailed;

/**
 * Used to test deeply coupled code with live data, where PHPUnit test cannot be made.
 * Used in conjunction with TestCase class. And Executed in the Testing Controller
 */
class Assertion
{
    /**
     * @param $result
     * @param $expected
     * @param string $message
     *
     * @throws AssertionFailed
     */
    public static function assertMore($result, $expected, $message = '')
    {
        if ($result <= $expected) {
            self::fail($result, $expected, $message, '<=', __FUNCTION__);
        }
    }

    /**
     * @param $result
     * @param $expected
     * @param string $message
     * @param string $operator
     * @param string $assertion
     *
     * @throws AssertionFailed
     */
    public static function fail($result, $expected, $message = '', $operator = '!==', $assertion = 'assertEqual')
    {
        if (!is_string($result)) {
            $result = json_encode($result);
        }

        if (!is_string($expected)) {
            $expected = json_encode($expected);
        }

        throw new AssertionFailed("{$message}. {$assertion} : \n'{$result}' \n{$operator} \n'{$expected}'");
    }

    /**
     * @param $result
     * @param $expected
     * @param string $message
     *
     * @throws AssertionFailed
     */
    public static function assertLess($result, $expected, $message = '')
    {
        if ($result >= $expected) {
            self::fail($result, $expected, $message, '>=', __FUNCTION__);
        }
    }

    /**
     * @param $result
     * @param $expected
     * @param string $message
     *
     * @throws AssertionFailed
     */
    public static function assertJsonEqual($result, $expected, $message = '')
    {
        $result = json_encode($result);
        $expected = json_encode($expected);

        if ($result !== $expected) {
            self::fail($result, $expected, $message, '!==', __FUNCTION__);
        }
    }

    public static function arrayHasFieldKeys(array $result, array $expected, $message = '')
    {
        foreach ($expected as $key => $value) {
            if (!isset($result[$key])) {
                self::fail($result, $key, $message, 'does not have', __FUNCTION__);
            }

            if (gettype($value) !== gettype($result[$key])) {
                self::fail(
                    gettype($value),
                    gettype($result[$key]),
                    $message,
                    "Types mismatch on key '{$key}' ",
                    __FUNCTION__
                );
            }

            if (is_array($value)) {
                self::arrayHasFieldKeys($result[$key], $value, "{$message}->{$key}");
            }
        }
    }

    public static function arrayHasFields(array $result, array $expected, $message = '')
    {
        foreach ($expected as $key => $value) {
            if (!isset($result[$key])) {
                self::fail($result, $key, $message, 'does not have', __FUNCTION__);
            }

            if (is_array($value)) {
                self::arrayHasFields($result[$key], $value, "{$message}->{$key}");
            } else {
                self::assertEqual($result[$key], $value, $message);
            }
        }
    }

    /**
     * @param $result
     * @param $expected
     * @param string $message
     *
     * @throws AssertionFailed
     */
    public static function assertEqual($result, $expected, $message = '')
    {
        if ($result !== $expected) {
            self::fail($result, $expected, $message, '!==', __FUNCTION__);
        }
    }
}
