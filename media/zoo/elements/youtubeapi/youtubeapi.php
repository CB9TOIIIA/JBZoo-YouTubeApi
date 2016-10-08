<?php
/**
 * @package   com_zoo
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// register ElementRepeatable class
App::getInstance('zoo')->loader->register('ElementRepeatable', 'elements:repeatable/repeatable.php');


class ElementYoutubeApi extends ElementRepeatable implements iRepeatSubmittable {

	protected function _hasValue($params = array()) {
		$videoID = $this->get('value');
		$videoID = rtrim($videoID);
		if (!empty($videoID)) {

		$itemurl = JRoute::_($this->app->route->item($this->_item, false), false, 2);
		$apikey = "PLEASE-POST-YOU-API-KEY";
		// get api key -  https://console.developers.google.com/project?pli=1
		$reservedimg ="http://img.youtube.com/vi/".$videoID."/mqdefault.jpg";
		$get_video = file_get_contents("https://www.googleapis.com/youtube/v3/videos?id=$videoID&key=$apikey&part=statistics,contentDetails,snippet");
		$content = json_decode($get_video, true);
// see ALL API DATA
// echo "<pre>";
// var_dump($content);
// echo "</pre>";
		foreach ($content['items'] as $details) {
			$title = $details['snippet']['title'];
			$datevideo = $details['snippet']['publishedAt'];
			$video_date = date('d.m.y', strtotime($datevideo));
			$smallimg = $details['snippet']['thumbnails']['medium']['url'];
			$viewCount = $details['statistics']['viewCount'];
			$likeCount = $details['statistics']['likeCount'];
			$duration = $details['contentDetails']['duration'];
			$duration = new DateInterval($duration);
			$duration = $duration->format('%H:%i:%s');
			$duration = str_replace("00:", "", $duration);
			echo "<a class='thumb' href='".$itemurl."'><img src=".$smallimg."></a><br>";
			echo("<a class='titlelink' href='".$itemurl."'>"."<h3>".$title."</h3></a>" . "<span class='videodate'>". $video_date. "</span> &mdash;" . "<span class='viewcount'>".$viewCount." просмотров </span> "." <span class='likeCount'>".$likeCount."</span>");
			// do readmore for like 30px
			echo "<div class='durationvideo'>".$duration."</div>";

		}
		// $value = '';
		// return !empty($value) || $value === '0';
		}

	}


	protected function _getSearchData() {
		return $this->get('value', $this->config->get('default'));
	}

	protected function _edit() {
		return $this->app->html->_('control.text', $this->getControlName('value'), $this->get('value', $this->config->get('default')), 'size="60" maxlength="255"');
	}


	public function _renderSubmission($params = array()) {
        return $this->_edit();
	}

}