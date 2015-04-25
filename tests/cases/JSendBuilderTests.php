<?php

use EvanDarwin\JSend\JSendBuilder;
use EvanDarwin\JSend\JSendResponse;

class JSendBuilderTests extends TestCase
{
  protected $defaults = array(
    "status"  => JSendResponse::STATUS_SUCCESS,
    "data"    => array(),
    "message" => "default message",
    "code"    => 123
  );

  /**
   * Creates a new JSendBuilder instance
   *
   * @param array $attrs Attribute overrides
   */
  protected function constructBuilder($attrs = array())
  {
    $attributes = (object)array_merge($this->defaults, $attrs);

    return new JSendBuilder(
      $attributes->status,
      $attributes->data,
      $attributes->message,
      $attributes->code
    );
  }

  /**
   * Test the construction of JSendBuilder
   */
  public function testConstruction()
  {
    $builder = $this->constructBuilder(); // Default args are valid.

    $this->assertNotNull($builder);
  }

  public function testAttributesSet()
  {
    $builder = $this->constructBuilder();

    $mirror = new ReflectionClass($builder);

    $properties = (object)array();

    // Use reflection to get private properties of the object.
    foreach(array('code', 'status', 'message', 'data') as $attr) {
      $prop = $mirror->getProperty($attr);
      $prop->setAccessible(true);

      $properties->{$attr} = $prop;
    }

    $this->assertEquals($properties->code->getValue($builder), $this->defaults['code']);
    $this->assertEquals($properties->status->getValue($builder), $this->defaults['status']);
    $this->assertEquals($properties->data->getValue($builder), $this->defaults['data']);
    $this->assertEquals($properties->message->getValue($builder), $this->defaults['message']);
  }

  public function testStatusAssignments()
  {
    $builder = $this->constructBuilder();

    $mirror = new ReflectionClass($builder);
    $prop = $mirror->getProperty('status');
    $prop->setAccessible(true);

    // Test that ->failed() works.
    $builder->failed();
    $this->assertEquals($prop->getValue($builder), JSendResponse::STATUS_FAIL);

    // Test that ->error() works.
    $builder->error();
    $this->assertEquals($prop->getValue($builder), JSendResponse::STATUS_ERROR);

    // Test that ->success() works.
    $builder->success();
    $this->assertEquals($prop->getValue($builder), JSendResponse::STATUS_SUCCESS);

    // That that the ->fail() alias works
    $builder->fail();
    $this->assertEquals($prop->getValue($builder), JSendResponse::STATUS_FAIL);
  }

  public function testDataAssignment()
  {
    $builder = $this->constructBuilder();

    $mirror = new ReflectionClass($builder);
    $prop = $mirror->getProperty('data');
    $prop->setAccessible(true);

    $builder->data(array(
      'a' => 123
    ));

    $this->assertEquals($prop->getValue($builder), array("a" => 123));
  }

  public function testMessageAssignment()
  {
    $builder = $this->constructBuilder();

    $mirror = new ReflectionClass($builder);
    $prop = $mirror->getProperty('message');
    $prop->setAccessible(true);

    $builder->message("Hello, world.");

    $this->assertEquals($prop->getValue($builder), "Hello, world.");
  }

  public function testCodeAssignment()
  {
    $builder = $this->constructBuilder();

    $mirror = new ReflectionClass($builder);
    $prop = $mirror->getProperty('code');
    $prop->setAccessible(true);

    $builder->code(69);

    $this->assertEquals($prop->getValue($builder), 69);
  }

  public function testGetResponse()
  {
    $builder = $this->constructBuilder();

    $builder->success();

    $this->assertInstanceOf('EvanDarwin\JSend\JSendResponse', $builder->get());
  }

  public function testSetStatus()
  {
    $builder = $this->constructBuilder();

    $builder->status('success');
    $builder->status('error');
    $builder->status('fail');

    $builder->status(JSendResponse::STATUS_SUCCESS);
    $builder->status(JSendResponse::STATUS_ERROR);
    $builder->status(JSendResponse::STATUS_FAIL);
  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testSetStatusWithUnknownValue()
  {
    $builder = $this->constructBuilder();

    $builder->status('hunter2');
  }
}
