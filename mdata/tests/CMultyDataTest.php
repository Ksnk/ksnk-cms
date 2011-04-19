<?php
require_once dirname(dirname(__FILE__)).'/CMultyData.php';

class CMultyDataTest extends CTestCase
{
 	public function testInitialization() {
		$c = $this->getMockForAbstractClass('CApplicationComponent',array('init','getIsInitialized'),'',NULL);
		$c->expects($this->any())
			->method('getIsInitialized')
			->will($this->returnValue(FALSE));
		$this->assertFalse($c->getIsInitialized());
		$c->init();
		$this->assertTrue($c->getIsInitialized());
	}
}
