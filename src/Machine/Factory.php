<?php

declare(strict_types=1);

namespace Stater\Machine;

use Stater\Events\Factory as EventFactory;
use Stater\States\Factory as StateFactory;

class Factory
{
	/**
	 * Hold all states in the state machine
	 *
	 * @var array
	 */
	private $states = [];

	/**
	 * Holds all events in the state machine
	 *
	 * @var array
	 */
	private $events = [];

	/**
	 * @var StateMachine
	 */
	private $machine;

	/**
	 * Factory constructor.
	 *
	 * @param StateMachine $machine
	 */
	public function __construct (StateMachine $machine)
	{
		$this->machine = $machine ?? new StateMachine(null);
	}

	/**
	 * Create state machine with inputs
	 *
	 * @param  array $entries
	 *
	 * @return mixed
	 */
	public function create (?array $entries)
	{

		if (null === $entries || empty($entries)) {
			return $this->machine;
		}

		foreach ($entries as $entry) {

			$states = $this->states($entry["state"], $entry["transitionTo"]);
			$event = $this->events($entry["event"]);

			$this->make($entry, $states, $event);
		}

	}

	private function make ($entry, $states, $event)
	{
		$this->machine
			->state(
				$states($entry["state"]["name"])
			)
			->on(
				$event($entry["event"]["name"]),
				$entry["condition"]
			)
			->transitionTo(
				$states($entry["transitionTo"]["name"]),
				$entry["callback"]
			);
	}

	/**
	 * @param $entry
	 *
	 * @return mixed
	 */
	private function states (...$entry)
	{
		$states = [];

		foreach ($entry as $item) {
			if (is_string($item)) {
				if (array_key_exists($item, $this->states)) {
					continue;
				} else {
					$states[] = $item;
				}
			}

			if (is_array($item)) {
				if (array_key_exists($item['name'], $this->states)) {
					continue;
				} else {
					$states[] = $item;
					$this->states['name'] = $item;
				}
			}
		}

		return (new StateFactory())->create($states);
	}

	/**
	 * @param $entry
	 *
	 * @return mixed
	 */
	private function events (...$entry)
	{
		$events = [];

		foreach ($entry as $item) {
			if (is_string($item)) {
				if (array_key_exists($item, $this->$events)) {
					continue;
				} else {
					$events[] = $item;
				}
			}

			if (is_array($item)) {
				if (array_key_exists($item['name'], $this->$events)) {
					continue;
				} else {
					$events[] = $item;
					$this->events['name'] = $item;
				}
			}
		}

		return (new EventFactory())->create($events);
	}

	/**
	 * Return the state machine map
	 *
	 * @return array
	 */
	public function get()
	{
		return $this->machine->get();
	}


}