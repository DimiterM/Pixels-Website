<?php


class Article
{
	
	public function data_to_html($blogpost)
	{
		$result = "<article>";

		$result .= "<h1><a href=\"?id=" . $blogpost['id'] . "\">" 
				. $blogpost['title'] . "</a></h1>";

		$result .= "<p>" . $blogpost['body'] . "</p>";
		
		$result .= "<time datetime=\"" . $blogpost['datetime'] . "\">" 
				. $blogpost['datetime'] . "</time>";

		$result .= "</article>";
		return $result;
	}
}


?>