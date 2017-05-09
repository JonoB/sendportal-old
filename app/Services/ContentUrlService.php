<?php

namespace App\Services;

use App\Interfaces\ContentUrlServiceInterface;

class ContentUrlService implements ContentUrlServiceInterface
{
    /**
     * The extracted urls
     *
     * @var array
     */
    protected $urls = [];

    /**
     * @param string $content
     * @return array
     */
    public function extract($content)
    {
        return $this->detectUrls($content);
    }

    /**
     * Extract all urls from the dom
     *
     * @param string $content
     * @return array
     */
    protected function detectUrls($content)
    {
        $nodes = $this->parseNodes($content);

        foreach ($nodes as $node)
        {
            $this->extractUrlFromNode($node);
        }

        return $this->urls;
    }

    /**
     * Get the href from the dom node
     *
     * @param string $node
     * @return void
     */
    protected function extractUrlFromNode($node)
    {
        if ( ! $url = $node->getAttribute('href'))
        {
            return;
        }

        if (filter_var($url, FILTER_VALIDATE_URL) === false)
        {
            return;
        }

        if (stripos($url, 'http') === false)
        {
            return;
        }

        $this->appendUrl($url);
    }

    /**
     * Append the url to the collection
     *
     * @param string $url
     * @return void
     */
    protected function appendUrl($url)
    {
        $url = $this->cleanUrl($url);

        // only append if this url is unique
        if ( ! in_array($url, $this->urls))
        {
            $this->urls[] = $url;
        }
    }

    /**
     * Remove trailing slash from url
     *
     * @param string $url
     * @return string
     */
    protected function cleanUrl($url)
    {
        return rtrim($url,'/');
    }

    /**
     * Get all links from the dom
     *
     * @param $content
     * @return \DOMNodeList
     */
    protected function parseNodes($content)
    {
        $dom = new \DOMDocument();

        // this needs to be enabled so that invalid HTML markup
        // does not throw an error
        libxml_use_internal_errors(true);

        $dom->loadHTML($content);

        return $dom->getElementsByTagName('a');
    }
}
