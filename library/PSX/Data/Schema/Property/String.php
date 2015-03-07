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

namespace PSX\Data\Schema\Property;

use PSX\Data\Schema\PropertySimpleAbstract;
use PSX\Data\Schema\ValidationException;

/**
 * String
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class String extends PropertySimpleAbstract
{
	protected $minLength;
	protected $maxLength;

	public function setMinLength($minLength)
	{
		$this->minLength = $minLength;

		return $this;
	}

	public function getMinLength()
	{
		return $this->minLength;
	}

	public function setMaxLength($maxLength)
	{
		$this->maxLength = $maxLength;

		return $this;
	}

	public function getMaxLength()
	{
		return $this->maxLength;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return md5(
			parent::getId() .
			$this->minLength .
			$this->maxLength
		);
	}

	public function validate($data)
	{
		parent::validate($data);

		if($data === null)
		{
			return true;
		}

		// must be an string or an object which can be casted to an string
		if(is_string($data))
		{
		}
		else if(is_object($data) && method_exists($data, '__toString'))
		{
			$data = (string) $data;
		}
		else
		{
			throw new ValidationException($this->getName() . ' must be a string');
		}

		if($this->minLength !== null)
		{
			if(strlen($data) < $this->minLength)
			{
				throw new ValidationException($this->getName() . ' must contain more then ' . $this->minLength . ' characters');
			}
		}

		if($this->maxLength !== null)
		{
			if(strlen($data) > $this->maxLength)
			{
				throw new ValidationException($this->getName() . ' must contain less then ' . $this->maxLength . ' characters');
			}
		}

		return true;
	}
}
