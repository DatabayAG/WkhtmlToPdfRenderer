<?php

require_once './Services/PDFGeneration/classes/class.ilPDFGenerationConstants.php';
require_once './Services/PDFGeneration/interfaces/interface.ilRendererConfig.php';
require_once './Services/PDFGeneration/interfaces/interface.ilPDFRenderer.php';
require_once './Services/PDFGeneration/classes/class.ilPDFRendererPlugin.php';
require_once 'Customizing/global/plugins/Services/PDFGeneration/Renderer/WkhtmlToPdfRenderer/classes/class.ilWkhtmlToPdfConfig.php';

class ilWkhtmlToPdfRendererPlugin extends ilPDFRendererPlugin
{
	/**
	 * @var ilWkhtmlToPdfConfig
	 */
	protected $config;

	/**
	 * @var ilLanguage
	 */
	protected $lng;
	
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
	 * from ilPlugin
	 *
	 * ilDummyRendererPlugin constructor.
	 */
	public function __construct()
	{
		global $DIC;
		$this->lng = $DIC['lng'];
		parent::__construct();
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
		$this->config = new ilWkhtmlToPdfConfig();
		$form->setTitle($this->lng->txt('pdfg_renderer_wkhtp_config'));

		$path = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_path'), 'path');
		$form->addItem($path);

		$this->appendOutputOptionsForm($form);
		$this->appendPageSettingsForm($form);
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

		$form->getItemByPostVar('path')->setValue(					$this->config->getWKHTMLToPdfDefaultPath());
		$form->getItemByPostVar('zoom')->setValue(					$this->config->getZoom());
		$form->getItemByPostVar('external_links')->setValue(1);
		$form->getItemByPostVar('external_links')->setChecked(			$this->config->getExternalLinks());
		$form->getItemByPostVar('enable_forms')->setValue(1);
		$form->getItemByPostVar('enable_forms')->setChecked(			$this->config->getEnabledForms());
		$form->getItemByPostVar('user_stylesheet')->setValue(			$this->config->getUserStylesheet());
		$form->getItemByPostVar('low_quality')->setValue(1);
		$form->getItemByPostVar('low_quality')->setChecked(				$this->config->getLowQuality());
		$form->getItemByPostVar('greyscale')->setValue(1);
		$form->getItemByPostVar('greyscale')->setChecked(				$this->config->getGreyscale());
		$form->getItemByPostVar('orientation')->setValue(				$this->config->getOrientation());
		$form->getItemByPostVar('page_size')->setValue(				$this->config->getPageSize());
		$form->getItemByPostVar('margin_left')->setValue(				$this->config->getMarginLeft());
		$form->getItemByPostVar('margin_right')->setValue(			$this->config->getMarginRight());
		$form->getItemByPostVar('margin_top')->setValue(				$this->config->getMarginTop());
		$form->getItemByPostVar('margin_bottom')->setValue(			$this->config->getMarginBottom());
		$form->getItemByPostVar('print_media_type')->setValue(1);
		$form->getItemByPostVar('print_media_type')->setChecked(		$this->config->getPrintMediaType());
		$form->getItemByPostVar('javascript_delay')->setValue(		$this->config->getJavascriptDelay());
		$form->getItemByPostVar('checkbox_svg')->setValue(			$this->config->getCheckboxSvg());
		$form->getItemByPostVar('checkbox_checked_svg')->setValue(	$this->config->getCheckboxCheckedSvg());
		$form->getItemByPostVar('radio_button_svg')->setValue(		$this->config->getRadioButtonSvg());
		$form->getItemByPostVar('radio_button_checked_svg')->setValue($this->config->getRadioButtonCheckedSvg());
		$form->getItemByPostVar('header_select')->setValue(			$this->config->getHeaderType());
		$form->getItemByPostVar('head_text_left')->setValue(			$this->config->getHeaderTextLeft());
		$form->getItemByPostVar('head_text_center')->setValue(		$this->config->getHeaderTextCenter());
		$form->getItemByPostVar('head_text_right')->setValue(			$this->config->getHeaderTextRight());
		$form->getItemByPostVar('head_text_spacing')->setValue(		$this->config->getHeaderTextSpacing());
		$form->getItemByPostVar('head_text_line')->setValue(1);
		$form->getItemByPostVar('head_text_line')->setChecked(			$this->config->isHeaderTextLine());
		$form->getItemByPostVar('head_html_line')->setValue(1);
		$form->getItemByPostVar('head_html_line')->setChecked(			$this->config->isHeaderHtmlLine());
		$form->getItemByPostVar('head_html_spacing')->setValue(		$this->config->getHeaderHtmlSpacing());
		$form->getItemByPostVar('head_html')->setValue(				$this->config->getHeaderHtml());
		$form->getItemByPostVar('footer_select')->setValue(			$this->config->getFooterType());
		$form->getItemByPostVar('footer_text_left')->setValue(		$this->config->getFooterTextLeft());
		$form->getItemByPostVar('footer_text_center')->setValue(		$this->config->getFooterTextCenter());
		$form->getItemByPostVar('footer_text_right')->setValue(		$this->config->getFooterTextRight());
		$form->getItemByPostVar('footer_text_spacing')->setValue(		$this->config->getFooterTextSpacing());
		$form->getItemByPostVar('footer_text_line')->setValue(1);
		$form->getItemByPostVar('footer_text_line')->setChecked(		$this->config->isFooterTextLine());
		$form->getItemByPostVar('footer_html_line')->setValue(1);
		$form->getItemByPostVar('footer_html_line')->setChecked(		$this->config->isFooterHtmlLine());
		$form->getItemByPostVar('footer_html')->setValue(				$this->config->getFooterHtml());
		$form->getItemByPostVar('footer_html_spacing')->setValue(		$this->config->getFooterHtmlSpacing());

		ilPDFGeneratorUtils::setCheckedIfTrue($form);
	}

