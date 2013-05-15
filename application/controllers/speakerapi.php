<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
/**
 * speaker deck api
 */
class Speakerapi extends REST_Controller
{

	protected $speakerdeck_homepage = "https://speakerdeck.com";

	public function category_get()
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


	public function one_category_get()
	{
		$category_url = $this->speakerdeck_homepage."/p/all";
		$category = $this->get('c');
		if($category != '')
		{
			$category_url = $this->speakerdeck_homepage."/c/".$category;
		}

		$page = $this->get('p');

		if($page == '')
		{
			$page = 1;
		}

		$category_url = $category_url."?page=".$page;

		$category_html = $this->get_ssl_page($category_url);

		// var_dump($category_html);

		$pattern = '/<div\s+class="talk\s+public"\s+data-id="([a-z0-9]+)"\s+data-slide-count="(\d+)">(.*?)<\/div>\s+<\/div>/ms';
		preg_match_all($pattern, $category_html, $talks, PREG_SET_ORDER);

		// var_dump($talks);exit;

		$all_data = array('page'=>$page);
		if(!empty($talks[0]))
		{
			foreach ($talks as $key => $value)
			{
				$all_data['all_slide'][$key]['data_id'] = $value[1];
				$all_data['all_slide'][$key]['data_slide_count'] = intval($value[2]);

				$pattern_data_url = '/<a class="slide_preview scrub" href="(.*?)">/ms';
				preg_match($pattern_data_url, $value[3],$match_data_url);
				$all_data['all_slide'][$key]['data_url'] = $this->speakerdeck_homepage.$match_data_url[1];

				$pattern_data_slide_thumb = '/<img alt="Thumb_slide_0" src="(.*?)" \/>/ms';
				preg_match($pattern_data_slide_thumb, $value[3],$match_data_slide_thumb);
				$all_data['all_slide'][$key]['data_slide_thumb'] = $match_data_slide_thumb[1];

				$pattern_data_title = '/<h3 class="title">\s+<a href=".*?">(.*?)<\/a>/ms';
				preg_match($pattern_data_title, $value[3],$match_data_title);
				$all_data['all_slide'][$key]['data_title'] = $match_data_title[1];

				$pattern_data_time = '/<p class="date">\s+(.*?)\sby/ms';
				preg_match($pattern_data_time, $value[3],$match_data_time);
				$all_data['all_slide'][$key]['data_time'] = strtotime($match_data_time[1]);
				// var_dump($match_data_url);

				$pattern_data_author = '/by\s+<a href="(.*?)">(.*?)<\/a><\/p>/ms';
				preg_match($pattern_data_author, $value[3],$match_data_author);
				$all_data['all_slide'][$key]['data_author_url'] = $this->speakerdeck_homepage.$match_data_author[1];
				$all_data['all_slide'][$key]['data_author'] = $match_data_author[2];
			}
		}
		$this->response($all_data, 200);

	}



	public function get_ssl_page($url = '')
	{
		// $url = 'https://speakerd.s3.amazonaws.com/presentations/f080c130744c01306b5122000a1c8083/preview_slide_0.jpg';
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