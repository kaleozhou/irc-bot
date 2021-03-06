<?php
/**
 * Copyright 2017 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

use WildPHP\Core\Events\EventEmitter;

class EventEmitterTest extends \PHPUnit\Framework\TestCase
{
	public function testFirst()
	{
		$eventEmitter = new EventEmitter();

		$eventEmitter->on('test', [$this, 'ing']);
		$eventEmitter->on('test', [$this, 'foo']);
		$eventEmitter->first('test', [$this, 'bar']);

		$listeners = $eventEmitter->listeners('test');
		self::assertEquals([[$this, 'bar'], [$this, 'ing'], [$this, 'foo']], $listeners);
		self::assertEquals([$this, 'bar'], array_shift($listeners));

		$eventEmitter->on('ing', [$this, 'foo']);
		$listeners = $eventEmitter->listeners('ing');
		self::assertEquals([[$this, 'foo']], $listeners);
		self::assertEquals([$this, 'foo'], array_shift($listeners));
	}

	public function ing()
	{

	}

	public function foo()
	{

	}

	public function bar()
	{

	}
}
