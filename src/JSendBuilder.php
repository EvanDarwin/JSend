<?php

namespace EvanDarwin\JSend;

use InvalidArgumentException;

class JSendBuilder {
    /** @var int */
    private $status;
    
    /** @var mixed[] */
    private $data;
    
    /** @var string[] */
    private $errors;
    
    /** @var string|null */
    private $message;
    
    /** @var int|null */
    private $code;
    
    /**
     * Construct a new JSendBuilder object.
     *
     * @param int         $status  The status of the response
     * @param array       $data    The data to include in the response
     * @param array       $errors
     * @param string|null $message The human-readable message to include with the response
     * @param int|null    $code    The human (error) code
     */
    public function __construct(
        int $status = JSendResponse::STATUS_SUCCESS,
        array $data = array(),
        array $errors = array(),
        string $message = null,
        int $code = null) {
        
        $this->status = $status;
        $this->data = $data;
        $this->errors = $errors;
        $this->message = $message;
        $this->code = $code;
    }
    
    /**
     * Sets the status to successful.
     */
    public function success(): self {
        $this->status(JSendResponse::STATUS_SUCCESS);
        return $this;
    }
    
    /**
     * Sets the status to be an error.
     */
    public function error(): self {
        $this->status(JSendResponse::STATUS_ERROR);
        return $this;
    }
    
    /**
     * Sets the status to failed.
     */
    public function failed(): self {
        $this->status(JSendResponse::STATUS_FAIL);
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
     * @internal
     */
    protected function status(?string $status): self {
        $valid = [JSendResponse::STATUS_SUCCESS, JSendResponse::STATUS_FAIL, JSendResponse::STATUS_ERROR];
        if ($status !== null && in_array($status, $valid, true)) {
            throw new InvalidArgumentException("Unable to parse status '${status}'");
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
    public function data(array $data): self {
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
    public function errors(array $errors): self {
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
    public function message(string $message): self {
        $this->message = $message;
        return $this;
    }
    
    /**
     * Set the error code
     *
     * @param int $code
     *
     * @return $this
     */
    public function code(?int $code): self {
        $this->code = $code;
        return $this;
    }
    
    /**
     * Returns the built object
     *
     * @return JSendResponse|mixed
     *
     * @throws InvalidArgumentException
     */
    public function get() {
        return new JSendResponse($this->status, $this->data, $this->errors, $this->code, $this->message);
    }
}
