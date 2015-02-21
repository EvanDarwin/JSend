<?php

namespace JSend;

final class JSendBuilder
{
    private $status, $data, $message, $code;

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