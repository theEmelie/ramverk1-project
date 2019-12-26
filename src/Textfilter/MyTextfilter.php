<?php
namespace Anax\Textfilter;

use \Michelf\Markdown;

/**
 * Filter and format text content.
 */
class MyTextfilter
{
    /**
     * @var array $filters Supported filters with method names of
     *                     their respective handler.
     */
    protected $filters = [

        "markdown"  => "markdown",
    ];



    /**
     * Call each filter on the text and return the processed text.
     *
     * @param string $text   The text to filter.
     * @param array  $filter Array of filters to use.
     *
     * @return string with the formatted text.
     */
    public function parse($text, $filter)
    {
        switch ($filter) {
            case "markdown":
                $output = $this->markdown($text);
                break;

            default:
                $output = $text;
                break;
        }
        return $output;
    }


    /**
     * Format text according to Markdown syntax.
     *
     * @param string $text The text that should be formatted.
     *
     * @return string as the formatted html text.
     *
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function markdown($text)
    {
        return Markdown::defaultTransform($text);
    }
}
