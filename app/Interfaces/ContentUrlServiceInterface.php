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

}
