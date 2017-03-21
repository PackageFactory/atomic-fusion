# PackageFactory.AtomicFusion

> Prototypes that help implementing atomic-design and a component-architecture in Neos.Fusion

- `PackageFactory.AtomicFusion:Component`: create component that adds all properties to the `props` context 
and afterwards evaluetes the `renderer`
- `PackageFactory.AtomicFusion:ClassNames`: create conditional class-names from fusion-keys

## Usage 

```
prototype(Vendor.Site:Component) < prototype(PackageFactory.AtomicFusion:Component) {
    
    #
    # all fusion properties except renderer are evaluated and passed 
    # are made available to the renderer as object ``props`` in the context
    # 
    title = ''
    description = ''
    bold = false

    #
    # the renderer path is evaluated with the props in the context
    # that way regardless off nesting the props can be accessed
    # easily via ${props.__name__}
    # 
    renderer = Neos.Fusion:Tag {
    
        #
        # the properties of the AtomicFusion:ClassNames object are evaluated 
        # and the keys of all non-false properties are returned
        # 
        # this allows effective definition of conditional css-classes
        #
        attributes.class = PackageFactory.AtomicFusion:ClassNames {
            component = true
            component--bold = ${props.bold} 
        }
        
        content = Neos.Fusion:Array {
            headline = Neos.Fusion:Tag {
                tagName = 'h1'
                content = ${props.title}
            }

            description = Neos.Fusion:Tag {
                tagName = 'p'
                content = ${props.description}
            }
        }
    }
}
```

## Installation

PackageFactory.AtomicFusion is available via packagist. Just add `"packagefactory/atomicfusion" : "~1.0"` to the 
require section of the composer.json or run `composer require packagefactory/atomicfusion`. 

We use semantic-versioning so every breaking change will increase the major-version number.

## License

see [LICENSE file](LICENSE)
