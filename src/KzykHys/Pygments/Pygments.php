<?php

namespace KzykHys\Pygments;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class Pygments
{

    /**
     * @var string
     */
    private $pygmentize;

    /**
     * @param string $pygmentize
     */
    public function __construct($pygmentize = 'pygmentize')
    {
        $this->pygmentize = $pygmentize;
    }

    /**
     * Highlight the input code
     *
     * @param string $code
     * @param string $lexer
     * @param string $formatter
     * @param array  $options
     *
     * @return string
     */
    public function highlight($code, $lexer = null, $formatter = null, $options = array())
    {
        $builder = $this->createProcessBuilder();

        if ($lexer) {
            $builder->add('-l')->add($lexer);
        } else {
            $builder->add('-g');
        }

        if ($formatter) {
            $builder->add('-f')->add($formatter);
        }

        if (count($options)) {
            $arg = [];

            foreach ($options as $key => $value) {
                $arg[] = sprintf('%s=%s', $key, $value);
            }

            $builder->add('-O')->add(implode(',', $arg));
        }

        $process = $builder->getProcess();
        $process->setStdin($code);

        return $this->getOutput($process);
    }

    /**
     * @param string $style
     * @param null   $selector
     *
     * @return string
     */
    public function getCss($style = 'default', $selector = null)
    {
        $builder = $this->createProcessBuilder();
        $builder->add('-f')->add('html');
        $builder->add('-S')->add($style);

        if ($selector) {
            $builder->add('-a')->add($selector);
        }

        return $this->getOutput($builder->getProcess());
    }

    /**
     * @param $fileName
     *
     * @return string
     */
    public function guessLexer($fileName)
    {
        $process = $this->createProcessBuilder()
            ->setArguments(array('-N', $fileName))
            ->getProcess();

        return $this->getOutput($process);
    }

    /**
     * @return array
     */
    public function getLexers()
    {
        $process = $this->createProcessBuilder()
            ->setArguments(array('-L', 'lexer'))
            ->getProcess();

        $output = $this->getOutput($process);

        return $this->parseList($output);
    }

    /**
     * @return array
     */
    public function getFormatters()
    {
        $process = $this->createProcessBuilder()
            ->setArguments(array('-L', 'formatter'))
            ->getProcess();

        $output = $this->getOutput($process);

        return $this->parseList($output);
    }

    /**
     * @return array
     */
    public function getStyles()
    {
        $process = $this->createProcessBuilder()
            ->setArguments(array('-L', 'style'))
            ->getProcess();

        $output = $this->getOutput($process);

        return $this->parseList($output);
    }

    /**
     * @return ProcessBuilder
     */
    protected function createProcessBuilder()
    {
        return ProcessBuilder::create()->setPrefix($this->pygmentize);
    }

    /**
     * @param Process $process
     *
     * @throws \RuntimeException
     * @return string
     */
    protected function getOutput(Process $process)
    {
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }

    /**
     * @param $input
     *
     * @return array
     */
    protected function parseList($input)
    {
        $list = array();

        if (preg_match_all('/^\* (.*?):\r?\n *([^\r\n]*?)$/m', $input, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $names = explode(',', $match[1]);

                foreach ($names as $name) {
                    $list[trim($name)] = $match[2];
                }
            }
        }

        return $list;
    }

} 