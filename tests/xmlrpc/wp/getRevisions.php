<?php

/**
 * @group xmlrpc
 */
class Tests_XMLRPC_wp_getRevisions extends WP_XMLRPC_UnitTestCase {

	function test_invalid_username_password() {
		$result = $this->myxmlrpcserver->wp_getRevisions( array( 1, 'username', 'password', 0 ) );
		$this->assertInstanceOf( 'IXR_Error', $result );
		$this->assertEquals( 403, $result->code );
	}

	function test_incapable_user() {
		$this->make_user_by_role( 'subscriber' );

		$post_id = $this->factory->post->create();

		$result = $this->myxmlrpcserver->wp_getRevisions( array( 1, 'subscriber', 'subscriber', $post_id ) );
		$this->assertInstanceOf( 'IXR_Error', $result );
		$this->assertEquals( 401, $result->code );
	}

	function test_capable_user() {
		$this->make_user_by_role( 'editor' );

		$post_id = $this->factory->post->create();
		$result = $this->myxmlrpcserver->wp_getRevisions( array( 1, 'editor', 'editor', $post_id ) );
		$this->assertNotInstanceOf( 'IXR_Error', $result );
	}

	function test_revision_count() {
		$this->make_user_by_role( 'editor' );

		$post_id = $this->factory->post->create();

		$result = $this->myxmlrpcserver->wp_getRevisions( array( 1, 'editor', 'editor', $post_id ) );
		$this->assertInternalType( 'array', $result );
		$this->assertCount( 0, $result );

		wp_insert_post( array( 'ID' => $post_id, 'post_content' => 'Edit 1' ) );

		$result = $this->myxmlrpcserver->wp_getRevisions( array( 1, 'editor', 'editor', $post_id ) );
		$this->assertInternalType( 'array', $result );
		$this->assertCount( 1, $result );
	}
}
