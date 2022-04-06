<?php

namespace Fendinger;

class ConsentGate
{

	public function getGooglemapsHtml($src)
	{
		try {
			// $src = 'https://www.google.de/maps/place/Bastian+Allgeier+GmbH/@49.3979886,8.8033528,17z/data=!3m1!4b1!4m5!3m4!1s0x4797ea5672e002ad:0x4002670cf9e4e1be!8m2!3d49.3979839!4d8.8055394';
			// $src = 'https://goo.gl/maps/rFsFXPMyxeWUzp7x6';
			// $src = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2596.577241579442!2d8.80335281626329!3d49.39798857051395!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4797ea5672e002ad%3A0x4002670cf9e4e1be!2sBastian%20Allgeier%20GmbH!5e0!3m2!1sde!2sde!4v1647516259971!5m2!1sde!2sde" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';

			$src = trim($src);
			if (substr($src, 0, 7) == '<iframe') {
				$content = $src;
			} else {
				if (preg_match('!goo.gl\/maps(.*)!', $src, $array) === 1) {
					if ($headers = get_headers($src, 1)) {
						$src = @$headers['Location'][0];
					}
				}
				if (preg_match('!google.(.*)\/maps\/place\/(.*)!', $src, $array) === 1) {
					if (@$array[2]) {
						$array_all = explode('/', $array[2]);
						$array_geo = explode(',', $array_all[1]);
						$map_lat = str_replace('@', '', $array_geo[0]);
						$map_lon = $array_geo[1];
						$map_zoom = (str_replace('z', '', $array_geo[2]) + 1000);
						$map_fid = '!' . urlencode(substr($array_all[2], (strpos($array_all[2], '!1s0x') + 1), 39));
						$map_bcp47_country =  substr($array_all[2], -2);
						$map_bcp47_language = substr($array_all[2], -2);
						$map_string = str_replace('+', '%20', str_replace('-', '%2C', $array_all[0]));
						$src = 'https://www.google.' . $array[1] . '/maps/embed?pb=';
						$src .= '!1m18';
						$src .= '!1m12!1m3';
						$src .= '!1d' . $map_zoom;			
						$src .= '!2d' . $map_lon;
						$src .= '!3d' . $map_lat;
						$src .= '!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1';
						$src .= '!3m3';
						$src .= '!1m2';
						$src .= $map_fid;
						$src .= '!2s' . $map_string;
						$src .= '!5e0!3m2!1s' . $map_bcp47_country . '!2s' . $map_bcp47_language;
						$src .= '!4v' . time() . '000';
						$src .= '!5m2!1s' . $map_bcp47_country . '!2s' . $map_bcp47_language;
					} else {
						$src = '';
					}
				} elseif (preg_match('!google.com\/maps\/embed(.*)!', $src, $array) === 1) {
					$src = $src;
				}
				$content = '<iframe src="' . $src . '" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';
			}
			return $content;
		}
		catch (Exception $e) {
			return Response($e->getMessage());
		}
	}

