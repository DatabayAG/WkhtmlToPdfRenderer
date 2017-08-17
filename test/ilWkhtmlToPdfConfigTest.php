<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once __DIR__ .'/../classes/class.ilWkhtmlToPdfConfig.php';
/**
 * Class ilWebkitHtmlToPdfTransformerTest
 * @package ilPdfGenerator
 */
class ilWkhtmlToPdfConfigTest  extends PHPUnit_Framework_TestCase
{
	/**
	 * @var ilWkhtmlToPdfConfig
	 */
	protected $config;

	protected function setUp()
	{
		$this->config = new ilWkhtmlToPdfConfig();
	}

	public function testInstanceCanBeCreated()
	{
		$this->assertInstanceOf('ilWkhtmlToPdfConfig', $this->config);
	}


	public function testDefaultConfig()
	{
		$this->assertFalse($this->config->getEnabledForms());
		$this->assertTrue($this->config->getExternalLinks());
		$this->assertSame(500, $this->config->getJavascriptDelay());
		$this->assertSame(1, $this->config->getZoom());
		$this->assertSame('Portrait', $this->config->getOrientation());
		$this->assertSame('A4', $this->config->getPageSize());
		$this->assertSame('0.5cm', $this->config->getMarginLeft());
		$this->assertSame('2cm', $this->config->getMarginRight());
		$this->assertSame('0.5cm', $this->config->getMarginBottom());
		$this->assertSame('2cm', $this->config->getMarginTop());

	}

