<?php

class PygmentsTest extends PHPUnit_Framework_TestCase
{

    public function testPygments()
    {
        $pygments = new \KzykHys\Pygments\Pygments();
        $html = $pygments->highlight('<?php phpinfo();', null, 'html', ['linenos' => 1]);

        var_dump($html);
        var_dump($pygments->getCss());
        var_dump($pygments->getStyles());
        var_dump($pygments->getLexers());
        var_dump($pygments->getFormatters());
    }

} 