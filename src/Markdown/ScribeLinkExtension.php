<?php

namespace Dgtlss\Scribe\Markdown;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Inline\Parser\InlineParserInterface;
use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Parser\InlineParserMatch;
use League\CommonMark\Parser\Inline\InlineParserContext;

class ScribeLinkExtension implements ExtensionInterface, InlineParserInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addInlineParser($this);
    }

    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::regex('\[\[([^\]]+)\]\]');
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();
        $match = $cursor->match('/\[\[([^\]]+)\]\]/');
        
        if ($match === null) {
            return false;
        }

        $path = trim($match[1]);
        $url = route('scribe.show', ['path' => $path]);
        $link = new Link($url, $path);
        
        $inlineContext->getContainer()->appendChild($link);

        return true;
    }
}