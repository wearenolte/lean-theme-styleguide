# The LEAN Theme's styleguide
View in a single page all your coded UI components.

By default it reads the atoms, molecules, organisms and templates from the components folder of the Lean Theme.

##Configurations
###Change the template file

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

###Add code to the Head tag

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

###Add code to the Footer tag

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

###Change the component's folder path

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

###Change the styleguide's CSS stylesheet

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