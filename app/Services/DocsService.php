<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DocsService extends Controller
{
    /**
     * Get the base path for documentation files
     */
    private static function getDocsPath(): string
    {
        return app_path('UI/views/Documentation');
    }

    /**
     * Build hierarchical tree structure from documentation folder
     */
    public static function buildTree(?string $parentPath = null): array
    {
        $basePath = self::getDocsPath();
        $currentPath = $parentPath ? $basePath . '/' . $parentPath : $basePath;

        if (!File::exists($currentPath)) {
            return [];
        }

        $items = [];
        $directories = File::directories($currentPath);
        $files = File::files($currentPath);

        // Process directories first
        foreach ($directories as $directory) {
            $dirName = basename($directory);
            $relativePath = $parentPath ? $parentPath . '/' . $dirName : $dirName;

            $items[] = [
                'id' => md5($relativePath),
                'type' => 'folder',
                'text' => self::formatName($dirName),
                'path' => $relativePath,
                'leaf' => false,
                'state' => 'closed',
            ];
        }

        // Process markdown files
        foreach ($files as $file) {
            if ($file->getExtension() !== 'md') {
                continue;
            }

            $fileName = $file->getFilename();
            $relativePath = $parentPath ? $parentPath . '/' . $fileName : $fileName;

            $items[] = [
                'id' => md5($relativePath),
                'type' => 'document',
                'text' => self::formatName(Str::replace('.md', '', $fileName)),
                'path' => $relativePath,
                'leaf' => true,
            ];
        }

        return $items;
    }

    /**
     * Get document content and metadata
     */
    public static function getDocument(string $path): array
    {
        $filePath = self::getDocsPath() . '/' . $path;

        if (!File::exists($filePath)) {
            return [
                'found' => false,
                'content' => null,
                'html' => null,
                'title' => null,
                'toc' => [],
                'breadcrumbs' => [],
            ];
        }

        $markdownContent = File::get($filePath);
        $html = Str::markdown($markdownContent, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return [
            'found' => true,
            'content' => $markdownContent,
            'html' => $html,
            'title' => self::extractTitle($markdownContent),
            'toc' => self::extractTableOfContents($markdownContent),
            'breadcrumbs' => self::getBreadcrumbs($path),
        ];
    }

    /**
     * Get default (first) document
     */
    public static function getDefaultDocument(): ?string
    {
        $docsPath = self::getDocsPath();

        // Look for getting-started.md first
        if (File::exists($docsPath . '/getting-started.md')) {
            return 'getting-started.md';
        }

        // Otherwise, get first .md file
        $files = File::files($docsPath);
        foreach ($files as $file) {
            if ($file->getExtension() === 'md') {
                return $file->getFilename();
            }
        }

        return null;
    }

    /**
     * Search documentation files
     */
    public static function searchDocs(string $query): array
    {
        if (empty(trim($query))) {
            return [];
        }

        $results = [];
        $docsPath = self::getDocsPath();
        $allFiles = File::allFiles($docsPath);
        $query = strtolower($query);

        foreach ($allFiles as $file) {
            if ($file->getExtension() !== 'md') {
                continue;
            }

            $content = File::get($file->getPathname());
            $relativePath = Str::replace($docsPath . '/', '', $file->getPathname());

            // Search in filename and content
            $filename = strtolower($file->getFilename());
            $contentLower = strtolower($content);

            if (str_contains($filename, $query) || str_contains($contentLower, $query)) {
                // Extract context around match
                $context = self::extractSearchContext($content, $query);

                $results[] = [
                    'id' => md5($relativePath),
                    'type' => 'document',
                    'text' => self::formatName(Str::replace('.md', '', $file->getFilename())),
                    'path' => $relativePath,
                    'context' => $context,
                    'leaf' => true,
                ];
            }
        }

        return $results;
    }

    /**
     * Extract table of contents from Markdown headings
     */
    private static function extractTableOfContents(string $markdown): array
    {
        $toc = [];
        $lines = explode("\n", $markdown);

        foreach ($lines as $line) {
            // Match headings (## or ###, skip # for main title)
            if (preg_match('/^(#{2,3})\s+(.+)$/', $line, $matches)) {
                $level = strlen($matches[1]);
                $text = $matches[2];
                $slug = Str::slug($text);

                $toc[] = [
                    'level' => $level,
                    'text' => $text,
                    'slug' => $slug,
                ];
            }
        }

        return $toc;
    }

    /**
     * Extract title from Markdown (first # heading)
     */
    private static function extractTitle(string $markdown): ?string
    {
        $lines = explode("\n", $markdown);

        foreach ($lines as $line) {
            if (preg_match('/^#\s+(.+)$/', $line, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Generate breadcrumbs from file path
     */
    private static function getBreadcrumbs(string $path): array
    {
        $breadcrumbs = [
            ['text' => 'Documentation', 'path' => null],
        ];

        $parts = explode('/', $path);
        $currentPath = '';

        foreach ($parts as $index => $part) {
            $currentPath .= ($currentPath ? '/' : '') . $part;
            $isLast = $index === count($parts) - 1;

            $text = self::formatName(Str::replace('.md', '', $part));

            $breadcrumbs[] = [
                'text' => $text,
                'path' => $isLast ? null : $currentPath,
            ];
        }

        return $breadcrumbs;
    }

    /**
     * Format folder/file name for display
     */
    private static function formatName(string $name): string
    {
        return Str::of($name)
            ->replace('-', ' ')
            ->replace('_', ' ')
            ->title()
            ->toString();
    }

    /**
     * Extract context around search match
     */
    private static function extractSearchContext(string $content, string $query, int $contextLength = 150): string
    {
        $content = strip_tags(Str::markdown($content));
        $pos = stripos($content, $query);

        if ($pos === false) {
            return Str::limit($content, $contextLength);
        }

        $start = max(0, $pos - $contextLength / 2);
        $excerpt = substr($content, $start, $contextLength);

        if ($start > 0) {
            $excerpt = '...' . $excerpt;
        }

        if ($start + $contextLength < strlen($content)) {
            $excerpt .= '...';
        }

        return $excerpt;
    }
}
