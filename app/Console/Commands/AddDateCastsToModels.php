<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class AddDateCastsToModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'models:add-date-casts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add created_at and updated_at date casts to all models';

    /**
     * The date casts to be added.
     *
     * @var array
     */
    protected $dateCasts = [
        "'created_at' => 'date:Y-m-d'",
        "'updated_at' => 'date:Y-m-d'",
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Adding date casts to models...');
        
        $modelsPath = app_path('Models');
        $modelFiles = File::glob($modelsPath . '/*.php');
        
        $modifiedModels = 0;
        $skippedModels = 0;
        
        foreach ($modelFiles as $modelFile) {
            $className = pathinfo($modelFile, PATHINFO_FILENAME);
            $fullClassName = 'App\\Models\\' . $className;
            
            // Skip if the file doesn't exist or can't be included safely
            if (!file_exists($modelFile) || !is_readable($modelFile)) {
                $this->warn("Skipping {$className}: file not readable");
                $skippedModels++;
                continue;
            }
            
            // Make sure the class exists and is a Model
            if (!class_exists($fullClassName)) {
                $this->warn("Skipping {$className}: class doesn't exist");
                $skippedModels++;
                continue;
            }
            
            $reflectionClass = new ReflectionClass($fullClassName);
            
            // Skip if it's not a model
            if (!$reflectionClass->isSubclassOf('Illuminate\Database\Eloquent\Model')) {
                $this->warn("Skipping {$className}: not a model");
                $skippedModels++;
                continue;
            }
            
            $content = File::get($modelFile);
            
            // Check if the model already has casts
            $hasCasts = preg_match('/protected\s+\$casts\s*=\s*\[/m', $content);
            
            // Prepare the casts array content
            $castsContent = "protected \$casts = [\n";
            foreach ($this->dateCasts as $cast) {
                $castsContent .= "        {$cast},\n";
            }
            $castsContent .= "    ];";
            
            if ($hasCasts) {
                // Check if the dates are already cast
                $hasCreatedAtCast = preg_match("/'created_at'\s*=>\s*'date:Y-m-d'/", $content);
                $hasUpdatedAtCast = preg_match("/'updated_at'\s*=>\s*'date:Y-m-d'/", $content);
                
                if ($hasCreatedAtCast && $hasUpdatedAtCast) {
                    $this->info("{$className} already has the date casts. No changes needed.");
                    $skippedModels++;
                    continue;
                }
                
                // Add missing date casts to existing $casts array
                $newContent = $content;
                
                if (!$hasCreatedAtCast) {
                    $newContent = preg_replace(
                        '/protected\s+\$casts\s*=\s*\[/',
                        "protected \$casts = [\n        'created_at' => 'date:Y-m-d',",
                        $newContent,
                        1
                    );
                }
                
                if (!$hasUpdatedAtCast) {
                    // If we just added created_at, we need to handle the comma
                    if (!$hasCreatedAtCast) {
                        $newContent = preg_replace(
                            "/'created_at'\s*=>\s*'date:Y-m-d'/",
                            "'created_at' => 'date:Y-m-d',\n        'updated_at' => 'date:Y-m-d'",
                            $newContent,
                            1
                        );
                    } else {
                        // Just add updated_at
                        $newContent = preg_replace(
                            '/protected\s+\$casts\s*=\s*\[/',
                            "protected \$casts = [\n        'updated_at' => 'date:Y-m-d',",
                            $newContent,
                            1
                        );
                    }
                }
                
                // Write the changes back to the file
                File::put($modelFile, $newContent);
                $this->info("{$className} updated with date casts.");
                $modifiedModels++;
            } else {
                // Add new $casts property
                $newContent = preg_replace(
                    '/(class\s+' . $className . '\s+extends\s+Model\s*{)/',
                    "$1\n\n    {$castsContent}\n",
                    $content,
                    1
                );
                
                // Write the changes back to the file
                File::put($modelFile, $newContent);
                $this->info("{$className} added with date casts.");
                $modifiedModels++;
            }
        }
        
        $this->newLine();
        $this->info("Operation completed. {$modifiedModels} models modified, {$skippedModels} models skipped.");
    }
}