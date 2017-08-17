<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once __DIR__ .'/../classes/class.ilWkhtmlToPdfConfigFormGUI.php';
require_once 'Services/Form/classes/class.ilTextInputGUI.php';
require_once 'Services/Form/classes/class.ilCheckboxInputGUI.php';
require_once 'Services/Form/classes/class.ilSelectInputGUI.php';
require_once 'Services/Form/classes/class.ilFormSectionHeaderGUI.php';
require_once 'Services/Form/classes/class.ilRadioGroupInputGUI.php';
require_once 'Services/Language/classes/class.ilLanguage.php';
require_once 'Services/Administration/classes/class.ilSetting.php';

/**
 * Class ilWebkitHtmlToPdfTransformerGUITest
 * @package ilPdfGenerator
 */
class ilWkhtmlToPdfConfigFormGUITest  extends PHPUnit_Framework_TestCase
{

	protected $lng;
	
	protected $form;

	/**
	 * ilPhantomJsHtmlToPdfTransformerGUITest constructor.
	 */
	public function __construct()
	{
		$this->lng = $this->getMockBuilder('ilLanguage')
						  ->disableOriginalConstructor()
						  ->getMock();
		$this->lng->method('txt')
				  ->will($this->returnArgument(0));
	}

	protected static function getMethod($name) {
		$class = new ReflectionClass('ilWkhtmlToPdfConfigFormGUI');
		$method = $class->getMethod($name);
		$method->setAccessible(true);
		return $method;
	}

	public static function callMethod($obj, $name, array $args) {
		$class = new \ReflectionClass($obj);
		$method = $class->getMethod($name);
		$method->setAccessible(true);
		return $method->invokeArgs($obj, $args);
	}

	protected function setUp()
	{
		$this->form = new ilWkhtmlToPdfConfigFormGUI();
		$this->callMethod($this->form, 'setLanguage', array($this->lng));
	}

	public function testBuildMarginBottomForm()
	{
		$transformer = self::getMethod('buildMarginBottomForm');
		$this->assertInstanceOf('ilTextInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_margin_bottom', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('margin_bottom', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildMarginTopForm()
	{
		$transformer = self::getMethod('buildMarginTopForm');
		$this->assertInstanceOf('ilTextInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_margin_top', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('margin_top', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildMarginRightForm()
	{
		$transformer = self::getMethod('buildMarginRightForm');
		$this->assertInstanceOf('ilTextInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_margin_right', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('margin_right', $transformer->invokeArgs($this->form, array())->getPostVar());
	}
	public function testBuildMarginLeftForm()
	{
		$transformer = self::getMethod('buildMarginLeftForm');
		$this->assertInstanceOf('ilTextInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_margin_left', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('margin_left', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildJavascriptDelayForm()
	{
		$transformer = self::getMethod('buildMarginBottomForm');
		$this->assertInstanceOf('ilTextInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_margin_bottom', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('margin_bottom', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildPageSizesForm()
	{
		$transformer = self::getMethod('buildPageSizesForm');
		$this->assertInstanceOf('ilSelectInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_page_size', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('page_size', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildOrientationsForm()
	{
		$transformer = self::getMethod('buildOrientationsForm');
		$this->assertInstanceOf('ilSelectInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_orientation', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('orientation', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildZoomForm()
	{
		$transformer = self::getMethod('buildZoomForm');
		$this->assertInstanceOf('ilTextInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_zoom', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('zoom', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildHeaderForm()
	{
		$transformer = self::getMethod('buildHeaderForm');
		$this->assertInstanceOf('ilRadioGroupInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_header_type', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('header_select', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildCheckboxSvgForm()
	{
		$transformer = self::getMethod('buildCheckboxSvgForm');
		$this->assertInstanceOf('ilTextInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_checkbox_svg', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('checkbox_svg', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildCheckedRadiobuttonSvgForm()
	{
		$transformer = self::getMethod('buildCheckedRadiobuttonSvgForm');
		$this->assertInstanceOf('ilTextInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_radio_button_checked_svg', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('radio_button_checked_svg', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildRadiobuttonSvgForm()
	{
		$transformer = self::getMethod('buildRadiobuttonSvgForm');
		$this->assertInstanceOf('ilTextInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_radio_button_svg', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('radio_button_svg', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildCheckedCheckboxSvgForm()
	{
		$transformer = self::getMethod('buildCheckedCheckboxSvgForm');
		$this->assertInstanceOf('ilTextInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_checkbox_checked_svg', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('checkbox_checked_svg', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildPrintMediaTypeForm()
	{
		$transformer = self::getMethod('buildPrintMediaTypeForm');
		$this->assertInstanceOf('ilCheckboxInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_print_media_type', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('print_media_type', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildGreyScaleForm()
	{
		$transformer = self::getMethod('buildGreyScaleForm');
		$this->assertInstanceOf('ilCheckboxInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_greyscale', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('greyscale', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildLowQualityForm()
	{
		$transformer = self::getMethod('buildLowQualityForm');
		$this->assertInstanceOf('ilCheckboxInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_low_quality', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('low_quality', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildUserStylesheetForm()
	{
		$transformer = self::getMethod('buildUserStylesheetForm');
		$this->assertInstanceOf('ilTextInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_user_stylesheet', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('user_stylesheet', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildEnableFormsForm()
	{
		$transformer = self::getMethod('buildEnableFormsForm');
		$this->assertInstanceOf('ilCheckboxInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_enable_forms', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('enable_forms', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildExternalLinksForm()
	{
		$transformer = self::getMethod('buildExternalLinksForm');
		$this->assertInstanceOf('ilCheckboxInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_external_links', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('external_links', $transformer->invokeArgs($this->form, array())->getPostVar());
	}

	public function testBuildFooterForm()
	{
		$transformer = self::getMethod('buildFooterForm');
		$this->assertInstanceOf('ilRadioGroupInputGUI', $transformer->invokeArgs($this->form, array()));
		$this->assertSame('pdfg_renderer_wkhtp_footer_type', $transformer->invokeArgs($this->form, array())->getTitle());
		$this->assertSame('footer_select', $transformer->invokeArgs($this->form, array())->getPostVar());
	}



} 