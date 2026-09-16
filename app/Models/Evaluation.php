<?php

namespace App\Models;

use App\Core\Model;

class Evaluation extends Model
{
    protected string $table = 'evaluations';


    /**
     * Get criteria for a hackathon.
     */
    public function getCriteriaForHackathon(
        int $hackathonId
    ): array {
        $statement = $this->db->prepare(
            "SELECT
                id,
                hackathon_id,
                name,
                description,
                max_score,
                weight
             FROM evaluation_criteria
             WHERE hackathon_id = :hackathon_id
             ORDER BY id ASC"
        );

        $statement->execute([
            'hackathon_id' => $hackathonId,
        ]);

        return $statement->fetchAll();
    }


    /**
     * Check whether an evaluation already exists
     * for a judge, submission and criterion.
     */
    public function exists(
        int $submissionId,
        int $judgeId,
        int $criteriaId
    ): bool {
        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM {$this->table}
             WHERE submission_id = :submission_id
               AND judge_id = :judge_id
               AND criteria_id = :criteria_id"
        );

        $statement->execute([
            'submission_id' => $submissionId,
            'judge_id' => $judgeId,
            'criteria_id' => $criteriaId,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }


    /**
     * Get all evaluations for a submission by a judge.
     */
    public function getForSubmissionByJudge(
        int $submissionId,
        int $judgeId
    ): array {
        $statement = $this->db->prepare(
            "SELECT
                e.*,
                ec.name AS criteria_name,
                ec.max_score,
                ec.weight
             FROM {$this->table} e
             INNER JOIN evaluation_criteria ec
                ON ec.id = e.criteria_id
             WHERE e.submission_id = :submission_id
               AND e.judge_id = :judge_id
             ORDER BY ec.id ASC"
        );

        $statement->execute([
            'submission_id' => $submissionId,
            'judge_id' => $judgeId,
        ]);

        return $statement->fetchAll();
    }


    /**
     * Save one criterion evaluation.
     */
    public function create(
        int $submissionId,
        int $judgeId,
        int $criteriaId,
        float $score,
        ?string $feedback
    ): int {
        $statement = $this->db->prepare(
            "INSERT INTO {$this->table} (
                submission_id,
                judge_id,
                criteria_id,
                score,
                feedback,
                evaluated_at
             )
             VALUES (
                :submission_id,
                :judge_id,
                :criteria_id,
                :score,
                :feedback,
                CURRENT_TIMESTAMP
             )"
        );

        $statement->execute([
            'submission_id' => $submissionId,
            'judge_id' => $judgeId,
            'criteria_id' => $criteriaId,
            'score' => $score,
            'feedback' => $feedback,
        ]);

        return (int) $this->db->lastInsertId();
    }
}