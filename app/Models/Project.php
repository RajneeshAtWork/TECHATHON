<?php

namespace App\Models;

use App\Core\Model;

class Project extends Model
{
    protected string $table = 'projects';


    /**
     * Find a project by ID.
     */
    public function findById(int $id): ?array
    {
        $statement = $this->db->prepare(
            "SELECT
                p.*,
                h.title AS hackathon_title,
                h.status AS hackathon_status,
                hr.registration_type,
                hr.user_id AS registration_user_id,
                hr.team_id,
                t.name AS team_name
             FROM {$this->table} p

             INNER JOIN hackathons h
                ON h.id = p.hackathon_id

             INNER JOIN hackathon_registrations hr
                ON hr.id = p.registration_id

             LEFT JOIN teams t
                ON t.id = hr.team_id

             WHERE p.id = :id

             LIMIT 1"
        );

        $statement->execute([
            'id' => $id,
        ]);

        $result = $statement->fetch();

        return $result ?: null;
    }


    /**
     * Find a project by registration ID.
     */
    public function findByRegistration(
        int $registrationId
    ): ?array {
        $statement = $this->db->prepare(
            "SELECT
                p.*,
                h.title AS hackathon_title,
                h.status AS hackathon_status,
                hr.registration_type,
                hr.user_id AS registration_user_id,
                hr.team_id,
                t.name AS team_name
             FROM {$this->table} p

             INNER JOIN hackathons h
                ON h.id = p.hackathon_id

             INNER JOIN hackathon_registrations hr
                ON hr.id = p.registration_id

             LEFT JOIN teams t
                ON t.id = hr.team_id

             WHERE p.registration_id = :registration_id

             LIMIT 1"
        );

        $statement->execute([
            'registration_id' => $registrationId,
        ]);

        $result = $statement->fetch();

        return $result ?: null;
    }


    /**
     * Get all projects visible to a participant.
     *
     * Individual projects are visible to the registered user.
     * Team projects are visible to every member of the registered team.
     */
    public function getForUser(
        int $userId
    ): array {
        $statement = $this->db->prepare(
            "SELECT
                p.*,
                h.title AS hackathon_title,
                h.status AS hackathon_status,
                hr.registration_type,
                hr.team_id,
                t.name AS team_name,

                (
                    SELECT s.status
                    FROM submissions s
                    WHERE s.project_id = p.id
                    ORDER BY s.version DESC
                    LIMIT 1
                ) AS latest_status

             FROM {$this->table} p

             INNER JOIN hackathons h
                ON h.id = p.hackathon_id

             INNER JOIN hackathon_registrations hr
                ON hr.id = p.registration_id

             LEFT JOIN teams t
                ON t.id = hr.team_id

             LEFT JOIN team_members tm
                ON tm.team_id = hr.team_id
               AND tm.user_id = :member_user_id

             WHERE hr.status = 'registered'

               AND (
                    hr.user_id = :direct_user_id

                    OR (
                        hr.registration_type = 'team'
                        AND tm.user_id IS NOT NULL
                    )
               )

             ORDER BY
                p.updated_at DESC,
                p.id DESC"
        );

        $statement->execute([
            'direct_user_id' => $userId,
            'member_user_id' => $userId,
        ]);

        return $statement->fetchAll();
    }


    /**
     * Create a new project.
     */
    public function create(
        int $hackathonId,
        int $registrationId,
        string $title,
        string $description,
        ?string $githubUrl,
        ?string $demoUrl,
        ?string $videoUrl
    ): int {
        $statement = $this->db->prepare(
            "INSERT INTO {$this->table} (
                hackathon_id,
                registration_id,
                title,
                description,
                github_url,
                demo_url,
                video_url
             )
             VALUES (
                :hackathon_id,
                :registration_id,
                :title,
                :description,
                :github_url,
                :demo_url,
                :video_url
             )"
        );

        $statement->execute([
            'hackathon_id' => $hackathonId,
            'registration_id' => $registrationId,
            'title' => $title,
            'description' => $description,
            'github_url' => $githubUrl,
            'demo_url' => $demoUrl,
            'video_url' => $videoUrl,
        ]);

        return (int) $this->db->lastInsertId();
    }


    /**
     * Update a project.
     */
    public function update(
        int $id,
        string $title,
        string $description,
        ?string $githubUrl,
        ?string $demoUrl,
        ?string $videoUrl
    ): bool {
        $statement = $this->db->prepare(
            "UPDATE {$this->table}
             SET
                title = :title,
                description = :description,
                github_url = :github_url,
                demo_url = :demo_url,
                video_url = :video_url,
                updated_at = CURRENT_TIMESTAMP
             WHERE id = :id"
        );

        return $statement->execute([
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'github_url' => $githubUrl,
            'demo_url' => $demoUrl,
            'video_url' => $videoUrl,
        ]);
    }


    /**
     * Check whether a project already exists for a registration.
     */
    public function existsForRegistration(
        int $registrationId
    ): bool {
        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM {$this->table}
             WHERE registration_id = :registration_id"
        );

        $statement->execute([
            'registration_id' => $registrationId,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }
}