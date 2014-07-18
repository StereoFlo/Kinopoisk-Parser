<?php

class parser {
	
	public $login = NULL;
	public $password = NULL;
	public $refer = 'http://www.kinopoisk.ru';
	public $film_url = 'http://www.kinopoisk.ru/level/1/film/';
	
	/**
	* To start the class, need two parameters login and password, if this two parameter are specified, start the authenfication
	* @param string $login is you login on on the site 'kinpoisk.ru'
	* @param string $password is you password on on the site 'kinpoisk.ru'
	* 
	* @return void
	*/
	
	public function __construct($login, $password) 
	{
		if (is_null($login) or is_null($password))
		{
			die('You need to specify login and password');
		}
		else
		{
			$this->login = $login;
			$this->password = $password;
			$this->post('http://www.kinopoisk.ru/level/30/', 
						'shop_user[login]='. $this->login .'&shop_user[pass]='. $this->password .'&shop_user[mem]=on&auth=%E2%EE%E9%F2%E8+%ED%E0+%F1%E0%E9%F2',
						$this->refer);
		}
	}
	
	/**
	* Get the film id, u can specify a film id or a film url
	* @param string $data
	* 
	* @return string
	*/
	
	public function get_id ($data)
	{
		preg_match('#([0-9]{2,8})#', $data, $m);
		return $m[0];
	}
	
	/**
	* Send post request
	* @param string $url is a url to send request
	* @param string $post is an additional params
	* @param string $refer is a referer
	* 
	* @return string
	*/
	
	public function post ($url, $post, $refer)
	{
		if($post == null)
		{
			$post = false;
		}
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.4) Gecko/2008102920 AdCentriaIM/1.7 Firefox/3.0.4");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_REFERER, $refer);
		curl_setopt($ch, CURLOPT_COOKIEJAR, "./cookie.txt");
		curl_setopt($ch, CURLOPT_COOKIEFILE, "./cookie.txt");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		return $result;
	}
	
	/**
	* function for delete tags
	* @param array $data
	* @param string $postresult
	* 
	* @return object
	*/
	
	public function parse ($data, $postresult)
	{
		$result = new stdClass();
		foreach($data as $index => $value)
		{
			preg_match($value, $postresult, $matches);
			$result->$index = preg_replace("#<br.+?>#is","$1",$matches[1]);
			$result->$index = preg_replace("#\n#is","",$result->$index);
			$result->$index = preg_replace("#\t#is","",$result->$index);
			$result->$index = preg_replace("#\r#is","",$result->$index);
			$result->$index = preg_replace("#&nbsp;#is"," ",$result->$index);
			$result->$index = preg_replace("#  #is","",$result->$index);
			$result->$index = preg_replace("#<.+?>(.+?)</.+?>#is","$1",$result->$index);
		}
		
		return $result;
	}
	
	/**
	* Encode result to UTF8
	* @param string $type can be 'mb' or 'iconv'
	* @param string $film_id a film id
	* 
	* @return string
	*/
	
	public function encode_result ($type = 'mb', $film_id)
	{
		$result = NULL;
	    $result = $this->post($this->film_url . $film_id, null, $this->refer);
	    if ($type == 'iconv')
	    {
			$result = iconv("windows-1251", "utf8", $result);
		}
		elseif ($type == 'mb')
		{
			$result = mb_convert_encoding($result, "utf-8", "windows-1251");
		}
		else
		{
			$result = 'Type is not correct';
		}
	    return $result;
	}
	
}

?>