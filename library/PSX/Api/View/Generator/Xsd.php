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

namespace PSX\Api\View\Generator;

use DOMDocument;
use DOMElement;
use PSX\Api\View;
use PSX\Api\View\GeneratorAbstract;
use PSX\Data\Schema\GeneratorInterface;
use PSX\Data\Schema\Property;
use PSX\Data\Schema\PropertyInterface;
use PSX\Data\Schema\PropertySimpleAbstract;
use PSX\Data\Schema\Generator\Xsd as XsdGenerator;

/**
 * Xsd
 *
 * @see     http://www.w3.org/XML/Schema
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Xsd extends GeneratorAbstract
{
	protected $targetNamespace;

	public function __construct($targetNamespace)
	{
		$this->targetNamespace = $targetNamespace;
	}

	public function generate(View $view)
	{
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->formatOutput = true;

		$schema = $dom->createElementNS('http://www.w3.org/2001/XMLSchema', 'xs:schema');
		$schema->setAttribute('xmlns:tns', $this->targetNamespace);
		$schema->setAttribute('targetNamespace', $this->targetNamespace);
		$schema->setAttribute('elementFormDefault', 'qualified');

		$this->appendSchema($schema, $view);

		$dom->appendChild($schema);

		return $dom->saveXML();
	}

	public function appendSchema(DOMElement $element, View $view)
	{
		$xsdGenerator = new XsdGenerator($this->targetNamespace);

		foreach($view as $type => $schema)
		{
			$dom = new DOMDocument();
			$dom->loadXML($xsdGenerator->generate($schema));

			foreach($dom->documentElement->childNodes as $childNode)
			{
				if($childNode->nodeType == XML_ELEMENT_NODE)
				{
					$name = $childNode->getAttribute('name');

					if(!empty($name))
					{
						if($childNode->nodeName == 'xs:element')
						{
							$childNode->setAttribute('name', $this->getPrefix($type));

							$element->appendChild($element->ownerDocument->importNode($childNode, true));
						}
						else
						{
							$element->appendChild($element->ownerDocument->importNode($childNode, true));
						}
					}
				}
			}
		}

		// add missing methods with an void type
		$methods = View::getMethods();

		foreach($methods as $method => $methodName)
		{
			if($view->has($method))
			{
				// request
				if(!$view->has($method | View::TYPE_REQUEST))
				{
					$el = $element->ownerDocument->createElementNS('http://www.w3.org/2001/XMLSchema', 'xs:element');
					$el->setAttribute('name', $this->getPrefix($method | View::TYPE_REQUEST));
					$el->setAttribute('type', 'tns:' . 'void');

					$element->appendChild($el);
				}

				// response
				if(!$view->has($method | View::TYPE_RESPONSE))
				{
					$el = $element->ownerDocument->createElementNS('http://www.w3.org/2001/XMLSchema', 'xs:element');
					$el->setAttribute('name', $this->getPrefix($method | View::TYPE_RESPONSE));
					$el->setAttribute('type', 'tns:' . 'void');

					$element->appendChild($el);
				}
			}
		}

		// add default schema types
		$this->appendDefaultSchema($element);
	}

	protected function appendDefaultSchema(DOMElement $element)
	{
		// fault element
		$complexType = $element->ownerDocument->createElementNS('http://www.w3.org/2001/XMLSchema', 'xs:complexType');
		$complexType->setAttribute('name', 'fault');

		$sequence = $element->ownerDocument->createElementNS('http://www.w3.org/2001/XMLSchema', 'xs:sequence');

		$el = $element->ownerDocument->createElementNS('http://www.w3.org/2001/XMLSchema', 'xs:element');
		$el->setAttribute('name', 'success');
		$el->setAttribute('type', 'xs:boolean');
		$el->setAttribute('minOccurs', 1);
		$el->setAttribute('maxOccurs', 1);
		$sequence->appendChild($el);

		$el = $element->ownerDocument->createElementNS('http://www.w3.org/2001/XMLSchema', 'xs:element');
		$el->setAttribute('name', 'title');
		$el->setAttribute('type', 'xs:string');
		$el->setAttribute('minOccurs', 0);
		$el->setAttribute('maxOccurs', 1);
		$sequence->appendChild($el);

		$el = $element->ownerDocument->createElementNS('http://www.w3.org/2001/XMLSchema', 'xs:element');
		$el->setAttribute('name', 'message');
		$el->setAttribute('type', 'xs:string');
		$el->setAttribute('minOccurs', 1);
		$el->setAttribute('maxOccurs', 1);
		$sequence->appendChild($el);

		$el = $element->ownerDocument->createElementNS('http://www.w3.org/2001/XMLSchema', 'xs:element');
		$el->setAttribute('name', 'trace');
		$el->setAttribute('type', 'xs:string');
		$el->setAttribute('minOccurs', 0);
		$el->setAttribute('maxOccurs', 1);
		$sequence->appendChild($el);

		$el = $element->ownerDocument->createElementNS('http://www.w3.org/2001/XMLSchema', 'xs:element');
		$el->setAttribute('name', 'context');
		$el->setAttribute('type', 'xs:string');
		$el->setAttribute('minOccurs', 0);
		$el->setAttribute('maxOccurs', 1);
		$sequence->appendChild($el);

		$complexType->appendChild($sequence);

		$element->appendChild($complexType);

		// void element
		$complexType = $element->ownerDocument->createElementNS('http://www.w3.org/2001/XMLSchema', 'xs:complexType');
		$complexType->setAttribute('name', 'void');

		$sequence = $element->ownerDocument->createElementNS('http://www.w3.org/2001/XMLSchema', 'xs:sequence');

		$complexType->appendChild($sequence);

		$element->appendChild($complexType);

		// exception element
		$el = $element->ownerDocument->createElementNS('http://www.w3.org/2001/XMLSchema', 'xs:element');
		$el->setAttribute('name', 'exceptionRecord');
		$el->setAttribute('type', 'tns:' . 'fault');
		$element->appendChild($el);
	}
}