	/**
	 * @param ilPropertyFormGUI $form
	 */
	protected function appendOutputOptionsForm(ilPropertyFormGUI $form)
	{
		$section_header = new ilFormSectionHeaderGUI();
		$section_header->setTitle($this->lng->txt('pdfg_renderer_wkhtp_output_options'));
		$form->addItem($section_header);

		$form->addItem($this->buildExternalLinksForm());
		$form->addItem($this->buildEnableFormsForm());
		$form->addItem($this->buildUserStylesheetForm());
		$form->addItem($this->buildLowQualityForm());
		$form->addItem($this->buildGreyScaleForm());
		$form->addItem($this->buildPrintMediaTypeForm());
		$form->addItem($this->buildJavascriptDelayForm());
		$form->addItem($this->buildCheckboxSvgForm());
		$form->addItem($this->buildCheckedCheckboxSvgForm());
		$form->addItem($this->buildRadiobuttonSvgForm());
		$form->addItem($this->buildCheckedRadiobuttonSvgForm());
	}


	/**
	 * @param ilPropertyFormGUI $form
	 */
	protected function appendPageSettingsForm(ilPropertyFormGUI $form)
	{
		$section_header = new ilFormSectionHeaderGUI();
		$section_header->setTitle($this->lng->txt('pdfg_renderer_wkhtp_page_settings'));
		$form->addItem($section_header);

		$form->addItem($this->buildZoomForm());
		$form->addItem($this->buildOrientationsForm());
		$form->addItem($this->buildPageSizesForm());
		$form->addItem($this->buildMarginLeftForm());
		$form->addItem($this->buildMarginRightForm());
		$form->addItem($this->buildMarginTopForm());
		$form->addItem($this->buildMarginBottomForm());
		$form->addItem($this->buildHeaderForm());
		$form->addItem($this->buildFooterForm());
	}

	/**
	 * @return ilRadioGroupInputGUI
	 */
	protected function buildHeaderForm()
	{
		$header_select	= new ilRadioGroupInputGUI($this->lng->txt('pdfg_renderer_wkhtp_header_type'), 'header_select');
		$header_select->addOption(new ilRadioOption($this->lng->txt('pdfg_renderer_wkhtp_none'), ilPDFGenerationConstants::HEADER_NONE, ''));
		$header_select->addOption($this->buildHeaderTextForm());
		$header_select->addOption($this->buildHeaderHtmlForm());

		return $header_select;
	}

