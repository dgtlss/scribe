<?php

namespace Dgtlss\Scribe\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SearchController
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        $results = $this->searchInDocumentation($query);
        return response()->json($results);
    }

    private function searchInDocumentation($query)
    {
        $results = [];
        $docsPath = resource_path('views/docs');
        $files = File::allFiles($docsPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'md') {
                $content = file_get_contents($file->getPathname());
                if (Str::contains(strtolower($content), strtolower($query))) {
                    $relativePath = Str::after($file->getPathname(), $docsPath . '/');
                    $results[] = [
                        'title' => $this->extractTitle($content),
                        'path' => '/docs/' . str_replace('.md', '', $relativePath),
                        'excerpt' => $this->extractExcerpt($content, $query)
                    ];
                }
            }
        }

        return $results;
    }

    private function extractTitle($content)
    {
        if (preg_match('/^#\s*(.*)$/m', $content, $matches)) {
            return trim($matches[1]);
        }
        return 'Untitled';
    }

    private function extractExcerpt($content, $query)
    {
        $position = stripos($content, $query);
        if ($position === false) {
            return Str::limit(strip_tags($content), 100);
        }
        $start = max(0, $position - 50);
        $excerpt = substr($content, $start, 100);
        return '...' . strip_tags($excerpt) . '...';
    }
}