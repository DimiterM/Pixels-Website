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

	public function return_button()
	{
		// one article
		return "<a href=\"news.php\">All news</a>";
	}

	public function page_buttons($page, $hasNext)
	{
		// list of articles
		$prev = ($page > 0 ? "<a href=\"?page=" . ($page - 1) . "\">Previous page</a>" : "");
		$next = ($hasNext ? "<a href=\"?page=" . ($page + 1) . "\">Next page</a>" : "");

		return $prev . $next;
	}
}


?>