<?php

use EvanDarwin\JSend\JSendBuilder;
use EvanDarwin\JSend\JSendResponse;

class JSendBuilderTests extends \PHPUnit\Framework\TestCase {
    protected $defaults = array(
        'status'  => JSendResponse::STATUS_SUCCESS,
        'data'    => array(),
        'errors'  => array(),
        'message' => 'default message',
        'code'    => 123
    );
    
    /**
     * Test the construction of JSendBuilder
     */
    public function testConstruction(): void {
        $builder = $this->constructBuilder(); // Default args are valid.
        
        $this->assertNotNull($builder);
    }
    
    /**
     * Creates a new JSendBuilder instance
     *
     * @param array $attrs Attribute overrides
     * @return JSendBuilder
     */
    protected function constructBuilder($attrs = array()): JSendBuilder {
        $attributes = (object)array_merge($this->defaults, $attrs);
        
        return new JSendBuilder(
            $attributes->status,
            $attributes->data,
            $attributes->errors,
            $attributes->message,
            $attributes->code
        );
    }
    
    /**
     * @throws ReflectionException
     */
    public function testAttributesSet(): void {
        $builder = $this->constructBuilder();
        
        $mirror = new ReflectionClass($builder);
        
        $properties = (object)array();
        
        // Use reflection to get private properties of the object.
        foreach (array('code', 'status', 'errors', 'message', 'data') as $attr) {
            $prop = $mirror->getProperty($attr);
            $prop->setAccessible(true);
            
            $properties->{$attr} = $prop;
        }
        
        $this->assertEquals($properties->code->getValue($builder), $this->defaults['code']);
        $this->assertEquals($properties->status->getValue($builder), $this->defaults['status']);
        $this->assertEquals($properties->data->getValue($builder), $this->defaults['data']);
        $this->assertEquals($properties->errors->getValue($builder), $this->defaults['errors']);
        $this->assertEquals($properties->message->getValue($builder), $this->defaults['message']);
    }
    
    /**
     * @throws ReflectionException
     */
    public function testStatusAssignments(): void {
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
    }
    
    /**
     * @throws ReflectionException
     */
    public function testDataAssignment(): void {
        $builder = $this->constructBuilder();
        
        $mirror = new ReflectionClass($builder);
        $prop = $mirror->getProperty('data');
        $prop->setAccessible(true);
        
        $expected = ['a' => 123];
        $builder->data($expected);
        $this->assertEquals($prop->getValue($builder), $expected);
    }
    
    /**
     * @throws ReflectionException
     */
    public function testMessageAssignment(): void {
        $builder = $this->constructBuilder();
        
        $mirror = new ReflectionClass($builder);
        $prop = $mirror->getProperty('message');
        $prop->setAccessible(true);
        
        $expected = 'Hello, world.';
        $builder->message($expected);
        $this->assertEquals($prop->getValue($builder), $expected);
    }
    
    /**
     * @throws ReflectionException
     */
    public function testCodeAssignment(): void {
        $builder = $this->constructBuilder();
        
        $mirror = new ReflectionClass($builder);
        $prop = $mirror->getProperty('code');
        $prop->setAccessible(true);
        
        $expected = 69;
        $builder->code($expected);
        $this->assertEquals($prop->getValue($builder), $expected);
    }
    
    public function testGetResponse() {
        $builder = $this->constructBuilder();
        $response = $builder->get();
        
        $this->assertInstanceOf('EvanDarwin\JSend\JSendResponse', $response);
    }
    
    public function testErrorsExistsInResponse() {
        $builder = $this->constructBuilder();
        
        $result = $builder->errors(['hello' => 'world'])->get()->getArray();
        
        $this->assertTrue((array_key_exists('hello', $result['errors']) && $result['errors']['hello'] == 'world'));
    }
}
