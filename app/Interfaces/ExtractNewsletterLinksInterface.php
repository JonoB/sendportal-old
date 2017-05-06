<?php

namespace App\Interfaces;

interface ExtractNewsletterLinksInterface
{
    /**
     * Find all links and replace them
     *
     * @param string $content
     * @return void
     */
    public function handle($content);
}
