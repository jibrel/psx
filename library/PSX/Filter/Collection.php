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

namespace PSX\Filter;

use PSX\FilterAbstract;

/**
 * Collection
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Collection extends FilterAbstract
{
	protected $filters;

	/**
	 * @param array<PSX\FilterInterface>
	 */
	public function __construct(array $filters)
	{
		$this->filters = $filters;
	}

	/**
	 * Returns true if all filters allow the value
	 *
	 * @param mixed $value
	 * @return boolean
	 */
	public function apply($value)
	{
		$modified = false;

		foreach($this->filters as $filter)
		{
			$result = $filter->apply($value);

			if($result === false)
			{
				return false;
			}
			else if($result === true)
			{
			}
			else
			{
				$modified = true;
				$value    = $result;
			}
		}

		return $modified ? $value : true;
	}

	public function getErrorMessage()
	{
		return '%s contains invalid values';
	}
}
