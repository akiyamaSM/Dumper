**Note: project isn't complete use at your own risk**

# README

A simple HTML variable dumper for PHP >= 5.3.

## Usage
```php
    use Wicked\Dumper\Dumper;

    ...

    $array = [1, true, 0.8, "String"];

    $dumper = new Dumper();

    $dumper->dump($array);
```

#### Output

<samp class="parent">(Array : length 4) <span class="arrow">▶</span> [<samp class="child" style="display: block;">[0] =&gt; integer 1</samp><samp class="child" style="display: block;">[1] =&gt; boolean true</samp><samp class="child" style="display: block;">[2] =&gt; double 0.8</samp><samp class="child" style="display: block;">[3] =&gt; string [length : 6] String</samp>]</samp>


## TODO
* Extensive tests on Dumper
* Create styling
* Inject CSS and JavaScript
* Visibility highlighting for object properties
* Comments and documentation
* I'm sure that I'm forgetting something