	/**
	 * @return ilRadioOption
	 */
	protected function buildHeaderTextForm()
	{
		$header_text_option = new ilRadioOption($this->lng->txt('pdfg_renderer_wkhtp_text'), ilPDFGenerationConstants::HEADER_TEXT, '');

		$header_text_left = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_header_text_left'), 'head_text_left');
		$header_text_option->addSubItem($header_text_left);

		$header_text_center = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_header_text_center'), 'head_text_center');
		$header_text_option->addSubItem($header_text_center);

		$header_text_right = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_header_text_right'), 'head_text_right');
		$header_text_option->addSubItem($header_text_right);

		$head_text_spacing = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_spacing'), 'head_text_spacing');
		$header_text_option->addSubItem($head_text_spacing);

		$head_text_line = new ilCheckboxInputGUI($this->lng->txt('pdfg_renderer_wkhtp_header_line'), 'head_text_line');

		$header_text_option->addSubItem($head_text_line);
		return $header_text_option;
	}

	/**
	 * @return ilRadioOption
	 */
	protected function buildHeaderHtmlForm()
	{
		$header_html_option = new ilRadioOption($this->lng->txt("pdfg_renderer_wkhtp_html"), ilPDFGenerationConstants::HEADER_HTML, '');

		$header_html = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_html'), 'head_html');
		$header_html_option->addSubItem($header_html);

		$head_html_spacing = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_spacing'), 'head_html_spacing');
		$header_html_option->addSubItem($head_html_spacing);

		$head_html_line = new ilCheckboxInputGUI($this->lng->txt('pdfg_renderer_wkhtp_header_line'), 'head_html_line');
		$header_html_option->addSubItem($head_html_line);
		return $header_html_option;
	}

	/**
	 * @return ilTextInputGUI
	 */
	protected function buildMarginBottomForm()
	{
		$margin_bottom = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_margin_bottom'), 'margin_bottom');
		return $margin_bottom;
	}

	/**
	 * @return ilTextInputGUI
	 */
	protected function buildMarginTopForm()
	{
		$margin_top = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_margin_top'), 'margin_top');
		return $margin_top;
	}

	/**
	 * @return ilTextInputGUI
	 */
	protected function buildMarginRightForm()
	{
		$margin_right = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_margin_right'), 'margin_right');
		return $margin_right;
	}

	/**
	 * @return ilTextInputGUI
	 */
	protected function buildMarginLeftForm()
	{
		$margin_left = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_margin_left'), 'margin_left');
		return $margin_left;
	}

	/**
	 * @return ilSelectInputGUI
	 */
	protected function buildPageSizesForm()
	{
		$page_size = new ilSelectInputGUI($this->lng->txt('pdfg_renderer_wkhtp_page_size'), 'page_size');
		$page_size->setOptions(ilPDFGenerationConstants::getPageSizesNames());
		return $page_size;
	}

	/**
	 * @return ilSelectInputGUI
	 */
	protected function buildOrientationsForm()
	{
		$orientation = new ilSelectInputGUI($this->lng->txt('pdfg_renderer_wkhtp_orientation'), 'orientation');
		$orientation->setOptions(ilPDFGenerationConstants::getOrientations());
		return $orientation;
	}

	/**
	 * @return ilTextInputGUI
	 */
	protected function buildZoomForm()
	{
		$zoom = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_zoom'), 'zoom');
		return $zoom;
	}

	/**
	 * @return ilTextInputGUI
	 */
	protected function buildCheckedRadiobuttonSvgForm()
	{
		$radio_button_checked_svg = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_radio_button_checked_svg'), 'radio_button_checked_svg');
		return $radio_button_checked_svg;
	}

	/**
	 * @return ilTextInputGUI
	 */
	protected function buildRadiobuttonSvgForm()
	{
		$radio_button_svg = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_radio_button_svg'), 'radio_button_svg');
		return $radio_button_svg;
	}

	/**
	 * @return ilTextInputGUI
	 */
	protected function buildCheckedCheckboxSvgForm()
	{
		$checkbox_checked_svg = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_checkbox_checked_svg'), 'checkbox_checked_svg');
		return $checkbox_checked_svg;
	}

	/**
	 * @return ilTextInputGUI
	 */
	protected function buildCheckboxSvgForm()
	{
		$checkbox_svg = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_checkbox_svg'), 'checkbox_svg');
		return $checkbox_svg;
	}

