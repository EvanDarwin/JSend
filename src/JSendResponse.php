<?php

namespace EvanDarwin\JSend;

class JSendResponse
{
  const STATUS_SUCCESS = 1;
  const STATUS_FAIL    = 2;
  const STATUS_ERROR   = 3;

  private $status;
  private $data           = null;
  private $errors         = array();
  private $code, $message = null;

  public function __construct($status, $data = null, $errors = null, $code = null, $message = null)
  {
    $validStatuses = array(
        self::STATUS_SUCCESS,
        self::STATUS_FAIL,
        self::STATUS_ERROR
    );

    // Validate they gave us a valid status.
    if (!in_array($status, $validStatuses) || $status == null) {
      throw new \InvalidArgumentException("Invalid status type provided. Use one of the JSendResponse constants");
    }

    // Validate $data
    if (!is_null($data) && !is_array($data)) {
      throw new \InvalidArgumentException("Invalid data type provided, must be array or null");
    }

    // Validate $errors
    if (!is_array($errors)) {
      throw new \InvalidArgumentException("Invalid errors type provided, must be array");
    }

    // Validate $code
    if (!is_int($code) && !is_string($code) && !is_null($code)) {
      throw new \InvalidArgumentException("Status code must be null, an integer, or a string");
    }

    // Validate $message
    if (!is_null($message) && !is_string($message)) {
      throw new \InvalidArgumentException("Error message must be null or a string");
    }

    $this->status  = $status;
    $this->data    = $data;
    $this->errors  = $errors;
    $this->code    = $code;
    $this->message = $message;
  }

  /**
   * Returns the data contained in the response
   *
   * @return null|array
   */
  public function getData()
  {
    return $this->data;
  }

  /**
   * Returns the errors contained in the response
   *
   * @return array
   */
  public function getErrors()
  {
    return $this->errors;
  }

  /**
   * Returns the reference code for this error.
   *
   * @return int
   */
  public function getCode()
  {
    return $this->code;
  }

  /**
   * @return null
   */
  public function getMessage()
  {
    return $this->message;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->getResponse();
  }

  /**
   * Returns the request response to return to the client
   *
   * @return string
   */
  public function getResponse()
  {
    return json_encode($this->getArray());
  }

  /**
   * Returns the array version of the request response
   *
   * @return array
   */
  public function getArray()
  {
    $errors = empty($this->errors) ? null : $this->errors;

    $response = array(
        'status' => $this->getStatus(),
        'data'   => $this->data,
        'errors' => $errors,
    );

    if (!is_null($this->code)) {
      $response['code'] = $this->code;
    }

    if (!is_null($this->message)) {
      $response['message'] = $this->message;
    }

    return $response;
  }

  /**
   * Returns the status of the request.
   *
   * @return string
   */
  public function getStatus()
  {
    if ($this->status == self::STATUS_SUCCESS) {
      return "success";
    } else if ($this->status == self::STATUS_FAIL) {
      return "fail";
    } else {
      return "error";
    }
  }
}
