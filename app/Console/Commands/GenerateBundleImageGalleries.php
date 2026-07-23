<?php

namespace App\Console\Commands;

use App\Models\Bundle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateBundleImageGalleries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-bundle-image-galleries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a distinct 3-image gallery per bundle from LoremFlickr, keyworded from each bundle\'s own description';

    /**
     * Brand/model noise words stripped before picking the head noun of each description phrase.
     *
     * @var array<int, string>
     */
    private const BRAND_WORDS = [
        'sony', 'canon', 'nikon', 'fujifilm', 'fuji', 'panasonic', 'blackmagic', 'bmpcc',
        'dji', 'rode', 'sennheiser', 'zoom', 'godox', 'manfrotto', 'sandisk', 'lexar',
        'apple', 'macbook', 'gopro', 'sigma', 'tamron', 'zhiyun', 'moza', 'atomos',
        'aputure', 'nanlite', 'profoto', 'lowepro', 'pelican',
    ];

    /**
     * Filler / connector words stripped before picking the head noun of each description phrase.
     *
     * @var array<int, string>
     */
    private const STOPWORDS = [
        'and', 'kit', 'set', 'package', 'combo', 'bundle', 'pair', 'duo', 'trio', 'pack',
        'with', 'plus', 'the', 'a', 'an', 'of', 'on', 'on-camera', 'off-camera',
        'core', 'starter', 'essentials', 'toolkit', 'spare', 'extra',
    ];

    private const FALLBACK_KEYWORDS = ['camera', 'equipment'];

    private const LAST_RESORT_KEYWORDS = ['camera'];

    /**
     * LoremFlickr matches tags loosely (closer to OR than AND), so a generic English
     * word like "hood", "box", or "monitor" can just as easily pull in an unrelated
     * photo (a car hood, a moving box, a lizard) as a piece of gear. Every head noun
     * extracted from a description is mapped through this table to a small set of
     * tags that reliably resolve to camera/production-gear photos; anything not
     * listed here falls back to the safest possible anchor, 'camera'.
     *
     * @var array<string, string>
     */
    private const SAFE_TAG_MAP = [
        'camera' => 'camera', 'cameras' => 'camera', 'body' => 'camera', 'setup' => 'camera',
        'rig' => 'camera', 'transmitter' => 'camera', 'monitor' => 'camera', 'box' => 'camera',
        'hybrid' => 'camera', 'vlog' => 'camera',

        'lens' => 'lens', 'lenses' => 'lens', 'zoom' => 'lens', 'telephoto' => 'lens',
        'filter' => 'lens', 'filters' => 'lens', 'hood' => 'lens', 'prime' => 'lens',

        'mic' => 'microphone', 'microphone' => 'microphone', 'lav' => 'microphone', 'pole' => 'microphone',

        'light' => 'light', 'lights' => 'light', 'wand' => 'light', 'panel' => 'light',
        'panels' => 'light', 'softbox' => 'light', 'softboxes' => 'light',

        'flash' => 'flash',
        'gimbal' => 'gimbal', 'stabilizer' => 'gimbal',
        'drone' => 'drone',
        'laptop' => 'laptop', 'reader' => 'laptop', 'ssd' => 'laptop',
        'tripod' => 'tripod', 'stand' => 'tripod', 'stands' => 'tripod', 'monopod' => 'tripod',

        'reflector' => 'photography', 'recorder' => 'audio',
    ];

    /**
     * LoremFlickr's tag matching is loose enough that generic terms - "camera", "lens",
     * "photography" - match almost any photo (Flickr auto-tags photos with the EXIF
     * camera/lens used to shoot them, so a landscape shot is just as likely to surface
     * as a product photo). Testing dozens of tag combos against real bundle keywords
     * found only these specific nouns reliably return actual gear photos when paired
     * with the 'camera,gear' anchor; anything else falls back to the anchor alone
     * rather than risk an unrelated image.
     *
     * @var array<int, string>
     */
    private const HIGH_CONFIDENCE_SPECIFIC = [
        'drone', 'gimbal', 'tripod', 'microphone', 'laptop', 'flash', 'headphones', 'softbox',
    ];

    /**
     * MD5 hashes of image bytes already accepted in this run, so no two images
     * (even across different bundles) end up identical.
     *
     * @var array<string, bool>
     */
    private array $seenHashes = [];

    public function handle(): int
    {
        $disk = Storage::disk('public');
        $bundles = Bundle::query()->orderBy('id')->get();

        foreach ($bundles as $bundle) {
            $keywords = $this->extractKeywords($bundle->description ?? '');

            if (empty($keywords)) {
                $keywords = ['camera', 'equipment'];
            }

            $urlTags = $this->buildSearchTags($keywords);

            $bundleDir = public_path('images/combos/' . $bundle->id);

            if (! is_dir($bundleDir)) {
                mkdir($bundleDir, 0755, true);
            }

            // Overwrite any previously assigned images for this bundle.
            if ($bundle->image) {
                $disk->delete($bundle->image);
            }

            foreach ((array) ($bundle->gallery_images ?? []) as $oldGalleryPath) {
                $disk->delete($oldGalleryPath);
            }

            $storedPaths = [];

            for ($i = 1; $i <= 3; $i++) {
                $lock = ($bundle->id * 10) + $i;
                $localPath = $bundleDir . "/image-{$i}.jpg";

                if (! $this->downloadImage($urlTags, $lock, $localPath)) {
                    $this->warn("Warning: Could not download image {$i}/3 for '{$bundle->name}' (#{$bundle->id}), skipping it.");
                    continue;
                }

                $fileContents = file_get_contents($localPath);

                if ($fileContents === false) {
                    $this->warn("Warning: Could not read downloaded image {$i}/3 for '{$bundle->name}' (#{$bundle->id}), skipping it.");
                    continue;
                }

                $storedFilename = Str::slug($bundle->name) . '_' . uniqid() . '.jpg';
                $storedPath = 'bundles/' . $storedFilename;

                $disk->put($storedPath, $fileContents);
                $storedPaths[] = $storedPath;
            }

            if (empty($storedPaths)) {
                $this->error("Failed to assign any images to '{$bundle->name}' (#{$bundle->id}).");
                continue;
            }

            $bundle->update([
                'image' => $storedPaths[0],
                'gallery_images' => $storedPaths,
            ]);

            $this->info(sprintf(
                "Bundle #%d '%s': keywords=[%s] -> assigned %d image(s).",
                $bundle->id,
                $bundle->name,
                implode(', ', $keywords),
                count($storedPaths)
            ));
        }

        $this->newLine();
        $this->info('Done. Cover images and galleries updated on the public storage disk under bundles/.');

        return self::SUCCESS;
    }

    /**
     * Extract 2-3 short, meaningful equipment keywords from a bundle's description text.
     *
     * @return array<int, string>
     */
    private function extractKeywords(string $description): array
    {
        $segments = preg_split('/[,\n;]+/', $description) ?: [];
        $segments = array_values(array_filter(
            array_map('trim', $segments),
            fn (string $segment) => $segment !== ''
        ));

        $keywords = [];

        foreach (array_slice($segments, 0, 3) as $segment) {
            $words = preg_split('/\s+/', strtolower($segment)) ?: [];

            $clean = array_values(array_filter($words, function (string $word) {
                if ($word === '') {
                    return false;
                }

                if (preg_match('/\d/', $word) === 1) {
                    return false;
                }

                if (preg_match('/^(i|ii|iii|iv|v|vi|vii|viii|ix|x)$/', $word) === 1) {
                    return false;
                }

                if (in_array($word, self::BRAND_WORDS, true)) {
                    return false;
                }

                if (in_array($word, self::STOPWORDS, true)) {
                    return false;
                }

                return true;
            }));

            if (empty($clean)) {
                $fallback = preg_replace('/[^a-z]/', '', (string) end($words));
                $keywords[] = $fallback !== '' ? $fallback : 'equipment';

                continue;
            }

            $keywords[] = count($clean) >= 2
                ? implode(' ', array_slice($clean, -2))
                : $clean[0];
        }

        return array_values(array_unique(array_filter($keywords)));
    }

    /**
     * Reduce a (possibly multi-word) display keyword down to the single real word
     * LoremFlickr needs as a tag - its head noun, i.e. the last word/segment - then
     * run it through SAFE_TAG_MAP so ambiguous English words can't pull in an
     * unrelated photo.
     */
    private function toUrlTag(string $keyword): string
    {
        $tokens = preg_split('/[\s-]+/', $keyword) ?: [$keyword];
        $tokens = array_values(array_filter($tokens, fn (string $t) => $t !== ''));
        $last = $tokens !== [] ? (string) end($tokens) : $keyword;
        $tag = preg_replace('/[^a-z]/', '', strtolower($last));

        if ($tag === '') {
            return 'camera';
        }

        return self::SAFE_TAG_MAP[$tag] ?? 'camera';
    }

    /**
     * Build the actual LoremFlickr search tags for a bundle: always anchor on the
     * reliable 'camera,gear' pair, then layer in at most one bundle-specific term -
     * but only when that term is on HIGH_CONFIDENCE_SPECIFIC. Everything else stays
     * on the safe anchor rather than risk an unrelated photo (see HIGH_CONFIDENCE_SPECIFIC).
     *
     * @param  array<int, string>  $displayKeywords
     * @return array<int, string>
     */
    private function buildSearchTags(array $displayKeywords): array
    {
        $mapped = array_map(fn (string $keyword) => $this->toUrlTag($keyword), $displayKeywords);

        $specific = null;

        foreach ($mapped as $tag) {
            if (in_array($tag, self::HIGH_CONFIDENCE_SPECIFIC, true)) {
                $specific = $tag;
                break;
            }
        }

        $tags = $specific !== null ? ['camera', 'gear', $specific] : ['camera', 'gear'];

        return array_values(array_unique($tags));
    }

    /**
     * Download a single LoremFlickr image to $destPath. Tries the bundle's own tags
     * across a few lock values first, then falls back to increasingly generic tags.
     * Rejects LoremFlickr's own "no results" placeholder and anything already used
     * elsewhere in this run so no two images end up identical.
     *
     * @param  array<int, string>  $urlTags
     */
    private function downloadImage(array $urlTags, int $lock, string $destPath): bool
    {
        $tagSets = [
            implode(',', $urlTags),
            implode(',', self::FALLBACK_KEYWORDS),
            implode(',', self::LAST_RESORT_KEYWORDS),
        ];

        foreach ($tagSets as $tagSetIndex => $tags) {
            // A few lock values per tag set: guards against hash collisions and
            // gives LoremFlickr another roll of the dice if one lock is unlucky.
            for ($attempt = 0; $attempt < 3; $attempt++) {
                $attemptLock = $lock + ($tagSetIndex * 5000) + ($attempt * 97);
                $url = "https://loremflickr.com/800/600/{$tags}?lock={$attemptLock}";

                usleep(150000);

                try {
                    $response = Http::timeout(30)->get($url);
                } catch (\Throwable $e) {
                    continue;
                }

                if (! $response->successful()) {
                    continue;
                }

                $effectiveUrl = (string) $response->effectiveUri();

                if (str_contains($effectiveUrl, 'defaultImage')) {
                    continue;
                }

                $body = $response->body();

                if (strlen($body) < 5000) {
                    continue;
                }

                if (! str_starts_with($body, "\xFF\xD8")) {
                    continue;
                }

                $hash = md5($body);

                if (isset($this->seenHashes[$hash])) {
                    continue;
                }

                $this->seenHashes[$hash] = true;
                file_put_contents($destPath, $body);

                return true;
            }
        }

        return false;
    }
}
