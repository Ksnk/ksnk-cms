<?php
require_once('/simpletest/autorun.php');
require_once('/xilen/cms/admin/engine/cache.php');

class TestOfLogging extends UnitTestCase {
    function testLogCreatesNewFileOnFirstMessage() {
        $log = new Cache();
        $this->assertFalse(empty($log));
     }
}
?>