<?php
namespace App\Services\Links\Exceptions;
use Exception as BaseException;

class NotUniqueHashGenerateException extends LinksServiceException {

    protected $message;
    /**
     * TooManyRequestsException constructor.
     * @param $message
     * @param int $code
     * @param BaseException $previous
     */
    public function __construct($message, $code = 0, BaseException $previous = null)
    {
        $this->message = $message;
        parent::__construct('Not unique hash generate: '. $this->getMessage().' ', $code, $previous);
    }
}
