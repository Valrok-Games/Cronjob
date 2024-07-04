<?php

use PHPUnit\Framework\TestCase;
use Valrok\Cronjob\Cronjob;
use Valrok\Cronjob\CronjobManager;

class CronjobManagerTest extends TestCase {

    protected function setUp(): void {
        parent::setUp();

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

    public function testAddCronjob() {
        $cronjob = $this->createMock(Cronjob::class);
        $cronjob->expects($this->once())->method('schedule');

        $manager = new CronjobManager();
        $manager->add($cronjob);

        $cronjobs = $manager->get_cronjobs();
        $this->assertCount(1, $cronjobs);

        $key = md5(json_encode($cronjob));
        $this->assertArrayHasKey($key, $cronjobs);
    }

    public function testAddMultipleCronjobs() {
        $cronjob1 = new Cronjob( 'test_cron', function(): void {
            return;
        }, 'hourly' );

        $cronjob2 = new Cronjob( 'test_cron', function(): void {
            return;
        }, 'weekly' );

        $manager = new CronjobManager();
        $manager->clear();

        $manager->add($cronjob1, $cronjob2);

        $cronjobs = $manager->get_cronjobs();
        $this->assertCount(2, $cronjobs);
    }

    public function testRemoveCronjob() {
        $cronjob = $this->createMock(Cronjob::class);
        $cronjob->expects($this->once())->method('schedule');
        $cronjob->expects($this->once())->method('deschedule');

        $manager = new CronjobManager();
        $manager->clear();

        $manager->add($cronjob);
        $manager->remove($cronjob);

        $cronjobs = $manager->get_cronjobs();
        $this->assertCount(0, $cronjobs);
    }

    public function testRemoveNonExistentCronjob() {
        $cronjob = $this->createMock(Cronjob::class);

        $manager = new CronjobManager();
        $manager->clear();
        $manager->remove($cronjob);

        $cronjobs = $manager->get_cronjobs();
        $this->assertCount(0, $cronjobs );
    }

    public function testGetCronjobs() {
        $cronjob1 = new Cronjob( 'test_cron', function(): void {
            return;
        }, 'hourly' );

        $cronjob2 = new Cronjob( 'test_cron', function(): void {
            return;
        }, 'weekly' );

        $manager = new CronjobManager();
        $manager->clear();
        $manager->add($cronjob1, $cronjob2);

        $cronjobs = $manager->get_cronjobs();
        $this->assertCount(2, $cronjobs);
    }

    public function testClearCronjobs() {
        $manager = new CronjobManager();
        $manager->clear();

        $cronjobs = $manager->get_cronjobs();
        $this->assertCount(0, $cronjobs);
    }
}