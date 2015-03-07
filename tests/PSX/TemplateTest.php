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

namespace PSX;

use PSX\Data\Record;
use PSX\Template\ErrorException;

/**
 * TemplateTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class TemplateTest extends \PHPUnit_Framework_TestCase
{
	public function testTransform()
	{
		$template = new Template();

		$template->setDir('tests/PSX/Template/files');
		$template->set('foo.htm');

		$this->assertEquals('tests/PSX/Template/files', $template->getDir());
		$this->assertEquals('foo.htm', $template->get());
		$this->assertTrue($template->hasFile());
		$this->assertFalse($template->fileExists());
		$this->assertEquals('tests/PSX/Template/files/foo.htm', $template->getFile());

		$template->assign('foo', 'bar');

		$content = $template->transform();

		$this->assertEquals('Hello bar', $content);
	}

	public function testFallbackTemplate()
	{
		$template = new Template();
		$template->assign('key_1', 'bar');
		$template->assign('key_2', array(1, 2));
		$template->assign('key_3', array(new Record('foo', array('bar' => 'foo'))));
		$template->assign('key_4', new Record('foo', array('bar' => 'foo')));
		$template->assign('key_5', new \DateTime('2014-12-27'));
		$template->assign('key_6', new StringObject());
		$template->assign('key_7', false);
		$template->assign('key_8', true);

		$content = $template->transform();

		preg_match('/<body>(.*)<\/body>/ims', $content, $matches);

		$this->assertArrayHasKey(1, $matches);
		$this->assertXmlStringEqualsXmlString($this->getExpectedFallbackTemplate(), $matches[1]);
	}

	public function testTransformException()
	{
		$template = new Template();
		$template->setDir('tests/PSX/Template/files');
		$template->set('error.htm');

		try
		{
			$template->transform();

			$this->fail('Must throw an excetion');
		}
		catch(ErrorException $e)
		{
			$this->assertInstanceOf('RuntimeException', $e->getOriginException());
			$this->assertEquals('tests/PSX/Template/files/error.htm', $e->getTemplateFile());
			$this->assertEquals('foobar', $e->getRenderedHtml());
		}
	}

	protected function getExpectedFallbackTemplate()
	{
		return <<<HTML
<dl>
	<dt>key_1</dt>
	<dd class="scalar">bar</dd>
	<dt>key_2</dt>
	<dd class="array">
		<ul>
			<li>1</li>
			<li>2</li>
		</ul>
	</dd>
	<dt>key_3</dt>
	<dd class="array">
		<ul>
			<li>
				<dl>
					<dt>bar</dt>
					<dd class="scalar">foo</dd>
				</dl>
			</li>
		</ul>
	</dd>
	<dt>key_4</dt>
	<dd class="object">
		<dl>
			<dt>bar</dt>
			<dd class="scalar">foo</dd>
		</dl>
	</dd>
	<dt>key_5</dt>
	<dd class="scalar">2014-12-27T00:00:00+00:00</dd>
	<dt>key_6</dt>
	<dd class="scalar">foo</dd>
	<dt>key_7</dt>
	<dd class="scalar">0</dd>
	<dt>key_8</dt>
	<dd class="scalar">1</dd>
</dl>
HTML;
	}
}

class StringObject
{
	public function __toString()
	{
		return 'foo';
	}
}
