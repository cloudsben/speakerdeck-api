<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
/**
 * speaker deck api
 */
class Speakerapi extends REST_Controller
{

	protected $speakerdeck_homepage = "https://speakerdeck.com";

	public function all_category_get()
	{

		$homepage_html = $this->get_ssl_page($this->speakerdeck_homepage);

		$pattern = '/<a\ href="(.*)"\ class="navitem">(.*)<\/a>/';
		preg_match_all($pattern, $homepage_html, $matches);
		$all_path = $matches[1];
		$all_name = $matches[2];

		$all_data = array();

		foreach ($all_name as $key => $value)
		{
			$all_data[$key]["name"] = $value;
			$all_data[$key]["url"] = $this->speakerdeck_homepage.$all_path[$key];
		}

		$all_data = array_values($all_data);

		$this->response($all_data, 200);
	}


	public function slides_get()
	{
		$category_url = $this->speakerdeck_homepage."/p/all";
		$category = $this->get('category');
		if($category != '')
		{
			$category_url = $this->speakerdeck_homepage."/c/".$category;
		}

		$page = intval($this->get('page'));

		if($page == 0)
		{
			$page = 1;
		}

		$category_url = $category_url."?page=".$page;

		$category_html = $this->get_ssl_page($category_url);
		$all_data = array('page'=>$page);

		$all_data['slides'] = $this->get_list_slides($category_html);
		
		$this->response($all_data, 200);

	}

	public function detail_get()
	{

		$url = $this->input->get('url');
		if ($url == '')
		{
			$this->response(array('status'=>-1,'msg'=>'empty url'),200);
		}

		$detail_html = $this->get_ssl_page($url);

		// var_dump($detail_html);
		$all_data = array();

		$pattern_data_id = '/<div class="speakerdeck-embed" data-id="(.*?)"/';
		preg_match($pattern_data_id, $detail_html, $match_data_id);
		$all_data['data_id'] = $match_data_id[1];
		
		$pattern_data_category = '/Published <mark>(.*)<\/mark>\s+in <mark><a href=".*">(.*)<\/a>/';
		preg_match($pattern_data_category, $detail_html, $match_data_category);
		$all_data['data_category'] = $match_data_category[2];
		
		$pattern_data_author = '/<div id="talk-details">\s+<header>\s+<h1>(.*)<\/h1>\s+<h2>by <a href="(.*)">(.*)<\/a><\/h2>/';
		preg_match($pattern_data_author, $detail_html, $match_data_author);
		$all_data['data_title'] = $match_data_author[1];
		$all_data['data_author_url'] = $this->speakerdeck_homepage.$match_data_author[2];
		$all_data['data_author'] = $match_data_author[3];
		
		$pattern_data_description = '/<div class="description">\s+<p>(.*?)<\/p>/';
		preg_match($pattern_data_description, $detail_html, $match_data_description);
		$all_data['data_desription'] = html_entity_decode($match_data_description[1]);
		
		$pattern_data_star_num = '/<a href=".*" class="stargazers">(.*)Stars/';
		preg_match($pattern_data_star_num, $detail_html, $match_data_star_num);
		$all_data['data_star_num'] = intval($match_data_star_num[1]);
		
		$pattern_data_stat_num = '/<li class="views">Stats <span>(.*) Views<\/span><\/li>/';
		preg_match($pattern_data_stat_num, $detail_html, $match_data_stat_num);
		$all_data['data_stat_num'] = intval(str_replace(',', '', $match_data_stat_num[1]));
		
		$pattern_data_download_pdf = '/<a href="(.*)" class="grey" id="share_pdf">Download PDF<\/a>/';
		preg_match($pattern_data_download_pdf, $detail_html, $match_data_download_pdf);
		$all_data['data_download_pdf'] = $match_data_download_pdf[1];
		
		$slides_html_url = 'https://speakerdeck.com/player/';
		$slides_html = $this->get_ssl_page($slides_html_url.$all_data['data_id']);
		
		$json_pattern = '/var\ talk\ =\ (.*)\};/';
		preg_match($json_pattern, $slides_html, $json_maches);
		$json_maches = $json_maches[1] . '}';
		$slides_json_data = json_decode($json_maches);
		
		$all_data['data_time'] = $slides_json_data->modified_at;
		$all_data['data_slides'] = $slides_json_data->slides;
		
		$this->response($all_data, 200);
	 }

