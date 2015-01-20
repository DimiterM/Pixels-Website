<?php


class Areas
{
	public function data_to_html($data)
	{
		$result = "";
		foreach ($data as $key => $value)
		{
			$coords = substr($value['coords'],
				strlen("POLYGON(("), -strlen("))"));
			$coords = str_replace(" ", ",", $coords);

			$result .= "<area shape=\"poly\" coords=\""
			. $coords . "\" href=\""
			. $value['link'] . "\" alt=\""
			. $value['id'] . "\" />";
		}
		return $result;
	}
}


?>