	public function getOpenstreetmapHtml($src)
	{
		try {
			// $src = 'https://www.openstreetmap.org/search?query=B%C3%B6hmer%20Weg%2022%2069151%20Neckargem%C3%BCnd%20Germany#map=19/49.39830/8.80587';
			// $src = 'https://www.openstreetmap.org/query?lat=49.39826&lon=8.80578';
			// $src = 'https://www.openstreetmap.org/way/620792357';
			// $src = 'https://www.openstreetmap.org/#map=19/49.39830/8.80553';
			// $src = 'https://osm.org/go/0DwwCbv~k';
			// $src = '<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.openstreetmap.org/export/embed.html?bbox=8.804439604282381%2C49.397228436992854%2C8.806622922420503%2C49.39937200049954&amp;layer=mapnik" style="border: 1px solid black"></iframe><br/><small><a href="https://www.openstreetmap.org/#map=19/49.39830/8.80553">Größere Karte anzeigen</a></small>';
			// $src = 'geo:49.39830,8.80553?z=19';

			$src = trim($src);
			if (substr($src, 0, 7) == '<iframe') {
				$content = $src;
			} else {
				$content = '';
				$lat = 0;
				$lon = 0;
				switch (substr($src, 0, 34)) {
					case 'https://www.openstreetmap.org/sear':
						$array_map = explode('#', $src);
						$array_coords = explode('/', @$array_map[1]);
						$lat = @$array_coords[1];
						$lon = @$array_coords[2];
						break;
					case 'https://www.openstreetmap.org/quer':
						$array_url = explode('?', $src);
						$array_coords = explode('&', @$array_url[1]);
						$array_lat = explode('=', @$array_coords[0]);
						$array_lon = explode('=', @$array_coords[1]);
						$lat = @$array_lat[1];
						$lon = @$array_lon[1];
						break;
					case 'https://www.openstreetmap.org/#map':
						$array_url = explode('#', $src);
						$array_coords = explode('/', @$array_url[1]);
						$lat = @$array_coords[1];
						$lon = @$array_coords[2];
						break;
				}
				if ($lat || $lon) {
					$bbox = ConsentGate::getOpenstreetmapBBox($lat, $lon, 250);
					$content = '<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.openstreetmap.org/export/embed.html?bbox=' . $bbox[0] . '%2C' . $bbox[1] . '%2C' . $bbox[2] . '%2C' . $bbox[3] . '&amp;layer=mapnik" style="border: 1px solid black"></iframe><br/><small><a href="https://www.openstreetmap.org/#map=19/' . $lat . '/' . $lon . '">Größere Karte anzeigen</a></small>';
				}
			}

			return $content;
		}
		catch (Exception $e) {
			return Response($e->getMessage());
		}
	}

	private function getOpenstreetmapCoordOffset($what, $lat, $lon, $offset) {
		$earthRadius = 6378137;
		$coord = [0 => $lat, 1 => $lon];
		$radOff = $what === 0 ? $offset / $earthRadius : $offset / ($earthRadius * cos(M_PI * $coord[0] / 180));
		return $coord[$what] + $radOff * 180 / M_PI;
	}

	private function getOpenstreetmapBBox($lat, $lon, $area) {
		$offset = $area / 2;
		return [
			0 => ConsentGate::getOpenstreetmapCoordOffset(1, $lat, $lon, -$offset),
			1 => ConsentGate::getOpenstreetmapCoordOffset(0, $lat, $lon, -$offset),
			2 => ConsentGate::getOpenstreetmapCoordOffset(1, $lat, $lon, $offset),
			3 => ConsentGate::getOpenstreetmapCoordOffset(0, $lat, $lon, $offset),
			4 => $lat,
			5 => $lon
		]; // 0 = minlon, 1 = minlat, 2 = maxlon, 3 = maxlat, 4,5 = original val (marker)
	}
		

	public function getTwittertweetHtml($src)
	{
		try {
			$src = trim($src);
			if (substr($src, 0, 4) == '<div') {
				$content = $src;
			} else {
				$src = urlencode($src);
				$content = '<div class="twitter-tweet twitter-tweet-rendered" style="display: flex; max-width: 550px; width: 100%; margin-top: 10px; margin-bottom: 10px;"><iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" allowfullscreen="true" class="" style="position: static; visibility: visible; width: 550px; height: 552px; display: block; flex-grow: 1;" title="Twitter Tweet" src="' . $src . '"></iframe></div><script async="" src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>';
			}
			return $content;
		}
		catch (Exception $e) {
			return Response($e->getMessage());
		}
	}

	public function getFacebookpageHtml($src)
	{
		try {
			$src = trim($src);
			if (substr($src, 0, 7) == '<iframe') {
				$content = $src;
			} else {
				$src = urlencode($src);
				$content = '<iframe src="https://www.facebook.com/plugins/page.php?href=' . $src . '&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" width="100%" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>';
			}
			return $content;
		}
		catch (Exception $e) {
			return Response($e->getMessage());
		}
	}

}