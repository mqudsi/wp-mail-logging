<?php

/**
 * @author No3x
 * Tests are written in the AAA-Rule
 * There are three basic sections to our test: Arrange, Act, and Assert.
 */
class WPML_Plugin_Test extends WP_UnitTestCase {
	
	private $plugin;

	function setUp() {
		parent::setUp();
		$this->plugin = &$GLOBALS['WPML_Plugin'];
	}
	
	function test_PluginInitialization() {
		$this->assertFalse( null == $this->plugin );
	}
	
	function test_getTablename() {
		global $wpdb;
		
		// Arrange
		$tableName = 'testTable';
		// Act
		$prefixed = WPML_Plugin::getTablename( $tableName );
		// Assert
		$this->assertEquals( $wpdb->prefix . 'wpml_testTable', $prefixed );
	}

	function test_log_email() {
		global $wpdb;
		
		$to = array(
				'email@example.com',
				'email2@example.com'
		);
		$subject = rand_str();
		$message = "Hello, this is a test message";
		
		if( !isset( $_SERVER['SERVER_NAME'] ) ) {
			$_SERVER['SERVER_NAME'] = 'vvv';
		}
		
		wp_mail($to, $subject, $message);
		
		$tableName = WPML_Plugin::getTablename( 'mails' );
		$rows = $wpdb->get_results( "SELECT * FROM $tableName WHERE subject = '{$subject}'" );
		
		$count = count( $rows );
		$this->assertEquals( 1, $count);
		
		$row = $rows[0];
		$this->assertEquals( $subject,  $row->subject );
		$this->assertEquals( $message,  $row->message );
		
		$this->assertTrue( strpos( $row->receiver, $to[0] ) !== false );
		$this->assertTrue( strpos( $row->receiver, $to[1] ) !== false );
	}
	
	
}
