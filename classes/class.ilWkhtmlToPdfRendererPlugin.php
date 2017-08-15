<?php

require_once './Services/PDFGeneration/classes/class.ilPDFGenerationConstants.php';
require_once './Services/PDFGeneration/interfaces/interface.ilRendererConfig.php';
require_once './Services/PDFGeneration/interfaces/interface.ilPDFRenderer.php';
require_once './Services/PDFGeneration/classes/class.ilPDFRendererPlugin.php';
require_once 'Customizing/global/plugins/Services/PDFGeneration/Renderer/WkhtmlToPdfRenderer/classes/class.ilWkhtmlToPdfConfig.php';
require_once 'Customizing/global/plugins/Services/PDFGeneration/Renderer/WkhtmlToPdfRenderer/classes/class.ilWkhtmlToPdfConfigFormGUI.php';

class ilWkhtmlToPdfRendererPlugin extends ilPDFRendererPlugin
{
	/**
	 * @var ilWkhtmlToPdfConfig
	 */
	protected $config;

	
	/**
	 * @var string
	 */
	const CTYPE = 'Services';

	/**
	 * @var string
	 */
	const CNAME = 'PDFGeneration';

	/**
	 * @var string
	 */
	const SLOT_ID = 'Renderer';

	/**
	 * @var string
	 */
	const PNAME = 'WkhtmlToPdfRenderer';

	/**+
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * @return string
	 */
	public function getPluginName()
	{
		return self::PNAME;
	}

	/**
	 * @return self
	 */
	public static function getInstance()
	{
		if(self::$instance instanceof self)
		{
			return self::$instance;
		}

		self::$instance = ilPluginAdmin::getPluginObject(
			self::CTYPE,
			self::CNAME,
			self::SLOT_ID,
			self::PNAME
		);

		return self::$instance;
	}

	/**
	 * @param ilPropertyFormGUI $form
	 * @param string            $service
	 * @param string            $purpose
	 */
	public function addConfigElementsToForm(\ilPropertyFormGUI $form, $service, $purpose)
	{
		$gui = new ilWkhtmlToPdfConfigFormGUI();
		$gui->addConfigForm($form);
	}

	/**
	 * @param ilPropertyFormGUI $form
	 * @param string            $service
	 * @param string            $purpose
	 * @param ilWkhtmlToPdfConfig     $config
	 */
	public function populateConfigElementsInForm(\ilPropertyFormGUI $form, $service, $purpose, $config)
	{
		$this->config = new ilWkhtmlToPdfConfig($config);
		$gui = new ilWkhtmlToPdfConfigFormGUI();
		$gui->populateForm($form, $this->config);
	}

	/**
	 * @param ilPropertyFormGUI $form
	 * @param string            $service
	 * @param string            $purpose
	 * @return bool
	 */
	public function validateConfigInForm(\ilPropertyFormGUI $form, $service, $purpose)
	{
		$gui = new ilWkhtmlToPdfConfigFormGUI();
		return $gui->validateForm();
	}

	/**
	 * @param ilPropertyFormGUI $form
	 * @param string            $service
	 * @param string            $purpose
	 * @return array
	 */
	public function getConfigFromForm(\ilPropertyFormGUI $form, $service, $purpose)
	{
		$gui = new ilWkhtmlToPdfConfigFormGUI();
		return $gui->getConfigFromForm($form);
	}

	/**
	 * @param string $service
	 * @param string $purpose
	 * @return ilWkhtmlToPdfConfig
	 */
	public function getDefaultConfig($service, $purpose)
	{
		$config = new ilWkhtmlToPdfConfig();
		return $config;
	}

	/**
	 * @param string             $service
	 * @param string             $purpose
	 * @param array              $config
	 * @param ilPDFGenerationJob $job
	 */
	public function generatePDF($service, $purpose, $config, $job)
	{
		$html_file	= $this->getHtmlTempName();
		file_put_contents($html_file, implode('',$job->getPages()));
		$this->createPDFFileFromHTMLFile($html_file, $config, $job);
	}

	/**
	 * @param $a_path_to_file
	 * @param $config
	 * @param ilPDFGenerationJob $job
	 */
	public function createPDFFileFromHTMLFile($a_path_to_file, $config, $job)
	{

		if(is_array($a_path_to_file))
		{
			$files_list_as_string = ' ';
			foreach($a_path_to_file as $file)
			{
				if(file_exists($file))
				{
					$files_list_as_string .= ' '.$files_list_as_string;
				}
			}
			$this->runCommandLine($files_list_as_string, $job->getFilename(), $config);
		}
		else if(file_exists($a_path_to_file))
		{
			$this->runCommandLine($a_path_to_file, $job->getFilename(), $config);
		}
	}

	/**
	 * @param $a_path_to_file
	 * @param $a_target
	 * @param $config
	 */
	protected function runCommandLine($a_path_to_file, $a_target, $config)
	{
		global $DIC;
		$log = $DIC['ilLog'];

		$config			= new ilWkhtmlToPdfConfig($config);
		$temp_file		= $this->getPdfTempName();
		$args			= $config->getCommandLineConfig() . ' ' . $a_path_to_file . ' ' . $temp_file . $this->redirectLog();
		$return_value	= ilUtil::execQuoted($config->getWKHTMLToPdfDefaultPath(), $args);

		$log->debug('ilWebkitHtmlToPdfTransformer command line config: ' . $args);
		foreach($return_value as $key => $value)
		{
			$log->debug('ilWebkitHtmlToPdfTransformer return value line ' . $key . ' : ' . $value );
		}
		if(file_exists($temp_file))
		{
			$log->debug('ilWebkitHtmlToPdfTransformer file exists: ' . $temp_file . ' file size is :' . filesize($temp_file) . ' bytes, will be renamed to '. $a_target);
			rename($temp_file, $a_target);
		}
		else
		{
			$log->info('ilWebkitHtmlToPdfTransformer error: ' . print_r($return_value, true) );
		}
	}

	/**
	 * @return string
	 */
	protected function redirectLog()
	{
		return	$redirect_log = ' 2>&1 ';
	}

	/**
	 * @return string
	 */
	public function getPdfTempName()
	{
		return $this->getTempFileName('pdf');
	}

	/**
	 * @return string
	 */
	public function getHtmlTempName()
	{
		return $this->getTempFileName('html');
	}

	/**
	 * @param $file_type
	 * @return string
	 */
	protected function getTempFileName($file_type)
	{
		return ilUtil::ilTempnam() . '.' . $file_type;
	}

}
