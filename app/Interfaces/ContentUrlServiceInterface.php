<?php

namespace App\Interfaces;

interface ContentUrlServiceInterface
{
    /**
     * Detect all the unique links from a document
     *
     * @param string $content
     * @return array
     */
    public function extract($content);

    /**
     * Replace selected links in content
     *
     * @param array $from
     * @param array $to
     * @return string
     */
    public function replaceUrls($content, array $from = [], array $to = []);
}