	/**
	 * @return ilTextInputGUI
	 */
	protected function buildJavascriptDelayForm()
	{
		$javascript_delay = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_javascript_delay'), 'javascript_delay');
		return $javascript_delay;
	}

	/**
	 * @return ilCheckboxInputGUI
	 */
	protected function buildPrintMediaTypeForm()
	{
		$print_media = new ilCheckboxInputGUI($this->lng->txt('pdfg_renderer_wkhtp_print_media_type'), 'print_media_type');
		return $print_media;
	}

	/**
	 * @return ilCheckboxInputGUI
	 */
	protected function buildGreyScaleForm()
	{
		$grey_scale = new ilCheckboxInputGUI($this->lng->txt('pdfg_renderer_wkhtp_greyscale'), 'greyscale');
		return $grey_scale;
	}

	/**
	 * @return ilCheckboxInputGUI
	 */
	protected function buildLowQualityForm()
	{
		$low_quality = new ilCheckboxInputGUI($this->lng->txt('pdfg_renderer_wkhtp_low_quality'), 'low_quality');
		return $low_quality;
	}

	/**
	 * @return ilTextInputGUI
	 */
	protected function buildUserStylesheetForm()
	{
		$user_stylesheet = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_user_stylesheet'), 'user_stylesheet');
		return $user_stylesheet;
	}

	/**
	 * @return ilCheckboxInputGUI
	 */
	protected function buildEnableFormsForm()
	{
		$enable_forms = new ilCheckboxInputGUI($this->lng->txt('pdfg_renderer_wkhtp_enable_forms'), 'enable_forms');
		return $enable_forms;
	}

	/**
	 * @return ilCheckboxInputGUI
	 */
	protected function buildExternalLinksForm()
	{
		$external_links = new ilCheckboxInputGUI($this->lng->txt('pdfg_renderer_wkhtp_external_links'), 'external_links');
		return $external_links;
	}

	/**
	 * @return ilRadioGroupInputGUI
	 */
	protected function buildFooterForm()
	{
		$footer_select	= new ilRadioGroupInputGUI($this->lng->txt('pdfg_renderer_wkhtp_footer_type'), 'footer_select');
		$footer_select->addOption(new ilRadioOption($this->lng->txt("pdfg_renderer_wkhtp_none"), ilPDFGenerationConstants::FOOTER_NONE, ''));
		$footer_select->addOption($this->buildFooterTextForm());
		$footer_select->addOption($this->buildFooterHtmlForm());

		return $footer_select;
	}


	/**
	 * @return ilRadioOption
	 */
	protected function buildFooterHtmlForm()
	{
		$footer_html_option = new ilRadioOption($this->lng->txt('pdfg_renderer_wkhtp_html'), ilPDFGenerationConstants::FOOTER_HTML, '');

		$footer_html = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_footer_html'), 'footer_html');
		$footer_html_option->addSubItem($footer_html);

		$footer_html_spacing = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_spacing'), 'footer_html_spacing');
		$footer_html_option->addSubItem($footer_html_spacing);

		$footer_html_line = new ilCheckboxInputGUI($this->lng->txt('pdfg_renderer_wkhtp_footer_line'), 'footer_html_line');
		$footer_html_option->addSubItem($footer_html_line);
		return $footer_html_option;
	}

