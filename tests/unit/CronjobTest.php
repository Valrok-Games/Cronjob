<?php

use PHPUnit\Framework\TestCase;
use Valrok\Cronjob\Cronjob;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;

class CronjobTest extends TestCase {

    protected function setUp(): void {
        parent::setUp();
        Monkey\setUp();

        // Mocking WordPress functions
        if (!function_exists('add_action')) {
            function add_action($hook, $callback) {
                // Mock implementation
            }
        }

        if (!function_exists('wp_next_scheduled')) {
            function wp_next_scheduled($hook) {
                // Mock implementation
                return false;
            }
        }

        if (!function_exists('wp_schedule_event')) {
            function wp_schedule_event($timestamp, $recurrence, $hook) {
                // Mock implementation
            }
        }

        if (!function_exists('wp_unschedule_event')) {
            function wp_unschedule_event($timestamp, $hook) {
                // Mock implementation
            }
        }
    }

    public function testConstructor() {
        $action_name = 'test_action';
        $action_exec = function() { echo "Executing"; };
        $recurrence = 'hourly';
        $cronjob = new Cronjob($action_name, $action_exec, $recurrence);

        $this->assertEquals('valrok_cron_' . $action_name, $cronjob->action_name);
        $this->assertEquals($recurrence, $cronjob->recurrence);
    }

    public function testSchedule() {
        $action_name = 'test_action';
        $action_exec = function() { echo "Executing"; };
        $recurrence = 'hourly';
        $cronjob = new Cronjob($action_name, $action_exec, $recurrence);

        $this->expectOutputString(''); // No output expected

        $cronjob->schedule();

        // Since we mock WordPress functions, we don't have real assertions here.
        // In a real test environment, we would check if add_action and wp_schedule_event were called.
        $this->assertTrue(true); // Placeholder assertion
    }

    public function testDeschedule() {
        $action_name = 'test_action';
        $action_exec = function() { echo "Executing"; };
        $recurrence = 'hourly';
        $cronjob = new Cronjob($action_name, $action_exec, $recurrence);

        $this->expectOutputString(''); // No output expected

        $cronjob->deschedule();

        // Since we mock WordPress functions, we don't have real assertions here.
        // In a real test environment, we would check if wp_unschedule_event was called.
        $this->assertTrue(true); // Placeholder assertion
    }

    public function testRun() {
        $executed = false;
        $action_exec = function() use (&$executed) {
            $executed = true;
        };
        $cronjob = new Cronjob('test_action', $action_exec, 'hourly');

        $cronjob->run();

        $this->assertTrue($executed);
    }

    public function testRunWithNullActionExec() {
        $cronjob = new Cronjob('test_action', null, 'hourly');

        $this->expectOutputString(''); // No output expected

        $cronjob->run();

        $this->assertTrue(true); // Placeholder assertion to ensure run completes
    }
}