<?php
/**
 * An example that serves the index.view
 * @author visco
 *
 */
class IndexController extends \sb\Controller\HTML\HTML5{

	/**
	 * This sets the page title for a .view
	 * @var string
	 */
	public $title = 'My App Homepage';

	/**
	 * This runs before the page is rendered and allows you to check args etc.
	 * If you return false, e.g. if certain login criteria are not met etc, the page is not rendered for the user
	 */
	public function onBeforeRender($template=''){
		//include additional css files
		//$this->styles[] = 'some_file.css';

		//do something if post data is set
		//if(isset($this->request->post['test'])){//do somthing}
           
	 
	}

	public function notFound(){
		parent::notFound();
	}
}
?>