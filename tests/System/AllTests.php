<?php

namespace System;

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'System\AllTests::main');
}

require_once __DIR__ . '/../bootstrap.php';

class AllTests
{
    public static function main()
    {
        \PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new SystemTestSuite('System Tests');

        //$suite->addTestSuite('System\Core\AllTests');

        return $suite;
    }
}