	public function search_get()
	{
		$keyword = $this->get('keyword');
		
		$page = intval($this->get('page'));
		if ($page == 0)
		{
			$page = 1;
		}
		
		$search_html = $this->get_ssl_page($this->speakerdeck_homepage.'/search?q='.$keyword.'&_pjax=true&page='.$page);
		
		$all_data = array('page'=>$page);

		$all_data['slides'] = $this->get_list_slides($search_html);
		$this->response($all_data, 200);
		
	 }
	 
	 public function author_get()
	 {
		$username = $this->get('username');
		if($username == '')
		{
			$this->response(array('status'=>-1,'msg'=>'empty username'),200);
		}
		$author_html = $this->get_ssl_page($this->speakerdeck_homepage.'/'.$username);
		
		$pattern = '/<\/h1>\s+<a href="(.*)" class="bio_mugshot">.*<\/a>\s+<h2>(.*)<\/h2>\s+<div class="bio">(.*)<\/p><\/div>/ms';
		preg_match($pattern, $author_html, $matches);
		$patterns = array('/<p>/','/<\/p>/');

		$replacements = array('', "\n");
		$description = preg_replace($patterns, $replacements, $matches[3]);
		$all_data['author_name'] = $matches[2];
		$all_data['author_url'] = $this->speakerdeck_homepage.$matches[1];
		$all_data['author_description'] = $description;
		
		$all_data['author_slides'] = $this->get_list_slides($author_html);

		$this->response($all_data, 200);

	 }
	 
	 public function get_list_slides($html)
	 {
		$data = array(); 
		$pattern = '/<div\s+class="talk\s+public"\s+data-id="([a-z0-9]+)"\s+data-slide-count="(\d+)">(.*?)<\/div>\s+<\/div>/ms';
		preg_match_all($pattern, $html, $talks, PREG_SET_ORDER);

		if(!empty($talks[0]))
		{
			foreach ($talks as $key => $value)
			{
				$data[$key]['data_id'] = $value[1];
				$data[$key]['data_slide_count'] = intval($value[2]);

				$pattern_data_url = '/<a class="slide_preview scrub" href="(.*?)">/ms';
				preg_match($pattern_data_url, $value[3],$match_data_url);
				$data[$key]['data_url'] = $this->speakerdeck_homepage.$match_data_url[1];

				$pattern_data_slide_thumb = '/<img alt="Thumb_slide_0" src="(.*?)" \/>/ms';
				preg_match($pattern_data_slide_thumb, $value[3],$match_data_slide_thumb);
				$data[$key]['data_slide_thumb'] = $match_data_slide_thumb[1];

				$pattern_data_title = '/<h3 class="title">\s+<a href=".*?">(.*?)<\/a>/ms';
				preg_match($pattern_data_title, $value[3],$match_data_title);
				$data[$key]['data_title'] = $match_data_title[1];

				$pattern_data_time = '/<p class="date">\s+(.*?)\sby/ms';
				preg_match($pattern_data_time, $value[3],$match_data_time);
				$data[$key]['data_time'] = strtotime($match_data_time[1]);
				// var_dump($match_data_url);

				$pattern_data_author = '/by\s+<a href="(.*?)">(.*?)<\/a><\/p>/ms';
				preg_match($pattern_data_author, $value[3],$match_data_author);
				$data[$key]['data_author_url'] = $this->speakerdeck_homepage.$match_data_author[1];
				$data[$key]['data_author'] = $match_data_author[2];
			}
		}
		return $data;
	 }

	public function get_ssl_page($url = '')
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

}