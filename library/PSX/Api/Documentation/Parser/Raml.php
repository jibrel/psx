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

namespace PSX\Api\Documentation\Parser;

use PSX\Api\Documentation;
use PSX\Api\Documentation\ParserInterface;
use PSX\Api\Resource;
use PSX\Api\Resource\Generator\Swagger;
use PSX\Data\Schema\Property;
use PSX\Data\Schema\Parser\JsonSchema;
use PSX\Data\SchemaInterface;
use Symfony\Component\Yaml\Parser;

/**
 * Raml
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link	http://phpsx.org
 */
class Raml implements ParserInterface
{
	public function parse($file, $path)
	{
		$path     = Swagger::transformRoute($path);
		$resource = new Resource(Resource::STATUS_ACTIVE, $path);
		$parser   = new Parser();
		$data     = $parser->parse(file_get_contents($file));

		if(isset($data[$path]) && is_array($data[$path]))
		{
			$mergedTrait = array();
			if(isset($data[$path]['is']) && is_array($data[$path]['is']))
			{
				foreach($data[$path]['is'] as $traitName)
				{
					$trait = $this->getTrait($data, $traitName);
					if(is_array($trait))
					{
						$mergedTrait = array_merge($mergedTrait, $trait);
					}
				}
			}

			if(isset($data[$path]['displayName']))
			{
				$resource->setTitle($data[$path]['displayName']);
			}

			if(isset($data[$path]['description']))
			{
				$resource->setDescription($data[$path]['description']);
			}

			$this->parseUriParameters($resource, $data[$path]);

			foreach($data[$path] as $methodName => $row)
			{
				if(in_array($methodName, ['get', 'post', 'put', 'delete']) && is_array($row))
				{
					if(!empty($mergedTrait))
					{
						$row = array_merge_recursive($row, $mergedTrait);
					}

					$method = Resource\Factory::getMethod(strtoupper($methodName));

					$this->parseQueryParameters($method, $row);
					$this->parseRequest($method, $row, $file);
					$this->parseResponses($method, $row, $file);

					$resource->addMethod($method);
				}
			}
		}

		$version = $this->getNormalizedVersion($data);
		$title   = null;

		if(isset($data['title']))
		{
			$title = $data['title'];
		}

		$doc = new Documentation\Version($title);
		$doc->addResource($version, $resource);

		return $doc;
	}

	protected function getTrait(array $data, $name)
	{
		if(isset($data['traits']) && is_array($data['traits']))
		{
			foreach($data['traits'] as $row)
			{
				if(isset($row[$name]))
				{
					return $row[$name];
				}
			}
		}

		return null;
	}

	protected function parseUriParameters(Resource $resource, array $data)
	{
		if(isset($data['uriParameters']) && is_array($data['uriParameters']))
		{
			foreach($data['uriParameters'] as $name => $definition)
			{
				if(!empty($name) && is_array($definition))
				{
					$resource->addPathParameter($this->getParameter($name, $definition));
				}
			}
		}
	}

	protected function parseQueryParameters(Resource\MethodAbstract $method, array $data)
	{
		if(isset($data['queryParameters']) && is_array($data['queryParameters']))
		{
			foreach($data['queryParameters'] as $name => $definition)
			{
				if(!empty($name) && is_array($definition))
				{
					$method->addQueryParameter($this->getParameter($name, $definition));
				}
			}
		}
	}

	protected function getParameter($name, array $definition)
	{
		$type     = isset($definition['type']) ? $definition['type'] : 'string';
		$property = $this->getPropertyType($type, $name);

		if(isset($definition['description']))
		{
			$property->setDescription($definition['description']);
		}

		if(isset($definition['required']))
		{
			$property->setRequired((bool) $definition['required']);
		}

		if(isset($definition['enum']) && is_array($definition['enum']))
		{
			$property->setEnumeration($definition['enum']);
		}

		if(isset($definition['pattern']))
		{
			$property->setPattern($definition['pattern']);
		}

		if(isset($definition['minLength']))
		{
			$property->setMinLength($definition['minLength']);
		}

		if(isset($definition['maxLength']))
		{
			$property->setMaxLength($definition['maxLength']);
		}

		if(isset($definition['minimum']))
		{
			$property->setMin($definition['minimum']);
		}

		if(isset($definition['maximum']))
		{
			$property->setMax($definition['maximum']);
		}

		return $property;
	}

	protected function parseRequest(Resource\MethodAbstract $method, array $data, $file)
	{
		if(isset($data['body']) && is_array($data['body']))
		{
			$schema = $this->getBodySchema($data['body'], $file);

			if($schema instanceof SchemaInterface)
			{
				$method->setRequest($schema);
			}
		}
	}

	protected function parseResponses(Resource\MethodAbstract $method, array $data, $file)
	{
		if(isset($data['responses']) && is_array($data['responses']))
		{
			foreach($data['responses'] as $statusCode => $row)
			{
				if(isset($row['body']) && is_array($row['body']))
				{
					$schema = $this->getBodySchema($row['body'], $file);

					if($schema instanceof SchemaInterface)
					{
						$method->addResponse($statusCode, $schema);
					}
				}
			}
		}
	}

	protected function getBodySchema(array $body, $file)
	{
		$parser = new JsonSchema();

		foreach($body as $contentType => $row)
		{
			if($contentType == 'application/json' && isset($row['schema']) && is_string($row['schema']))
			{
				if(substr($row['schema'], 0, 8) == '!include')
				{
					$dir    = pathinfo($file, PATHINFO_DIRNAME);
					$file   = trim(substr($row['schema'], 8));
					$path   = $dir . DIRECTORY_SEPARATOR . $file;

					if(is_file($path))
					{
						$schema = file_get_contents($dir . DIRECTORY_SEPARATOR . $file);
					}
					else
					{
						throw new \RuntimeException('Can not import file ' . $path);
					}
				}
				else
				{
					$schema = $row['schema'];
				}

				return $parser->parse($schema);
			}
		}

		return null;
	}

	protected function getPropertyType($type, $name)
	{
		switch($type)
		{
			case 'integer':
				return new Property\Integer($name);

			case 'number':
				return new Property\Float($name);

			case 'date':
				return new Property\DateTime($name);

			case 'boolean':
				return new Property\Boolean($name);

			case 'string':
			default:
				return new Property\String($name);
		}
	}

	protected function getNormalizedVersion(array $data)
	{
		if(isset($data['version']))
		{
			$version = (int) ltrim($data['version'], 'v');
		}

		if(empty($version))
		{
			$version = 1;
		}

		return $version;
	}
}