	/**
	 * @return ilRadioOption
	 */
	protected function buildFooterTextForm()
	{
		$footer_text_option = new ilRadioOption($this->lng->txt('pdfg_renderer_wkhtp_text'), ilPDFGenerationConstants::FOOTER_TEXT, '');

		$footer_text_left = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_footer_text_left'), 'footer_text_left');
		$footer_text_option->addSubItem($footer_text_left);

		$footer_text_center = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_footer_text_center'), 'footer_text_center');
		$footer_text_option->addSubItem($footer_text_center);

		$footer_text_right = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_footer_text_right'), 'footer_text_right');
		$footer_text_option->addSubItem($footer_text_right);

		$footer_text_spacing = new ilTextInputGUI($this->lng->txt('pdfg_renderer_wkhtp_spacing'), 'footer_text_spacing');
		$footer_text_option->addSubItem($footer_text_spacing);

		$footer_text_line = new ilCheckboxInputGUI($this->lng->txt('pdfg_renderer_wkhtp_footer_line'), 'footer_text_line');

		$footer_text_option->addSubItem($footer_text_line);
		return $footer_text_option;
	}
	/**
	 * @param ilPropertyFormGUI $form
	 * @param string            $service
	 * @param string            $purpose
	 * @return bool
	 */
	public function validateConfigInForm(\ilPropertyFormGUI $form, $service, $purpose)
	{
		$everything_ok	= true;
		$config			= new ilWkhtmlToPdfConfig();
		$config->setPath(ilUtil::stripSlashes($_POST['path']));
		if(mb_stripos($config->getPath(), 'wkhtmlto') === false)
		{
			ilUtil::sendFailure($this->lng->txt("file_not_found"),true);
			$everything_ok = false;
		}
		else
		{
			$config->setZoom((float) $_POST['zoom']);
			$config->setExternalLinks((int) $_POST['external_links']);
			$config->setEnabledForms((int) $_POST['enable_forms']);
			$config->setUserStylesheet(ilUtil::stripSlashes($_POST['user_stylesheet']));
			$config->setLowQuality((int) $_POST['low_quality']);
			$config->setGreyscale((int) $_POST['greyscale']);
			$config->setOrientation(ilUtil::stripSlashes($_POST['orientation']));
			$config->setPageSize(ilUtil::stripSlashes($_POST['page_size']));
			$config->setMarginLeft(ilUtil::stripSlashes($_POST['margin_left']));
			$config->setMarginRight(ilUtil::stripSlashes($_POST['margin_right']));
			$config->setMarginTop(ilUtil::stripSlashes($_POST['margin_top']));
			$config->setMarginBottom(ilUtil::stripSlashes($_POST['margin_bottom']));
			$config->setPrintMediaType((int) $_POST['print_media_type']);
			$config->setJavascriptDelay((int) $_POST['javascript_delay']);
			$config->setCheckboxSvg(ilUtil::stripSlashes($_POST['checkbox_svg']));
			$config->setCheckboxCheckedSvg(ilUtil::stripSlashes($_POST['checkbox_checked_svg']));
			$config->setRadioButtonSvg(ilUtil::stripSlashes($_POST['radio_button_svg']));
			$config->setRadioButtonCheckedSvg(ilUtil::stripSlashes($_POST['radio_button_checked_svg']));
			$config->setHeaderType((int) $_POST['header_select']);
			$config->setHeaderTextLeft(ilUtil::stripSlashes($_POST['head_text_left']));
			$config->setHeaderTextCenter(ilUtil::stripSlashes($_POST['head_text_center']));
			$config->setHeaderTextRight(ilUtil::stripSlashes($_POST['head_text_right']));
			$config->setHeaderTextSpacing((int) $_POST['head_text_spacing']);
			$config->setHeaderTextLine((int) $_POST['head_text_line']);
			$config->setHeaderHtmlLine((int) $_POST['head_html_line']);
			$config->setHeaderHtmlSpacing((int) $_POST['head_html_spacing']);
			$config->setHeaderHtml(ilUtil::stripSlashes($_POST['head_html']));
			$config->setFooterType((int) $_POST['footer_select']);
			$config->setFooterTextLeft(ilUtil::stripSlashes($_POST['footer_text_left']));
			$config->setFooterTextCenter(ilUtil::stripSlashes($_POST['footer_text_center']));
			$config->setFooterTextRight(ilUtil::stripSlashes($_POST['footer_text_right']));
			$config->setFooterTextSpacing((int) $_POST['footer_text_spacing']);
			$config->setFooterTextLine((int) $_POST['footer_text_line']);
			$config->setFooterHtmlLine((int) $_POST['footer_html_line']);
			$config->setFooterHtmlSpacing((int) $_POST['footer_html_spacing']);
			$config->setFooterHtml(ilUtil::stripSlashes($_POST['footer_html']));
		}

		return $everything_ok;
	}

