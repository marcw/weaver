<?php

namespace MarcW\Weaver;

use \Wa72\HtmlPageDom\HtmlPageCrawler;


/**
 * Weaver
 *
 * @author Marc Weistroff <marc@weistroff.net>
 */
class Weaver
{
    public static function createPattern($nBlocks, $nFragments)
    {
        if (0 === $nBlocks) {
            return str_repeat("-", $nFragments);
        }
        if (0 === $nFragments) {
            return str_repeat("*", $nBlocks);
        }

        $nParts = $nFragments + 1;

        $length = floor($nBlocks / $nParts);
        if ($length == 0) {
            $length = 1;
        }

        $pattern = '';
        $nWeavedFragments = 0;
        $nWeavedBlocks = 0;

        $len = 0;
        for ($i = 0; $i < $nBlocks + $nFragments; $i++) {
            if ($nWeavedBlocks >= $nBlocks || ($i != 0 && $nWeavedFragments < $nFragments && $len == $length)) {
                $pattern .= '-';
                $len = 0;
                $nWeavedFragments++;
                continue;
            }
            if ($nWeavedBlocks < $nBlocks) {
                $pattern .= '*';
                $nWeavedBlocks++;
            }
            $len++;
        }

        return $pattern;
    }

    /**
     * weave.
     *
     * @param string $body HTML body
     * @param array $fragments
     * @return
     */
    public function weave($body, array $fragments = [])
    {
        $fragments = array_filter($fragments, function($value) {
            return ($value != "" && $value != null);
        });
        $nFragments = count($fragments);
        if ($nFragments == 0) {
            return $body;
        }

        $nExplicit = substr_count($body, '__WEAVE__');
        if ($nExplicit > 0) {
            for($i = 0; $i < $nExplicit; $i++) {
                $body = preg_replace('/__WEAVE__/', array_shift($fragments), $body, 1);
            }
        }

        $nFragments = count($fragments);
        if ($nFragments == 0) {
            return $body;
        }

        $dom = new \DOMDocument();
        $dom->loadHTML(utf8_decode((string)$body));
        $xpath = new \DOMXPath($dom);

        $blocks = $xpath->query("/html/body/*");
        if ($blocks->length === 0) {
            return implode("", $fragments);
        }

        $pattern = $this->createPattern($blocks->length, $nFragments);

        $fabric = '';
        // we now weave the pattern
        $i = 0;
        foreach (str_split($pattern) as $code) {
            switch ($code) {
            case '*':
                $node = $blocks->item($i);
                $i++;
                $fabric .= $node->ownerDocument->saveXML($node);
                break;
            case '-':
                $fabric .= array_shift($fragments);
                break;
            }
        }

        return $fabric;
    }
}

