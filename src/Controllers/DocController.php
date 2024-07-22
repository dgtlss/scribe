<?php

namespace Dgtlss\Scribe\Controllers;

use Illuminate\Support\Facades\File;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use Illuminate\Support\Str;

class DocController
{
    public function show($path = 'index')
    {
        $filePath = resource_path("views/docs/{$path}.md");

        if (!File::exists($filePath)) {
            abort(404);
        }

        $content = File::get($filePath);
        
        $title = $this->extractTitle($content);
        $content = $this->removeFirstH1($content);

        $html = $this->convertMarkdownToHtml($content);
        
        $tableOfContents = $this->generateTableOfContents($html);

        return view('scribe::layout', [
            'content' => $html,
            'currentPath' => $path,
            'logo' => config('scribe.logo.path'),
            'sidebar' => $this->generateSidebar($path),
            'title' => $title,
            'tableOfContents' => $tableOfContents,
            'filePath' => $filePath,
        ]);
    }

    private function extractTitle($content)
    {
        if (preg_match('/^#\s*(.*)$/m', $content, $matches)) {
            return trim($matches[1]);
        }
        return 'Documentation';
    }

    private function removeFirstH1($content)
    {
        return preg_replace('/^#\s*.*$/m', '', $content, 1);
    }

    // private function convertMarkdownToHtml($markdown)
    // {
    //     $environment = new Environment();
    //     $environment->addExtension(new CommonMarkCoreExtension());
    //     $environment->addRenderer(FencedCode::class, new CodeBlockRenderer());

    //     // we need to add id's to headers for the table of contents, so we need to enable the header_id extension but dont show any ¶
    //     $environment->addExtension(new \League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension());
    //     $environment->addExtension(new \League\CommonMark\Extension\Autolink\AutolinkExtension());

        

    //     $converter = new MarkdownConverter( $environment);
    //     return $converter->convert($markdown);
    // }

    private function convertMarkdownToHtml($markdown)
    {
        if(empty($markdown)){
            $markdown = 'No content found, please add some content to this page.';
        }

        $environment = new Environment([
            'heading_permalink' => [
                'html_class' => 'heading-permalink',
                'id_prefix' => '',
                'fragment_prefix' => '',
                'insert' => 'before',
                'min_heading_level' => 1,
                'max_heading_level' => 6,
                'title' => 'Permalink',
                'symbol' => '',  // Remove the visible symbol
                'aria_hidden' => true,
            ],
        ]);

        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new \League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension());
        $environment->addExtension(new \League\CommonMark\Extension\Autolink\AutolinkExtension());
        $environment->addRenderer(FencedCode::class, new CodeBlockRenderer());

