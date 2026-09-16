<?php

namespace App\Models;

use App\Core\Model;

class Judge extends Model
{
    protected string $table = 'judges';


    /**
     * Find the judge profile belonging to a user.
     */
    public function findByUserId(
        int $userId
    ): ?array {
        $statement = $this->db->prepare(
            "SELECT
                j.*,
                u.name,
                u.email
             FROM {$this->table} j
             INNER JOIN users u
                ON u.id = j.user_id
             WHERE j.user_id = :user_id
             LIMIT 1"
        );

        $statement->execute([
            'user_id' => $userId,
        ]);

        $result = $statement->fetch();

        return $result ?: null;
    }


    /**
     * Check whether this judge is assigned to a hackathon.
     */
    public function isAssignedToHackathon(
        int $judgeId,
        int $hackathonId
    ): bool {
        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM hackathon_judges
             WHERE judge_id = :judge_id
               AND hackathon_id = :hackathon_id"
        );

        $statement->execute([
            'judge_id' => $judgeId,
            'hackathon_id' => $hackathonId,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }


    /**
     * Get hackathons assigned to a judge.
     */
    public function getAssignedHackathons(
        int $judgeId
    ): array {
        $statement = $this->db->prepare(
            "SELECT
                h.id,
                h.title,
                h.status,
                h.participation_type,
                h.hackathon_start,
                h.hackathon_end,
                hj.assigned_at,

                (
                    SELECT COUNT(DISTINCT s.id)
                    FROM submissions s
                    INNER JOIN projects p
                        ON p.id = s.project_id
                    WHERE p.hackathon_id = h.id
                      AND s.status IN (
                          'submitted',
                          'late'
                      )
                ) AS submission_count,

                (
                    SELECT COUNT(DISTINCT e.submission_id)
                    FROM evaluations e
                    INNER JOIN submissions s
                        ON s.id = e.submission_id
                    INNER JOIN projects p
                        ON p.id = s.project_id
                    WHERE p.hackathon_id = h.id
                      AND e.judge_id = hj.judge_id
                ) AS evaluated_submission_count

             FROM hackathon_judges hj

             INNER JOIN hackathons h
                ON h.id = hj.hackathon_id

             WHERE hj.judge_id = :judge_id

             ORDER BY
                hj.assigned_at DESC,
                h.id DESC"
        );

        $statement->execute([
            'judge_id' => $judgeId,
        ]);

        return $statement->fetchAll();
    }


    /**
     * Get submitted projects for a hackathon assigned to this judge.
     */
    public function getSubmissionsForHackathon(
        int $judgeId,
        int $hackathonId
    ): array {
        $statement = $this->db->prepare(
            "SELECT
                s.id AS submission_id,
                s.project_id,
                s.version,
                s.submission_notes,
                s.submitted_at,
                s.status,

                p.title AS project_title,
                p.description AS project_description,
                p.github_url,
                p.demo_url,
                p.video_url,

                hr.registration_type,
                hr.team_id,

                t.name AS team_name

             FROM hackathon_judges hj

             INNER JOIN submissions s
                ON s.status IN (
                    'submitted',
                    'late'
                )

             INNER JOIN projects p
                ON p.id = s.project_id
               AND p.hackathon_id = hj.hackathon_id

             INNER JOIN hackathon_registrations hr
                ON hr.id = p.registration_id

             LEFT JOIN teams t
                ON t.id = hr.team_id

             WHERE hj.judge_id = :judge_id
               AND hj.hackathon_id = :hackathon_id

             ORDER BY
                s.submitted_at DESC,
                s.id DESC"
        );

        $statement->execute([
            'judge_id' => $judgeId,
            'hackathon_id' => $hackathonId,
        ]);

        return $statement->fetchAll();
    }


    /**
     * Get one submission if it belongs to a hackathon assigned to the judge.
     */
    public function getSubmissionForJudge(
        int $judgeId,
        int $submissionId
    ): ?array {
        $statement = $this->db->prepare(
            "SELECT
                s.id AS submission_id,
                s.project_id,
                s.version,
                s.submission_notes,
                s.submitted_at,
                s.status,

                p.hackathon_id,
                p.title AS project_title,
                p.description AS project_description,
                p.github_url,
                p.demo_url,
                p.video_url,

                h.title AS hackathon_title,

                hr.registration_type,
                hr.team_id,

                t.name AS team_name

             FROM submissions s

             INNER JOIN projects p
                ON p.id = s.project_id

             INNER JOIN hackathons h
                ON h.id = p.hackathon_id

             INNER JOIN hackathon_judges hj
                ON hj.hackathon_id = p.hackathon_id

             INNER JOIN hackathon_registrations hr
                ON hr.id = p.registration_id

             LEFT JOIN teams t
                ON t.id = hr.team_id

             WHERE s.id = :submission_id
               AND hj.judge_id = :judge_id
               AND s.status IN (
                   'submitted',
                   'late'
               )

             LIMIT 1"
        );

        $statement->execute([
            'submission_id' => $submissionId,
            'judge_id' => $judgeId,
        ]);

        $result = $statement->fetch();

        return $result ?: null;
    }


    /**
     * Count submissions assigned to this judge that still
     * need at least one evaluation.
     */
    public function countPendingEvaluations(
        int $judgeId
    ): int {
        $statement = $this->db->prepare(
            "SELECT COUNT(DISTINCT s.id)

             FROM submissions s

             INNER JOIN projects p
                ON p.id = s.project_id

             INNER JOIN hackathon_judges hj
                ON hj.hackathon_id = p.hackathon_id

             WHERE hj.judge_id = :judge_id

               AND s.status IN (
                   'submitted',
                   'late'
               )

               AND NOT EXISTS (
                   SELECT 1
                   FROM evaluations e
                   WHERE e.submission_id = s.id
                     AND e.judge_id = hj.judge_id
               )"
        );

        $statement->execute([
            'judge_id' => $judgeId,
        ]);

        return (int) $statement->fetchColumn();
    }


    /**
     * Count submissions evaluated by this judge.
     */
    public function countCompletedEvaluations(
        int $judgeId
    ): int {
        $statement = $this->db->prepare(
            "SELECT COUNT(DISTINCT submission_id)
             FROM evaluations
             WHERE judge_id = :judge_id"
        );

        $statement->execute([
            'judge_id' => $judgeId,
        ]);

        return (int) $statement->fetchColumn();
    }
}