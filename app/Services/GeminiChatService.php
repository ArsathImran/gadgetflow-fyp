<?php

namespace App\Services;

use App\Models\Bundle;
use App\Models\Gadget;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class GeminiChatService
{
    private const MAX_GADGETS = 40;

    private const FALLBACK_MESSAGE = "Sorry, I'm having trouble reaching the GadgetFlow Assistant right now. Please try again in a moment, or browse Gadgets and Combos directly from the menu while you wait.";

    /**
     * Get an AI reply for the given conversation history and new user message.
     *
     * @param  array<int, array{role: string, message: string}>  $history
     */
    public function getReply(array $history, string $newMessage): string
    {
        $apiKey = config('services.gemini.key');

        if (empty($apiKey)) {
            Log::warning('GeminiChatService: GEMINI_API_KEY is not configured; returning fallback reply.');

            return self::FALLBACK_MESSAGE;
        }

        $model = config('services.gemini.model', 'gemini-flash-latest');
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

        try {
            $response = Http::timeout(20)
                ->retry(1, 250, throw: false)
                ->post($endpoint.'?key='.$apiKey, [
                    'systemInstruction' => [
                        'parts' => [
                            ['text' => $this->buildSystemInstruction()],
                        ],
                    ],
                    'contents' => $this->buildContents($history, $newMessage),
                ]);

            if (! $response->successful()) {
                Log::error('GeminiChatService: Gemini API request failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return self::FALLBACK_MESSAGE;
            }

            $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

            if (! is_string($text) || trim($text) === '') {
                Log::error('GeminiChatService: Unexpected Gemini API response shape.', [
                    'body' => $response->json(),
                ]);

                return self::FALLBACK_MESSAGE;
            }

            return trim($text);
        } catch (Throwable $e) {
            Log::error('GeminiChatService: Exception while calling Gemini API.', [
                'message' => $e->getMessage(),
            ]);

            return self::FALLBACK_MESSAGE;
        }
    }

    /**
     * Build the Gemini `contents` array from prior chat history plus the new message.
     * Gemini uses "user" / "model" roles, so our stored "assistant" role is mapped to "model".
     *
     * @param  array<int, array{role: string, message: string}>  $history
     * @return array<int, array{role: string, parts: array<int, array{text: string}>}>
     */
    private function buildContents(array $history, string $newMessage): array
    {
        $contents = [];

        foreach ($history as $entry) {
            $text = trim((string) ($entry['message'] ?? ''));

            if ($text === '') {
                continue;
            }

            $contents[] = [
                'role' => ($entry['role'] ?? 'user') === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $text]],
            ];
        }

        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $newMessage]],
        ];

        return $contents;
    }

    /**
     * Build the system instruction, including a live snapshot of active gadgets and bundles
     * so the assistant only ever recommends real, currently-rentable inventory.
     */
    private function buildSystemInstruction(): string
    {
        $gadgetLines = Gadget::query()
            ->where('status', 'active')
            ->with('category')
            ->orderBy('category_id')
            ->orderBy('name')
            ->limit(self::MAX_GADGETS)
            ->get()
            ->map(function (Gadget $gadget) {
                $category = $gadget->category?->name ?? 'Uncategorized';
                $hourly = $gadget->hourly_rental_price !== null
                    ? sprintf(' or RM%s/hour', number_format((float) $gadget->hourly_rental_price, 2))
                    : '';

                return sprintf(
                    '- %s (%s): RM%s/day%s, %d in stock',
                    $gadget->name,
                    $category,
                    number_format((float) $gadget->daily_rental_price, 2),
                    $hourly,
                    $gadget->quantity
                );
            })
            ->implode("\n");

        $bundleLines = Bundle::query()
            ->where('status', 'active')
            ->orderBy('type')
            ->orderBy('name')
            ->get()
            ->map(function (Bundle $bundle) {
                $typeLabel = $bundle->type === 'wedding' ? 'Wedding Combo' : 'Short Film Combo';
                $description = $bundle->description ? Str::limit($bundle->description, 140) : 'No description provided.';
                $price = $bundle->daily_rental_price !== null
                    ? sprintf('RM%s/day', number_format((float) $bundle->daily_rental_price, 2))
                    : 'price on request';

                return sprintf('- %s (%s): %s - %s', $bundle->name, $typeLabel, $description, $price);
            })
            ->implode("\n");

        $gadgetLines = $gadgetLines !== '' ? $gadgetLines : '(No active gadgets are currently listed.)';
        $bundleLines = $bundleLines !== '' ? $bundleLines : '(No active combo bundles are currently listed.)';

        return <<<TEXT
        You are the GadgetFlow Assistant, a friendly and knowledgeable customer support and recommendation chatbot for GadgetFlow, a gadget rental platform.

        About GadgetFlow:
        - Customers rent individual gadgets (smartphones, laptops, cameras, headphones, gaming consoles, and more) by the hour or by the day.
        - Individual gadget rentals can be picked up walk-in or delivered to the customer.
        - GadgetFlow also offers themed combo packages ("bundles") of gear for Wedding Photography and Short Film Production. Combo bundles are walk-in pickup only - delivery is not available for them.
        - Rentals require a refundable security deposit and may incur a late fee per day if returned after the due date.

        Below is the CURRENT, REAL, active inventory pulled fresh from the database. Only recommend items from this list - never invent, assume, or hallucinate a product that is not listed here. Whenever you recommend something, always mention its exact name so the customer can search for it in the app.

        AVAILABLE GADGETS:
        {$gadgetLines}

        AVAILABLE COMBO BUNDLES:
        {$bundleLines}

        Answer customer questions about how rentals, pricing, deposits, and pickup/delivery work, and recommend specific gadgets or bundles by exact name based on what the customer describes needing. Keep replies concise, friendly, and focused on GadgetFlow. If you don't know the answer or the question is unrelated to GadgetFlow, say so honestly instead of making something up.
        TEXT;
    }
}
