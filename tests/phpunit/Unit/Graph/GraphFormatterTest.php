<?php

namespace SRF\Tests\Unit\Formats;

use SRF\Graph\GraphFormatter;
use SRF\Graph\GraphNode;


/**
 * @covers \SRF\Graph\GraphFormatter
 * @group semantic-result-formats
 *
 * @license GNU GPL v2+
 *
 * @author Sebastian Schmid (gesinn.it)
 */
class GraphFormatterTest extends \PHPUnit_Framework_TestCase {

	/*
	* @see https://www.semantic-mediawiki.org/wiki/Help:Graph_format
	*/
	private $options = [
		'graphName' => "Unit Test",
		'graphSize' => "100",
		'nodeShape' => "rect",
		'nodeLabel' => "displaytitle",
		'rankDir' => "TB",
		'wordWrapLimit' => "20",
		'parentRelation' => "parent",
		'enableGraphLink' => "yes",
		'showGraphLabel' => "yes",
		'showGraphColor' => "yes",
		'showGraphLegend' => "yes",
	];

	private $graphFormatter;

	private $nodes = [];

	protected function setUp() {
		parent::setUp();

		$this->graphFormatter = new GraphFormatter( $this->options );

		$this->nodes = [];

		$node1 = new GraphNode( 'Team:Alpha' );
		$node1->setLabel( "Alpha" );
		$node1->addParentNode( "Casted", "Person:Alexander Gesinn" );
		$this->nodes[] = $node1;

		$node2 = new GraphNode( 'Team:Beta' );
		$node2->setLabel( "Beta" );
		$node2->addParentNode( "Casted", "Person:Sebastian Schmid" );
		$node2->addParentNode( "Casted", "Person:Alexander Gesinn" );
		$node2->addParentNode( "Part of Team ", "Team:Alpha" );
		$this->nodes[] = $node2;

		$this->graphFormatter->buildGraph( $this->nodes );
	}

	public function testCanConstruct() {
		$this->assertInstanceOf( GraphFormatter::class, new GraphFormatter( $this->options ) );
	}

	public function testGetWordWrappedText() {
		$text = 'Lorem ipsum dolor sit amet';
		$expected = 'Lorem \nipsum \ndolor sit \namet';

		$this->assertEquals( GraphFormatter::getWordWrappedText( $text, 10 ), $expected );
	}

	public function testGetGraphLegend() {
		$expected =
			'<div class="graphlegend"><div class="graphlegenditem" style="color: black">black: Casted</div><div class="graphlegenditem" style="color: red">red: Part of Team </div></div>';

		$this->assertEquals( $this->graphFormatter->getGraphLegend(), $expected );
	}
}