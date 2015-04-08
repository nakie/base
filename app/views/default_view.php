<?php
/**
 * This is the default view object instantiated for controllers
 * 
 * @author Charles Abbott
 * @package Views
 * 
 */
class default_view extends application_view 
{
	protected $template = "default.php";

	public function __construct($section="", $view="")
	{
		parent::__construct($section,$view);
	}
	
	public function echo_page(){
		parent::echo_page();
	}
}
?>