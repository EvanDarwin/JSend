<?php

use EvanDarwin\JSend\JSendResponse;

class JSendResponseTests extends TestCase
{
  protected $defaults = array(
      "status"  => JSendResponse::STATUS_SUCCESS,
      "data"    => array(),
      "errors"  => array(),
      "code"    => null,
      "message" => "default"
  );

  public function testConstructResponse()
  {
    $response = $this->constructResponse();

    $this->assertNotNull($response);

    $this->assertEquals($response->getStatus(), 'success');
    $this->assertEquals($response->getCode(), $this->defaults['code']);
    $this->assertEquals($response->getMessage(), $this->defaults['message']);
    $this->assertEquals($response->getData(), $this->defaults['data']);
    $this->assertEquals($response->getErrors(), $this->defaults['errors']);
  }

  protected function constructResponse(array $attributes = array())
  {
    $attributes = (object)array_merge($this->defaults, $attributes);

    return new JSendResponse(
        $attributes->status,
        $attributes->data,
        $attributes->errors,
        $attributes->code,
        $attributes->message
    );
  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testConstructValidateData()
  {
    $response = $this->constructResponse(array(
        'data' => "fail"
    ));
  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testConstructValidateCode()
  {
    $response = $this->constructResponse(array(
        'code' => (object)array()
    ));
  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testConstructValidateMessage()
  {
    $response = $this->constructResponse(array(
        'message' => (object)array()
    ));
  }

  public function validStatusProvider()
  {
    return array(
        array(JSendResponse::STATUS_SUCCESS),
        array(JSendResponse::STATUS_ERROR),
        array(JSendResponse::STATUS_FAIL),
    );
  }

  public function invalidStatusProvider()
  {
    return array(
        array('success'),
        array('error'),
        array('fail'),
    );
  }

  /**
   * @dataProvider invalidStatusProvider
   * @expectedException InvalidArgumentException
   *
   * @param string|int $status The status to construct with
   */
  public function testInvalidStatus($status)
  {
    $this->constructResponse(array(
        'status' => $status
    ));
  }

  /**
   * Test valid statuses
   *
   * @dataProvider validStatusProvider
   *
   * @param string|int $status The status to construct with
   */
  public function testValidStatus($status)
  {
    $response = $this->constructResponse(array(
        'status' => $status
    ));

    $this->assertNotNull($response);
  }

  public function testGetStatus()
  {
    // success
    $response = $this->constructResponse();

    $this->assertEquals($response->getStatus(), "success");

    // error
    $response = $this->constructResponse(array(
        'status' => JSendResponse::STATUS_ERROR
    ));

    $this->assertEquals($response->getStatus(), "error");

    // fail
    $response = $this->constructResponse(array(
        'status' => JSendResponse::STATUS_FAIL
    ));

    $this->assertEquals($response->getStatus(), "fail");
  }

  public function testResponseValid()
  {
    $response = $this->constructResponse(array(
        'code' => 123
    ));

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
