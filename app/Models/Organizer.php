<?php

namespace App\Models;

use App\Core\Model;

class Organizer extends Model
{
    protected string $table = 'organizers';

    public function findByUserId(int $userId): ?array
    {
        $statement = $this->db->prepare(
            "SELECT *
             FROM {$this->table}
             WHERE user_id = :user_id
             LIMIT 1"
        );

        $statement->execute([
            'user_id' => $userId,
        ]);

        $organizer = $statement->fetch();

        return $organizer ?: null;
    }

    public function create(array $data): int
    {
        $statement = $this->db->prepare(
            "INSERT INTO {$this->table} (
                user_id,
                organization_name,
                organization_description,
                website
            )
            VALUES (
                :user_id,
                :organization_name,
                :organization_description,
                :website
            )"
        );

        $statement->execute([
            'user_id' => $data['user_id'],
            'organization_name' => $data['organization_name'],
            'organization_description' =>
                $data['organization_description'] ?? null,
            'website' => $data['website'] ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function getDashboardStatistics(
        int $organizerId
    ): array {
        $statistics = [];

        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM hackathons
             WHERE organizer_id = :organizer_id"
        );

        $statement->execute([
            'organizer_id' => $organizerId,
        ]);

        $statistics['total_hackathons'] =
            (int) $statement->fetchColumn();

        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM hackathons
             WHERE organizer_id = :organizer_id
             AND status = 'draft'"
        );

        $statement->execute([
            'organizer_id' => $organizerId,
        ]);

        $statistics['draft_hackathons'] =
            (int) $statement->fetchColumn();

        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM hackathons
             WHERE organizer_id = :organizer_id
             AND status = 'pending_approval'"
        );

        $statement->execute([
            'organizer_id' => $organizerId,
        ]);

        $statistics['pending_hackathons'] =
            (int) $statement->fetchColumn();

        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM hackathons
             WHERE organizer_id = :organizer_id
             AND status = 'approved'"
        );

        $statement->execute([
            'organizer_id' => $organizerId,
        ]);

        $statistics['approved_hackathons'] =
            (int) $statement->fetchColumn();

        return $statistics;
    }

    public function getHackathons(
        int $organizerId
    ): array {
        $statement = $this->db->prepare(
            "SELECT
                h.id,
                h.title,
                h.slug,
                h.description,
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
                h.updated_at,
                c.name AS category_name
             FROM hackathons h
             LEFT JOIN categories c
                ON c.id = h.category_id
             WHERE h.organizer_id = :organizer_id
             ORDER BY h.created_at DESC"
        );

        $statement->execute([
            'organizer_id' => $organizerId,
        ]);

        return $statement->fetchAll();
    }
}