# The LEAN Theme's styleguide
View in a single page all your coded UI components.

By default it reads the atoms, molecules, organisms and templates from the components folder of the Lean Theme.

## How to use

### Component Autoload

The UI components will be loaded automatically.

If they don't show up then you may need to pass dummy data. (Read next section)

### Passing dummy data to the components in the styleguide

To pass the arguments to the components in the styleguide, you will need to create a json file named exactly as the component
and declare the data in the following format:

```json
{
  "variants": [
    {
      "arg1": "Lorem Ipsum",
      "arg2": "Lorem Ipsum"
    }
  ]
}
```

**example:**

Component filename: _link.php_

Component dummy data filename: _link.json_

```json
{
  "variants": [
    {
      "class": "color-red",
      "title": "Lorem Ipsum Title",
      "url": "http://google.com"
    }
  ]
}
```

### Declaring component variants

Simply create another object in the variants array:

```json
{
  "variants": [
    {
      "arg1": "Lorem Ipsum",
      "arg2": "Lorem Ipsum"
    },
    {
      "arg1": "Lorem Ipsum",
      "arg2": "Lorem Ipsum"
    }
  ]
}
```

**example:**

```json
{
  "variants": [
    {
      "title": "Lorem Ipsum Title",
      "url": "http://google.com"
    },
    {
      "style": "red",
      "title": "Lorem Ipsum Title",
      "url": "http://google.com"
    }
  ]
}
```

**Note**: the `style` argument is a keyword convention in the styleguide.
You can declare multiple styles for a component and they will be printed in the component's heading info section.

Current version supports to 4 styles per component:

`style`

`style2`

`style3`

`style4`

### Removing the styleguide container in a component section

If you happen to have a full width component and want to visualize the component without the styleguide's container.

You can add `"container": false` in the json file of the component:

```json
{
  "container": false,
  "variants": [
    {
      "title": "Lorem Ipsum Title",
      "url": "http://google.com"
    }
  ]
}
```

## Configurations

### Change the template file

Use this filter:
`lean_styleguide_template`


**example**:

Adding your own template in the theme's folder.
```php
add_filter(
    'lean_styleguide_template',
    function() {
        return get_stylesheet_directory() . '/styleguide-template2.php';
    }
);
```

**Important**:
The custom template will need to have this 3 action calls:

`do_action( 'lean_styleguide_header' );`

`do_action( 'lean_styleguide_content' );`

`do_action( 'lean_styleguide_footer' );`

You can view the styleguide-template.php from this library for reference.

### Add code to the Head tag

Use this action: `lean_styleguide_header`

**example**:
```php
add_action(
    'lean_styleguide_header',
    function() {
        echo '<style>
        body {
            background-color: blue;
        }
        </style>';
    }
);
```

### Add code to the Footer tag

Use this action: `lean_styleguide_footer`

**example**:
```php
add_action(
    'lean_styleguide_footer',
    function() {
        echo '<script>
            console.log(\'hello\')
        </script>';
    }
);
```

### Change the component's folder path

Use this filter: `lean_styleguide_component_dir_path`

**example**:
```php
add_action(
	'lean_styleguide_component_dir_path',
	function() {
		return get_template_directory() . '/frontend/views';
	}
);
```

### Change the styleguide's CSS stylesheet

Use this filter: `lean_styleguide_css`

**example**:
```php
add_filter(
	'lean_styleguide_css',
	function() {
		return get_stylesheet_directory() . '/frontend/dist/main2.css';
	}
);
```