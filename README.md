Pygments.php - A Thin Wrapper for the Python Pygments
=====================================================

[![Latest Stable Version](https://poser.pugx.org/kzykhys/pygments/v/stable.png)](https://packagist.org/packages/kzykhys/pygments)
[![Build Status](https://travis-ci.org/kzykhys/Pygments.php.png?branch=master)](https://travis-ci.org/kzykhys/Pygments.php)
[![Coverage Status](https://coveralls.io/repos/kzykhys/Pygments.php/badge.png)](https://coveralls.io/r/kzykhys/Pygments.php)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3efddd61-f6e2-4f4a-949d-9ca7230f6e56/mini.png)](https://insight.sensiolabs.com/projects/3efddd61-f6e2-4f4a-949d-9ca7230f6e56)

A PHP wrapper for the [Pygments](http://pygments.org/), the Python syntax highlighter.

Requirements
------------

* PHP5.3+
* Python 2.4+
* Pygments (`sudo easy_install Pygments`)

Installation
------------

Create or update your composer.json and run `composer update`

``` json
{
    "require": {
        "kzykhys/pygments": ">=1.0"
    }
}
```

Usage
-----

### Highlight the source code

``` php
<?php

use KzykHys\Pygments\Pygments;

$pygments = new Pygments();
$html = $pygments->highlight(file_get_contents('index.php'), 'php', 'html');
$text = $pygments->highlight('package main', 'go', 'ansi');
```

### Generate a CSS

``` php
<?php

use KzykHys\Pygments\Pygments;

$pygments = new Pygments();
$css = $pygments->getCss('monokai');
$prefixedCss = $pygments->getCss('default', '.syntax');
```

### Guesses a lexer name

``` php
<?php

use KzykHys\Pygments\Pygments;

$pygments = new Pygments();
$pygments->guessLexer('foo.rb'); // ruby
```

### Get a list of lexers/formatters/styles

``` php
<?php

use KzykHys\Pygments\Pygments;

$pygments = new Pygments();
$pygments->getLexers()
$pygments->getFormatters();
$pygments->getStyles();
```

### Custom `pygmentize` path

``` php
<?php

use KzykHys\Pygments\Pygments;

$pygments = new Pygments('/path/to/pygmentize');
```

License
-------

The MIT License

Author
------

Kazuyuki Hayashi (@kzykhys)
