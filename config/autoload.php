<?php

class autoload
{
	//load Every Class you need
	function load()
	{
		return array(
			//"to load your own library just put the location here",
			//"example",
			//LIBRARY_PATH."mylibrary",
			//"or something like",
			//INCLUDE_PATH."library/mylibrary",
			
			LIBRARY_PATH."lists",
			
		);

	}
}