<?php

namespace Valrok\Cronjob;

/**
 * Init Cronjobs
 */
class CronjobManager {

    /**
     * Scheduled cronjobs.
     *
     * @var array
     */
    private static array $cronjobs;

    /**
     * Construct
     */
	public function __construct() {}

	/**
	 * Add cronjobs
	 *
	 * @param Cronjob ...$cronjobs
	 * @return self
	 */
	public function add( Cronjob ...$cronjobs ): self {
		foreach ( $cronjobs as $task ) {
			if ( ! $task instanceof Cronjob ) {
				continue;
			}

			$task->schedule();

            $key = md5( json_encode( $task ) );
            self::$cronjobs[ $key ] = $task;
		}

		return $this;
	}

	/**
	 * Remove cronjobs
	 *
	 * @param Cronjob ...$cronjobs
	 * @return self
	 */
	public function remove( Cronjob ...$cronjobs ): self {
		foreach ( $cronjobs as $task ) {
			if ( ! $task instanceof Cronjob ) {
				continue;
			}

			$task->deschedule();

            $key = md5( json_encode( $task ) );
            if ( ! isset( self::$cronjobs[ $key ] ) ) {
                continue;
            }

            unset( self::$cronjobs[ $key ] );
		}

		return $this;
	}

    /**
     * Get scheduled cronjobs.
     *
     * @return array
     */
    public function get_cronjobs(): array { 
        $cronjobs = isset( self::$cronjobs ) ? self::$cronjobs : [];
        return self::$cronjobs;
    }

}
