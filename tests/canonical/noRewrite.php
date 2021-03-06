<?php

require_once dirname( dirname( __FILE__ ) ) . '/canonical.php';

/**
 * @group canonical
 * @group rewrite
 * @group query
 */
class Tests_Canonical_NoRewrite extends Tests_Canonical {

	var $structure = '';

	// These test cases are run against the test handler in WP_Canonical

	function data() {
		/* Format:
		 * [0]: $test_url,
		 * [1]: expected results: Any of the following can be used
		 *      array( 'url': expected redirection location, 'qv': expected query vars to be set via the rewrite AND $_GET );
		 *      array( expected query vars to be set, same as 'qv' above )
		 *      (string) expected redirect location
		 * [3]: (optional) The ticket the test refers to, Can be skipped if unknown.
		 */
		return array(
			array( '/?p=123', '/?p=123' ),

			// This post_type arg should be stripped, because p=1 exists, and does not have post_type= in its query string
			array( '/?post_type=fake-cpt&p=1', '/?p=1' ),

			// Strip an existing but incorrect post_type arg
			array( '/?post_type=page&page_id=1', '/?p=1' ),

			array( '/?p=358 ', array('url' => '/?p=358',  'qv' => array('p' => '358') ) ), // Trailing spaces
			array( '/?p=358%20', array('url' => '/?p=358',  'qv' => array('p' => '358') ) ),

			array( '/?page_id=1', '/?p=1' ), // redirect page_id to p (should cover page_id|p|attachment_id to one another
			array( '/?page_id=1&post_type=revision', '/?p=1' ),

		);
	}
}
