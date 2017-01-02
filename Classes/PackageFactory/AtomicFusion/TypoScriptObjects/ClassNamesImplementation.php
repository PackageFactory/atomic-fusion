<?php
namespace PackageFactory\AtomicFusion\TypoScriptObjects;

/**
 * This file is part of the PackageFactory.AtomicFusion package
 *
 * (c) 2016
 * Wilhelm Behncke <wilhelm.behncke@googlemail.com>
 * Martin Ficzel <martin.ficzel@gmx.de>
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TypoScript\TypoScriptObjects\ArrayImplementation;

/**
 * A TypoScript ClassNames-Object
 *
 * All property keys are concatenated into a single string, that can be assigned to a html class
 * attribute, if their value evaluates to `true`.
 *
 * @api
 */
class ClassNamesImplementation extends ArrayImplementation
{
    /**
     * Properties that are ignored
     *
     * @var array
     */
    protected $ignoreProperties = ['__meta'];

    /**
     * @return void|string
     */
    public function evaluate()
    {
        $sortedChildTypoScriptKeys = $this->sortNestedTypoScriptKeys();

        $props = [];
        foreach ($sortedChildTypoScriptKeys as $key) {
            if ($this->tsValue($key)) {
                $props[] = $key;
            }
        }

        return implode(' ', $props);
    }
}
