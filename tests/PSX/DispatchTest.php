<?php
/*
 * psx
 * A object oriented and modular based PHP framework for developing
 * dynamic web applications. For the current version and informations
 * visit <http://phpsx.org>
 *
 * Copyright (c) 2010-2014 Christoph Kappestein <k42b3.x@gmail.com>
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

namespace PSX;

use PSX\Dispatch;
use PSX\Dispatch\ControllerFactory;
use PSX\Dispatch\VoidSender;
use PSX\Http\Request;
use PSX\Http\Response;
use PSX\Http\Stream\StringStream;
use PSX\Loader;
use PSX\Loader\Location;
use PSX\Loader\LocationFinder\CallbackMethod;
use PSX\ModuleAbstract;
use PSX\Template;

/**
 * DispatchTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class DispatchTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
	}

	protected function tearDown()
	{
	}

	public function testRoute()
	{
		$locationFinder = new CallbackMethod(function($method, $path){

			return new Location(array(Location::KEY_SOURCE => 'PSX\Dispatch\DummyController'));

		});

		$loader   = new Loader($locationFinder, getContainer()->get('loader_callback_resolver'));
		$dispatch = new Dispatch(getContainer()->get('config'), $loader, new ControllerFactory(getContainer()), new VoidSender());
		$request  = new Request(new Url('http://localhost.com'), 'GET');
		$response = new Response();
		$response->setBody(new StringStream());

		$dispatch->route($request, $response);

		$this->assertEquals('foo', (string) $response->getBody());
	}

	public function testRouteException()
	{
		$locationFinder = new CallbackMethod(function($method, $path){

			return new Location(array(Location::KEY_SOURCE => 'PSX\Dispatch\ExceptionController'));

		});

		getContainer()->get('config')->set('psx_debug', false);

		$loader   = new Loader($locationFinder, getContainer()->get('loader_callback_resolver'));
		$dispatch = new Dispatch(getContainer()->get('config'), $loader, new ControllerFactory(getContainer()), new VoidSender());
		$request  = new Request(new Url('http://localhost.com'), 'GET');
		$response = new Response();
		$response->setBody(new StringStream());

		$dispatch->route($request, $response);

		$expect = <<<JSON
{
	"success": false,
	"message": "The server encountered an internal error and was unable to complete your request.",
	"title": "Internal Server Error"
}
JSON;

		$this->assertJsonStringEqualsJsonString($expect, (string) $response->getBody());
	}

	public function testRouteExceptionHtml()
	{
		$locationFinder = new CallbackMethod(function($method, $path){

			return new Location(array(Location::KEY_SOURCE => 'PSX\Dispatch\ExceptionController'));

		});

		getContainer()->get('config')->set('psx_debug', false);
		getContainer()->get('template')->set(null);

		$loader   = new Loader($locationFinder, getContainer()->get('loader_callback_resolver'));
		$dispatch = new Dispatch(getContainer()->get('config'), $loader, new ControllerFactory(getContainer()), new VoidSender());
		$request  = new Request(new Url('http://localhost.com'), 'GET', array('Accept' => 'text/html'));
		$response = new Response();
		$response->setBody(new StringStream());

		$dispatch->route($request, $response);

		$expect = <<<HTML
<!DOCTYPE>
<html>
<head>
	<title>Internal Server Error</title>
	<style type="text/css">
	body
	{
		margin:0px;
		font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;
		font-size:14px;
		line-height:1.42857143;
	}

	.title
	{
		background-color:#f2dede;
		color:#a94442;
		padding:8px;
		padding-left:32px;
	}

	.title h1
	{
		margin:0px;
	}

	.message
	{
		background-color:#333;
		color:#fff;
		padding:8px;
		padding-left:32px;
	}

	.trace
	{
		background-color:#ececec;
		padding:8px;
		padding-left:32px;
		margin-bottom:8px;
	}

	.trace pre
	{
		margin:0px;
	}

	.context
	{
		background-color:#ececec;
		padding:8px;
		padding-left:32px;
	}

	.context pre
	{
		margin:0px;
	}
	</style>
</head>

<body>

<div class="title">
	<h1>Internal Server Error</h1>
</div>

<div class="message">
	The server encountered an internal error and was unable to complete your request.</div>



</body>
</html>

HTML;

		$this->assertEquals($expect, (string) $response->getBody());
	}

	public function testRouteExceptionXml()
	{
		$locationFinder = new CallbackMethod(function($method, $path){

			return new Location(array(Location::KEY_SOURCE => 'PSX\Dispatch\ExceptionController'));

		});

		getContainer()->get('config')->set('psx_debug', false);

		$loader   = new Loader($locationFinder, getContainer()->get('loader_callback_resolver'));
		$dispatch = new Dispatch(getContainer()->get('config'), $loader, new ControllerFactory(getContainer()), new VoidSender());
		$request  = new Request(new Url('http://localhost.com'), 'GET', array('Accept' => 'application/xml'));
		$response = new Response();
		$response->setBody(new StringStream());

		$dispatch->route($request, $response);

		$expect = <<<XML
<?xml version="1.0"?>
<record>
	<success>false</success>
	<title>Internal Server Error</title>
	<message>The server encountered an internal error and was unable to complete your request.</message>
</record>
XML;

		$this->assertXmlStringEqualsXmlString($expect, (string) $response->getBody());
	}

	public function testRouteExceptionJson()
	{
		$locationFinder = new CallbackMethod(function($method, $path){

			return new Location(array(Location::KEY_SOURCE => 'PSX\Dispatch\ExceptionController'));

		});

		getContainer()->get('config')->set('psx_debug', false);

		$loader   = new Loader($locationFinder, getContainer()->get('loader_callback_resolver'));
		$dispatch = new Dispatch(getContainer()->get('config'), $loader, new ControllerFactory(getContainer()), new VoidSender());
		$request  = new Request(new Url('http://localhost.com'), 'GET', array('Accept' => 'application/json'));
		$response = new Response();
		$response->setBody(new StringStream());

		$dispatch->route($request, $response);

		$expect = <<<JSON
{
	"success": false,
	"message": "The server encountered an internal error and was unable to complete your request.",
	"title": "Internal Server Error"
}
JSON;

		$this->assertJsonStringEqualsJsonString($expect, (string) $response->getBody());
	}

	public function testRouteExceptionXhr()
	{
		$locationFinder = new CallbackMethod(function($method, $path){

			return new Location(array(Location::KEY_SOURCE => 'PSX\Dispatch\ExceptionController'));

		});

		getContainer()->get('config')->set('psx_debug', false);

		$loader   = new Loader($locationFinder, getContainer()->get('loader_callback_resolver'));
		$dispatch = new Dispatch(getContainer()->get('config'), $loader, new ControllerFactory(getContainer()), new VoidSender());
		$request  = new Request(new Url('http://localhost.com'), 'GET', array('X-Requested-With' => 'XMLHttpRequest'));
		$response = new Response();
		$response->setBody(new StringStream());

		$dispatch->route($request, $response);

		$expect = <<<JSON
{
	"success": false,
	"message": "The server encountered an internal error and was unable to complete your request.",
	"title": "Internal Server Error"
}
JSON;

		$this->assertJsonStringEqualsJsonString($expect, (string) $response->getBody());
	}

	public function testRouteRedirectException()
	{
		$locationFinder = new CallbackMethod(function($method, $path){

			return new Location(array(Location::KEY_SOURCE => 'PSX\Dispatch\RedirectExceptionController'));

		});

		getContainer()->get('config')->set('psx_debug', false);

		$loader   = new Loader($locationFinder, getContainer()->get('loader_callback_resolver'));
		$dispatch = new Dispatch(getContainer()->get('config'), $loader, new ControllerFactory(getContainer()), new VoidSender());
		$request  = new Request(new Url('http://localhost.com'), 'GET');
		$response = new Response();
		$response->setBody(new StringStream());

		$dispatch->route($request, $response);

		$this->assertEquals(307, $response->getStatusCode());
		$this->assertEquals('http://localhost.com/foobar', $response->getHeader('Location'));
	}
}
