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

namespace PSX\Data\Schema;

/**
 * PropertySimpleAbstract
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
abstract class PropertySimpleAbstract extends PropertyAbstract
{
	protected $pattern;
	protected $enumeration;

	/**
	 * @param string $pattern
	 * @return $this
	 */
	public function setPattern($pattern)
	{
		$this->pattern = $pattern;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * @param array $enumeration
	 * @return $this
	 */
	public function setEnumeration(array $enumeration)
	{
		$this->enumeration = $enumeration;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getEnumeration()
	{
		return $this->enumeration;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return md5(
			parent::getId() .
			$this->pattern .
			($this->enumeration ? implode(',', $this->enumeration) : '')
		);
	}

	/**
	 * @return boolean
	 */
	public function validate($data)
	{
		parent::validate($data);

		if($data === null)
		{
			return true;
		}

		if($this->pattern !== null)
		{
			$result = preg_match('/^(' . $this->pattern . '){1}$/', $data);

			if(!$result)
			{
				throw new ValidationException($this->getName() . ' does not match pattern');
			}
		}

		if($this->enumeration !== null)
		{
			if(!in_array($data, $this->enumeration))
			{
				throw new ValidationException($this->getName() . ' is not in enumeration');
			}
		}
	}
}
