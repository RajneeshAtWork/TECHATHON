<?php

namespace App\Models;

use App\Core\Model;
use PDO;
use Throwable;

class HackathonRegistration extends Model
{
    protected string $table = 'hackathon_registrations';


    /**
     * Find the active registration for a user in a hackathon.
     *
     * Checks both:
     * - individual registrations
     * - team registrations where the user is the registered team leader
     */
    public function findForUser(
        int $hackathonId,
        int $userId
    ): ?array {
        $statement = $this->db->prepare(
            "SELECT
            hr.*,
            t.name AS team_name
         FROM {$this->table} hr

         LEFT JOIN teams t
            ON t.id = hr.team_id

         LEFT JOIN team_members tm
            ON tm.team_id = hr.team_id
           AND tm.user_id = :member_user_id

         WHERE hr.hackathon_id = :hackathon_id
           AND hr.status = 'registered'
           AND (
                hr.user_id = :direct_user_id
                OR (
                    hr.registration_type = 'team'
                    AND tm.user_id IS NOT NULL
                )
           )

         LIMIT 1"
        );

        $statement->execute([
            'hackathon_id' => $hackathonId,
            'direct_user_id' => $userId,
            'member_user_id' => $userId,
        ]);

        $result = $statement->fetch();

        return $result ?: null;
    }


    /**
     * Check whether a user already participates in this hackathon.
     *
     * This also checks team membership, so a team member cannot
     * register individually after already belonging to a registered team.
     */
    public function userHasActiveRegistration(
        int $hackathonId,
        int $userId
    ): bool {
        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM {$this->table} hr
             WHERE hr.hackathon_id = :hackathon_id
               AND hr.status = 'registered'
               AND (
                    hr.user_id = :user_id
                    OR (
                        hr.registration_type = 'team'
                        AND hr.team_id IN (
                            SELECT tm.team_id
                            FROM team_members tm
                            WHERE tm.user_id = :team_user_id
                        )
                    )
               )"
        );

        $statement->execute([
            'hackathon_id' => $hackathonId,
            'user_id' => $userId,
            'team_user_id' => $userId,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }


    /**
     * Count active individual registrations.
     *
     * This is used against max_participants.
     */
    public function countIndividualRegistrations(
        int $hackathonId
    ): int {
        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM {$this->table}
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
     * This is used against max_teams.
     */
    public function countTeamRegistrations(
        int $hackathonId
    ): int {
        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM {$this->table}
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
     * Create an individual registration.
     */
    public function createIndividual(
        int $hackathonId,
        int $userId
    ): int {
        return $this->insertRegistration(
            $hackathonId,
            'individual',
            $userId,
            null
        );
    }


    /**
     * Create a team registration.
     *
     * The team leader becomes user_id for the registration.
     */
    public function createTeam(
        int $hackathonId,
        int $teamId,
        int $leaderId
    ): int {
        return $this->insertRegistration(
            $hackathonId,
            'team',
            $leaderId,
            $teamId
        );
    }


    /**
     * Insert a registration.
     */
    private function insertRegistration(
        int $hackathonId,
        string $registrationType,
        ?int $userId,
        ?int $teamId
    ): int {
        $statement = $this->db->prepare(
            "INSERT INTO {$this->table} (
                hackathon_id,
                registration_type,
                user_id,
                team_id,
                status
             )
             VALUES (
                :hackathon_id,
                :registration_type,
                :user_id,
                :team_id,
                'registered'
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


    /**
     * Return a user's active teams that can be selected.
     */
    public function getTeamsForUser(
        int $userId
    ): array {
        $statement = $this->db->prepare(
            "SELECT
                t.id,
                t.name,
                t.description,
                t.leader_id,
                COUNT(tm2.id) AS member_count
             FROM teams t
             INNER JOIN team_members tm
                ON tm.team_id = t.id
               AND tm.user_id = :user_id
             LEFT JOIN team_members tm2
                ON tm2.team_id = t.id
             WHERE t.status = 'active'
             GROUP BY
                t.id,
                t.name,
                t.description,
                t.leader_id
             ORDER BY t.name ASC"
        );

        $statement->execute([
            'user_id' => $userId,
        ]);

        return $statement->fetchAll();
    }


    /**
     * Check whether a specific user is the leader of a team.
     */
    public function isTeamLeader(
        int $teamId,
        int $userId
    ): bool {
        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM teams
             WHERE id = :team_id
               AND leader_id = :user_id
               AND status = 'active'"
        );

        $statement->execute([
            'team_id' => $teamId,
            'user_id' => $userId,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }


    /**
     * Check whether a team belongs to the user's active teams.
     */
    public function userIsTeamMember(
        int $teamId,
        int $userId
    ): bool {
        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM team_members tm
             INNER JOIN teams t
                ON t.id = tm.team_id
             WHERE tm.team_id = :team_id
               AND tm.user_id = :user_id
               AND t.status = 'active'"
        );

        $statement->execute([
            'team_id' => $teamId,
            'user_id' => $userId,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }


    /**
     * Get team member count.
     */
    public function getTeamMemberCount(
        int $teamId
    ): int {
        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM team_members
             WHERE team_id = :team_id"
        );

        $statement->execute([
            'team_id' => $teamId,
        ]);

        return (int) $statement->fetchColumn();
    }

    /**
     * Get all active hackathon registrations visible to a user.
     *
     * Individual registrations are linked directly through user_id.
     * Team registrations are visible to every member of the registered team.
     */
    public function getForUser(int $userId): array
    {
        $statement = $this->db->prepare(
            "SELECT
            hr.id,
            hr.hackathon_id,
            hr.registration_type,
            hr.user_id,
            hr.team_id,
            hr.status,
            hr.registered_at,

            h.title AS hackathon_title,
            h.slug AS hackathon_slug,
            h.status AS hackathon_status,
            h.participation_type,
            h.hackathon_start,
            h.hackathon_end,

            c.name AS category_name,

            t.name AS team_name

         FROM {$this->table} hr

         INNER JOIN hackathons h
            ON h.id = hr.hackathon_id

         LEFT JOIN categories c
            ON c.id = h.category_id

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

         ORDER BY hr.registered_at DESC,
                  hr.id DESC"
        );

        $statement->execute([
            'direct_user_id' => $userId,
            'member_user_id' => $userId,
        ]);

        return $statement->fetchAll();
    }

    /**
     * Find a registration visible to a specific participant.
     *
     * Individual registration:
     *   hr.user_id = participant
     *
     * Team registration:
     *   participant belongs to the registered team.
     */
    public function findForUserRegistration(
        int $registrationId,
        int $userId
    ): ?array {
        $statement = $this->db->prepare(
            "SELECT
            hr.*,
            h.title AS hackathon_title,
            t.name AS team_name
         FROM {$this->table} hr

         INNER JOIN hackathons h
            ON h.id = hr.hackathon_id

         LEFT JOIN teams t
            ON t.id = hr.team_id

         LEFT JOIN team_members tm
            ON tm.team_id = hr.team_id
           AND tm.user_id = :member_user_id

         WHERE hr.id = :registration_id
           AND hr.status = 'registered'
           AND (
                hr.user_id = :direct_user_id
                OR (
                    hr.registration_type = 'team'
                    AND tm.user_id IS NOT NULL
                )
           )

         LIMIT 1"
        );

        $statement->execute([
            'registration_id' => $registrationId,
            'direct_user_id' => $userId,
            'member_user_id' => $userId,
        ]);

        $result = $statement->fetch();

        return $result ?: null;
    }

}