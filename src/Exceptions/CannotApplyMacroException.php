<?php

namespace Spatie\CollectionMacros\Exceptions;

use Exception;

/**
 * The exception thrown when a macro cannot be applied to a Collection class.
 */
class CannotApplyMacroException extends Exception
{
    /**
     * Thrown when a method with the same name as a new macro already exists.
     *
     * @param string $macro           The macro being applied.
     * @param string $collectionClass The Collection class being applied to.
     * @return static
     */
    public static function methodAlreadyExists(string $macro, string $collectionClass): self
    {
        return new static(
            "The MACRO \"$macro\" cannot be assigned to $collectionClass because it already exists as a method"
        );
    }
}
