<?php
namespace PackageFactory\AtomicFusion\Eel;

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

use Neos\Flow\Annotations as Flow;
use Neos\Eel\ProtectedContextAwareInterface;

/**
 * AtomicFusion EEl-Helper
 *
 * @api
 */
class AtomicFusionHelper implements ProtectedContextAwareInterface
{
    /**
     * Render the arguments as class-names after applying some rules
     *
     * @param mixed $arguments [optional] class-name rules
     * argument rules:
     * - falsy: (null, '', [], {}) -> not rendered
     * - array: all items that are scalar and truthy are rendered as class-name
     * - object: keys that have a truthy values are rendered as class-name
     * - scalar: is cast to string and rendered as class-name
     */
    public function classNames(...$arguments)
    {
        $classNames = [];
        foreach ($arguments as $argument) {
            if ((bool)$argument === true) {
                if (is_array($argument)) {
                    $keys = array_keys($argument);
                    $isAssoc = array_keys($keys) !== $keys;
                    if ($isAssoc) {
                        foreach ($argument as $className => $condition) {
                            if ((bool)$condition === true) {
                                $classNames[] = (string)$className;
                            }
                        }
                    } else {
                        foreach ($argument as $className) {
                            if (is_scalar($className) && (bool)$className === true) {
                                $classNames[] = (string)$className;
                            }
                        }
                    }
                } elseif (is_scalar($argument)) {
                    $classNames[] = (string)$argument;
                }
            }
        }
        return implode(' ', $classNames);
    }

    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
