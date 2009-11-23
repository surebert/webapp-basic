<?php
/**
 * An example view that applies to index.view
 * @author visco
 *
 */
class IndexView extends sb_View_XHTML{
	
	/**
	 * This sets the page title for an .view extending sb_HTMLView
	 * @var string
	 */
	public $title = 'My App Homepage';
	
	/**
	 * Override the default css style sheets loaded for this page.  The default is to load just app.css
	 */
	//public $styles = Array('frontpage.css');
	
	/**
	 * This runs before the page is rendered and allows you to check args etc. 
	 * If you return false, e.g. if certain login criteria are not met etc, the page is not rendered for the user
	 */
	public function on_before_render($template){
		
		//would load frontpage.css in addition to default app.css
		//$this->styles[] = 'frontpage.css';
		
		//do something if $_POST['test'] is set
		//if(isset($this->request->post['test'])){//do somthing}
	}
}
?>