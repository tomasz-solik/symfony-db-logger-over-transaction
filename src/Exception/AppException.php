<?php
/**
 * pmi-sklep-backend - RuntimeException.php
 *
 * Initial version by: Toamsz Solik
 * Initial version created on: 06.11.20 / 13:37
 */

namespace App\Exception;

use Throwable;

class AppException extends \RuntimeException
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}