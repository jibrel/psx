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

namespace PSX\Data\Schema\Generator;

/**
 * HtmlTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class HtmlTest extends GeneratorTestCase
{
	public function testGenerate()
	{
		$generator = new Html();
		$result    = $generator->generate($this->getSchema());

		$expect = <<<'HTML'
<div>
	<div id="type-0181a2f995053a0c040d39abe18f6bc7" class="type">
		<h1>news</h1>
		<div class="type-description">An general news entry</div>
		<table class="table type-properties">
			<colgroup>
				<col width="20%" />
				<col width="20%" />
				<col width="40%" />
				<col width="20%" />
			</colgroup>
			<thead>
				<tr>
					<th>Property</th>
					<th>Type</th>
					<th>Description</th>
					<th>Constraints</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<span class="property-name property-optional">tags</span>
					</td>
					<td>
						<span class="property-type type-array">Array&lt;<span class="property-type property-type-string">String</span>&gt;</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td/>
				</tr>
				<tr>
					<td>
						<span class="property-name property-required">receiver</span>
					</td>
					<td>
						<span class="property-type type-array">Array&lt;<span class="property-type type-object">
								<a href="#type-80f95cf5fd279866b5859c275abd7fa2">author</a>
							</span>&gt;</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td/>
				</tr>
				<tr>
					<td>
						<span class="property-name property-optional">read</span>
					</td>
					<td>
						<span class="property-type property-type-boolean">Boolean</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td/>
				</tr>
				<tr>
					<td>
						<span class="property-name property-required">author</span>
					</td>
					<td>
						<span class="property-type type-object">
							<a href="#type-80f95cf5fd279866b5859c275abd7fa2">author</a>
						</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td/>
				</tr>
				<tr>
					<td>
						<span class="property-name property-optional">sendDate</span>
					</td>
					<td>
						<span class="property-type property-type-date">
							<a href="http://tools.ietf.org/html/rfc3339#section-5.6" title="RFC3339">Date</a>
						</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td/>
				</tr>
				<tr>
					<td>
						<span class="property-name property-optional">readDate</span>
					</td>
					<td>
						<span class="property-type property-type-datetime">
							<a href="http://tools.ietf.org/html/rfc3339#section-5.6" title="RFC3339">DateTime</a>
						</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td/>
				</tr>
				<tr>
					<td>
						<span class="property-name property-optional">expires</span>
					</td>
					<td>
						<span class="property-type property-type-duration">
							<span title="ISO 8601">Duration</span>
						</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td/>
				</tr>
				<tr>
					<td>
						<span class="property-name property-required">price</span>
					</td>
					<td>
						<span class="property-type property-type-float">Float</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td>
						<dl class="property-constraint">
							<dt>Minimum</dt>
							<dd>
								<span class="constraint-minimum">1</span>
							</dd>
							<dt>Maximum</dt>
							<dd>
								<span class="constraint-maximum">100</span>
							</dd>
						</dl>
					</td>
				</tr>
				<tr>
					<td>
						<span class="property-name property-optional">rating</span>
					</td>
					<td>
						<span class="property-type property-type-integer">Integer</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td>
						<dl class="property-constraint">
							<dt>Minimum</dt>
							<dd>
								<span class="constraint-minimum">1</span>
							</dd>
							<dt>Maximum</dt>
							<dd>
								<span class="constraint-maximum">5</span>
							</dd>
						</dl>
					</td>
				</tr>
				<tr>
					<td>
						<span class="property-name property-required">content</span>
					</td>
					<td>
						<span class="property-type property-type-string">String</span>
					</td>
					<td>
						<span class="property-description">Contains the main content of the news entry</span>
					</td>
					<td>
						<dl class="property-constraint">
							<dt>Minimum</dt>
							<dd>
								<span class="constraint-minimum">3</span>
							</dd>
							<dt>Maximum</dt>
							<dd>
								<span class="constraint-maximum">512</span>
							</dd>
						</dl>
					</td>
				</tr>
				<tr>
					<td>
						<span class="property-name property-optional">question</span>
					</td>
					<td>
						<span class="property-type property-type-string">String</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td>
						<dl class="property-constraint">
							<dt>Enumeration</dt>
							<dd>
								<span class="constraint-enumeration">
									<ul class="property-enumeration">
										<li>
											<span class="constraint-enumeration-value">foo</span>
										</li>
										<li>
											<span class="constraint-enumeration-value">bar</span>
										</li>
									</ul>
								</span>
							</dd>
						</dl>
					</td>
				</tr>
				<tr>
					<td>
						<span class="property-name property-optional">coffeeTime</span>
					</td>
					<td>
						<span class="property-type property-type-time">Time</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td/>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="type-80f95cf5fd279866b5859c275abd7fa2" class="type">
		<h1>author</h1>
		<div class="type-description">An simple author element with some description</div>
		<table class="table type-properties">
			<colgroup>
				<col width="20%" />
				<col width="20%" />
				<col width="40%" />
				<col width="20%" />
			</colgroup>
			<thead>
				<tr>
					<th>Property</th>
					<th>Type</th>
					<th>Description</th>
					<th>Constraints</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<span class="property-name property-required">title</span>
					</td>
					<td>
						<span class="property-type property-type-string">String</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td>
						<dl class="property-constraint">
							<dt>Pattern</dt>
							<dd>
								<span class="constraint-pattern">[A-z]{3,16}</span>
							</dd>
						</dl>
					</td>
				</tr>
				<tr>
					<td>
						<span class="property-name property-optional">email</span>
					</td>
					<td>
						<span class="property-type property-type-string">String</span>
					</td>
					<td>
						<span class="property-description">We will send no spam to this addresss</span>
					</td>
					<td/>
				</tr>
				<tr>
					<td>
						<span class="property-name property-optional">categories</span>
					</td>
					<td>
						<span class="property-type type-array">Array&lt;<span class="property-type property-type-string">String</span>&gt;</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td/>
				</tr>
				<tr>
					<td>
						<span class="property-name property-optional">locations</span>
					</td>
					<td>
						<span class="property-type type-array">Array&lt;<span class="property-type type-object">
								<a href="#type-93ef595df6d9e735702cba3611adba27">location</a>
							</span>&gt;</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td/>
				</tr>
				<tr>
					<td>
						<span class="property-name property-optional">origin</span>
					</td>
					<td>
						<span class="property-type type-object">
							<a href="#type-93ef595df6d9e735702cba3611adba27">origin</a>
						</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td/>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="type-93ef595df6d9e735702cba3611adba27" class="type">
		<h1>location</h1>
		<div class="type-description">Location of the person</div>
		<table class="table type-properties">
			<colgroup>
				<col width="20%" />
				<col width="20%" />
				<col width="40%" />
				<col width="20%" />
			</colgroup>
			<thead>
				<tr>
					<th>Property</th>
					<th>Type</th>
					<th>Description</th>
					<th>Constraints</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<span class="property-name property-optional">lat</span>
					</td>
					<td>
						<span class="property-type property-type-integer">Integer</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td/>
				</tr>
				<tr>
					<td>
						<span class="property-name property-optional">long</span>
					</td>
					<td>
						<span class="property-type property-type-integer">Integer</span>
					</td>
					<td>
						<span class="property-description"/>
					</td>
					<td/>
				</tr>
			</tbody>
		</table>
	</div>
</div>
HTML;

		$this->assertXmlStringEqualsXmlString($expect, '<div>' . $result . '</div>');
	}
}