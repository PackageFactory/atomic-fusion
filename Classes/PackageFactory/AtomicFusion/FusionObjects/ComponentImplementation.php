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
 * A Fusion Component-Object
 *
 * All properties except ``renderer`` are pushed into a context variable ``props``
 * afterwards the ``renderer`` is evaluated
 *
 * //fusionPath renderer The variable to display a dump of.
 * //fusionPath * generic Fusion values that will be added to the ``props`` object in the context
 * @api
 */
class ComponentImplementation extends ArrayImplementation
{
    /**
     * Properties that are ignored and not included into the ``props`` context
     *
     * @var array
     */
    protected $ignoreProperties = ['__meta', 'renderer'];

    /**
     * Get the component props as an associative array
     *
     * @return array
     */
    public function getProps()
    {
        $sortedChildFusionKeys = $this->sortNestedFusionKeys();

        $props = [];
        foreach ($sortedChildFusionKeys as $key) {
            try {
                $props[$key] = $this->fusionValue($key);
            } catch (\Exception $e) {
                $props[$key] = $this->runtime->handleRenderingException($this->path . '/' . $key, $e);
            }
        }

        return $props;
    }

    /**
     * Render the component with the given props
     *
     * @param array $props
     * @return void|string
     */
    public function renderComponent(array $props)
    {
        $context = $this->runtime->getCurrentContext();
        $context['props'] = $props;
        $this->runtime->pushContextArray($context);
        $result = $this->runtime->render($this->path . '/renderer');
        $this->runtime->popContext();

        return $result;
    }

    /**
     * Evaluate the fusion-keys and transfer the result into the context as ``props``
     * afterwards evaluate the ``renderer`` with this context
     *
     * @return void|string
     */
    public function evaluate()
    {
        $props = $this->getProps();
        return $this->renderComponent($props);
    }
}
