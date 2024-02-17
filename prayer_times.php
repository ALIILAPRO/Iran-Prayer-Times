<?php
function sanitize($buffer) {

    $search = array(
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/' // Remove HTML comments
    );

    $replace = array(
        '>',
        '<',
        '\\1',
        ''
    );

    $buffer = preg_replace($search, $replace, $buffer);

    return $buffer;
}


function get_content ($city = "تهران")
{
	$url = "https://www.tala.ir/prayer-times/" . urlencode($city);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	//For local tests
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
	$result = curl_exec($ch);
	
	return $result;
}

function hunt($data, $start, $end, $intro = "")
{
	if($intro == "")
	{
		//If there is no intro, we will start looking after the start tag.
		$intro_found = true;
		$target = $start;
	}
	else
	{
		//If there is an intro, we will look after the intro, first!
		$intro_found = false;
		$target = $intro;	
	}
	
	$buffering = false;
	$buffer = "";
	for($i = 0; $i < mb_strlen($data) - mb_strlen($target); $i++)
	{
		if(mb_substr($data, $i, mb_strlen($target)) == $target)
		{
			if($buffering)
			{
				//If we were buffering so far, this is the end!
				return $buffer;
			}
			elseif($intro_found)
			{
				//If the intro is already found, it's time to start buffer and look for the end tag.
				$buffering = true;
				$i += mb_strlen($target) - 1;
				$target = $end;
			}
			else
			{
				$intro_found = true;
				$i += mb_strlen($target) - 1;
				$target = $start;
			}
		}
		elseif($buffering)
		{
			$buffer .= mb_substr($data, $i, 1);
		}
	}
	
	return $buffer;
}

function get_times($city = "تهران")
{
	$data = get_content();
	$data = sanitize($data);
	$outcome = hunt($data, "<div class=\"left\">", "</div> </div>");
	preg_match_all('#<div>(.*?)<\/div>#', $outcome, $times);
	return $times[1];
}

function translate_times($times)
{
	$term_dictionary = ["Morning Azan", "Sunrise", "Noon Azan", "Sunset", "Maghrib Azan", "Islamic Midnight"];
	$en_num = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ":"];
	$fa_num = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", ":"];
	$new_times = array();
	foreach($times as $k => $v)
	{
		$phrases = explode(":", $v, 2);
		$phrases[0] = $term_dictionary[$k];
		$phrases[1] = str_replace($fa_num, $en_num, $phrases[1]);
		$new_times[$k] = implode(":", $phrases);
	}

	return $new_times;
}

if(isset($argc))
{
	//The code is runnign through cli
	if($argc == 1) //Just the code
		$city = readline("City Name (fa): ");

	elseif($argc == 2) //with city name
		$city = $argv[1];

	elseif($argc == 3) //with language pref fa or en.
	{
		$city = $argv[1];
		$lang = $argv[2];
	}
}
elseif(isset($_REQUEST["city"])) //The code is running other way!
{
	$city = $_REQUEST["city"];
	if(isset($_REQUEST["lang"]))
		$lang = $_REQUEST["lang"];
}


if(isset($city))
	$times = get_times($city);

if(isset($times))
{
	if(isset($lang))
		$times = translate_times($times);

	$output = implode("\r\n", $times);
	echo $output . PHP_EOL;
}
?>
