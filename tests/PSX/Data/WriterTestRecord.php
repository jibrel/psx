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

namespace PSX\Data;

use DateTime;
use PSX\ActivityStream;
use PSX\Data\NotSupportedException;
use PSX\Data\RecordAbstract;
use PSX\Data\WriterResult;
use PSX\Data\WriterInterface;

/**
 * WriterTestRecord
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class WriterTestRecord extends RecordAbstract
{
	protected $id;
	protected $author;
	protected $title;
	protected $content;
	protected $date;

	public function getRecordInfo()
	{
		return new RecordInfo('record', array(
			'id'      => $this->id,
			'author'  => $this->author,
			'title'   => $this->title,
			'content' => $this->content,
			'date'    => $this->date,
		));
	}

	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function setAuthor($author)
	{
		$this->author = $author;
	}
	
	public function getAuthor()
	{
		return $this->author;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	public function getTitle()
	{
		return $this->title;
	}

	public function setContent($content)
	{
		$this->content = $content;
	}
	
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @param DateTime $date
	 */
	public function setDate(DateTime $date)
	{
		$this->date = $date;
	}
	
	public function getDate()
	{
		return $this->date;
	}
}
