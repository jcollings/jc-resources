<?php
/**
 * Check to see if single resource
 */
function jcr_is_single_resource(){
	if(is_singular( 'resource' )){
		return true;
	}
	return false;
}