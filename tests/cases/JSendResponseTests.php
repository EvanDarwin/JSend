<?php

use EvanDarwin\JSend\JSendResponse;
use PHPUnit\Framework\TestCase;

class JSendResponseTests extends TestCase {
    protected $defaults = [
        'status'  => JSendResponse::STATUS_SUCCESS,
        'data'    => array(),
        'errors'  => array(),
        'code'    => null,
        'message' => 'default',
    ];
    
    public function testConstructResponse(): void {
        $response = $this->constructResponse();
        
        $this->assertNotNull($response);
        
        $this->assertEquals($response->getStatus(), 'success');
        $this->assertEquals($response->getCode(), $this->defaults['code']);
        $this->assertEquals($response->getMessage(), $this->defaults['message']);
        $this->assertEquals($response->getData(), $this->defaults['data']);
        $this->assertEquals($response->getErrors(), $this->defaults['errors']);
    }
    
    protected function constructResponse(array $attributes = array()): JSendResponse {
        $attributes = array_merge($this->defaults, $attributes);
        return new JSendResponse(
            $attributes['status'],
            $attributes['data'],
            $attributes['errors'],
            $attributes['code'],
            $attributes['message']
        );
    }
    
    public function testConstructValidateData(): void {
        $this->expectException(TypeError::class);
        $this->constructResponse(['data' => 'fail']);
    }
    
    public function testConstructValidateCode(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Status code must be null or an integer');
        $this->constructResponse(['code' => (object)array()]);
    }
    
    public function testConstructValidateMessage(): void {
        $this->expectException(TypeError::class);
        $this->constructResponse(array(
            'message' => (object)array()
        ));
    }
    
    public function validStatusProvider(): array {
        return array(
            array(JSendResponse::STATUS_SUCCESS),
            array(JSendResponse::STATUS_ERROR),
            array(JSendResponse::STATUS_FAIL),
        );
    }
    
    /**
     * Test valid statuses
     *
     * @dataProvider validStatusProvider
     *
     * @param string|int $status The status to construct with
     */
    public function testValidStatus(int $status): void {
        $response = $this->constructResponse(array(
            'status' => $status
        ));
        
        $this->assertNotNull($response);
    }
    
    public function testGetStatus(): void {
        // success
        $response = $this->constructResponse();
        $this->assertEquals($response->getStatus(), 'success');
        
        // error
        $response = $this->constructResponse(['status' => JSendResponse::STATUS_ERROR]);
        $this->assertEquals($response->getStatus(), 'error');
        
        // fail
        $response = $this->constructResponse(['status' => JSendResponse::STATUS_FAIL]);
        $this->assertEquals($response->getStatus(), 'fail');
    }
    
    public function testResponseValid(): void {
        $response = $this->constructResponse(['code' => 123]);
        $expectedJson = json_encode(
            array(
                'status'  => 'success',
                'code'    => 123,
                'errors'  => null,
                'message' => $this->defaults['message'],
                'data'    => $this->defaults['data']
            )
        );
        
        $this->assertJsonStringEqualsJsonString(
            $response->getResponse(),
            $expectedJson
        );
        
        $this->assertJsonStringEqualsJsonString(
            $response->__toString(),
            $expectedJson
        );
    }
}
