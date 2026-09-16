<?php

namespace App\Models;

use App\Core\Model;

class Submission extends Model
{
    protected string $table = 'submissions';


    /**
     * Get the latest submission for a project.
     */
    public function findLatestForProject(
        int $projectId
    ): ?array {
        $statement = $this->db->prepare(
            "SELECT *
             FROM {$this->table}
             WHERE project_id = :project_id
             ORDER BY version DESC
             LIMIT 1"
        );

        $statement->execute([
            'project_id' => $projectId,
        ]);

        $result = $statement->fetch();

        return $result ?: null;
    }


    /**
     * Get all submissions for a project.
     */
    public function getForProject(
        int $projectId
    ): array {
        $statement = $this->db->prepare(
            "SELECT *
             FROM {$this->table}
             WHERE project_id = :project_id
             ORDER BY version DESC"
        );

        $statement->execute([
            'project_id' => $projectId,
        ]);

        return $statement->fetchAll();
    }


    /**
     * Create a draft submission.
     */
    public function createDraft(
        int $projectId,
        string $notes
    ): int {
        $version = $this->getNextVersion($projectId);

        $statement = $this->db->prepare(
            "INSERT INTO {$this->table} (
                project_id,
                version,
                submission_notes,
                status
             )
             VALUES (
                :project_id,
                :version,
                :submission_notes,
                'draft'
             )"
        );

        $statement->execute([
            'project_id' => $projectId,
            'version' => $version,
            'submission_notes' => $notes,
        ]);

        return (int) $this->db->lastInsertId();
    }


    /**
     * Submit a new version.
     */
    public function createSubmitted(
        int $projectId,
        string $notes
    ): int {
        $version = $this->getNextVersion($projectId);

        $statement = $this->db->prepare(
            "INSERT INTO {$this->table} (
                project_id,
                version,
                submission_notes,
                submitted_at,
                status
             )
             VALUES (
                :project_id,
                :version,
                :submission_notes,
                CURRENT_TIMESTAMP,
                'submitted'
             )"
        );

        $statement->execute([
            'project_id' => $projectId,
            'version' => $version,
            'submission_notes' => $notes,
        ]);

        return (int) $this->db->lastInsertId();
    }


    /**
     * Get the next submission version.
     */
    private function getNextVersion(
        int $projectId
    ): int {
        $statement = $this->db->prepare(
            "SELECT COALESCE(
                MAX(version),
                0
             ) + 1
             FROM {$this->table}
             WHERE project_id = :project_id"
        );

        $statement->execute([
            'project_id' => $projectId,
        ]);

        return (int) $statement->fetchColumn();
    }


    /**
     * Update a draft.
     */
    public function updateDraft(
        int $id,
        string $notes
    ): bool {
        $statement = $this->db->prepare(
            "UPDATE {$this->table}
             SET submission_notes = :notes
             WHERE id = :id
               AND status = 'draft'"
        );

        return $statement->execute([
            'id' => $id,
            'notes' => $notes,
        ]);
    }


    /**
     * Mark a draft as submitted.
     */
    public function submitDraft(
        int $id
    ): bool {
        $statement = $this->db->prepare(
            "UPDATE {$this->table}
             SET
                submitted_at = CURRENT_TIMESTAMP,
                status = 'submitted'
             WHERE id = :id
               AND status = 'draft'"
        );

        return $statement->execute([
            'id' => $id,
        ]);
    }
}