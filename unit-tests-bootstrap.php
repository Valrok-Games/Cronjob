<?php
/**
 * This file must only be loaded when running the unit-tests.
 * It is automatically bootstrapped by the phpunit.xml file before running unit-tests.
 * @author FMP
 * @requires https://github.com/sebastianbergmann/phpunit
 * @requires https://github.com/Brain-WP/BrainMonkey
 * @since 1.0.0
 */

if ( ! defined( 'SRC_DIR' ) ) {
	define( 'SRC_DIR', __DIR__ . '/src' );
}

/**
 * Load vendor files.
 */
require_once __DIR__ . '/vendor/autoload.php';
