<?php
namespace PackageFactory\AtomicFusion\TypoScriptObjects;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TypoScript\TypoScriptObjects\ArrayImplementation;

/**
 * A TypoScript Component-Object
 *
 * All properties except ``value`` are pushed into a context variable ``props``
 * with that context the value is rendered
 *
 * //tsPath value The variable to display a dump of.
 * @api
 */
class ComponentImplementation extends ArrayImplementation
{
    /**
     * If you iterate over "properties" these in here should usually be ignored.
     * For example additional properties in "Case" that are not "Matchers".
     *
     * @var array
     */
    protected $ignoreProperties = ['__meta', 'value'];

    /**
     * Return render value with the properties as ``props`` in the context
     *
     * @return void|string
     */
    public function evaluate()
    {
        $sortedChildTypoScriptKeys = $this->sortNestedTypoScriptKeys();

        $props = [];
        foreach ($sortedChildTypoScriptKeys as $key) {
            try {
                $props[$key] = $this->tsValue($key);
            } catch (\Exception $e) {
                $props[$key] = $this->tsRuntime->handleRenderingException($this->path . '/' . $key, $e);
            }
        }

        $context = $this->tsRuntime->getCurrentContext();
        $context['props'] = $props;
        $this->tsRuntime->pushContextArray($context);
        $result = $this->tsRuntime->render($this->path . '/value');
        $this->tsRuntime->popContext();

        return $result;
    }
}
