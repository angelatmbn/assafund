<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Exceptions\RateLimitException;
use Throwable;

class AIJurnalService
{
    public function generateAndSave(array $context): void
    {
        try {
            $response = OpenAI::responses()->create([
                'model' => 'gpt-5', // atau model lain yang kamu pakai
                'input' => json_encode([
                    'task'   => 'generate_journal_entries',
                    'schema' => [
                        'header' => ['tgl', 'no_referensi', 'deskripsi'],
                        'detail' => ['no_akun', 'nama_akun', 'deskripsi', 'debit', 'credit'],
                    ],
                    'transaction' => $context,
                ]),
            ]);

            $json = json_decode($response->outputText, true);

            // TODO: simpan ke tabel jurnal + jurnal_detail di sini
            // (hanya jika $json valid)

        } catch (RateLimitException $e) {
            // catat log, tapi jangan ganggu user
            \Log::warning('AI jurnal rate limited', ['message' => $e->getMessage()]);
            // opsi: panggil fallback rule-based di sini
        } catch (Throwable $e) {
            \Log::error('AI jurnal error', ['message' => $e->getMessage()]);
            // opsi: fallback juga
        }
    }
}
