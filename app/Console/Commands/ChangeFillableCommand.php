<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionClass;

class ChangeFillableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'models:convert-to-fillable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert models from using $guarded to $fillable with all database fields';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Converting models from $guarded to $fillable...');
        
        $modelsPath = app_path('Models');
        $modelFiles = File::glob($modelsPath . '/*.php');
        
        $convertedModels = 0;
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
            
            // Check if the model uses $guarded
            $hasGuarded = preg_match('/protected\s+\$guarded\s*=/m', $content);
            $hasFillable = preg_match('/protected\s+\$fillable\s*=/m', $content);
            
            if (!$hasGuarded && $hasFillable) {
                $this->info("{$className} already uses \$fillable. No changes needed.");
                $skippedModels++;
                continue;
            }
            
            if (!$hasGuarded && !$hasFillable) {
                $this->warn("{$className} doesn't appear to use \$guarded or \$fillable");
                $skippedModels++;
                continue;
            }
            
            // Create a model instance to get the table name
            try {
                $model = new $fullClassName();
                $tableName = $model->getTable();
                
                // Check if the table exists
                if (!Schema::hasTable($tableName)) {
                    $this->warn("Skipping {$className}: table {$tableName} doesn't exist");
                    $skippedModels++;
                    continue;
                }
                
                // Get all columns from the table
                $columns = Schema::getColumnListing($tableName);
                
                // Remove 'id' column
                $columns = array_filter($columns, function($column) {
                    return $column !== 'id';
                });
                
                // Convert columns to fillable array string
                $fillableStr = "[\n        '" . implode("',\n        '", $columns) . "'\n    ]";
                
                $newContent = $content;
                
                // If model has both $guarded and $fillable
                if ($hasGuarded && $hasFillable) {
                    // Remove $guarded
                    $newContent = preg_replace('/protected\s+\$guarded\s*=\s*(\[\s*\]|\[.*?\]);/s', '', $newContent);
                    
                    // Update $fillable with all columns
                    $newContent = preg_replace(
                        '/protected\s+\$fillable\s*=\s*\[.*?\];/s',
                        "protected \$fillable = {$fillableStr};",
                        $newContent
                    );
                } 
                // If model only has $guarded
                else if ($hasGuarded) {
                    // Replace $guarded with $fillable
                    $newContent = preg_replace(
                        '/protected\s+\$guarded\s*=\s*(\[\s*\]|\[.*?\]);/s',
                        "protected \$fillable = {$fillableStr};",
                        $newContent
                    );
                }
                
                // Write the changes back to the file
                File::put($modelFile, $newContent);
                
                $this->info("{$className} converted to use \$fillable successfully.");
                $convertedModels++;
            } catch (\Exception $e) {
                $this->error("Error processing {$className}: {$e->getMessage()}");
                $skippedModels++;
            }
        }
        
        $this->newLine();
        $this->info("Conversion completed. {$convertedModels} models converted, {$skippedModels} models skipped.");
    }
}
