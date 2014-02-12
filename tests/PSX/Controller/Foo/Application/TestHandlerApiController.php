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

namespace PSX\Controller\Foo\Application;

use PSX\Controller\HandlerApiAbstract;
use PSX\Data\RecordAbstract;
use PSX\Filter;
use PSX\Filter\FilterDefinition;
use PSX\Filter\Definition\Property;
use PSX\Handler\Database\TestHandler;
use PSX\Sql;
use PSX\Sql\TableManager;
use PSX\Validate;

/**
 * TestHandlerApiController
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class TestHandlerApiController extends HandlerApiAbstract
{
	protected function getFilterDefinition()
	{
		return new FilterDefinition(new Validate(), array(
			new Property('id', Validate::TYPE_INTEGER),
			new Property('userId', Validate::TYPE_INTEGER),
			new Property('title', Validate::TYPE_STRING, array(new Filter\Length(3, 16))),
			new Property('date', Validate::TYPE_STRING, array(new Filter\DateTime())),
		));
	}

	protected function getDefaultHandler()
	{
		return new TestHandler(new TableManager($this->getSql()));
	}
}