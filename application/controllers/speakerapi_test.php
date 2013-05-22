<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Speakerapi_test extends CI_Controller
{
	protected $server;

	function __construct()
	{
		parent::__construct();
		$this->load->library('unit_test');
		$this->load->library('rest');
		$this->load->helper('url');
		$this->unit->active(TRUE);
		$this->server = base_url();
	}
	
	function index()
	{
		$this->test_all_category_get();
		$this->test_slides_get();
		$this->test_detail_get();
		$this->test_search_get();
		$this->test_author_get();
		echo $this->unit->report();
	}
	
	function test_all_category_get()
	{
		$this->rest->initialize(array('server' => $this->server));
		$ret = $this->rest->get('speakerapi/all_category/');
		$this->unit->run(count($ret['item']) > 0, TRUE, '测试所有分类返回结果!');
	}

	function test_slides_get()
	{
		$this->rest->initialize(array('server' => $this->server));
		$ret = $this->rest->get('speakerapi/slides/books/3');
		$this->unit->run(count($ret['slides']) > 0, TRUE, '测试某一个分类的列表返回结果!');
	}
	
	function test_detail_get()
	{
		$this->rest->initialize(array('server' => $this->server));
		$ret = $this->rest->get('speakerapi/detail?url=https://speakerdeck.com/geeforr/whats-new-in-ruby-2-dot-0');
		$this->unit->run($ret['data_id'], 'is_string', '测试 内容 id 返回结果!');
		$this->unit->run($ret['data_download_pdf'], 'is_string', '测试 内容 pdf 下载地址 返回结果!');
		$this->unit->run(count($ret['data_slides']) > 0, TRUE, '测试内容的slides返回结果!');
	}
	
	function test_search_get()
	{
		$this->rest->initialize(array('server' => $this->server));
		$ret = $this->rest->get('speakerapi/search/books/2');
		$this->unit->run(count($ret['slides']) > 0, TRUE, '测试搜索列表返回结果!');
	}
	
	function test_author_get()
	{
		$this->rest->initialize(array('server' => $this->server));
		$ret = $this->rest->get('speakerapi/author/cloudsben');
		$this->unit->run(strlen($ret['author_name']) > 0, TRUE, '测试作者名称返回结果');
		$this->unit->run(strlen($ret['author_url']) > 0, TRUE, '测试作者 url 地址返回结果');
	}
	
}