<?php

namespace App\Console\Commands;

use App\Jobs\ProcessGalleryImage;
use App\Models\GalleryCategory;
use App\Models\GalleryDay;
use App\Models\GalleryEvent;
use App\Models\GalleryImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class ImportGalleryFolder extends Command
{
    protected $signature = 'gallery:import
        {event-slug : e.g. aca-2025}
        {source : Path to the folder containing day1/day2/day3 subfolders. Relative paths are resolved against public/, e.g. images/gallery/aca_2025}
        {--event-name= : Display name for the event, e.g. "ACA 2025" (only needed first time)}
        {--dry-run : Preview what would be imported without touching the DB or filesystem}';

    protected $description = 'Import existing day/category image folders into the gallery_* tables';

    protected array $imageExtensions = ['jpg', 'jpeg', 'png', 'webp'];

    public function handle(): int
    {
        $eventSlug = $this->argument('event-slug');
        $source = $this->resolveSourcePath($this->argument('source'));
        $dryRun = (bool) $this->option('dry-run');

        if (! is_dir($source)) {
            $this->error("Source path does not exist: {$source}");
            return self::FAILURE;
        }

        $this->info("Reading from: {$source}");

        $event = $dryRun
            ? GalleryEvent::firstWhere('slug', $eventSlug)
            : GalleryEvent::firstOrCreate(
                ['slug' => $eventSlug],
                ['name' => $this->option('event-name') ?? Str::headline($eventSlug)]
            );

        if (! $event && $dryRun) {
            $this->warn("Event '{$eventSlug}' doesn't exist yet — would be created on a real run.");
        }

        $dayFolders = $this->subdirectories($source);

        if (empty($dayFolders)) {
            $this->warn("No subfolders found in {$source}");
            return self::SUCCESS;
        }

        $totalImported = 0;
        $totalSkipped = 0;

        foreach ($dayFolders as $dayPath) {
            $daySlug = Str::slug(basename($dayPath));
            $dayLabel = Str::title(preg_replace('/day(\d+)/i', 'Day $1', basename($dayPath)));

            $this->info("\nDay: {$dayLabel} ({$daySlug})");

            $day = $dryRun ? null : GalleryDay::firstOrCreate(
                ['gallery_event_id' => $event->id, 'slug' => $daySlug],
                ['label' => $dayLabel, 'order' => 0]
            );

            $categoryFolders = $this->subdirectories($dayPath);

            if (empty($categoryFolders)) {
                $this->warn("  No category subfolders found in {$dayPath}");
                continue;
            }

            foreach ($categoryFolders as $categoryPath) {
                $rawName = basename($categoryPath);
                $categorySlug = Str::slug($rawName);
                $categoryName = Str::upper(trim(str_replace(['_', '-'], ' ', $rawName)));

                $files = $this->imageFiles($categoryPath);

                $this->line("  Category: {$categoryName} — " . count($files) . ' image(s)');

                if (empty($files)) {
                    continue;
                }

                if ($dryRun) {
                    continue;
                }

                $category = GalleryCategory::firstOrCreate(
                    ['gallery_day_id' => $day->id, 'slug' => $categorySlug],
                    ['name' => $categoryName, 'order' => 0]
                );

                $destDir = "gallery/{$eventSlug}/{$daySlug}/{$categorySlug}";

                foreach ($files as $index => $filePath) {
                    $filename = basename($filePath);
                    $destPath = "{$destDir}/{$filename}";

                    $alreadyExists = GalleryImage::where('gallery_category_id', $category->id)
                        ->where('path', $destPath)
                        ->exists();

                    if ($alreadyExists) {
                        $totalSkipped++;
                        continue;
                    }

                    Storage::disk('public')->put(
                        $destPath,
                        file_get_contents($filePath)
                    );

                    $image = GalleryImage::create([
                        'gallery_category_id' => $category->id,
                        'path' => $destPath,
                        'order' => $index,
                    ]);

                    ProcessGalleryImage::dispatch($image);

                    $totalImported++;
                }
            }
        }

        $this->newLine();

        if ($dryRun) {
            $this->info('Dry run complete — nothing was written. Remove --dry-run to actually import.');
        } else {
            $this->info("Imported {$totalImported} image(s), skipped {$totalSkipped} already-imported.");
            $this->info('Thumbnail generation jobs have been queued — make sure a queue worker is running:');
            $this->line('  php artisan queue:work');
        }

        return self::SUCCESS;
    }

    /**
     * Relative paths are resolved against public/ (e.g. "images/gallery/aca_2025").
     * Absolute paths (starting with / or a drive letter) are used as-is.
     */
    protected function resolveSourcePath(string $source): string
    {
        if (Str::startsWith($source, ['/', 'C:', 'D:'])) {
            return rtrim($source, '/');
        }

        return rtrim(public_path($source), '/');
    }

    protected function subdirectories(string $path): array
    {
        $finder = (new Finder())->directories()->in($path)->depth(0)->sortByName();

        return array_map(fn ($dir) => $dir->getPathname(), iterator_to_array($finder));
    }

    protected function imageFiles(string $path): array
    {
        $finder = (new Finder())->files()->in($path)->depth(0)->sortByName();

        return array_values(array_filter(
            array_map(fn ($f) => $f->getPathname(), iterator_to_array($finder)),
            fn ($f) => in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), $this->imageExtensions)
        ));
    }
}