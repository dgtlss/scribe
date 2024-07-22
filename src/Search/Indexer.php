<?php

namespace Dgtlss\Scribe\Search;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Indexer
{
    public function indexSubfolder(string $subfolder): array
    {
        $path = resource_path("views/docs/$subfolder");
        $index = [];

        if (!File::isDirectory($path)) {
            return $index;
        }

        $files = File::allFiles($path);

        foreach ($files as $file) {
            if ($file->getExtension() !== 'md') {
                continue;
            }

            $relativePath = Str::after($file->getPathname(), resource_path('views/docs/'));
            $content = File::get($file->getPathname());
            $title = $this->extractTitle($content);

            $index[] = [
                'path' => Str::beforeLast($relativePath, '.md'),
                'title' => $title,
                'content' => $this->sanitizeContent($content),
            ];
        }

        return $index;
    }

    private function extractTitle(string $content): string
    {
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            if (Str::startsWith(trim($line), '# ')) {
                return trim(Str::after($line, '# '));
            }
        }
        return 'Untitled';
    }

    private function sanitizeContent(string $content): string
    {
        // Remove Markdown syntax for better searching
        $content = preg_replace('/```[\s\S]*?```/', '', $content); // Remove code blocks
        $content = preg_replace('/#{1,6}\s.*/', '', $content); // Remove headers
        $content = preg_replace('/\[.*?\]\(.*?\)/', '', $content); // Remove links
        return strip_tags($content);
    }
}