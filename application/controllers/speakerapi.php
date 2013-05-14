<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
/**
 * speaker deck api
 */
class Speakerapi extends REST_Controller
{
	public function category_get()
	{
		$speakerdeck_homepage = "https://speakerdeck.com/";
		$homepage_html = $this->get_ssl_page($speakerdeck_homepage);

		$pattern = '/<a\ href="(.*)"\ class="navitem">(.*)<\/a>/';
		preg_match_all($pattern, $homepage_html, $matches);
		$all_path = $matches[1];
		$all_name = $matches[2];

		$all_data = array();

		foreach ($all_name as $key => $value)
		{
			$all_data[$key]["name"] = $value;
			$all_data[$key]["url"] = "https://speakerdeck.com".$all_path[$key];
		}

		$all_data = array_values($all_data);

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