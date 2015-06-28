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

namespace PSX\Sql;

use PSX\Data\Record;
use PSX\DateTime;
use PSX\Sql;

/**
 * TableTestCase
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
trait TableTestCase
{
	/**
	 * Returns the table wich should be used for the test. The table must
	 * have the following fields: id, userId, title, date. And the following
	 * default values:
	 * <code>
	 * 	id = 1,
	 * 	userId = 1,
	 * 	title = 'foo',
	 * 	date = '2013-04-29 16:56:32'
	 *
	 * 	id = 2,
	 * 	userId = 1,
	 * 	title = 'bar',
	 * 	date = '2013-04-29 16:56:32'
	 *
	 * 	id = 3,
	 * 	userId = 2,
	 * 	title = 'test',
	 * 	date = '2013-04-29 16:56:32'
	 *
	 * 	id = 4,
	 * 	userId = 3,
	 * 	title = 'blub',
	 * 	date = '2013-04-29 16:56:32'
	 * </code>
	 *
	 * @return PSX\Table\TableInterface
	 */
	protected function getTable()
	{
		$this->markTestIncomplete('Table not given');
	}

	public function testGetAll()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$result = $table->getAll();

		$this->assertEquals(true, is_array($result));
		$this->assertEquals(4, count($result));

		$expect = array(
			new Record('comment', array(
				'id' => 4,
				'userId' => 3,
				'title' => 'blub',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
			new Record('comment', array(
				'id' => 3,
				'userId' => 2,
				'title' => 'test',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
			new Record('comment', array(
				'id' => 2,
				'userId' => 1,
				'title' => 'bar',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
			new Record('comment', array(
				'id' => 1,
				'userId' => 1,
				'title' => 'foo',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
		);

		$this->assertEquals($expect, $result);
	}

	public function testGetAllStartIndex()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$result = $table->getAll(3);

		$this->assertEquals(true, is_array($result));
		$this->assertEquals(1, count($result));

		$expect = array(
			new Record('comment', array(
				'id' => 1,
				'userId' => 1,
				'title' => 'foo',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
		);

		$this->assertEquals($expect, $result);
	}

	public function testGetAllCount()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$result = $table->getAll(0, 2);

		$this->assertEquals(true, is_array($result));
		$this->assertEquals(2, count($result));

		$expect = array(
			new Record('comment', array(
				'id' => 4,
				'userId' => 3,
				'title' => 'blub',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
			new Record('comment', array(
				'id' => 3,
				'userId' => 2,
				'title' => 'test',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
		);

		$this->assertEquals($expect, $result);
	}

	public function testGetAllStartIndexAndCountDefault()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$result = $table->getAll(2, 2);

		$this->assertEquals(true, is_array($result));
		$this->assertEquals(2, count($result));

		$expect = array(
			new Record('comment', array(
				'id' => 2,
				'userId' => 1,
				'title' => 'bar',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
			new Record('comment', array(
				'id' => 1,
				'userId' => 1,
				'title' => 'foo',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
		);

		$this->assertEquals($expect, $result);
	}

	public function testGetAllStartIndexAndCountDesc()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$result = $table->getAll(2, 2, 'id', Sql::SORT_DESC);

		$this->assertEquals(true, is_array($result));
		$this->assertEquals(2, count($result));

		$expect = array(
			new Record('comment', array(
				'id' => 2,
				'userId' => 1,
				'title' => 'bar',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
			new Record('comment', array(
				'id' => 1,
				'userId' => 1,
				'title' => 'foo',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
		);

		$this->assertEquals($expect, $result);
	}

	public function testGetAllStartIndexAndCountAsc()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$result = $table->getAll(2, 2, 'id', Sql::SORT_ASC);

		$this->assertEquals(true, is_array($result));
		$this->assertEquals(2, count($result));

		$expect = array(
			new Record('comment', array(
				'id' => 3,
				'userId' => 2,
				'title' => 'test',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
			new Record('comment', array(
				'id' => 4,
				'userId' => 3,
				'title' => 'blub',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
		);

		$this->assertEquals($expect, $result);
	}

	public function testGetAllSortDesc()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$result = $table->getAll(0, 2, 'id', Sql::SORT_DESC);

		$this->assertEquals(true, is_array($result));
		$this->assertEquals(2, count($result));

		$expect = array(
			new Record('comment', array(
				'id' => 4,
				'userId' => 3,
				'title' => 'blub',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
			new Record('comment', array(
				'id' => 3,
				'userId' => 2,
				'title' => 'test',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
		);

		$this->assertEquals($expect, $result);

		foreach($result as $row)
		{
			$this->assertTrue($row->getId() != null);
			$this->assertTrue($row->getTitle() != null);
		}

		// check order
		$this->assertEquals(4, $result[0]->getId());
		$this->assertEquals(3, $result[1]->getId());
	}

	public function testGetAllSortAsc()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$result = $table->getAll(0, 2, 'id', Sql::SORT_ASC);

		$this->assertEquals(true, is_array($result));
		$this->assertEquals(2, count($result));

		$expect = array(
			new Record('comment', array(
				'id' => 1,
				'userId' => 1,
				'title' => 'foo',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
			new Record('comment', array(
				'id' => 2,
				'userId' => 1,
				'title' => 'bar',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
		);

		$this->assertEquals($expect, $result);
	}

	public function testGetAllCondition()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$con    = new Condition(array('userId', '=', 1));
		$result = $table->getAll(0, 16, 'id', Sql::SORT_DESC, $con);

		$this->assertEquals(true, is_array($result));
		$this->assertEquals(2, count($result));

		$expect = array(
			new Record('comment', array(
				'id' => 2,
				'userId' => 1,
				'title' => 'bar',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
			new Record('comment', array(
				'id' => 1,
				'userId' => 1,
				'title' => 'foo',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
		);

		$this->assertEquals($expect, $result);
	}

	public function testGetAllConditionAndConjunction()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$con = new Condition();
		$con->add('userId', '=', 1, 'AND');
		$con->add('userId', '=', 3);
		$result = $table->getAll(0, 16, 'id', Sql::SORT_DESC, $con);

		$this->assertEquals(true, is_array($result));
		$this->assertEquals(0, count($result));

		// check and condition with result
		$con = new Condition();
		$con->add('userId', '=', 1, 'AND');
		$con->add('title', '=', 'foo');
		$result = $table->getAll(0, 16, 'id', Sql::SORT_DESC, $con);

		$this->assertEquals(true, is_array($result));
		$this->assertEquals(1, count($result));

		$expect = array(
			new Record('comment', array(
				'id' => 1,
				'userId' => 1,
				'title' => 'foo',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
		);

		$this->assertEquals($expect, $result);
	}

	public function testGetAllConditionOrConjunction()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$con = new Condition();
		$con->add('userId', '=', 1, 'OR');
		$con->add('userId', '=', 3);
		$result = $table->getAll(0, 16, 'id', Sql::SORT_DESC, $con);

		$this->assertEquals(true, is_array($result));
		$this->assertEquals(3, count($result));

		$expect = array(
			new Record('comment', array(
				'id' => 4,
				'userId' => 3,
				'title' => 'blub',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
			new Record('comment', array(
				'id' => 2,
				'userId' => 1,
				'title' => 'bar',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
			new Record('comment', array(
				'id' => 1,
				'userId' => 1,
				'title' => 'foo',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
		);

		$this->assertEquals($expect, $result);
	}

	public function testGetBy()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$result = $table->getByUserId(1);

		$this->assertEquals(true, is_array($result));
		$this->assertEquals(2, count($result));

		$expect = array(
			new Record('comment', array(
				'id' => 2,
				'userId' => 1,
				'title' => 'bar',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
			new Record('comment', array(
				'id' => 1,
				'userId' => 1,
				'title' => 'foo',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
		);

		$this->assertEquals($expect, $result);
	}

	public function testGetOneBy()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$row = $table->getOneById(1);

		$expect = array(
			new Record('comment', array(
				'id' => 1,
				'userId' => 1,
				'title' => 'foo',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
		);

		$this->assertEquals($expect, array($row));
	}

	public function testGet()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$row = $table->get(1);

		$expect = array(
			new Record('comment', array(
				'id' => 1,
				'userId' => 1,
				'title' => 'foo',
				'date' => new \DateTime('2013-04-29 16:56:32'),
			)),
		);

		$this->assertEquals($expect, array($row));
	}

	public function testGetSupportedFields()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$fields = $table->getSupportedFields();

		$this->assertEquals(array('id', 'userId', 'title', 'date'), $fields);
	}

	public function testGetCount()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$this->assertEquals(4, $table->getCount());
		$this->assertEquals(2, $table->getCount(new Condition(array('userId', '=', 1))));
		$this->assertEquals(1, $table->getCount(new Condition(array('userId', '=', 3))));
	}

	public function testGetRecord()
	{
		$table = $this->getTable();

		if(!$table instanceof TableQueryInterface)
		{
			$this->markTestSkipped('Table not an query interface');
		}

		$obj = $table->getRecord();

		$this->assertInstanceOf('PSX\Data\RecordInterface', $obj);
		$this->assertEquals('record', $obj->getRecordInfo()->getName());
		$this->assertEquals(array('id', 'userId', 'title', 'date'), array_keys($obj->getRecordInfo()->getFields()));
	}

	public function testCreate()
	{
		$table = $this->getTable();

		if(!$table instanceof TableManipulationInterface)
		{
			$this->markTestSkipped('Table not an manipulation interface');
		}

		$record = $table->getRecord();
		$record->setId(5);
		$record->setUserId(2);
		$record->setTitle('foobar');
		$record->setDate(new DateTime());

		$table->create($record);

		$this->assertEquals(5, $table->getLastInsertId());

		$row = $table->getOneById(5);

		$this->assertInstanceOf('PSX\Data\RecordInterface', $row);
		$this->assertEquals(5, $row->getId());
		$this->assertEquals(2, $row->getUserId());
		$this->assertEquals('foobar', $row->getTitle());
		$this->assertInstanceOf('DateTime', $row->getDate());
	}

	/**
	 * @expectedException PSX\Exception
	 */
	public function testCreateEmpty()
	{
		$table = $this->getTable();

		if(!$table instanceof TableManipulationInterface)
		{
			$this->markTestSkipped('Table not an manipulation interface');
		}

		$table->create(array());
	}

	public function testUpdate()
	{
		$table = $this->getTable();

		if(!$table instanceof TableManipulationInterface)
		{
			$this->markTestSkipped('Table not an manipulation interface');
		}

		$row = $table->getOneById(1);
		$row->setUserId(2);
		$row->setTitle('foobar');
		$row->setDate(new DateTime());

		$table->update($row);

		$row = $table->getOneById(1);

		$this->assertEquals(2, $row->getUserId());
		$this->assertEquals('foobar', $row->getTitle());
		$this->assertInstanceOf('DateTime', $row->getDate());
	}

	/**
	 * @expectedException PSX\Exception
	 */
	public function testUpdateEmpty()
	{
		$table = $this->getTable();

		if(!$table instanceof TableManipulationInterface)
		{
			$this->markTestSkipped('Table not an manipulation interface');
		}

		$table->update(array());
	}

	public function testDelete()
	{
		$table = $this->getTable();

		if(!$table instanceof TableManipulationInterface)
		{
			$this->markTestSkipped('Table not an manipulation interface');
		}

		$row = $table->getOneById(1);

		$table->delete($row);

		$row = $table->getOneById(1);

		$this->assertEmpty($row);
	}

	/**
	 * @expectedException PSX\Exception
	 */
	public function testDeleteEmpty()
	{
		$table = $this->getTable();

		if(!$table instanceof TableManipulationInterface)
		{
			$this->markTestSkipped('Table not an manipulation interface');
		}

		$table->delete(array());
	}
}
