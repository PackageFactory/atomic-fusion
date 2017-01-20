<?php
namespace PackageFactory\AtomicFusion\FusionObjects;

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
use Neos\Fusion\FusionObjects\ArrayImplementation;

/**
 * A TypoScript Component-Object
 *
 * All properties except ``renderer`` are pushed into a context variable ``props``
 * afterwards the ``renderer`` is evaluated
 *
 * //tsPath renderer The variable to display a dump of.
 * //tsPath * generic Fusion values that will be added to the ``props`` object in the context
 * @api
 */
class ComponentImplementation extends ArrayImplementation
{
    /**
     * Properties that are ignored and added to the props
     *
     * @var array
     */
    protected $ignoreProperties = ['__meta', 'renderer'];

    /**
     * Evaluate the fusion-keys and transfer the result into the context as ``props``
     * afterwards evaluate the ``renderer`` with this context
     *
     * @return void|string
     */
    public function evaluate()
    {
        $sortedChildFusionKeys = $this->sortNestedTypoScriptKeys();

        $props = [];
        foreach ($sortedChildFusionKeys as $key) {
            try {
                $props[$key] = $this->tsValue($key);
            } catch (\Exception $e) {
                $props[$key] = $this->tsRuntime->handleRenderingException($this->path . '/' . $key, $e);
            }
        }

        $context = $this->tsRuntime->getCurrentContext();
        $context['props'] = $props;
        $this->tsRuntime->pushContextArray($context);
        $result = $this->tsRuntime->render($this->path . '/renderer');
        $this->tsRuntime->popContext();

        return $result;
    }
}
