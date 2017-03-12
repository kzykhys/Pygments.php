<?php

namespace KzykHys\Pygments\Test;

use KzykHys\Pygments\Pygments;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class PygmentsTest extends TestCase
{
    /**
     * @dataProvider provideSamples
     */
    public function testHighlight($input, $expected, $expectedL, $expectedG, $lexer)
    {
        $pygments = new Pygments(getenv('PYGMENTIZE_PATH'));

        $this->assertEquals($expectedG, $pygments->highlight($input, null, 'html'));
        $this->assertEquals($expected, $pygments->highlight($input, $lexer, 'html'));
        $this->assertEquals($expectedL, $pygments->highlight($input, null, 'html', ['linenos' => 1]));
    }

    /**
     * @dataProvider provideCss
     */
    public function testGetCss($expected, $expectedA, $style)
    {
        $pygments = new Pygments(getenv('PYGMENTIZE_PATH'));

        $this->assertEquals($expected, $pygments->getCss($style));
        $this->assertEquals($expectedA, $pygments->getCss($style, '.syntax'));
    }

    public function testGetLexers()
    {
        $pygments = new Pygments(getenv('PYGMENTIZE_PATH'));
        $lexers = $pygments->getLexers();

        $this->assertArrayHasKey('python', $lexers);
    }

    public function testGetFormatters()
    {
        $pygments = new Pygments(getenv('PYGMENTIZE_PATH'));
        $formatters = $pygments->getFormatters();

        $this->assertArrayHasKey('html', $formatters);
    }

    public function testGetStyles()
    {
        $pygments = new Pygments(getenv('PYGMENTIZE_PATH'));
        $styles = $pygments->getStyles();

        $this->assertArrayHasKey('monokai', $styles);
    }

    public function testGuessLexer()
    {
        $pygments = new Pygments(getenv('PYGMENTIZE_PATH'));

        $this->assertEquals('php', $pygments->guessLexer('index.php'));
        $this->assertEquals('go', $pygments->guessLexer('main.go'));
    }

    public function provideSamples()
    {
        $finder = new Finder();
        $finder
            ->in(__DIR__ . '/Resources/pygments-' . getenv('PYGMENTIZE_VERSION') . '/example')
            ->name("*.in")
            ->files()
            ->ignoreVCS(true);

        $samples = [];

        /* @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $samples[] = [
                $file->getContents(),
                file_get_contents(str_replace('.in', '.out', $file->getPathname())),
                file_get_contents(str_replace('.in', '.linenos.out', $file->getPathname())),
                file_get_contents(str_replace('.in', '.guess.out', $file->getPathname())),
                preg_replace('/\..*/', '', $file->getFilename()),
            ];
        }

        return $samples;
    }

    public function provideCss()
    {
        $finder = new Finder();
        $finder
            ->in(__DIR__ . '/Resources/pygments-' . getenv('PYGMENTIZE_VERSION') . '/css')
            ->files()
            ->ignoreVCS(true)
            ->name('*.css')
            ->notName('*.prefix.css');

        $css = [];

        /* @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $css[] = [
                $file->getContents(),
                file_get_contents(str_replace('.css', '.prefix.css', $file->getPathname())),
                str_replace('.css', '', $file->getFilename()),
            ];
        }

        return $css;
    }
}
