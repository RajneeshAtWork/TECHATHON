<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class ParticipantHackathon extends Model
{
    protected string $table = 'hackathons';

    /**
     * Get hackathons visible to participants.
     *
     * Approved and registration_open hackathons are visible.
     */
    public function getAllAvailable(): array
    {
        $statement = $this->db->query(
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
                u.name AS organizer_name,
                c.name AS category_name
             FROM hackathons h
             INNER JOIN organizers o
                ON o.id = h.organizer_id
             INNER JOIN users u
                ON u.id = o.user_id
             LEFT JOIN categories c
                ON c.id = h.category_id
             WHERE h.status IN (
                'approved',
                'registration_open'
             )
             ORDER BY
                CASE
                    WHEN h.status = 'registration_open'
                    THEN 0
                    ELSE 1
                END,
                h.registration_start ASC,
                h.created_at DESC"
        );

        return $statement->fetchAll();
    }

    /**
     * Find one participant-visible hackathon.
     */
    public function findAvailable(int $id): ?array
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
             WHERE h.id = :id
               AND h.status IN (
                    'approved',
                    'registration_open'
               )
             LIMIT 1"
        );

        $statement->execute([
            'id' => $id,
        ]);

        $hackathon = $statement->fetch();

        return $hackathon ?: null;
    }

    /**
     * Get a participant's current registration for a hackathon.
     */
    public function getRegistrationForUser(
        int $hackathonId,
        int $userId
    ): ?array {
        $statement = $this->db->prepare(
            "SELECT
                r.*,
                t.name AS team_name
             FROM hackathon_registrations r
             LEFT JOIN teams t
                ON t.id = r.team_id
             WHERE r.hackathon_id = :hackathon_id
               AND r.user_id = :user_id
               AND r.status = 'registered'
             LIMIT 1"
        );

        $statement->execute([
            'hackathon_id' => $hackathonId,
            'user_id' => $userId,
        ]);

        $registration = $statement->fetch();

        return $registration ?: null;
    }

    /**
     * Count active individual registrations.
     */
    public function countIndividualRegistrations(
        int $hackathonId
    ): int {
        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM hackathon_registrations
             WHERE hackathon_id = :hackathon_id
               AND registration_type = 'individual'
               AND status = 'registered'"
        );

        $statement->execute([
            'hackathon_id' => $hackathonId,
        ]);

        return (int) $statement->fetchColumn();
    }

    /**
     * Count active team registrations.
     *
     * One registration row = one team.
     */
    public function countTeamRegistrations(
        int $hackathonId
    ): int {
        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM hackathon_registrations
             WHERE hackathon_id = :hackathon_id
               AND registration_type = 'team'
               AND status = 'registered'"
        );

        $statement->execute([
            'hackathon_id' => $hackathonId,
        ]);

        return (int) $statement->fetchColumn();
    }

    /**
     * Get teams where the participant is a member.
     *
     * This does not attach a team to a hackathon.
     * A team can participate in multiple hackathons.
     */
    public function getTeamsForUser(int $userId): array
    {
        $statement = $this->db->prepare(
            "SELECT
                t.id,
                t.name
             FROM teams t
             INNER JOIN team_members tm
                ON tm.team_id = t.id
             WHERE tm.user_id = :user_id
             ORDER BY t.name ASC"
        );

        $statement->execute([
            'user_id' => $userId,
        ]);

        return $statement->fetchAll();
    }

    /**
     * Check whether a participant belongs to a team.
     */
    public function isUserTeamMember(
        int $userId,
        int $teamId
    ): bool {
        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM team_members
             WHERE team_id = :team_id
               AND user_id = :user_id"
        );

        $statement->execute([
            'team_id' => $teamId,
            'user_id' => $userId,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }

    /**
     * Create a registration.
     */
    public function createRegistration(
        int $hackathonId,
        int $userId,
        string $registrationType,
        ?int $teamId = null
    ): int {
        $statement = $this->db->prepare(
            "INSERT INTO hackathon_registrations (
                hackathon_id,
                registration_type,
                user_id,
                team_id,
                status,
                registered_at
            )
            VALUES (
                :hackathon_id,
                :registration_type,
                :user_id,
                :team_id,
                'registered',
                NOW()
            )"
        );

        $statement->execute([
            'hackathon_id' => $hackathonId,
            'registration_type' => $registrationType,
            'user_id' => $userId,
            'team_id' => $teamId,
        ]);

        return (int) $this->db->lastInsertId();
    }
}