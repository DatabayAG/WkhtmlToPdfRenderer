<?php

include_once './libs/composer/vendor/autoload.php';


/**
 * Class GlobalTestSuite
 */
const PHPUNIT = true;
class ilWkhtmlToPdfTestSuite extends PHPUnit_Framework_TestSuite
{
	const BLACKLIST = array(
		'ilWkhtmlToPdfTestSuite.php'
	);

	/**
	 * @return ilWkhtmlToPdfTestSuite
	 */
	public static function suite()
	{

		$suite = new ilWkhtmlToPdfTestSuite();
		self::addTestSuiteFiles($suite);
		return $suite;
	}

	/**
	 * @param PHPUnit_Framework_TestSuite $suite
	 */
	protected static function addTestSuiteFiles($suite)
	{

		$added		= array();
		$ignored	= array();

		$rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(dirname(__FILE__)));
		foreach($rii as $file)
		{
			if($file->isFile() && $file->getExtension() === 'php')
			{
				$class = str_replace(array('class.', '.php'), '', $file->getBasename());
				if( in_array($file->getFilename(), self::BLACKLIST))
				{
					$ignored[] = $class;
				}
				else
				{
					require_once $file;
					$reflection = new \ReflectionClass($class);
					if(!$reflection->isAbstract())
					{
						$added[] = $class;
						$suite->addTestSuite($class);
					}
				}
			}
		}

		self::printStatus($added, $ignored);
	}

	/**
	 * @param $added
	 * @param $ignored
	 */
	protected static function printStatus($added, $ignored)
	{
		echo "Searching for TestSuites...\n\n";

		echo "Added " . count($added) . " files to TestSuite: \n";
		sort($added);
		foreach($added as $suite)
		{
			echo "\t added: $suite\n";
		}

		echo "Ignored " . count($ignored) . " files from TestSuite: \n";
		sort($ignored);
		foreach($ignored as $suite)
		{
			echo "\t ignored: $suite\n";
		}

		echo "\n...done. Running TestSuites now:\n\n";
	}
}
