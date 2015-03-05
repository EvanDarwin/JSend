<?php

namespace EvanDarwin\JSend;

class JSendBuilder
{
    private $status, $data, $message, $code;

    /**
     * Construct a new JSendBuilder object.
     *
     * @param string      $status  The status of the response
     * @param array       $data    The data to include in the response
     * @param string|null $message The human-readable message to include with the response
     * @param int|null    $code    The human (error) code
     */
    public function __construct($status = 'success', array $data = array(), $message = null, $code = null)
    {
        $this->status  = $status;
        $this->data    = $data;
        $this->message = $message;
        $this->code    = $code;
    }

    /**
     * Sets the status to successful.
     */
    public function success()
    {
        $this->status = "success";

        return $this;
    }

    /**
     * Sets the status to be an error.
     */
    public function error()
    {
        $this->status = "error";

        return $this;
    }

    /**
     * Sets the status to failed.
     */
    public function fail()
    {
        $this->status = "fail";

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
                    $status = JSendResponse::STATUS_SUCCESS;
                    break;
                case 'error':
                    $status = JSendResponse::STATUS_ERROR;
                    break;
                case 'fail':
                    $status = JSendResponse::STATUS_FAIL;
                    break;
                default:
                    throw new \InvalidArgumentException("Unable to parse status '${status}'");
            }
        }

        $this->status = $status;

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
     * @return \JSend\JSendResponse
     * @throws \ArgumentException
     */
    public function get()
    {
        if (is_null($this->status)) {
            throw new \ArgumentException("The status code must be set.");
        }

        return new JSendResponse($this->status, $this->data, $this->code, $this->message);
    }
}
