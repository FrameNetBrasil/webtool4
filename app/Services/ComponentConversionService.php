<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ComponentConversionService
{
    /**
     * Component mappings from components41 to HTML
     */
    private array $componentMappings = [
        'form' => [
            'template' => '<form id="{id}" name="{id}" {attributes} class="ui form">
    <div class="ui card h-full w-full mb-2">
        {title_section}
        <div class="flex-grow-1 content bg-white" style="border-bottom: 1px solid rgba(34, 36, 38, 0.1);">
            {fields}
        </div>
        {buttons_section}
    </div>
</form>',
            'title_section' => '<div class="pt-2 pb-2 pl-3"><h3 class="ui header line-height-4">{title}</h3></div>',
            'buttons_section' => '<div class="flex-grow-0 pl-3 pt-2 pb-2">
            {buttons}
        </div>',
            'slots' => ['fields', 'buttons'],
            'attributes' => ['id', 'title', 'center', 'border']
        ],
        'hidden-field' => [
            'template' => '<input type="hidden" id="{id}" name="{id}" value="{value}">',
            'attributes' => ['id', 'value']
        ],
        'text-field' => [
            'template' => '<label for="{id}">{label}</label>
<div class="ui small input">
    <input
        type="text"
        id="{id}"
        name="{id}"
        value="{value}"
        placeholder="{placeholder}"
        {attributes}
    >
</div>',
            'attributes' => ['id', 'label', 'value', 'placeholder']
        ],
        'multiline-field' => [
            'template' => '<div class="form-field field">
    <label for="{id}">{label}</label>
    <textarea
        id="{id}"
        name="{id}"
        placeholder="{placeholder}"
        rows="{rows}"
        {attributes}
    >{value}</textarea>
</div>',
            'attributes' => ['id', 'label', 'value', 'placeholder', 'rows']
        ],
        'submit' => [
            'template' => '<button type="submit" class="ui medium {color} button" {attributes}>
    {label}
</button>',
            'attributes' => ['label', 'color']
        ]
    ];

    /**
     * Parse a Blade template and extract component usage
     */
    public function parseTemplate(string $templatePath): array
    {
        if (!File::exists($templatePath)) {
            throw new \Exception("Template file not found: {$templatePath}");
        }

        $content = File::get($templatePath);
        $components = [];

        // Find all components, including nested ones
        $this->findAllComponents($content, $components);

        return $components;
    }

    /**
     * Find all components in content, including nested ones
     */
    private function findAllComponents(string $content, array &$components): void
    {
        // Use a more comprehensive approach to find all x- tags
        $pattern = '/<x-([a-z0-9\-\.]+)([^>]*?)(\/>|>(?:.*?)<\/x-\1>)/s';
        
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        foreach ($matches as $match) {
            $componentName = $match[1][0];
            $attributesString = $match[2][0];
            $fullMatch = $match[0][0];
            $startPos = $match[0][1];
            $endPos = $startPos + strlen($fullMatch);
            
            $attributes = $this->parseAttributes($attributesString);
            
            // Check if it's a self-closing tag
            if (str_ends_with(trim($match[3][0]), '/>')) {
                $components[] = [
                    'name' => $componentName,
                    'full_match' => $fullMatch,
                    'attributes' => $attributes,
                    'slots' => [],
                    'content' => '',
                    'start_pos' => $startPos,
                    'end_pos' => $endPos
                ];
            } else {
                // Extract content between opening and closing tags
                $openingTag = $match[1][0] . $match[2][0];
                $openingTagEnd = strpos($fullMatch, '>') + 1;
                $closingTagStart = strrpos($fullMatch, '</x-' . $componentName . '>');
                
                $innerContent = substr($fullMatch, $openingTagEnd, $closingTagStart - $openingTagEnd);
                $slots = $this->parseSlots($innerContent);
                
                $components[] = [
                    'name' => $componentName,
                    'full_match' => $fullMatch,
                    'attributes' => $attributes,
                    'slots' => $slots,
                    'content' => $innerContent,
                    'start_pos' => $startPos,
                    'end_pos' => $endPos
                ];
            }
        }
    }

    /**
     * Parse component attributes from string
     */
    private function parseAttributes(string $attributesString): array
    {
        $attributes = [];
        $attributesString = trim($attributesString);
        
        if (empty($attributesString)) {
            return $attributes;
        }
        
        // More robust pattern to handle complex PHP expressions and hyphenated attributes
        $pattern = '/([\w\-]+)=(["\'])((?:[^"\'\\\\]|\\\\.)*)?\2|:([\w\-]+)=(["\'])((?:[^"\'\\\\]|\\\\.)*)\5/';
        
        preg_match_all($pattern, $attributesString, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            if (!empty($match[1])) {
                // Regular attribute
                $attributes[$match[1]] = $match[3] ?? '';
            } elseif (!empty($match[4])) {
                // Dynamic attribute (:attribute) - preserve PHP expression
                $attributes[$match[4]] = $match[6] ?? '';
            }
        }

        return $attributes;
    }

    /**
     * Parse slot content
     */
    private function parseSlots(string $content): array
    {
        $slots = [];

        // Match named slots like <x-slot:fields>content</x-slot:fields>
        $slotPattern = '/<x-slot:(\w+)>(.*?)<\/x-slot:\1>/s';
        preg_match_all($slotPattern, $content, $slotMatches, PREG_SET_ORDER);

        foreach ($slotMatches as $match) {
            $slots[$match[1]] = trim($match[2]);
        }

        // If no named slots found, treat entire content as default slot
        if (empty($slots) && !empty(trim($content))) {
            $slots['default'] = trim($content);
        }

        return $slots;
    }

    /**
     * Convert a single component to HTML
     */
    public function convertComponent(array $component): string
    {
        $componentName = $component['name'];
        
        // First try hardcoded mappings for complex components
        if (isset($this->componentMappings[$componentName])) {
            $mapping = $this->componentMappings[$componentName];
            
            // Handle special processing for different component types
            switch ($componentName) {
                case 'form':
                    return $this->convertFormComponent($component, $mapping);
                case 'hidden-field':
                case 'text-field':
                case 'multiline-field':
                case 'submit':
                    return $this->convertFieldComponent($component, $mapping);
                default:
                    return $this->convertGenericComponent($component, $mapping);
            }
        }
        
        // Try to load component from components41 folder
        return $this->convertComponents41Component($component);
    }

    private function convertFormComponent(array $component, array $mapping): string
    {
        $template = $mapping['template'];
        
        // Replace basic attributes
        foreach ($component['attributes'] as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }

        // Handle title section
        if (isset($component['attributes']['title']) && !empty($component['attributes']['title'])) {
            $titleSection = str_replace('{title}', $component['attributes']['title'], $mapping['title_section']);
            $template = str_replace('{title_section}', $titleSection, $template);
        } else {
            $template = str_replace('{title_section}', '', $template);
        }

        // Handle slots
        $template = str_replace('{fields}', $component['slots']['fields'] ?? '', $template);
        
        // Handle buttons section
        if (isset($component['slots']['buttons']) && !empty(trim($component['slots']['buttons']))) {
            $buttonsSection = str_replace('{buttons}', $component['slots']['buttons'], $mapping['buttons_section']);
            $template = str_replace('{buttons_section}', $buttonsSection, $template);
        } else {
            $template = str_replace('{buttons_section}', '', $template);
        }

        // Handle other attributes that might be passed to the form tag
        $otherAttributes = [];
        foreach ($component['attributes'] as $key => $value) {
            if (!in_array($key, ['id', 'title', 'center', 'border'])) {
                $otherAttributes[] = $key . '="' . htmlspecialchars($value) . '"';
            }
        }
        $template = str_replace('{attributes}', implode(' ', $otherAttributes), $template);

        // Clean up any remaining placeholders
        $template = preg_replace('/\{[^}]+\}/', '', $template);

        return $template;
    }

    private function convertFieldComponent(array $component, array $mapping): string
    {
        $template = $mapping['template'];
        
        // Replace explicitly mapped attributes
        $knownAttributes = $mapping['attributes'] ?? [];
        foreach ($knownAttributes as $attr) {
            if (isset($component['attributes'][$attr])) {
                $template = str_replace('{' . $attr . '}', $component['attributes'][$attr], $template);
            }
        }

        // Handle additional attributes (like HTMX, HTML attributes)
        $otherAttributes = [];
        foreach ($component['attributes'] as $key => $value) {
            if (!in_array($key, $knownAttributes)) {
                $otherAttributes[] = $key . '="' . htmlspecialchars($value) . '"';
            }
        }
        $template = str_replace('{attributes}', implode(' ', $otherAttributes), $template);

        // Clean up remaining placeholders by setting defaults
        $template = str_replace('{placeholder}', '', $template);
        $template = str_replace('{rows}', '3', $template);
        $template = str_replace('{color}', 'primary', $template);
        $template = preg_replace('/\{[^}]+\}/', '', $template);

        return $template;
    }

    private function convertGenericComponent(array $component, array $mapping): string
    {
        $template = $mapping['template'];

        // Replace attribute placeholders
        foreach ($component['attributes'] as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }

        // Replace slot placeholders
        foreach ($component['slots'] as $slotName => $slotContent) {
            $template = str_replace('{' . $slotName . '}', $slotContent, $template);
        }

        // Clean up any remaining placeholders
        $template = preg_replace('/\{[^}]+\}/', '', $template);

        return $template;
    }

    /**
     * Convert a component from components41 folder to HTML
     */
    private function convertComponents41Component(array $component): string
    {
        $componentName = $component['name'];
        $componentPath = $this->getComponents41Path($componentName);
        
        if (!File::exists($componentPath)) {
            throw new \Exception("Component file not found: {$componentPath}");
        }
        
        $template = File::get($componentPath);
        
        // Process the Blade template to convert to static HTML
        return $this->processBladeTemplate($template, $component);
    }
    
    /**
     * Get the path to a components41 file
     */
    private function getComponents41Path(string $componentName): string
    {
        // Handle dot notation (e.g., element.fe -> element/fe.blade.php)
        $path = str_replace('.', '/', $componentName);
        return app_path("UI/components41/{$path}.blade.php");
    }
    
    /**
     * Process a Blade template and convert it to static HTML
     */
    private function processBladeTemplate(string $template, array $component): string
    {
        // Replace {{$attributes}} with actual attributes
        $attributesString = $this->buildAttributesString($component['attributes']);
        $template = str_replace('{{$attributes}}', $attributesString, $template);
        
        // Replace $attributes->merge() calls
        $template = preg_replace_callback(
            '/\{\{\$attributes->merge\(\[(.*?)\]\)\}\}/',
            function($matches) use ($component) {
                // Parse the merge array and combine with component attributes
                $mergeAttributes = $this->parseMergeAttributes($matches[1]);
                $finalAttributes = array_merge($mergeAttributes, $component['attributes']);
                return $this->buildAttributesString($finalAttributes);
            },
            $template
        );
        
        // Replace component variables with their values
        foreach ($component['attributes'] as $key => $value) {
            // Handle different variable formats
            $template = str_replace('{{$' . $key . '}}', $value, $template);
            $template = str_replace('{!!$' . $key . '!!}', $value, $template);
        }
        
        // Handle default values for missing attributes
        $template = $this->handleDefaultAttributes($template, $component);
        
        return $template;
    }
    
    /**
     * Build HTML attributes string from array
     */
    private function buildAttributesString(array $attributes): string
    {
        $attributePairs = [];
        foreach ($attributes as $key => $value) {
            // Skip special attributes that aren't HTML attributes but are component props
            if (in_array($key, ['name', 'type', 'idColor', 'icon'])) {
                continue;
            }
            
            // Handle special cases for attributes
            if ($key === 'x-data' && $value === '') {
                // Handle x-data without value
                $attributePairs[] = 'x-data';
            } elseif ($value === true || $value === 'true') {
                $attributePairs[] = $key;
            } elseif ($value !== false && $value !== 'false' && $value !== null && $value !== '') {
                $attributePairs[] = $key . '="' . htmlspecialchars($value) . '"';
            }
        }
        return implode(' ', $attributePairs);
    }
    
    /**
     * Parse merge attributes from Blade syntax
     */
    private function parseMergeAttributes(string $mergeString): array
    {
        $attributes = [];
        
        // Simple parser for class merging and other attributes
        if (preg_match("/'class'\s*=>\s*'([^']+)'/", $mergeString, $matches)) {
            $attributes['class'] = $matches[1];
        }
        
        return $attributes;
    }
    
    /**
     * Handle default attributes for missing component attributes
     */
    private function handleDefaultAttributes(string $template, array $component): string
    {
        // Set defaults for common component attributes
        $defaults = [
            'icon' => 'circle', // Default icon if not specified
        ];
        
        foreach ($defaults as $key => $defaultValue) {
            if (!isset($component['attributes'][$key])) {
                $template = str_replace('{{$' . $key . '}}', $defaultValue, $template);
            }
        }
        
        // Clean up any remaining Blade variables that weren't replaced
        $template = preg_replace('/\{\{\$[^}]+\}\}/', '', $template);
        $template = preg_replace('/\{!!\$[^}]+!!\}/', '', $template);
        
        return $template;
    }

    /**
     * Convert entire template from components41 to plain HTML
     */
    public function convertTemplate(string $templatePath): string
    {
        $content = File::get($templatePath);
        
        // Convert components iteratively to handle nesting
        $maxIterations = 10; // Prevent infinite loops
        $iteration = 0;
        
        do {
            $iteration++;
            $components = $this->parseContent($content);
            
            // Filter only components that we can convert (either hardcoded mappings or components41 files)
            $components = array_filter($components, function($component) {
                return isset($this->componentMappings[$component['name']]) || 
                       File::exists($this->getComponents41Path($component['name']));
            });
            
            if (empty($components)) {
                break; // No more components to convert
            }
            
            // Sort components by position in template (process from end to start to avoid position shifts)
            usort($components, function($a, $b) {
                return $b['start_pos'] - $a['start_pos'];
            });

            foreach ($components as $component) {
                try {
                    $htmlEquivalent = $this->convertComponent($component);
                    $content = substr_replace($content, $htmlEquivalent, $component['start_pos'], 
                        $component['end_pos'] - $component['start_pos']);
                } catch (\Exception $e) {
                    // Skip components that can't be converted and continue with others
                    continue;
                }
            }
            
        } while (!empty($components) && $iteration < $maxIterations);

        return $content;
    }
    
    /**
     * Parse content string to find components (for iterative processing)
     */
    private function parseContent(string $content): array
    {
        $components = [];
        $this->findAllComponents($content, $components);
        return $components;
    }

    /**
     * Get list of all templates using components41
     */
    public function findTemplatesUsingComponents41(string $basePath = null): array
    {
        $basePath = $basePath ?? app_path('UI/views');
        $templates = [];

        $files = File::allFiles($basePath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $content = File::get($file->getPathname());
                
                // Check if template uses any x- components
                if (preg_match('/<x-[a-z0-9\-\.]+/', $content)) {
                    $components = $this->parseTemplate($file->getPathname());
                    
                    // Filter for components that can be converted (hardcoded mappings or components41 files)
                    $components41Used = array_filter($components, function($component) {
                        return isset($this->componentMappings[$component['name']]) || 
                               File::exists($this->getComponents41Path($component['name']));
                    });

                    if (!empty($components41Used)) {
                        $templates[] = [
                            'path' => $file->getPathname(),
                            'relative_path' => str_replace(app_path(), 'app', $file->getPathname()),
                            'components' => $components41Used
                        ];
                    }
                }
            }
        }

        return $templates;
    }

    /**
     * Create backup of template before conversion
     */
    public function createBackup(string $templatePath): string
    {
        $backupPath = $templatePath . '.backup.' . date('Y-m-d-H-i-s');
        File::copy($templatePath, $backupPath);
        return $backupPath;
    }

    /**
     * Validate converted template for basic syntax
     */
    public function validateTemplate(string $content): array
    {
        $errors = [];

        // Check for unclosed tags
        $openTags = [];
        preg_match_all('/<(\w+)(?:\s[^>]*)?>|<\/(\w+)>/', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            if (!empty($match[1])) {
                // Opening tag
                $openTags[] = $match[1];
            } elseif (!empty($match[2])) {
                // Closing tag
                $lastOpen = array_pop($openTags);
                if ($lastOpen !== $match[2]) {
                    $errors[] = "Mismatched tag: expected </{$lastOpen}>, found </{$match[2]}>";
                }
            }
        }

        if (!empty($openTags)) {
            $errors[] = "Unclosed tags: " . implode(', ', $openTags);
        }

        // Check for basic Blade syntax errors
        preg_match_all('/\{\{.*?\}\}/', $content, $bladeMatches);
        foreach ($bladeMatches[0] as $blade) {
            if (substr_count($blade, '{') !== substr_count($blade, '}')) {
                $errors[] = "Unbalanced braces in: {$blade}";
            }
        }

        return $errors;
    }
}