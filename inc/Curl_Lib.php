<?php 

require_once "Curl_Response.php";

class Curl_Lib {
	public static function get_web_page( $url, $cookiesIn = '' ){
	        $options = array(
	            CURLOPT_RETURNTRANSFER => true,     // return web page
	            CURLOPT_HEADER         => true,     //return headers in addition to content
	            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
	            CURLOPT_ENCODING       => "",       // handle all encodings
	            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
	            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
	            CURLOPT_TIMEOUT        => 120,      // timeout on response
	            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
	            CURLINFO_HEADER_OUT    => true,
	            CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
	            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
	            CURLOPT_COOKIE         => $cookiesIn
	        );

	        $ch      = curl_init( $url );
	        curl_setopt_array( $ch, $options );
	        $rough_content = curl_exec( $ch );
	        $err     = curl_errno( $ch );
	        $errmsg  = curl_error( $ch );
	        $info  = curl_getinfo( $ch );
	        curl_close( $ch );

	        $header_content = substr($rough_content, 0, $info['header_size']);
	        $body_content = trim(str_replace($header_content, '', $rough_content));
	        $pattern = "#Set-Cookie:\\s+(?<cookie>[^=]+=[^;]+)#m"; 
	        preg_match_all($pattern, $header_content, $matches); 
	        $cookiesOut = implode("; ", $matches['cookie']);

	        return Curl_Response::get($info)
	        		->setErrno($err)
	        		->setErrmsg($errmsg)
	        		->setHeaders($header_content)
	        		->setContent($body_content)
	        		->setCookies($cookiesOut);
	}

	public static function post_web_page($url, array $postFields = array(), $cookiesIn = '') {
		$curlOpts = array(
				CURLOPT_RETURNTRANSFER => true,     // return web page
				CURLOPT_HEADER         => true,     //return headers in addition to content
				CURLOPT_FOLLOWLOCATION => true,     // follow redirects
				CURLOPT_ENCODING       => "",       // handle all encodings
				CURLOPT_AUTOREFERER    => true,     // set referer on redirect
				CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
				CURLOPT_TIMEOUT        => 120,      // timeout on response
				CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
				CURLINFO_HEADER_OUT    => true,
				CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_COOKIE         => $cookiesIn,
				CURLOPT_POST 		   => true
			);

			$ch = curl_init($url);
	        curl_setopt_array( $ch, $curlOpts );
			!empty($postFields) ? curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields) : null;
	        $rough_content = curl_exec( $ch );
	        $err     = curl_errno( $ch );
	        $errmsg  = curl_error( $ch );
	        $info  = curl_getinfo( $ch );
	        curl_close( $ch );

	        $header_content = substr($rough_content, 0, $info['header_size']);
	        $body_content = trim(str_replace($header_content, '', $rough_content));
	        $pattern = "#Set-Cookie:\\s+(?<cookie>[^=]+=[^;]+)#m"; 
	        preg_match_all($pattern, $header_content, $matches); 
	        $cookiesOut = implode("; ", $matches['cookie']);

	        return Curl_Response::get($info)
	        		->setErrno($err)
	        		->setErrmsg($errmsg)
	        		->setHeaders($header_content)
	        		->setContent($body_content)
	        		->setCookies($cookiesOut);
	}
}