<?php
//require_once('/simpletest/autorun.php');
    require_once('/simpletest/unit_tester.php');
    require_once('/simpletest/shell_tester.php');
    require_once('/simpletest/mock_objects.php');
    require_once('/simpletest/reporter.php');
    require_once('/simpletest/xml.php');

ini_set('include_path',
  ini_get('include_path')
  .';..' // windows only include!
);
include_once ("wiki.ext.php");

function pps(&$x,$default=''){if(empty($x))return $default; else return $x;}

class Test_Preprocessor extends UnitTestCase {

	/**
	 * тестирование шаблона с генерацией нового класса
	 */
	function wikitxt($data){
		static $wiki_parcer;
		if(empty($wiki_parcer)) $wiki_parcer= new wiki_parcer();
		
		$wiki_parcer->read_string($data);
		return $wiki_parcer->wiki_txt();
	}
	
	/**
	 * тестируем wiki разметку в препроцессоре
	 */
		
	function test_wiki1() {
        $data='
Hello world
* one 
* two

just a sign
        
        ';
		$this->assertEqual(
			$this->wikitxt($data),
			'if( \'hello\' ){ world };'
		);
	}
	
}

	$test = &new TestSuite('testing a template engine');
    $test->addTestCase(new Test_Preprocessor());
    if (isset($_GET['xml']) || in_array('xml', (isset($argv) ? $argv : array()))) {
        $reporter = &new XmlReporter();
    } elseif (TextReporter::inCli()) {
        $reporter = &new TextReporter();
    } else {
        $reporter = &new HTMLReporter();
    }
    if (isset($_GET['dry']) || in_array('dry', (isset($argv) ? $argv : array()))) {
        $reporter->makeDry();
    }
    exit ($test->run($reporter) ? 0 : 1);
?>