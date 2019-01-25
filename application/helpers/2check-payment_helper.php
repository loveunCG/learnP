<?php
function twocheck_redirect( $url, $params )
{
	$url = $url . '?' . http_build_query($params, '', '&amp;');
	redirect( $url );
}