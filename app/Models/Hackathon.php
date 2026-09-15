<?php

namespace App\Models;

use App\Core\Model;

class Hackathon extends Model
{
    protected string $table = 'hackathons';

    /**
     * Get all hackathons with organizer and category information.
     */
    public function getAllWithDetails(): array
    {
        $statement = $this->db->query(
            "SELECT
                h.id,
                h.title,
                h.slug,
                h.participation_type,
                h.min_team_size,
                h.max_team_size,
                h.max_teams,
                h.max_participants,
                h.registration_start,
                h.registration_end,
                h.hackathon_start,
                h.hackathon_end,
                h.submission_deadline,
                h.status,
                h.created_at,

                u.name AS organizer_name,

                c.name AS category_name

             FROM hackathons h

             INNER JOIN organizers o
                ON o.id = h.organizer_id

             INNER JOIN users u
                ON u.id = o.user_id

             LEFT JOIN categories c
                ON c.id = h.category_id

             ORDER BY h.created_at DESC"
        );

        return $statement->fetchAll();
    }

    /**
     * Get hackathons waiting for admin approval.
     */
    public function getPendingApproval(): array
    {
        $statement = $this->db->query(
            "SELECT
                h.id,
                h.title,
                h.slug,
                h.participation_type,
                h.max_teams,
                h.max_participants,
                h.registration_start,
                h.registration_end,
                h.hackathon_start,
                h.hackathon_end,
                h.status,
                h.created_at,

                u.name AS organizer_name,

                c.name AS category_name

             FROM hackathons h

             INNER JOIN organizers o
                ON o.id = h.organizer_id

             INNER JOIN users u
                ON u.id = o.user_id

             LEFT JOIN categories c
                ON c.id = h.category_id

             WHERE h.status = 'pending_approval'

             ORDER BY h.created_at ASC"
        );

        return $statement->fetchAll();
    }

    /**
     * Find a hackathon by slug.
     */
    public function findBySlug(string $slug): ?array
    {
        $statement = $this->db->prepare(
            "SELECT
                h.*,
                u.name AS organizer_name,
                c.name AS category_name

             FROM hackathons h

             INNER JOIN organizers o
                ON o.id = h.organizer_id

             INNER JOIN users u
                ON u.id = o.user_id

             LEFT JOIN categories c
                ON c.id = h.category_id

             WHERE h.slug = :slug

             LIMIT 1"
        );

        $statement->execute([
            'slug' => $slug,
        ]);

        $hackathon = $statement->fetch();

        return $hackathon ?: null;
    }

    /**
     * Update hackathon status.
     */
    public function updateStatus(
        int $hackathonId,
        string $status
    ): void {
        $statement = $this->db->prepare(
            "UPDATE {$this->table}
             SET status = :status
             WHERE id = :id"
        );

        $statement->execute([
            'status' => $status,
            'id' => $hackathonId,
        ]);
    }
}