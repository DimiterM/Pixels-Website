<?php

class TemplateLoader
{
	static function load($filename)
	{
		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTMLFile("views/templates/" . $filename, LIBXML_HTML_NOIMPLIED);
		return $doc->saveHTML();
	}
}

?>