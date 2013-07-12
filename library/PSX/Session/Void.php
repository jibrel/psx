<?php
/*
 * psx
 * A object oriented and modular based PHP framework for developing
 * dynamic web applications. For the current version and informations
 * visit <http://phpsx.org>
 *
 * Copyright (c) 2010-2013 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This file is part of psx. psx is free software: you can
 * redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or any later version.
 *
 * psx is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with psx. If not, see <http://www.gnu.org/licenses/>.
 */

namespace PSX\Session;

/**
 * Session implementation wich actually doesnt start a session. Useful for cli
 * applications where it is not possible to start a session
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class Void extends \PSX\Session
{
	public function __construct($name, HandlerInterface $handler = null)
	{
		$this->setSessionTokenKey(__CLASS__);
		$this->setName($name);
		$this->setToken(md5($name));
	}

	public function set($key, $value)
	{
	}

	public function get($key)
	{
		return null;
	}

	public function has($key)
	{
		return false;
	}

	public function setSessionTokenKey($tokenKey)
	{
		$this->sessionTokenKey = $tokenKey;
	}

	public function getSessionTokenKey()
	{
		return $this->sessionTokenKey;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setToken($token)
	{
		$this->token = $token;
	}

	public function getToken()
	{
		return $this->token;
	}

	public function setId($id)
	{
	}

	public function getId()
	{
	}

	public function setSaveHandler(HandlerInterface $handler)
	{
	}

	public function setSavePath($path)
	{
	}

	public function start()
	{
	}

	public function close()
	{
	}

	public function destroy()
	{
	}

	public function isActive()
	{
		return false;
	}
}

