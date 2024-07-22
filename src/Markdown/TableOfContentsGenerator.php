<?php

namespace Dgtlss\Scribe\Markdown;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Node\Block\Heading;
use League\CommonMark\Node\Inline\Link;
use League\CommonMark\Node\Block\Paragraph;

class TableOfContentsGenerator implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addEventListener(DocumentParsedEvent::class, [$this, 'onDocumentParsed']);
    }

    public function onDocumentParsed(DocumentParsedEvent $event): void
    {
        $document = $event->getDocument();
        $toc = $this->generateTableOfContents($document);
        
        if (!empty($toc)) {
            $document->prependChild($toc);
        }
    }

    private function generateTableOfContents(Document $document): ?Paragraph
    {
        $tocItems = [];
        $headings = $document->filter(function ($node) {
            return $node instanceof Heading && $node->getLevel() <= 3;
        });

        foreach ($headings as $heading) {
            $id = $this->createAnchorId($heading);
            $link = new Link('#' . $id, $heading->getText());
            $tocItems[] = str_repeat('  ', $heading->getLevel() - 1) . '- ' . $link;
            
            // Add ID to the heading for linking
            $heading->data->set('attributes/id', $id);
        }

        if (empty($tocItems)) {
            return null;
        }

        $tocMarkdown = "## Table of Contents\n\n" . implode("\n", $tocItems);
        $tocDocument = $this->parseMarkdown($tocMarkdown);
        
        return $tocDocument->firstChild();
    }

    private function createAnchorId(Heading $heading): string
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $heading->getText()));
    }

    private function parseMarkdown(string $markdown): Document
    {
        $environment = new \League\CommonMark\Environment\Environment();
        $environment->addExtension(new \League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension());
        $parser = new \League\CommonMark\Parser\MarkdownParser($environment);
        
        return $parser->parse($markdown);
    }
}