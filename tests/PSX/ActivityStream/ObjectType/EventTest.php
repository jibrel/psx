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

namespace PSX\ActivityStream\ObjectType;

use PSX\ActivityStream\Object;
use PSX\Data\SerializeTestAbstract;
use PSX\DateTime;

/**
 * EventTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class EventTest extends SerializeTestAbstract
{
	public function testEvent()
	{
		$person = new Object();
		$person->setObjectType('person');
		$person->setDisplayName('Joe');

		$persons = new Collection();
		$persons->add($person);

		$event = new Event();
		$event->setDisplayName('Meeting with Joe');
		$event->setStartTime(new DateTime('2012-12-12T12:00:00Z'));
		$event->setEndTime(new DateTime('2012-12-12T13:00:00Z'));
		$event->setValidFrom(new DateTime('2012-12-12T13:00:00Z'));
		$event->setValidAfter(new DateTime('2012-12-12T13:00:00Z'));
		$event->setValidUntil(new DateTime('2012-12-12T13:00:00Z'));
		$event->setValidBefore(new DateTime('2012-12-12T13:00:00Z'));
		$event->setAttendedBy($persons);
		$event->setAttending($persons);
		$event->setInvited($persons);
		$event->setMaybeAttending($persons);
		$event->setNotAttendedBy($persons);
		$event->setNotAttending($persons);

		$content = <<<JSON
{
  "attendedBy": {"items": [{
        "objectType": "person",
        "displayName": "Joe"
      }]},
  "attending": {"items": [{
        "objectType": "person",
        "displayName": "Joe"
      }]},
  "endTime": "2012-12-12T13:00:00Z",
  "invited": {"items": [{
        "objectType": "person",
        "displayName": "Joe"
      }]},
  "maybeAttending": {"items": [{
        "objectType": "person",
        "displayName": "Joe"
      }]},
  "notAttendedBy": {"items": [{
        "objectType": "person",
        "displayName": "Joe"
      }]},
  "notAttending": {"items": [{
        "objectType": "person",
        "displayName": "Joe"
      }]},
  "startTime": "2012-12-12T12:00:00Z",
  "validFrom": "2012-12-12T13:00:00Z",
  "validAfter": "2012-12-12T13:00:00Z",
  "validUntil": "2012-12-12T13:00:00Z",
  "validBefore": "2012-12-12T13:00:00Z",
  "objectType": "event",
  "displayName": "Meeting with Joe"
}
JSON;

		$this->assertRecordEqualsContent($event, $content);

		$this->assertEquals('Meeting with Joe', $event->getDisplayName());
		$this->assertEquals(new DateTime('2012-12-12T12:00:00Z'), $event->getStartTime());
		$this->assertEquals(new DateTime('2012-12-12T13:00:00Z'), $event->getEndTime());
		$this->assertEquals(new DateTime('2012-12-12T13:00:00Z'), $event->getValidFrom());
		$this->assertEquals(new DateTime('2012-12-12T13:00:00Z'), $event->getValidAfter());
		$this->assertEquals(new DateTime('2012-12-12T13:00:00Z'), $event->getValidUntil());
		$this->assertEquals(new DateTime('2012-12-12T13:00:00Z'), $event->getValidBefore());
		$this->assertEquals($persons, $event->getAttendedBy());
		$this->assertEquals($persons, $event->getAttending());
		$this->assertEquals($persons, $event->getInvited());
		$this->assertEquals($persons, $event->getMaybeAttending());
		$this->assertEquals($persons, $event->getNotAttendedBy());
		$this->assertEquals($persons, $event->getNotAttending());
	}
}
