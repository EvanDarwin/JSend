<?php

namespace EvanDarwin\JSend;

use InvalidArgumentException;

class JSendResponse {
    public const STATUS_SUCCESS = 1;
    public const STATUS_FAIL    = 2;
    public const STATUS_ERROR   = 3;
    
    private $status;
    private $data;
    private $errors;
    private $code, $message;
    
    public function __construct($status, $data = null, $errors = null, $code = null, $message = null) {
        $validStatuses = array(
            self::STATUS_SUCCESS,
            self::STATUS_FAIL,
            self::STATUS_ERROR
        );
        
        // Validate they gave us a valid status.
        if ($status === null || !in_array($status, $validStatuses, true)) {
            throw new InvalidArgumentException('Invalid status type provided. Use one of the JSendResponse constants');
        }
        
        // Validate $data
        if ($data !== null && !is_array($data)) {
            throw new InvalidArgumentException('Invalid data type provided, must be array or null');
        }
        
        // Validate $errors
        if (!is_array($errors)) {
            throw new InvalidArgumentException('Invalid errors type provided, must be array');
        }
        
        // Validate $code
        if (!is_int($code) && $code !== null && !is_string($code)) {
            throw new InvalidArgumentException('Status code must be null, an integer, or a string');
        }
        
        // Validate $message
        if ($message !== null && !is_string($message)) {
            throw new InvalidArgumentException('Error message must be null or a string');
        }
        
        $this->status = $status;
        $this->data = $data;
        $this->errors = $errors;
        $this->code = $code;
        $this->message = $message;
    }
    
    /**
     * Returns the data contained in the response
     *
     * @return null|array
     */
    public function getData(): ?array {
        return $this->data;
    }
    
    /**
     * Returns the errors contained in the response
     *
     * @return array
     */
    public function getErrors(): array {
        return $this->errors;
    }
    
    /**
     * Returns the reference code for this error.
     *
     * @return int
     */
    public function getCode(): int {
        return $this->code;
    }
    
    /**
     * @return null
     */
    public function getMessage() {
        return $this->message;
    }
    
    /**
     * @return string
     */
    public function __toString() {
        return $this->getResponse();
    }
    
    /**
     * Returns the request response to return to the client
     *
     * @return string
     */
    public function getResponse(): string {
        return json_encode($this->getArray());
    }
    
    /**
     * Returns the array version of the request response
     *
     * @return array
     */
    public function getArray(): array {
        $errors = empty($this->errors) ? null : $this->errors;
        
        $response = array(
            'status' => $this->getStatus(),
            'data'   => $this->data,
            'errors' => $errors,
        );
        
        if ($this->code !== null) {
            $response['code'] = $this->code;
        }
        
        if ($this->message !== null) {
            $response['message'] = $this->message;
        }
        
        return $response;
    }
    
    /**
     * Returns the status of the request.
     *
     * @return string
     */
    public function getStatus(): string {
        if ($this->status === self::STATUS_SUCCESS) {
            return 'success';
        }
        
        if ($this->status === self::STATUS_FAIL) {
            return 'fail';
        }
        
        return 'error';
    }
}
