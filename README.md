# PackageFactory.AtomicFusion

> Prototypes that help implementing atomic-design and a component-architecture in Neos.Fusion

- `PackageFactory.AtomicFusion:Component`: create component that adds all properties to the `props` context 
and afterwards evaluetes the `renderer`
- `PackageFactory.AtomicFusion:ClassNames`: create conditional class-names from fusion-keys
- `PackageFactory.AtomicFusion:Editable`: create and editable tag for a property
- `PackageFactory.AtomicFusion:Content`: component base-prototype for inline editable content nodes 

## Usage 

### 1. Component definition

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
                content = ${props.description}
            }
        }
    }
}
```

### 2. Content Mapping

```
#
# Map node content zo a presentational component 
# 
# instead of Neos.Neos:Content PackageFactory.AtomicFusion:Content 
# is used base prototype 
#
prototype(Vendor.Site:ExampleContent) < prototype(PackageFactory.AtomicFusion:Content) {
	renderer = Vendor.Site:Component {
	
		# 
		# pass boolean property 'bold' from the
		# node to the component
		#
		bold = ${q(node).property('bold')}	
	
		#
		# use the editable component to pass an editable 
		# but use a span instead a div tag in the backend
		#
		title = PackageFactory.AtomicFusion:Editable {
			property = 'title'
			block = false
		}
		
		#
		# use the editable component to pass an editable 
		# tag for the property 'description'
		#
		description = PackageFactory.AtomicFusion:Editable {
			property = 'description'
		}
	}
}
```

## Installation

PackageFactory.AtomicFusion is available via packagist. Just run `composer require packagefactory/atomicfusion`. 

We use semantic-versioning so every breaking change will increase the major-version number.

## License

see [LICENSE file](LICENSE)
