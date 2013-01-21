<?

/*	CSTruter PHP Web Control Class version 1.0
	Author: Christoff Truter

	Date Created: 3 November 2006
	Last Update: 12 November 2006

	e-Mail: christoff@cstruter.com
	Website: www.cstruter.com
	Copyright 2006 CSTruter				*/


abstract class Controls
{

	protected $ID;	// If we need to place more than one control on a page, this will be the unique ID

	// Persist values sent via URL	

	protected function UrlParams($exclude)
	{

		$excludes = explode(",",$exclude);

		$returnValue = "";

		foreach ($_REQUEST as $key => $value) 
		{
			if (!(in_array($key, $excludes))) $returnValue.="&$key=$value";
		}
		return $returnValue;
	}

	protected function UID($Name)
	{
		return $this->ID.$Name;
	}

}

?>