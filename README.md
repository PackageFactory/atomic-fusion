# PackageFactory.AtomicFusion

> Prototypes and Helpers for implementing a component-architecture with Neos.Fusion

### Fusion-Prototypes

- `PackageFactory.AtomicFusion:Component`: create component that adds all properties to the `props` context 
  and afterwards evaluates the `renderer`
- `PackageFactory.AtomicFusion:ClassNames`: create conditional class-names from fusion-keys
- `PackageFactory.AtomicFusion:Editable`: create and editable tag for a property
- `PackageFactory.AtomicFusion:Content`: component base-prototype for inline editable content nodes 
- `PackageFactory.AtomicFusion:Augmenter`: add html-attributes to the rendered children 

### EEL-Helpers

- `AtomicFusion.classNames`: render all arguments as classNames and apply conditions if needed

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
        # all arguments of the AtomicFusion.classNames eelHelper are evaluated 
        # and the following rules are applied
        # 
        # - falsy: (null, '', [], {}) -> not rendered
        # - array: all items that are scalar and truthy are rendered as class-name
        # - object: keys that have a truthy values are rendered as class-name
        # - scalar: is cast to string and rendered as class-name
        # 
        attributes.class =  ${AtomicFusion.classNames('component' , {'component--bold': props.bold})} 
        
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
# Map node content to a presentational component 
# 
# instead of Neos.Neos:Content PackageFactory.AtomicFusion:Content 
# is used base prototype that adds the needed contentElementWrapping for the backend
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

### 3. Content Augmentation

The Augmenter-component can be used as processor or as a standalone prototype

```
#
# Standalone-Augmenter
# 
augmentedContent = PackageFactory.AtomicFusion:Augmenter {

    #
    # The content that shall be augmented. 
    #
    content = Neos.Fusion:Tag {
        tagName = 'h2'
        content = 'Lorem Ipsum'
    }
    
    #
    # If more than one tag is found the content is wrapped in the 
    # fallback-Tag before augmentation wich has "div" as default   
    # 
    fallbackTagName = 'div'
        
    #
    # All other fusion properties are added to the html-content
    # as html-attributes.
    # 
    class="foo" 
    data-example="data"

}

#
# Processor-Augmenter
#
augmentedContent = Neos.Fusion:Tag {
    tagName = 'h2'
    content = 'Lorem Ipsum'
    @process.augment = PackageFactory.AtomicFusion:Augmenter {
        class = "foo"
        data-example="data"
    }
}
```

### ClassName-Mapping

Atomic Fusion brings an fusion-prototype and an eel-helper to optimize 
the common need of creating classNames based on certain conditions. 

```
#
# the properties of the fusion protoype PackageFactory.AtomicFusion:ClassNames 
# are evaluated nd the keys of all non-false properties are returned
# 
attributes.class = PackageFactory.AtomicFusion:ClassNames {
    component = true
    component--bold = ${props.bold} 
}

#
# all arguments of the AtomicFusion:classNames eelHelper are evaluated 
# and the following rules are applied
# 
# - falsy: (null, '', [], {}) -> not rendered
# - array: all items that are scalar and truthy are rendered as class-name
# - object: keys that have a truthy values are rendered as class-name
# - scalar: is cast to string and rendered as class-name
# 
attributes.class = ${AtomicFusion:classNames(
    "component",
    {"component--bold": props.bold, "component--highlight": props.highlight}         
)}
```

## Installation

PackageFactory.AtomicFusion is available via packagist. Just run `composer require packagefactory/atomicfusion`. 

We use semantic-versioning so every breaking change will increase the major-version number.

## License

see [LICENSE file](LICENSE)
