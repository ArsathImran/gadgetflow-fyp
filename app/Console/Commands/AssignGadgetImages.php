<?php

namespace App\Console\Commands;

use App\Models\Gadget;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssignGadgetImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-gadget-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign downloaded local images to existing gadgets by exact name match';

    public function handle(): int
    {
        $imageMappings = [
            'Vivo X300 Pro' => 'Vivo-X300-Phantom-Black-1.png',
            'MacBook Pro 14 M4' => '14inch-m4-macbook-pro-right-angle-3.webp',
            'Dell XPS 13' => 'xps23.webp',
            'Sony ZV-E1' => 'sony-zv-e1-wide-in-hand.avif',
            'AirPods Max' => 'airpod_cups.JPG',
            'Sony WH-1000XM6' => 'sony_headphones_1000xm3_1675507928_72ce4551.jpg',
            'Samsung Galaxy S25' => 'imgi-1-gsmarena-001_trnz.png',
            'Canon EOS R6 Mark II' => '4967bd91d1eba80a8f8e9d2666998ef3.jpg',
        ];

        $disk = Storage::disk('public');

        foreach ($imageMappings as $gadgetName => $filename) {
            $gadget = Gadget::query()->where('name', $gadgetName)->first();

            if (! $gadget) {
                $this->warn("Warning: Gadget not found for '{$gadgetName}', skipping.");
                continue;
            }

            $sourcePath = public_path('images/sequence/' . $filename);

            if (! is_file($sourcePath)) {
                $this->warn("Warning: Source file missing for '{$gadgetName}' at '{$sourcePath}', skipping.");
                continue;
            }

            if ($gadget->image) {
                $disk->delete($gadget->image);
            }

            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $storedFilename = Str::slug($gadget->name) . '_' . uniqid() . '.' . $extension;
            $storedPath = 'gadgets/' . $storedFilename;

            $fileContents = file_get_contents($sourcePath);

            if ($fileContents === false) {
                $this->warn("Warning: Could not read source file for '{$gadgetName}', skipping.");
                continue;
            }

            $disk->put($storedPath, $fileContents);

            $gadget->update([
                'image' => $storedPath,
            ]);

            $this->info("Assigned image to {$gadgetName}");
        }

        $this->newLine();
        $this->info('Done. The copied images now live on the public storage disk under gadgets/.');

        return self::SUCCESS;
    }
}
