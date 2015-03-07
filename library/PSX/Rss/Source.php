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

namespace PSX\Rss;

use PSX\Data\RecordAbstract;
use PSX\Data\RecordInfo;

/**
 * Source
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Source extends RecordAbstract
{
	protected $text;
	protected $url;

	public function __construct($text = null, $url = null)
	{
		if($text !== null)
		{
			$this->setText($text);
		}

		if($url !== null)
		{
			$this->setUrl($url);
		}
	}

	public function getRecordInfo()
	{
		return new RecordInfo('source', array(
			'text' => $this->text,
			'url'  => $this->url,
		));
	}

	public function setText($text)
	{
		$this->text = $text;
	}

	public function getText()
	{
		return $this->text;
	}

	public function setUrl($url)
	{
		$this->url = $url;
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function __toString()
	{
		return $this->text;
	}
}
