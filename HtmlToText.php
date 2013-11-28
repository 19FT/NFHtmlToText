<?php
/**
 * Simple HTML to text converstion
 * Copyright 2013 Rob Allen
 * License: MIT
 *
 * Inspired by http://journals.jevon.org/users/jevon-phd/entry/19818
 *
 * Usage:
 *     $htmlToText = new \NF\HtmlToText();
 *     $textString = $htmlToText->convert($htmlString);
 *
 */

namespace NF;

use Exception;
use DOMDocument;
use DOMDocumentType;
use DOMElement;
use DOMText;

class HtmlToText
{
    // configurable properties
    protected $bulletSymbol = '*';
    protected $wrapColumn = 76;

    // Discard everything within these tags
    protected $ignoredBlockTags = array(
        'head',
        'script',
        'style',
    );

    // These tags need two new lines after them
    protected $doubleNewLineTags = array(
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'p',
        'div',
        'ol', 'ul',
        'table',
    );

    // These tags need a single new line after them
    protected $newLineTags = array(
        'br',
        'tr',
    );


    // Internal properties
    protected $liCharacter;

    /**
     * Convert $html to plain text
     *
     * @param  string $html HTML string
     * @return string       Plain text string
     */
    public function convert($html)
    {
        // Remove \r
        $html = str_replace("\r", "", $html);

        $doc = new DOMDocument();
        if (!$doc->loadHTML($html)) {
            throw new Exception("Failed to load html string");
        }

        // Walk the DOM document to convert to plain text
        $output = $this->processNode($doc);

        // Remove leading and trailing spaces on each line
        $output = preg_replace("/[ \t]*\n[ \t]*/im", "\n", $output);

        // Word wrap if required
        if ($this->wrapColumn) {
            $output = wordwrap($output, $this->wrapColumn);
        }

        // Remove leading and trailing whitespace
        $output = trim($output);

        // Add a final new line
        $output .= "\n";

        return $output;
    }

    /**
     * Recursively called method that creates the plain text required for a
     * give tag.
     *
     * NOTE: This is rather simplistic and only handles reasonably well
     * structured block tags along with OL and UL lists. All inline tags are
     * ignored.
     *
     * @param  DOMNode $node DOM node to process
     * @return string       plain text representation of the node
     */
    protected function processNode($node)
    {
        if ($node instanceof DOMDocumentType) {
            return '';
        }

        if ($node instanceof DOMText) {
            // return the plain text
            return preg_replace("/\\s+/im", " ", $node->wholeText);
        }

        $tag = strtolower($node->nodeName);
        if (in_array($tag, $this->ignoredBlockTags)) {
            return '';
        }

        $lastTag = $this->lastTagName($node);
        $nextTag = $this->nextTagName($node);
        $output = '';

        if ($tag == 'ol') {
            // ordered lists start from one
            $this->liCharacter = 1;
        }
        if ($tag == 'ul') {
            // unsigned lists start with a bullet point
            $this->liCharacter = $this->bulletSymbol;
        }

        if ($tag == 'li') {
            if ($lastTag == 'li') {
                // add a new line, but not for the first item
                $output .= "\n";
            }
            // add the list character and increment if an OL
            $output .= $this->liCharacter . ' ';
            if (is_int($this->liCharacter)) {
                $this->liCharacter++;
            }

        }

        // processes child nodes:
        if ($node->childNodes) {
            $numberOfNodes = $node->childNodes->length;
            for ($i = 0; $i < $numberOfNodes; $i++) {
                $childNode = $node->childNodes->item($i);
                $output .= $this->processNode($childNode);
            }
        }

        // add new lines
        if (in_array($tag, $this->doubleNewLineTags)){
            $output = trim($output);
            $output .= "\n\n";
        }
        if (in_array($tag, $this->newLineTags)) {
            $output = trim($output);
            $output .= "\n";
        }

        return $output;

    }

    /**
     * Find the previous sibling tag name for this node
     *
     * @param  DOMNode $node DOM node
     * @return string        tag name
     */
    protected function lastTagName($node)
    {
        $lastNode = $node->previousSibling;
        while ($lastNode != null) {
            if ($lastNode instanceof DOMElement) {
                break;
            }
            $lastNode = $lastNode->previousSibling;
        }
        $lastTag = '';
        if ($lastNode instanceof DOMElement && $lastNode != null) {
            $lastTag = strtolower($lastNode->nodeName);
        }

        return $lastTag;
    }

    /**
     * Find the next sibling tag name for this node
     *
     * @param  DOMNode $node DOM node
     * @return string        tag name
     */
    protected function nextTagName($node)
    {
        $nextNode = $node->nextSibling;
        while ($nextNode != null) {
            if ($nextNode instanceof DOMElement) {
                break;
            }
            $nextNode = $nextNode->nextSibling;
        }
        $nextTag = '';
        if ($nextNode instanceof DOMElement && $nextNode != null) {
            $nextTag = strtolower($nextNode->nodeName);
        }

        return $nextTag;
    }


    /**
     * Getter for bulletSymbol
     *
     * @return mixed
     */
    public function getBulletSymbol()
    {
        return $this->bulletSymbol;
    }

    /**
     * Setter for bulletSymbol. This is the symbol to use for an unsigned list.
     * Defaults to an asterisk.
     *
     * @param mixed $bulletSymbol Value to set
     * @return self
     */
    public function setBulletSymbol($bulletSymbol)
    {
        $this->bulletSymbol = $bulletSymbol;
        return $this;
    }

    /**
     * Getter for wrapColumn
     *
     * @return mixed
     */
    public function getWrapColumn()
    {
        return $this->wrapColumn;
    }

    /**
     * Setter for wrapColumn. This is the number of characters to wrap
     * long lines on. Set to zero to disable.
     *
     * @param mixed $wrapColumn Value to set
     * @return self
     */
    public function setWrapColumn($wrapColumn)
    {
        $this->wrapColumn = $wrapColumn;
        return $this;
    }
}
