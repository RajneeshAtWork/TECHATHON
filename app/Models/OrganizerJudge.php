<?php

namespace App\Models;

use App\Core\Model;

class OrganizerJudge extends Model
{
    protected string $table = 'hackathon_judges';


    /**
     * Get all judges assigned to a hackathon.
     */
    public function getForHackathon(
        int $hackathonId,
        int $organizerId
    ): array {
        $sql = "
            SELECT
                hj.id AS assignment_id,
                hj.hackathon_id,
                hj.judge_id,
                hj.assigned_at,

                j.id AS judge_profile_id,
                j.user_id AS judge_user_id,
                j.expertise,

                u.name,
                u.email,
                u.status AS user_status,

                (
                    SELECT COUNT(*)
                    FROM evaluations e
                    INNER JOIN submissions s
                        ON s.id = e.submission_id
                    INNER JOIN projects p
                        ON p.id = s.project_id
                    WHERE e.judge_id = j.id
                      AND p.hackathon_id = h.id
                ) AS evaluation_count

            FROM hackathon_judges hj

            INNER JOIN hackathons h
                ON h.id = hj.hackathon_id

            INNER JOIN organizers o
                ON o.id = h.organizer_id

            INNER JOIN judges j
                ON j.id = hj.judge_id

            INNER JOIN users u
                ON u.id = j.user_id

            WHERE hj.hackathon_id = :hackathon_id
              AND o.id = :organizer_id

            ORDER BY
                u.name ASC,
                hj.assigned_at ASC
        ";

        return $this->query($sql, [
            'hackathon_id' => $hackathonId,
            'organizer_id' => $organizerId,
        ]);
    }


    /**
     * Get active judges who are not currently assigned
     * to the selected hackathon.
     */
    public function getAvailableJudges(
        int $hackathonId,
        int $organizerId
    ): array {
        $sql = "
            SELECT
                j.id AS judge_id,
                j.user_id,
                j.expertise,

                u.name,
                u.email

            FROM judges j

            INNER JOIN users u
                ON u.id = j.user_id

            INNER JOIN user_roles ur
                ON ur.user_id = u.id

            INNER JOIN roles r
                ON r.id = ur.role_id
               AND r.name = 'judge'

            WHERE u.status = 'active'

              AND EXISTS (
                  SELECT 1
                  FROM hackathons h
                  INNER JOIN organizers o
                      ON o.id = h.organizer_id
                  WHERE h.id = :hackathon_id
                    AND o.id = :organizer_id
              )

              AND NOT EXISTS (
                  SELECT 1
                  FROM hackathon_judges hj
                  WHERE hj.hackathon_id = :hackathon_id_check
                    AND hj.judge_id = j.id
              )

            ORDER BY
                u.name ASC
        ";

        return $this->query($sql, [
            'hackathon_id' => $hackathonId,
            'organizer_id' => $organizerId,
            'hackathon_id_check' => $hackathonId,
        ]);
    }


    /**
     * Find one judge assignment belonging to the
     * organizer's hackathon.
     */
    public function findAssignmentForOrganizer(
        int $assignmentId,
        int $hackathonId,
        int $organizerId
    ): ?array {
        $sql = "
            SELECT
                hj.id AS assignment_id,
                hj.hackathon_id,
                hj.judge_id,
                hj.assigned_at,

                j.user_id,
                j.expertise,

                u.name,
                u.email,

                h.title AS hackathon_title

            FROM hackathon_judges hj

            INNER JOIN hackathons h
                ON h.id = hj.hackathon_id

            INNER JOIN organizers o
                ON o.id = h.organizer_id

            INNER JOIN judges j
                ON j.id = hj.judge_id

            INNER JOIN users u
                ON u.id = j.user_id

            WHERE hj.id = :assignment_id
              AND hj.hackathon_id = :hackathon_id
              AND o.id = :organizer_id

            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'assignment_id' => $assignmentId,
            'hackathon_id' => $hackathonId,
            'organizer_id' => $organizerId,
        ]);

        $result = $statement->fetch();

        return $result ?: null;
    }


    /**
     * Check whether a judge is already assigned
     * to a hackathon.
     */
    public function isAssigned(
        int $hackathonId,
        int $judgeId
    ): bool {
        $sql = "
            SELECT 1
            FROM hackathon_judges
            WHERE hackathon_id = :hackathon_id
              AND judge_id = :judge_id
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'hackathon_id' => $hackathonId,
            'judge_id' => $judgeId,
        ]);

        return (bool) $statement->fetchColumn();
    }


    /**
     * Find a judge profile.
     */
    public function findJudge(int $judgeId): ?array
    {
        $sql = "
            SELECT
                j.id AS judge_id,
                j.user_id,
                j.expertise,

                u.name,
                u.email,
                u.status

            FROM judges j

            INNER JOIN users u
                ON u.id = j.user_id

            WHERE j.id = :judge_id

            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'judge_id' => $judgeId,
        ]);

        $result = $statement->fetch();

        return $result ?: null;
    }


    /**
     * Assign a judge to a hackathon.
     */
    public function assign(
        int $hackathonId,
        int $judgeId
    ): bool {
        if ($this->isAssigned($hackathonId, $judgeId)) {
            return false;
        }

        $sql = "
            INSERT INTO hackathon_judges (
                hackathon_id,
                judge_id,
                assigned_at
            )
            VALUES (
                :hackathon_id,
                :judge_id,
                NOW()
            )
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'hackathon_id' => $hackathonId,
            'judge_id' => $judgeId,
        ]);
    }


    /**
     * Count evaluations made by a judge
     * for a specific hackathon.
     */
    public function countEvaluations(
        int $judgeId,
        int $hackathonId
    ): int {
        $sql = "
            SELECT COUNT(*)
            FROM evaluations e

            INNER JOIN submissions s
                ON s.id = e.submission_id

            INNER JOIN projects p
                ON p.id = s.project_id

            WHERE e.judge_id = :judge_id
              AND p.hackathon_id = :hackathon_id
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'judge_id' => $judgeId,
            'hackathon_id' => $hackathonId,
        ]);

        return (int) $statement->fetchColumn();
    }


    /**
     * Remove a judge assignment from a hackathon.
     */
    public function removeAssignment(
        int $assignmentId,
        int $hackathonId
    ): bool {
        $sql = "
            DELETE FROM hackathon_judges
            WHERE id = :assignment_id
              AND hackathon_id = :hackathon_id
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'assignment_id' => $assignmentId,
            'hackathon_id' => $hackathonId,
        ]);
    }
}