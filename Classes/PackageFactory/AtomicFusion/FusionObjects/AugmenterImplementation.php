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
use Neos\Fusion\Service\HtmlAugmenter;

/**
 * A Fusion Augmenter-Object
 *
 * The fusion object can be used to add html-attributes to the rendererd content
 *
 * @api
 */
class AugmenterImplementation extends ArrayImplementation
{

    /**
     * @var HtmlAugmenter
     * @Flow\Inject
     */
    protected $htmlAugmenter;

    /**
     * Properties that are ignored
     *
     * @var array
     */
    protected $ignoreProperties = ['__meta', 'fallbackTagName', 'content'];

    /**
     * @return void|string
     */
    public function evaluate()
    {
        $content = $this->fusionValue('content');
        $fallbackTagName = $this->fusionValue('fallbackTagName');

        $sortedChildFusionKeys = $this->sortNestedFusionKeys();

        $attributes = [];
        foreach ($sortedChildFusionKeys as $key) {
            if ($fusionValue = $this->fusionValue($key)) {
                $attributes[$key] = $fusionValue;
            }
        }

        if ($attributes && is_array($attributes) && count($attributes) > 0) {
            return $this->htmlAugmenter->addAttributes($content, $attributes, $fallbackTagName);
        } else {
            return $content;
        }
    }
}
