<?php
/**
 * Prodo
 * WordPress Theme
 *
 * Web: https://www.facebook.com/a.axminenko
 * Email: a.axminenko@gmail.com
 *
 * Copyright 2015 Alexander Axminenko
 */
 
class ProdoTwitter {
	# Get Tweets
	public static function getTweets( $account ) {
		$cache = get_user_meta( 1, 'twitter_cache', false );
		$cache = reset( $cache );

		$fromCache = $isError = false;

		if ( is_array( $cache ) and isset( $cache['time'] ) and isset( $cache['feed'] ) ) {
			if ( time( ) - ( int ) $cache['time'] < 300 ) {
				$fromCache = true;
			}
		}

		if ( ! $fromCache ) {
			$url             = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
			$token           = '40743312-y3liCcAOihr7rUUtfQ3roaiYEVcCsM8tv7bpV21PO';
			$token_secret    = '6OL8O5LQJFIoHjcOml29L1TKyDhEkGGMTf78PxCE';
			$consumer_key    = 'qMwvjfLy4NUbRIYo7flwUQ';
			$consumer_secret = 'DdqVDcmF9LbCqhYfLPPgvcWh54XVQpZnu5KqjcMg';

			$query = array(
				'screen_name'            => $account
			);
			$oauth = array(
				'oauth_consumer_key'     => $consumer_key,
				'oauth_token'            => $token,
				'oauth_nonce'            => ( string ) mt_rand( ),
				'oauth_timestamp'        => time( ),
				'oauth_signature_method' => 'HMAC-SHA1',
				'oauth_version'          => '1.0'
			);

			$oauth = array_map( 'rawurlencode', $oauth );
			$query = array_map( 'rawurlencode', $query );
			$arr = array_merge( $oauth, $query );

			ksort( $arr );

			$querystring = urldecode( http_build_query( $arr, '', '&' ) );
			$base_string = 'GET&' . rawurlencode( $url ) . '&' . rawurlencode( $querystring );
			$key = rawurlencode( $consumer_secret ) . '&' . rawurlencode( $token_secret );
			$signature = rawurlencode( base64_encode( hash_hmac( 'sha1', $base_string, $key, true ) ) );
			$url .= '?' . http_build_query( $query );
			$oauth['oauth_signature'] = $signature;

			ksort( $oauth );

			$oauth = array_map( array( 'self', 'addQuotes' ), $oauth);
			$auth = 'OAuth ' . urldecode( http_build_query( $oauth, '', ', ' ) );

			$response = wp_remote_get( $url, array( 'headers' => array( 'Authorization' => $auth, 'Accept-Encoding' => '' ) ) );
			
			if ( ! is_wp_error( $response ) and is_array( $response ) and isset( $response['body'] ) ) {
				$json = json_decode( $response['body'] );
			} else {
				if ( is_array( $cache ) and isset( $cache['feed'] ) ) {
					$json = $cache['feed'];
					$fromCache = false;
					$isError = true;
				} else {
					return false;
				}
			}
		}

		if ( ! $fromCache ) {
			if ( ! $isError ) {
				$cache = array( 'feed' => $json, 'time' => time( ) );
				update_user_meta( 1, 'twitter_cache', $cache );
			}
		} else {
			$json = $cache['feed'];
		}

		if ( $json === false or isset( $json->errors ) or isset( $json->error ) ) {
			return false;
		}
		
		return $json;
	}

	# Add Quotes
	public static function addQuotes( $string ) {
		return '"' . $string . '"';
	}

	# Parse Links
	public static function parseLinks( $string ) {
		$string = preg_replace( "/@(\w+)/", "<a href=\"https://twitter.com/$1\">@$1</a>", $string );

		return popuplinks( make_clickable( $string ) );
	}
}
