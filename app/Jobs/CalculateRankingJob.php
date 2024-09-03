<?php

namespace App\Jobs;

use App\Models\Exam;
use App\ExamStatisticsTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Events\RankingUpdated;

class CalculateRankingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ExamStatisticsTrait;

    protected $exam;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Exam $exam)
    {
        $this->exam = $exam;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("Iniciando o Job CalculateRankingJob");
        try {
            Log::info("Entrou no bloco try");
            $rankings = $this->calculateUserRankings($this->exam->id);
            Log::info("Calculou os rankings");

            foreach ($rankings as $position => $ranking) {
                $this->exam->rankings()->updateOrCreate(
                    ['user_id' => $ranking['user']->id],
                    [
                        'position' => $position + 1,
                        'correct_answers' => $ranking['correct_answers'],
                        'exam_id' => $this->exam->id,
                    ]
                );
            }
            Log::info("Salvou os rankings");
            Log::info("Disparando o evento RankingUpdated");
            broadcast(new RankingUpdated($this->exam->id));
        } catch (Exception $e) {
            Log::error('Erro ao calcular o ranking: ' . $e->getMessage());
        }
    }

}
