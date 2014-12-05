<?php
namespace Controllers;

class Index extends \sb\Controller\HTML\HTML5{

	/**
	 * This sets the page title for a .view
	 * @var string
	 */
	public $title = SITE_NAME;

	/**
	 * This runs before the page is rendered and allows you to check args etc.
	 * If you return false, e.g. if certain login criteria are not met etc, the page is not rendered for the user
	 */
	public function onBeforeRender($method=''){

		//set up the css styles based on the current user
		$this->styles = Array(
			'app.css'
		);
	}
    
    /**
     * The index page of the site
     * @servable true
     */
    public function index(){
        return $this->renderView('/home/index');
    }
    
	/**
	 * Fired when no matching method/view is found for the request
	 */
	public function notFound(){
		parent::notFound();
	}
}
