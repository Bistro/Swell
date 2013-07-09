<?php

use Bistro\Swell\Hash;

class HashTest extends PHPUnit_Framework_TestCase
{
	public $hash;

	public function setUp()
	{
		parent::setUp();

		$this->hash = new Hash(array(
			'name' => "Dave",
			'author' => true
		));
	}

	public function testHas()
	{
		$this->assertTrue($this->hash->has('name'));
	}

	public function testHasNot()
	{
		$this->assertFalse($this->hash->has('nope'));
	}

	public function testGet()
	{
		$this->assertSame('Dave', $this->hash->get('name'));
	}

	public function testGetNullIfDoesntExist()
	{
		$this->assertNull($this->hash->get('nope'));
	}

	public function testGetCustomDefault()
	{
		$this->assertFalse($this->hash->get('nope', false));
	}

	public function testSet()
	{
		$this->hash->set('extra', "Yes!");
		$this->assertSame('Yes!', $this->hash->get('extra'));
	}

	public function testDelete()
	{
		$this->hash->delete('author');
		$this->assertNull($this->hash->get('author'));
	}

	public function testLength()
	{
		$this->assertSame(2, $this->hash->length());
	}

	public function testMerge()
	{
		$this->hash->merge(array('extra' => "Yes!"));
		$this->assertSame('Yes!', $this->hash->get('extra'));
	}

	public function testMergeOverwrites()
	{
		$this->hash->merge(array('author' => false));
		$this->assertFalse($this->hash->get('author'));
	}

	public function testClear()
	{
		$this->hash->clear();
		$this->assertSame(0, $this->hash->length());
	}

	public function testToArray()
	{
		$this->assertSame(array(
			'name' => 'Dave',
			'author' => true
		), $this->hash->toArray());
	}

	public function testToJson()
	{
		$this->assertSame('{"name":"Dave","author":true}', $this->hash->toJSON());
	}

	public function testArrayAccessGet()
	{
		$this->assertSame('Dave', $this->hash['name']);
	}

	public function testArrayAccessSet()
	{
		$this->hash['extra'] = "Yes!";
		$this->assertSame("Yes!", $this->hash['extra']);
	}

	public function testCountable()
	{
		$this->assertSame(2, $this->hash->count());
	}

	public function testIteratorAggregate()
	{
		$this->assertInstanceOf('ArrayIterator', $this->hash->getIterator());
	}

}
