<?php
/*
 * PSX is a open source PHP framework to develop RESTful APIs.
 * For the current version and informations visit <http://phpsx.org>
 *
 * Copyright 2010-2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace PSX\Command;

use InvalidArgumentException;
use PSX\CommandInterface;
use PSX\Dispatch\CommandFactoryInterface;
use PSX\Event;
use PSX\Event\Context\CommandContext;
use PSX\Event\CommandExecuteEvent;
use PSX\Event\CommandProcessedEvent;
use PSX\Event\ExceptionThrownEvent;
use PSX\Loader\Context;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Executor
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Executor
{
	protected $factory;
	protected $output;
	protected $eventDispatcher;

	protected $aliases = array();

	public function __construct(CommandFactoryInterface $factory, OutputInterface $output, EventDispatcherInterface $eventDispatcher)
	{
		$this->factory         = $factory;
		$this->output          = $output;
		$this->eventDispatcher = $eventDispatcher;
	}

	public function addAlias($alias, $className)
	{
		$this->aliases[$alias] = $className;
	}

	public function getClassName($className)
	{
		return isset($this->aliases[$className]) ? $this->aliases[$className] : $className;
	}

	public function getAliases()
	{
		return $this->aliases;
	}

	public function run(ParameterParserInterface $parser, Context $context = null)
	{
		$context    = $context ?: new Context();
		$className  = $this->getClassName($parser->getClassName());
		$command    = $this->factory->getCommand($className, $context);
		$parameters = $command->getParameters();

		$parser->fillParameters($parameters);

		$this->eventDispatcher->dispatch(Event::COMMAND_EXECUTE, new CommandExecuteEvent($command, $parameters));

		try
		{
			$command->onExecute($parameters, $this->output);
		}
		catch(\Exception $e)
		{
			$this->eventDispatcher->dispatch(Event::EXCEPTION_THROWN, new ExceptionThrownEvent($e, new CommandContext($parameters)));

			$context->set(Context::KEY_EXCEPTION, $e);

			$class = isset($this->config['psx_error_command']) ? $this->config['psx_error_command'] : 'PSX\Command\ErrorCommand';

			$this->factory->getCommand($class, $context)->onExecute(new Parameters(), $this->output);
		}

		$this->eventDispatcher->dispatch(Event::COMMAND_PROCESSED, new CommandProcessedEvent($command, $parameters));

		return $command;
	}
}
