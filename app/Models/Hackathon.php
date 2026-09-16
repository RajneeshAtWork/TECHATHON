<?php

namespace App\Models;

use App\Core\Model;

class Hackathon extends Model
{
    protected string $table = 'hackathons';

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

    public function create(array $data): int
    {
        $statement = $this->db->prepare(
            "INSERT INTO {$this->table} (
                organizer_id,
                category_id,
                title,
                slug,
                description,
                rules,
                requirements,
                participation_type,
                min_team_size,
                max_team_size,
                max_teams,
                max_participants,
                registration_start,
                registration_end,
                hackathon_start,
                hackathon_end,
                submission_deadline,
                status
            )
            VALUES (
                :organizer_id,
                :category_id,
                :title,
                :slug,
                :description,
                :rules,
                :requirements,
                :participation_type,
                :min_team_size,
                :max_team_size,
                :max_teams,
                :max_participants,
                :registration_start,
                :registration_end,
                :hackathon_start,
                :hackathon_end,
                :submission_deadline,
                'draft'
            )"
        );

        $statement->execute([
            'organizer_id' => $data['organizer_id'],
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'description' => $data['description'],
            'rules' => $data['rules'],
            'requirements' => $data['requirements'],
            'participation_type' => $data['participation_type'],
            'min_team_size' => $data['min_team_size'],
            'max_team_size' => $data['max_team_size'],
            'max_teams' => $data['max_teams'],
            'max_participants' => $data['max_participants'],
            'registration_start' => $data['registration_start'],
            'registration_end' => $data['registration_end'],
            'hackathon_start' => $data['hackathon_start'],
            'hackathon_end' => $data['hackathon_end'],
            'submission_deadline' => $data['submission_deadline'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function slugExists(string $slug): bool
    {
        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM {$this->table}
             WHERE slug = :slug"
        );

        $statement->execute([
            'slug' => $slug,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function findForOrganizer(
        int $hackathonId,
        int $organizerId
    ): ?array {
        $statement = $this->db->prepare(
            "SELECT
            h.*,
            c.name AS category_name
         FROM {$this->table} h
         LEFT JOIN categories c
            ON c.id = h.category_id
         WHERE h.id = :hackathon_id
           AND h.organizer_id = :organizer_id
         LIMIT 1"
        );

        $statement->execute([
            'hackathon_id' => $hackathonId,
            'organizer_id' => $organizerId,
        ]);

        $hackathon = $statement->fetch();

        return $hackathon ?: null;
    }

    public function updateForOrganizer(
        int $hackathonId,
        int $organizerId,
        array $data
    ): bool {
        $statement = $this->db->prepare(
            "UPDATE {$this->table}
         SET
            category_id = :category_id,
            title = :title,
            description = :description,
            rules = :rules,
            requirements = :requirements,
            participation_type = :participation_type,
            min_team_size = :min_team_size,
            max_team_size = :max_team_size,
            max_teams = :max_teams,
            max_participants = :max_participants,
            registration_start = :registration_start,
            registration_end = :registration_end,
            hackathon_start = :hackathon_start,
            hackathon_end = :hackathon_end,
            submission_deadline = :submission_deadline
         WHERE id = :hackathon_id
           AND organizer_id = :organizer_id"
        );

        $statement->execute([
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'rules' => $data['rules'],
            'requirements' => $data['requirements'],
            'participation_type' => $data['participation_type'],
            'min_team_size' => $data['min_team_size'],
            'max_team_size' => $data['max_team_size'],
            'max_teams' => $data['max_teams'],
            'max_participants' => $data['max_participants'],
            'registration_start' => $data['registration_start'],
            'registration_end' => $data['registration_end'],
            'hackathon_start' => $data['hackathon_start'],
            'hackathon_end' => $data['hackathon_end'],
            'submission_deadline' => $data['submission_deadline'],
            'hackathon_id' => $hackathonId,
            'organizer_id' => $organizerId,
        ]);

        return $statement->rowCount() > 0;
    }

    /**
     * Get organizer management information and statistics
     * for one organizer-owned hackathon.
     */
    public function getManagementDetails(
        int $hackathonId,
        int $organizerId
    ): ?array {
        $statement = $this->db->prepare(
            "SELECT
            h.id,
            h.title,
            h.slug,
            h.description,
            h.rules,
            h.requirements,
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

            c.name AS category_name,

            /*
             * Total active registrations.
             */
            (
                SELECT COUNT(*)
                FROM hackathon_registrations hr
                WHERE hr.hackathon_id = h.id
                  AND hr.status = 'registered'
            ) AS registration_count,

            /*
             * Registered teams.
             *
             * One registration row = one team slot.
             */
            (
                SELECT COUNT(*)
                FROM hackathon_registrations hr
                WHERE hr.hackathon_id = h.id
                  AND hr.registration_type = 'team'
                  AND hr.status = 'registered'
            ) AS team_count,

            /*
             * Registered individual participants.
             */
            (
                SELECT COUNT(*)
                FROM hackathon_registrations hr
                WHERE hr.hackathon_id = h.id
                  AND hr.registration_type = 'individual'
                  AND hr.status = 'registered'
            ) AS individual_count,

            /*
             * Participants belonging to registered teams.
             */
            (
                SELECT COUNT(DISTINCT tm.user_id)
                FROM hackathon_registrations hr
                INNER JOIN team_members tm
                    ON tm.team_id = hr.team_id
                WHERE hr.hackathon_id = h.id
                  AND hr.registration_type = 'team'
                  AND hr.status = 'registered'
            ) AS team_member_count,

            /*
             * Total projects.
             */
            (
                SELECT COUNT(*)
                FROM projects p
                WHERE p.hackathon_id = h.id
            ) AS project_count,

            /*
             * Submitted projects.
             *
             * DISTINCT project_id prevents multiple submission
             * versions from inflating the count.
             */
            (
                SELECT COUNT(DISTINCT s.project_id)
                FROM submissions s
                INNER JOIN projects p
                    ON p.id = s.project_id
                WHERE p.hackathon_id = h.id
                  AND s.status IN ('submitted', 'late')
            ) AS submission_count,

            /*
             * Judges assigned to this hackathon.
             */
            (
                SELECT COUNT(DISTINCT hj.judge_id)
                FROM hackathon_judges hj
                WHERE hj.hackathon_id = h.id
            ) AS judge_count

         FROM {$this->table} h

         LEFT JOIN categories c
            ON c.id = h.category_id

         WHERE h.id = :hackathon_id
           AND h.organizer_id = :organizer_id

         LIMIT 1"
        );

        $statement->execute([
            'hackathon_id' => $hackathonId,
            'organizer_id' => $organizerId,
        ]);

        $result = $statement->fetch();

        if (!$result) {
            return null;
        }

        /*
         * Total participants = individual registrations
         * + members of registered teams.
         */
        $result['participant_count'] =
            (int) $result['individual_count']
            + (int) $result['team_member_count'];

        return $result;
    }

}