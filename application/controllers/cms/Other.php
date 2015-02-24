<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once 'application/utils/base_controller.php';

class Other extends BaseController {

	public function index()
	{
		parent::partialViewResult('cms/cms_master', 'cms/other/index', null);
	}

	public function generateXMLMapSite()
	{

		$this->load->model('cms/PageModel');
		$this->load->model('cms/CatalogModel');
		$this->load->library('Xml_writer');

		$pages = $this->PageModel->getPages(new Paginator(0,100));

		$xml = new Xml_writer();
		$xml->setRootName('urlset', array('xmlns'=>"http://www.sitemaps.org/schemas/sitemap/0.9"));
		$xml->initiate();

		foreach ($pages as $item)
		{
			$xml->startBranch('url');
			$catalog = $item->IDCatalog!=NULL ? $this->CatalogModel->readCatalogByID($item->IDCatalog):NULL;
			$xml->addNode('loc',$catalog!=NULL ? base_url($catalog->Name . '/' . $item->Name ) : base_url($item->Name));
			$xml->addNode('lastmod', $item->UTime);
			$xml->addNode('changefreq', 'monthly');
			$xml->endBranch();
		}

		$this->load->helper('download');
		force_download('Sitemap.xml', $xml->getXml(false));
		 
	}
}