<?php

namespace App\Http\Annotations;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("METHOD")
 */
class ValidateRequestParams
{
    /**
     * @var array
     */
    public array $rules = [];

    /**
     * @var array
     */
    public array $errorMessages = [];
}
