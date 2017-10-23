<?php

namespace CS\Salary;


use Symfony\Component\OptionsResolver\OptionsResolver;

class DataResolver extends OptionsResolver
{
    /**
     * Convenience function that sets options as required at the same time setting their types.
     *
     * Pass item names as keys and the required type name string as value.
     * You can also pass multiple required types as an array of type names.
     *
     * @param array $required
     */
    public function setRequiredWithTypes(array $required)
    {
        $this->setRequired(array_keys($required));
        $this->setAllowedTypesAsArray($required);
    }

    /**
     * Convenience function that allows you to set allowed types at once using an array.
     *
     * Pass item names as keys and the required type name string as value.
     * You can also pass multiple required types as an array of type names.
     *
     * @param array $allowedTypes
     */
    public function setAllowedTypesAsArray(array $allowedTypes)
    {
        foreach ($allowedTypes as $name => $type) {
            $this->setAllowedTypes($name, $type);
        }
    }
}