	/**
	 * @param ilPropertyFormGUI $form
	 * @param string            $service
	 * @param string            $purpose
	 * @return array
	 */
	public function getConfigFromForm(\ilPropertyFormGUI $form, $service, $purpose)
	{
		return array(
			'path'						=> $form->getItemByPostVar('path')->getValue(),
			'zoom'						=> $form->getItemByPostVar('zoom')->getValue(),
			'external_links'			=> $form->getItemByPostVar('external_links')->getChecked(),
			'enable_forms'				=> $form->getItemByPostVar('enable_forms')->getChecked(),
			'user_stylesheet'			=> $form->getItemByPostVar('user_stylesheet')->getValue(),
			'low_quality'				=> $form->getItemByPostVar('low_quality')->getChecked(),
			'greyscale'					=> $form->getItemByPostVar('greyscale')->getChecked(),
			'orientation'				=> $form->getItemByPostVar('orientation')->getValue(),
			'page_size'					=> $form->getItemByPostVar('page_size')->getValue(),
			'margin_left'				=> $form->getItemByPostVar('margin_left')->getValue(),
			'margin_right'				=> $form->getItemByPostVar('margin_right')->getValue(),
			'margin_top'				=> $form->getItemByPostVar('margin_top')->getValue(),
			'margin_bottom'				=> $form->getItemByPostVar('margin_bottom')->getValue(),
			'print_media_type'			=> $form->getItemByPostVar('print_media_type')->getChecked(),
			'javascript_delay'			=> $form->getItemByPostVar('javascript_delay')->getValue(),
			'checkbox_svg'				=> $form->getItemByPostVar('checkbox_svg')->getValue(),
			'checkbox_checked_svg'		=> $form->getItemByPostVar('checkbox_checked_svg')->getValue(),
			'radio_button_svg'			=> $form->getItemByPostVar('radio_button_svg')->getValue(),
			'radio_button_checked_svg'	=> $form->getItemByPostVar('radio_button_checked_svg')->getValue(),
			'header_select'				=> $form->getItemByPostVar('header_select')->getValue(),
			'head_text_left'			=> $form->getItemByPostVar('head_text_left')->getValue(),
			'head_text_center'			=> $form->getItemByPostVar('head_text_center')->getValue(),
			'head_text_right'			=> $form->getItemByPostVar('head_text_right')->getValue(),
			'head_text_spacing'			=> $form->getItemByPostVar('head_text_spacing')->getValue(),
			'head_text_line'			=> $form->getItemByPostVar('head_text_line')->getValue(),
			'head_html_line'			=> $form->getItemByPostVar('head_html_line')->getValue(),
			'head_html_spacing'			=> $form->getItemByPostVar('head_html_spacing')->getValue(),
			'head_html'					=> $form->getItemByPostVar('head_html')->getValue(),
			'footer_select'				=> $form->getItemByPostVar('footer_select')->getValue(),
			'footer_text_left'			=> $form->getItemByPostVar('footer_text_left')->getValue(),
			'footer_text_right'			=> $form->getItemByPostVar('footer_text_right')->getValue(),
			'footer_text_spacing'		=> $form->getItemByPostVar('footer_text_spacing')->getValue(),
			'footer_text_center'		=> $form->getItemByPostVar('footer_text_center')->getValue(),
			'footer_text_line'			=> $form->getItemByPostVar('footer_text_line')->getValue(),
			'footer_html'				=> $form->getItemByPostVar('footer_html')->getValue(),
			'footer_html_spacing'		=> $form->getItemByPostVar('footer_html_spacing')->getValue()
		);
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

		$config			= new ilWkhtmlToPdfConfig($config);
		$temp_file		= $this->getPdfTempName();
		$args			= $config->getCommandLineConfig() . ' ' . $a_path_to_file . ' ' . $temp_file . $this->redirectLog();
		$return_value	= ilUtil::execQuoted($config->getWKHTMLToPdfDefaultPath(), $args);

		$DIC['ilLog']->debug('ilWebkitHtmlToPdfTransformer command line config: ' . $args);
		foreach($return_value as $key => $value)
		{
			$DIC['ilLog']->debug('ilWebkitHtmlToPdfTransformer return value line ' . $key . ' : ' . $value );
		}
		if(file_exists($temp_file))
		{
			$DIC['ilLog']->debug('ilWebkitHtmlToPdfTransformer file exists: ' . $temp_file . ' file size is :' . filesize($temp_file) . ' bytes, will be renamed to '. $a_target);
			rename($temp_file, $a_target);
		}
		else
		{
			$DIC['ilLog']->info('ilWebkitHtmlToPdfTransformer error: ' . print_r($return_value, true) );
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
