<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
/**
 * configure field
 *
 * @param array $options
 * @return array
 */
    protected function getConfiguration($options = [])
    {
        return array_merge_recursive([
            'mapped' => false,
            'choices' => [
                'salade' => 'salade',
                'tomate' => 'tomate',
                'olive' => 'olive',
                'fromage' => 'fromage',
                'saumon' => 'saumon',
                'thon' => 'thon',
                'riz' => 'riz',
            ],
        ], $options);
    }
}
