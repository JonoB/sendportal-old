<?php

namespace App\Services;

use App\Interfaces\ExtractNewsletterLinksInterface;

class ExtractNewsletterLinksService implements ExtractNewsletterLinksInterface
{
    protected $urls = [];

    public function handle($content)
    {
        return $this->detectUrls($content);
    }

    protected function detectUrls($content)
    {
        $nodes = $this->parseNodes($content);

        foreach ($nodes as $node)
        {
            $this->handleNode($node);
        }

        return $this->urls;
    }

    protected function handleNode($node)
    {
        if ( ! $url = $node->getAttribute('href'))
        {
            return;
        }

        if ( ! str_contains($url, 'http'))
        {
            return;
        }

        $this->appendUrl($url);
    }

    protected function appendUrl($url)
    {
        $url = $this->cleanUrl($url);

        // only append if this is unique
        if ( ! in_array($url, $this->urls))
        {
            $this->urls[] = $url;
        }
    }

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
