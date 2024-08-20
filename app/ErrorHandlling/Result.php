<?php

namespace App\ErrorHandlling;

class Result
{
    # constants of standard code.
    const PROCESS_SUCESS  = 0;
    const PROCESS_DOWN    = 1;
    const PROCESS_STOP    = 3;


    /**
     * Data to output.
     *
     * @var mixed
     */
    public $data = null;

    /**
     * Error message if is an error.
     *
     * @var string|null
     */
    public $errorMessage = null;

    /**
     * Stoping reason if code stoped.
     *
     * @var string|null
     */
    public $stopReason = null;

    /**
     * Output code
     *
     * @var int|null
     */
    public $code = null;

    /**
     * Initialize result object.
     */
    public function __construct(
        mixed $data = null,
        ?string $errorMessage = null,
        ?string $stopReason = null,
        ?int $code = null
    ) {
        $this->data              = $data;
        $this->errorMessage     = __($errorMessage);
        $this->stopReason      = $stopReason;
        $this->code            = $code;
    }

    public function isError(): bool
    {
        return $this->data === null && $this->errorMessage != null;
    }

    public function isStoped(string $reason = null): bool
    {
        $isError    = $this->isError();
        $sotped     = !is_null($this->stopReason);
        return ($isError && $sotped) || $sotped;
    }

    public function toArray(): array
    {
        return [
            'isStoped'      => $this->isStoped(),
            'stopReason'    => $this->stopReason,
            'isError'       => $this->isError(),
            'errorMessage'  => $this->errorMessage,
            'code'          => $this->code,
            'data'          => $this->data
        ];
    }


    public static function done(mixed $data): Result
    {
        return new self(
            data: $data,
            code: self::PROCESS_SUCESS
        );
    }

    public static function stop(string $stopReason, int $code = self::PROCESS_STOP): Result
    {
        return new self(
            stopReason: $stopReason,
            code: $code
        );
    }

    public static function error(mixed $errorMessage, int $code = self::PROCESS_DOWN): Result
    {
        return new self(
            errorMessage: $errorMessage,
            code: $code
        );
    }
}
