<?php

namespace EvanDarwin\JSend;

class JSendBuilder
{
  private $status  = "success";
  private $data    = array();
  private $errors  = array();
  private $message = '';
  private $code    = null;

  /**
   * Construct a new JSendBuilder object.
   *
   * @param string      $status  The status of the response
   * @param array       $data    The data to include in the response
   * @param string|null $message The human-readable message to include with the response
   * @param int|null    $code    The human (error) code
   */
  public function __construct(
      $status = JSendResponse::STATUS_SUCCESS,
      array $data = array(),
      array $errors = array(),
      $message = null,
      $code = null)
  {
    $this->status  = $status;
    $this->data    = $data;
    $this->errors  = $errors;
    $this->message = $message;
    $this->code    = $code;
  }

  /**
   * Sets the status to successful.
   */
  public function success()
  {
    $this->status = JSendResponse::STATUS_SUCCESS;

    return $this;
  }

  /**
   * Sets the status to be an error.
   */
  public function error()
  {
    $this->status = JSendResponse::STATUS_ERROR;

    return $this;
  }

  /**
   * Alias for ::failed()
   *
   * @deprecated
   */
  public function fail()
  {
    $this->failed();
  }

  /**
   * Sets the status to failed.
   */
  public function failed()
  {
    $this->status = JSendResponse::STATUS_FAIL;

    return $this;
  }

  /**
   * Sets the status of the response. Valid options are:
   *  - 'success'
   *  - 'error'
   *  - 'fail'
   *  - Any constant in JSendResponse
   *
   * @param $status
   *
   * @return $this
   *
   * @deprecated
   */
  public function status($status)
  {
    if (is_string($status)) {
      switch ($status) {
        case 'success':
          $this->status = JSendResponse::STATUS_SUCCESS;
          break;
        case 'error':
          $this->status = JSendResponse::STATUS_ERROR;
          break;
        case 'fail':
          $this->status = JSendResponse::STATUS_FAIL;
          break;
        default:
          throw new \InvalidArgumentException("Unable to parse status '${status}'");
      }
    } else {
      $this->status = $status;
    }

    return $this;
  }

  /**
   * Set the data of the response
   *
   * @param array $data
   *
   * @return $this
   */
  public function data(array $data)
  {
    $this->data = $data;

    return $this;
  }

  /**
   * Sets the errors in the response
   *
   * @param array $errors The errors
   *
   * @return $this
   */
  public function errors(array $errors)
  {
    $this->errors = $errors;

    return $this;
  }

  /**
   * Set the human readable message
   *
   * @param $message
   *
   * @return $this
   */
  public function message($message)
  {
    $this->message = $message;

    return $this;
  }

  /**
   * Set the error code
   *
   * @param $code
   *
   * @return $this
   */
  public function code($code)
  {
    $this->code = $code;

    return $this;
  }

  /**
   * Returns the built object
   *
   * @return \EvanDarwin\JSend\JSendResponse
   *
   * @throws \InvalidArgumentException
   */
  public function get()
  {
    return new JSendResponse($this->status, $this->data, $this->errors, $this->code, $this->message);
  }
}
