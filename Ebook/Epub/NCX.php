<?php
/**
 * Models an Epub doc
 * 		. "\t\t<meta name=\"dtb:uid\" content=\"" . $this->identifier . "\" />\n"
			. "\t\t<meta name=\"dtb:depth\" content=\"2\" />\n"
			. "\t\t<meta name=\"dtb:totalPageCount\" content=\"0\" />\n"
			. "\t\t<meta name=\"dtb:maxPageNumber\" content=\"0\" />\n"
 * @author visco
 * @package sb_Epub
 */
class sb_Ebook_Epub_NCX extends DOMDocument{

	/**
	 *
	 * @var DOMDocument
	 */
	public $xml;

	public $navmap;
	public $head;
	public $formatOutput = true;

	public function  __construct($version='1.0', $encoding='UTF-8') {
		parent::__construct($version, $encoding);

		$this->create_doc();
		$this->create_head();
	}

	public function create_doc(){
		$this->ncx = $this->appendChild($this->createElement('ncx'));
		$this->ncx->setAttribute('xmlns', 'http://www.daisy.org/z3986/2005/ncx/');
		$this->ncx->setAttribute('version', '2005-1');
		$this->ncx->setAttribute('xml:lang', 'en');

	}

	public function create_head(){
		$this->head = $this->ncx->appendChild($this->createElement('head'));
		$this->title = $this->ncx->appendChild($this->createElement('docTitle'));
		$this->author = $this->ncx->appendChild($this->createElement('docAuthor'));
		$this->navmap = $this->ncx->appendChild($this->createElement('navmap'));
	}

	public function set_title($title){
		$title_txt = $this->title->appendChild($this->createElement('text'));
		$title_txt->appendChild($this->createTextNode($title));
		return $title_txt;
	}

	public function set_author($author){
		$author_txt = $this->author->appendChild($this->createElement('text'));
		$author_txt->appendChild($this->createTextNode($author));
		return $this->author;
	}

	public function add_navpoint_to_navmap($id, $play_order, $name, $src){
		$nav_point = $this->navmap->appendChild($this->createElement('navPoint'));
		$nav_point->setAttribute('id', $id);
		$nav_point->setAttribute('playOrder', $play_order);
		$nav_label = $nav_point->appendChild($this->createElement('navLabel'));
		$nav_label_txt = $nav_label->appendChild($this->createElement('text'));

		$nav_label_txt->appendChild($this->createTextNode($name));
		$content = $nav_point->appendChild($this->createElement('content'));
		$content->setAttribute('src', $src);

		return $nav_point;
	}

}
?>