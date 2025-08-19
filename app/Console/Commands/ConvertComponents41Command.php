<?php

namespace App\Console\Commands;

use App\Services\ComponentConversionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ConvertComponents41Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:components41 
                            {template? : Specific template file to convert}
                            {--list : List all templates using components41}
                            {--backup : Create backups before conversion (default: true)}
                            {--validate : Validate converted templates}
                            {--dry-run : Show what would be converted without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert Blade templates from components41 to plain HTML';

    private ComponentConversionService $conversionService;

    public function __construct(ComponentConversionService $conversionService)
    {
        parent::__construct();
        $this->conversionService = $conversionService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Components41 to HTML Converter');
        $this->line('===============================');

        if ($this->option('list')) {
            return $this->listTemplates();
        }

        $templatePath = $this->argument('template');

        if ($templatePath) {
            return $this->convertSingleTemplate($templatePath);
        }

        return $this->convertAllTemplates();
    }

    /**
     * List all templates using components41
     */
    private function listTemplates(): int
    {
        $this->info('Scanning for templates using components41...');

        $templates = $this->conversionService->findTemplatesUsingComponents41();

        if (empty($templates)) {
            $this->info('No templates found using components41.');
            return 0;
        }

        $this->info("Found " . count($templates) . " templates using components41:");
        $this->line('');

        foreach ($templates as $template) {
            $this->line("📄 {$template['relative_path']}");
            
            $componentNames = array_map(fn($c) => $c['name'], $template['components']);
            $this->line("   Components: " . implode(', ', array_unique($componentNames)));
            $this->line('');
        }

        return 0;
    }

    /**
     * Convert a single template
     */
    private function convertSingleTemplate(string $templatePath): int
    {
        // Resolve relative path
        if (!str_starts_with($templatePath, '/')) {
            $templatePath = app_path('UI/views/' . $templatePath);
        }

        if (!File::exists($templatePath)) {
            $this->error("Template not found: {$templatePath}");
            return 1;
        }

        $this->info("Converting template: {$templatePath}");

        try {
            // Parse components
            $components = $this->conversionService->parseTemplate($templatePath);
            
            if (empty($components)) {
                $this->info('No components41 components found in this template.');
                return 0;
            }

            $this->line("Found components: " . implode(', ', array_unique(array_map(fn($c) => $c['name'], $components))));

            if ($this->option('dry-run')) {
                $this->info('DRY RUN - Showing converted content:');
                $this->line('=====================================');
                $converted = $this->conversionService->convertTemplate($templatePath);
                $this->line($converted);
                return 0;
            }

            // Create backup
            if ($this->option('backup') !== false) {
                $backupPath = $this->conversionService->createBackup($templatePath);
                $this->info("Backup created: {$backupPath}");
            }

            // Convert template
            $converted = $this->conversionService->convertTemplate($templatePath);

            // Validate if requested
            if ($this->option('validate')) {
                $errors = $this->conversionService->validateTemplate($converted);
                if (!empty($errors)) {
                    $this->warn('Validation errors found:');
                    foreach ($errors as $error) {
                        $this->line("  ❌ {$error}");
                    }
                }
            }

            // Write converted content
            File::put($templatePath, $converted);
            
            $this->info('✅ Template converted successfully!');
            return 0;

        } catch (\Exception $e) {
            $this->error("Conversion failed: {$e->getMessage()}");
            return 1;
        }
    }

    /**
     * Convert all templates using components41
     */
    private function convertAllTemplates(): int
    {
        $this->info('Scanning for all templates using components41...');

        $templates = $this->conversionService->findTemplatesUsingComponents41();

        if (empty($templates)) {
            $this->info('No templates found using components41.');
            return 0;
        }

        $this->info("Found " . count($templates) . " templates to convert.");

        if (!$this->confirm('Do you want to proceed with converting all templates?')) {
            $this->info('Conversion cancelled.');
            return 0;
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($templates as $template) {
            $this->line("Converting: {$template['relative_path']}");

            try {
                // Create backup
                if ($this->option('backup') !== false) {
                    $this->conversionService->createBackup($template['path']);
                }

                // Convert template
                $converted = $this->conversionService->convertTemplate($template['path']);

                // Validate if requested
                if ($this->option('validate')) {
                    $errors = $this->conversionService->validateTemplate($converted);
                    if (!empty($errors)) {
                        $this->warn("  Validation warnings for {$template['relative_path']}:");
                        foreach ($errors as $error) {
                            $this->line("    ❌ {$error}");
                        }
                    }
                }

                if (!$this->option('dry-run')) {
                    File::put($template['path'], $converted);
                }

                $this->info("  ✅ Converted successfully");
                $successCount++;

            } catch (\Exception $e) {
                $this->error("  ❌ Failed: {$e->getMessage()}");
                $errorCount++;
            }
        }

        $this->line('');
        $this->info("Conversion complete!");
        $this->info("✅ Successful: {$successCount}");
        
        if ($errorCount > 0) {
            $this->error("❌ Failed: {$errorCount}");
        }

        return $errorCount > 0 ? 1 : 0;
    }
}