	public function testDefaultConfigCommandline()
	{
		$cmd = ' --zoom 1 --enable-external-links --disable-forms --orientation Portrait --page-size A4 --javascript-delay 500 --margin-bottom 0.5cm --margin-left 0.5cm --margin-right 2cm --margin-top 2cm --quiet ';

		$this->assertSame($cmd, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigSimple()
	{
		$this->config->setOrientation('Portrait');
		$this->config->setPageSize('A1');
		$this->config->setZoom(0.5);
		$this->config->setJavascriptDelay(500);
		$this->config->setMarginLeft('2');
		$this->config->setMarginRight('2');
		$this->config->setMarginTop('2');
		$this->config->setMarginBottom('2');
		$exp = ' --zoom 0.5 --enable-external-links --disable-forms --orientation Portrait --page-size A1 --javascript-delay 500 --margin-bottom 2 --margin-left 2 --margin-right 2 --margin-top 2 --quiet ';
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	protected $default_start = ' --zoom 1 --enable-external-links --disable-forms ';

	protected $default_end = '--orientation Portrait --page-size A4 --javascript-delay 500 --margin-bottom 0.5cm --margin-left 0.5cm --margin-right 2cm --margin-top 2cm ';

	protected $default_quiet = '--quiet ';

	public function testGetCommandLineConfigOnObject()
	{
		$exp = $this->default_start . $this->default_end . $this->default_quiet;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithGrayscale()
	{
		$this->config->setGreyscale(true);
		$exp = $this->default_start . '--grayscale ' .  $this->default_end .  $this->default_quiet;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}
	
	public function testGetCommandLineConfigWithHeaderTextWithoutLine()
	{
		$this->config->setHeaderType(ilPDFGenerationConstants::HEADER_TEXT);
		$this->config->setHeaderTextLeft('Left');
		$this->config->setHeaderTextCenter('Center');
		$this->config->setHeaderTextRight('Right');
		$this->config->setHeaderTextSpacing(2);
		$exp = $this->default_start . $this->default_end .'--header-left "Left" --header-center "Center" --header-right "Right" --header-spacing 2 ' .  $this->default_quiet;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithHeaderTextWithLine()
	{
		$this->config->setHeaderType(ilPDFGenerationConstants::HEADER_TEXT);
		$this->config->setHeaderTextLeft('Left');
		$this->config->setHeaderTextCenter('Center');
		$this->config->setHeaderTextRight('Right');
		$this->config->setHeaderTextLine(true);
		$exp = $this->default_start . $this->default_end .'--header-left "Left" --header-center "Center" --header-right "Right" --header-line ' .  $this->default_quiet;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithHeaderHtmlWithoutLine()
	{
		$this->config->setHeaderType(ilPDFGenerationConstants::HEADER_HTML);
		$this->config->setHeaderHtml('<div><b>Test</b></div>');
		$this->config->setHeaderHtmlSpacing(2);
		$exp = $this->default_start . $this->default_end .'--header-html "<div><b>Test</b></div>" --header-spacing 2 ' .  $this->default_quiet;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithHeaderHtmlWithHeaderTextConfigured()
	{
		$this->config->setHeaderType(ilPDFGenerationConstants::HEADER_HTML);
		$this->config->setHeaderHtml('<div><b>Test</b></div>');
		$this->config->setHeaderHtmlSpacing(1);
		$this->config->setHeaderTextLeft('Left');
		$this->config->setHeaderTextCenter('Center');
		$this->config->setHeaderTextRight('Right');
		$exp = $this->default_start . $this->default_end .'--header-html "<div><b>Test</b></div>" --header-spacing 1 ' .  $this->default_quiet;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithHeaderHtmlWithLine()
	{
		$this->config->setHeaderType(ilPDFGenerationConstants::HEADER_HTML);
		$this->config->setHeaderHtml('<div><b>Test</b></div>');
		$this->config->setHeaderHtmlLine(true);
		$exp = $this->default_start . $this->default_end .'--header-html "<div><b>Test</b></div>" --header-line ' .  $this->default_quiet;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithFooterTextWithoutLine()
	{
		$this->config->setFooterType(ilPDFGenerationConstants::FOOTER_TEXT);
		$this->config->setFooterTextLeft('Left');
		$this->config->setFooterTextCenter('Center');
		$this->config->setFooterTextRight('Right');
		$this->config->setFooterTextSpacing(2);
		$exp = $this->default_start . $this->default_end .'--footer-left "Left" --footer-center "Center" --footer-right "Right" --footer-spacing 2 ' .  $this->default_quiet;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithFooterTextWithLine()
	{
		$this->config->setFooterType(ilPDFGenerationConstants::FOOTER_TEXT);
		$this->config->setFooterTextLeft('Left');
		$this->config->setFooterTextCenter('Center');
		$this->config->setFooterTextRight('Right');
		$this->config->setFooterTextLine(true);
		$exp = $this->default_start . $this->default_end .'--footer-left "Left" --footer-center "Center" --footer-right "Right" --footer-line ' .  $this->default_quiet;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithFooterHtmlWithoutLine()
	{
		$this->config->setFooterType(ilPDFGenerationConstants::FOOTER_HTML);
		$this->config->setFooterHtml('<div><b>Test</b></div>');
		$this->config->setFooterHtmlSpacing(2);
		$exp = $this->default_start . $this->default_end .'--footer-html "<div><b>Test</b></div>" --footer-spacing 2 ' .  $this->default_quiet;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithFooterHtmlWithLine()
	{
		$this->config->setFooterType(ilPDFGenerationConstants::FOOTER_HTML);
		$this->config->setFooterHtml('<div><b>Test</b></div>');
		$this->config->setFooterHtmlLine(true);
		$this->config->setFooterHtmlSpacing('1cm');
		$exp = $this->default_start . $this->default_end .'--footer-html "<div><b>Test</b></div>" --footer-spacing 1cm --footer-line ' .  $this->default_quiet;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithEnabledForms()
	{
		$this->config->setEnabledForms(true);
		$exp = ' --zoom 1 --enable-external-links --enable-forms '.$this->default_end . $this->default_quiet;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithEnabledExternalLinks()
	{
		$this->config->setExternalLinks(true);
		$exp = ' --zoom 1 --enable-external-links --disable-forms '.$this->default_end . $this->default_quiet;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithEnabledLowQuality()
	{
		$this->config->setLowQuality(true);
		$exp = ' --zoom 1 --enable-external-links --disable-forms --lowquality '.$this->default_end . $this->default_quiet;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	protected $default_margin_args = '--margin-bottom 0.5cm --margin-left 0.5cm --margin-right 2cm --margin-top 2cm --quiet ';

	public function testGetCommandLineConfigWithEnabledPrintMediaType()
	{
		$this->config->setPrintMediaType(true);
		$exp = ' --zoom 1 --enable-external-links --disable-forms --orientation Portrait --print-media-type --page-size A4 --javascript-delay 500 '. $this->default_margin_args;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithEnabledCustomStyleSheet()
	{
		$this->config->setUserStylesheet('my_super_css_class.css');
		$exp = ' --zoom 1 --enable-external-links --disable-forms --user-style-sheet "my_super_css_class.css" '.$this->default_end . $this->default_quiet;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithCheckbox()
	{
		$this->config->setCheckboxSvg('checkbox.svg');
		$exp = ' --zoom 1 --enable-external-links --disable-forms --orientation Portrait --page-size A4 --javascript-delay 500 --checkbox-svg "checkbox.svg" '. $this->default_margin_args;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithCheckedCheckbox()
	{
		$this->config->setCheckboxCheckedSvg('checkbox_checked.svg');
		$exp = ' --zoom 1 --enable-external-links --disable-forms --orientation Portrait --page-size A4 --javascript-delay 500 --checkbox-checked-svg "checkbox_checked.svg" '. $this->default_margin_args;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithRadiobutton()
	{
		$this->config->setRadioButtonSvg('radiobutton.svg');
		$exp = ' --zoom 1 --enable-external-links --disable-forms --orientation Portrait --page-size A4 --javascript-delay 500 --radiobutton-svg "radiobutton.svg" '. $this->default_margin_args;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithCheckedRadiobutton()
	{
		$this->config->setRadioButtonCheckedSvg('radiobutton_checked.svg');
		$exp = ' --zoom 1 --enable-external-links --disable-forms --orientation Portrait --page-size A4 --javascript-delay 500 --radiobutton-checked-svg "radiobutton_checked.svg" '. $this->default_margin_args;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithDisabledExternalLinks()
	{
		$this->config->setExternalLinks(false);
		$exp = ' --zoom 1 --disable-external-links --disable-forms --orientation Portrait --page-size A4 --javascript-delay 500 '. $this->default_margin_args;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testGetCommandLineConfigWithLandscape()
	{
		$this->config->setOrientation('Landscape');
		$exp = ' --zoom 1 --enable-external-links --disable-forms --orientation Landscape --page-size A4 --javascript-delay 500 '. $this->default_margin_args;
		$this->assertSame($exp, $this->config->getCommandLineConfig());
	}

	public function testHasInfoInterface()
	{
		$this->assertTrue($this->config->hasInfoInterface());
	}

	public function testSupportMultiSourceFiles()
	{
		$this->assertTrue($this->config->supportMultiSourcesFiles());
	}

	public function testSetPathShouldReturnPath()
	{
		$this->config->setPath('/MY/LITTLE/PATH');
		$this->assertSame('/MY/LITTLE/PATH', $this->config->getPath());
		$this->assertSame('/usr/local/bin/wkhtmltopdf', $this->config->getWKHTMLToPdfDefaultPath());
	}

	public function testGetConfigShouldReturnConfigObject()
	{
		$this->assertSame(array(), $this->config->getConfig());
	}

	public function testReadConfigFromObject()
	{
		$this->config->setExternalLinks(false);
		$this->config->setEnabledForms(true);
		$cfg = new ilWkhtmlToPdfConfig($this->config);
		$this->assertTrue($cfg->getEnabledForms());
		$this->assertFalse($cfg->getExternalLinks());
	}

	public function testReadConfigFromJson()
	{
		$json = array(
			"zoom" => "0.4", 
			"enable_forms" => "true",
			"external_links" => "true",
			"user_stylesheet" => "",
			"low_quality" => "",
			"greyscale" => "",
			"orientation" => "",
			"page_size" => "",
			"margin_left" => "",
			"margin_right" => "",
			"footer_html_spacing" => "",
			"footer_html" => "",
			"footer_text_line" => "",
			"footer_text_center" => "",
			"footer_text_spacing" => "",
			"footer_text_right" => "",
			"footer_text_left" => "",
			"footer_select" => "",
			"head_html_spacing" => "",
			"head_html_line" => "",
			"head_text_line" => "",
			"head_text_spacing" => "",
			"head_text_right" => "",
			"head_text_center" => "",
			"head_text_left" => "",
			"header_select" => "",
			"radio_button_checked_svg" => "",
			"radio_button_svg" => "",
			"checkbox_checked_svg" => "",
			"checkbox_svg" => "",
			"javascript_delay" => "",
			"print_media_type" => "",
			"margin_top" => "",
			"margin_bottom" => "",
		);
		$cfg = new ilWkhtmlToPdfConfig($json);
		$this->assertSame("0.4", $cfg->getZoom());
		$this->assertSame('true', $cfg->getExternalLinks());
	}
} 