        $converter = new MarkdownConverter($environment);
        return $converter->convert($markdown);
    }


    private function generateSidebar($currentPath)
    {
        $docsPath = resource_path('views/docs');
        $currentProject = explode('/', $currentPath)[0];
        $projectPath = $docsPath . '/' . $currentProject;
        
        if (!File::isDirectory($projectPath)) {
            return '';
        }

        $structure = $this->getProjectStructure($projectPath);
        return $this->renderSidebar($structure, $currentPath, $currentProject);
    }

    private function getProjectStructure($path)
    {
        $structure = [];
        $items = File::allFiles($path);

        foreach ($items as $item) {
            $relativePath = Str::after($item->getPathname(), $path . '/');
            $parts = explode('/', $relativePath);
            $filename = pathinfo($parts[count($parts) - 1], PATHINFO_FILENAME);

            if ($item->getExtension() !== 'md' || $filename === 'index') {
                continue;
            }

            $current = &$structure;
            for ($i = 0; $i < count($parts) - 1; $i++) {
                if (!isset($current[$parts[$i]])) {
                    $current[$parts[$i]] = [];
                }
                $current = &$current[$parts[$i]];
            }
            $current[$filename] = str_replace('.md', '', $relativePath);
        }

        return $structure;
    }

    private function getFilesInDirectory($directory, $projectName)
    {
        $files = [];
        $allFiles = File::allFiles($directory);

        foreach ($allFiles as $file) {
            if ($file->getExtension() === 'md') {
                $relativePath = Str::after($file->getPathname(), resource_path('views/docs/'));
                $filename = $file->getFilenameWithoutExtension();
                if ($filename !== 'index') {
                    $files[$filename] = str_replace('.md', '', $relativePath);
                }
            }
        }

        return $files;
    }

    // private function renderSidebar($structure, $currentSubfolder, $path = '')
    // {
    //     $html = '<ul class="space-y-1">';
    //     foreach ($structure as $key => $value) {
    //         if (is_array($value)) {
    //             $html .= '<li>';
    //             $html .= '<div class="font-display font-medium text-slate-900 dark:text-white mt-4 mb-2">' . ucfirst($key) . '</div>';
    //             $html .= $this->renderSidebar($value, $currentSubfolder, $path . $key . '/');
    //             $html .= '</li>';
    //         } else {
    //             $isActive = $currentSubfolder === explode('/', $value)[0];
    //             $activeClass = $isActive ? 'block w-full pl-3.5 before:pointer-events-none font-semibold text-sky-500 before:bg-sky-500' : 'block w-full pl-3.5 text-slate-500 before:hidden before:bg-slate-300 hover:text-slate-600 hover:before:block dark:text-slate-400 dark:before:bg-slate-700 dark:hover:text-slate-300';
    //             $html .= '<li><a href="/docs/' . $value . '" class="' . $activeClass . ' block px-2 py-1 rounded-md text-sm">' . ucfirst($key) . '</a></li>';
    //         }
    //     }
    //     $html .= '</ul>';
    //     return $html;
    // }

    private function renderSidebar($structure, $currentPath, $currentProject, $path = '')
    {
        $html = '<ul class="space-y-2">';
        
        if (empty($path)) {
            $html .= '<li id="projectTitle" class="font-semibold text-2xl mb-4 text-gray-100 w-full">' . Str::title($currentProject) . '</li>';
            
            // Sort the structure to prioritize files over folders
            $files = array_filter($structure, 'is_string');
            $folders = array_filter($structure, 'is_array');
            
            // Render files first
            foreach ($files as $key => $value) {
                $fullPath = $currentProject . '/' . $value;
                $isActive = $fullPath === $currentPath;
                $activeClass = $isActive 
                    ? 'block w-full pl-3.5 font-semibold text-sky-500' 
                    : 'block w-full pl-3.5 text-slate-500 hover:text-slate-600 hover:before:block dark:text-slate-400 dark:before:bg-slate-700 dark:hover:text-slate-300';
                $html .= '<li><a href="/docs/' . $fullPath . '" class="' . $activeClass . ' block px-2 py-1 rounded-md text-sm">' . Str::title($key) . '</a></li>';
            }
            
            // Then render folders
            $structure = $folders;
        }

        foreach ($structure as $key => $value) {
            if (is_array($value)) {
                $html .= '<li>';
                $html .= '<div class="font-medium text-gray-100 mb-1">' . Str::title($key) . '</div>';
                $html .= $this->renderSidebar($value, $currentPath, $currentProject, $path . $key . '/');
                $html .= '</li>';
            } else {
                $fullPath = $currentProject . '/' . $value;
                $isActive = $fullPath === $currentPath;
                $activeClass = $isActive 
                    ? 'block w-full pl-3.5 font-semibold text-sky-500' 
                    : 'block w-full pl-3.5 text-slate-500 hover:text-slate-600 hover:before:block dark:text-slate-400 dark:before:bg-slate-700 dark:hover:text-slate-300';
                $html .= '<li><a href="/docs/' . $fullPath . '" class="' . $activeClass . ' block px-2 py-1 rounded-md text-sm">' . Str::title($key) . '</a></li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }

    private function generateTableOfContents($html)
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new \DOMXPath($dom);
        $headers = $xpath->query('//h1|//h2|//h3|//h4|//h5|//h6');

        $toc = '<ul class="space-y-1 text-sm">';
        $previousLevel = 0;
        foreach ($headers as $header) {
            $level = intval(str_replace('h', '', $header->nodeName));
            $text = $header->textContent;
            $id = $this->generateSafeId($text);
            $header->setAttribute('id', $id);

            if ($level > $previousLevel) {
                $toc .= '<ul class="ml-1 space-y-2">';
            } else if ($level < $previousLevel) {
                $toc .= str_repeat('</ul>', $previousLevel - $level);
            }

            $toc .= '<li><a href="#' . $id . '" class="text-gray-300 hover:text-white toc-item">' . $text . '</a></li>';

            $previousLevel = $level;
        }
        $toc .= str_repeat('</ul>', $previousLevel);

        return $toc;
    }

    private function generateSafeId($text)
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $text));
    }
}

class CodeBlockRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (!($node instanceof FencedCode)) {
            throw new \InvalidArgumentException('Incompatible node type: ' . get_class($node));
        }

        $attributes = $node->data->get('attributes', []);
        $language = $node->getInfo() ?: 'text';
        $attributes['class'] = isset($attributes['class']) ? $attributes['class'] . ' ' : '';
        $attributes['class'] .= 'language-' . $language;

        $content = $node->getLiteral();
        
        if (config('scribe.syntax_highlighting.line_numbers', true)) {
            $attributes['class'] .= ' line-numbers';
        }

        return new HtmlElement(
            'pre',
            [],
            new HtmlElement('code', $attributes, $content)
        );
    }
}