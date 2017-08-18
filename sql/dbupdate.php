<#1>
<?php
/*
 * Use to register a renderer:
 * ===========================
 *
 * require_once './Services/PDFGeneration/classes/class.ilPDFCompInstaller.php';
 *
 * $renderer = 'DummyRenderer';
 * $path =  'Customizing/global/plugins/Services/PDFGeneration/Renderer/DummyRenderer/classes/class.ilDummyRendererPlugin.php';
 * ilPDFCompInstaller::registerRenderer($renderer, $path);
 *
 * $purpose = 'UserResults'; // According to name given. Call multiple times.
 * ilPDFCompInstaller::registerRendererAvailability($renderer, $service, $purpose);
 */
/*
 * Use to register a service/purpose:
 * ==================================
 *
 * require_once './Services/PDFGeneration/classes/class.ilPDFCompInstaller.php';
 *
 * ilPDFCompInstaller::registerPurpose($service,$purpose,$preferred);
 * or uninstall one:
 * ilPDFCompInstaller::unregisterPurpose($service, $purpose);
 * or uninstall all:
 * ilPDFCompInstaller::flushPurposes($service);
 *
 */
?>
<#2>
<?php
require_once './Services/PDFGeneration/classes/class.ilPDFCompInstaller.php';

 $renderer = 'WkhtmlToPdf';
 $path =  'Customizing/global/plugins/Services/PDFGeneration/Renderer/WkhtmlToPdfRenderer/classes/class.ilWkhtmlToPdfRendererPlugin.php';
 ilPDFCompInstaller::registerRenderer($renderer, $path);

 $service = 'Test';
 $purpose = 'UserResult'; // According to name given. Call multiple times.
 ilPDFCompInstaller::registerRendererAvailability($renderer, $service, $purpose);
 
 $purpose = 'PrintViewOfQuestions'; // According to name given. Call multiple times.
 ilPDFCompInstaller::registerRendererAvailability($renderer, $service, $purpose);

?>