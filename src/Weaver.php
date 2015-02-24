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
    /**
     * weave.
     *
     * @param string $body HTML body
     * @param array $fragments
     * @return
     */
    public function weave($body, array $fragments = array())
    {
        if (count($fragments) == 0) {
            return $body;
        }

        $dom = new \DOMDocument();
        $dom->loadHTML((string)$body);
        $xpath = new \DOMXPath($dom);

        $blocks = $xpath->query("/html/body/*");
        $parts = $blocks->length / count($fragments);
        $each = $blocks->length / $parts;

        $weaved = "";
        $fragmentIndex = 0;
        for ($i = 0; $i < $blocks->length; $i++) {
            $node = $blocks->item($i);
            $weaved .= $node->ownerDocument->saveXML($node);

            if (count($fragments) == 0) {
                continue;
            }

            if (($i+1) % $each != 0) {
                continue;
            }

            $weaved .= array_shift($fragments);
        }

        return $weaved;
    }
}
