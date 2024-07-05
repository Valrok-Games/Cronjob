<?php

namespace Valrok\Cronjob;

/**
 * Cronjob class
 */
class Cronjob {
	/**
	 * Name of hook registered for wp_scheduled task
	 *
	 * @var string
	 */
	public readonly string $action_name;
	/**
	 * Frequency of task
	 *
	 * @var integer
	 */
	public readonly string $recurrence;
	/**
	 * Command to run when the task is executing
	 * @var callable
	 */
	private mixed $action_exec;

	/**
	 * Cronjob call for wp scheduled event
	 *
	 * @param string $action_name
	 * @param callable $action_exec
	 * @param string $recurrence How often the event should subsequently recur. See wp_get_schedules() for accepted values.
	 * @param string $action_name_prefix
	 */
	public function __construct( string $action_name, mixed $action_exec, string $recurrence, string $action_name_prefix = 'valrok_cron_' ) {
		$this->action_name = $action_name_prefix . $action_name;
		$this->action_exec = $action_exec;
		$this->recurrence  = $recurrence;
	}

	/**
	 * Registeres the scheduled task
	 *
	 * @return void
	 */
	public function schedule(): void {
		add_action( $this->action_name, [ $this, 'run' ] );

		if ( ! wp_next_scheduled( $this->action_name ) ) {
			wp_schedule_event( time(), $this->recurrence, $this->action_name );
		}
	}

	/**
	 * Removes the registration of the scheduled task
	 *
	 * @return void
	 */
	public function deschedule(): void {
		$timestamp = wp_next_scheduled( $this->action_name );
		wp_unschedule_event( $timestamp, $this->action_name );
		remove_action( $this->action_name, [ $this, 'run' ] );
	}

	public function run(): void {
		if ( $this->action_exec == null ) {
			return;
		}

		call_user_func( $this->action_exec );
	}

}
