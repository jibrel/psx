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

namespace PSX\Test;

use PDOException;
use PSX\Http\Request;
use PSX\Http\Response;

/**
 * ControllerDbTestCase
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
abstract class ControllerDbTestCase extends \PHPUnit_Extensions_Database_TestCase
{
	use ContainerTestCaseTrait;

	protected static $con;

	protected $connection;

	public function getConnection()
	{
		if(!hasConnection())
		{
			$this->markTestSkipped('Database connection not available');
		}

		if(self::$con === null)
		{
			self::$con = getContainer()->get('connection');
		}

		if($this->connection === null)
		{
			$this->connection = self::$con;
		}

		return $this->createDefaultDBConnection($this->connection->getWrappedConnection(), getContainer()->get('config')->get('psx_sql_db'));
	}

	/**
	 * Loads an specific controller
	 *
	 * @param PSX\Http\Request $request
	 * @param PSX\Http\Response $response
	 * @return PSX\ControllerInterface
	 */
	protected function loadController(Request $request, Response $response)
	{
		return getContainer()->get('dispatch')->route($request, $response);
	}

	/**
	 * Returns the available modules for the testcase
	 *
	 * @return array
	 */
	abstract protected function getPaths();
}
