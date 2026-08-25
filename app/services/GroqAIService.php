<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GroqAIService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://api.groq.com/openai/v1';

    public function __construct()
    {
        $this->apiKey = (string) config('services.groq.key');
        $this->model = (string) config('services.groq.model');

        if (empty($this->apiKey)) {
            throw new RuntimeException(
                'Groq API key is not configured.'
            );
        }

        if (empty($this->model)) {
            throw new RuntimeException(
                'Groq AI model is not configured.'
            );
        }
    }

    /**
     * Send a prompt to the configured Groq-hosted LLM.
     */
    public function generate(string $prompt): string
    {
        $response = Http::withToken($this->apiKey)
            ->acceptJson()
            ->timeout(60)
            ->retry(2, 500)
            ->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,

                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $this->systemPrompt(),
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],

                'temperature' => 0.3,

                /*
                 * openai/gpt-oss-20b is a reasoning model. At the default
                 * 'medium' reasoning_effort, long/complex prompts (like the
                 * full roadmap prompt — assessment data + strict rules +
                 * JSON schema) can consume the entire token budget on
                 * internal reasoning, leaving nothing for the actual
                 * answer. This is what was causing "Groq returned an empty
                 * AI response" on the roadmap feature specifically (its
                 * prompt is longer/stricter than the working AI-summary
                 * prompt). 'low' leaves more of the budget for the answer
                 * itself, which is what we actually need here.
                 */
                'reasoning_effort' => 'low',

                /*
                 * NOTE: response_format => ['type' => 'json_object'] was
                 * tried here and removed — it triggered a 413 "Request too
                 * large" error even with max_completion_tokens unchanged
                 * at 4096 (a value already confirmed safe without this
                 * param). This mode appears to reserve extra hidden
                 * budget on this model/account combination. The prompt
                 * text already instructs strict JSON output, so this mode
                 * isn't required — just not worth the added request size
                 * on this account's limits.
                 */

                /*
                 * Reserve enough of the model's output budget for the
                 * actual answer. NOTE: this was briefly raised to 8192,
                 * but that triggered a 413 "Request too large" error —
                 * not from the model's real 131k context window, but from
                 * a per-account/org request-size limit on this Groq plan.
                 * 4096 is confirmed safe for this account; reasoning_effort
                 * above does the actual work of leaving more of that
                 * budget for the answer instead of internal reasoning.
                 *
                 * `max_completion_tokens` is the current Groq/OpenAI field
                 * name — `max_tokens` still works but is deprecated.
                 */
                'max_completion_tokens' => 4096,
            ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'Groq AI request failed: ' .
                $response->status() . ' ' .
                $response->body()
            );
        }

        $content = $response->json(
            'choices.0.message.content'
        );

        if (!is_string($content) || trim($content) === '') {
            /*
             * A 2xx response with empty content usually means one of:
             *  - finish_reason "length": the model ran out of tokens
             *    before writing (or finishing) the actual answer.
             *  - the configured model was retired/replaced by Groq and
             *    is silently returning nothing instead of erroring.
             *  - a reasoning-style model spent its whole budget on
             *    internal reasoning tokens.
             *
             * Logging the raw response here means the next failure shows
             * finish_reason/usage in the log instead of just "empty",
             * so it can be diagnosed without reproducing it live.
             */
            Log::warning('Groq returned an empty AI response', [
                'model' => $this->model,
                'finish_reason' => $response->json('choices.0.finish_reason'),
                'usage' => $response->json('usage'),
                'raw_body' => $response->body(),
            ]);

            throw new RuntimeException(
                'Groq returned an empty AI response.'
            );
        }

        return trim($content);
    }

    /**
     * Base behaviour shared by YARA AI features.
     */
    protected function systemPrompt(): string
    {
        return <<<'PROMPT'
You are YARA's AI Readiness Analysis Assistant.

Your role is to analyze structured organizational AI readiness assessment data and produce professional, concise and evidence-based business analysis.

Important rules:
- Base your analysis only on the assessment information provided by YARA.
- Never invent company facts, scores, initiatives, risks or capabilities.
- Clearly distinguish organizational readiness from country AI readiness.
- Do not modify or reinterpret YARA's calculated scores.
- Do not calculate new maturity scores unless explicitly requested.
- Treat YARA's deterministic scoring engine as the authoritative source for scores and maturity levels.
- Focus on useful interpretation, strengths, weaknesses, gaps and priorities.
- Use professional language suitable for executives and consultants.
PROMPT;
    }

    /**
     * Return the configured model name.
     */
    public function model(): string
    {
        return $this->model;
    }
}
