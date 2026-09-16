<?php

namespace App\Models;

use App\Core\Model;

class OrganizerRegistration extends Model
{
    protected string $table = 'hackathon_registrations';


    /**
     * Get registrations for an organizer-owned hackathon.
     */
    public function getForHackathon(
        int $hackathonId,
        int $organizerId
    ): array {
        $statement = $this->db->prepare(
            "SELECT
                hr.id AS registration_id,
                hr.hackathon_id,
                hr.registration_type,
                hr.user_id,
                hr.team_id,
                hr.status,
                hr.registered_at,

                u.name AS participant_name,
                u.email AS participant_email,

                t.name AS team_name,
                t.leader_id AS team_leader_id,

                leader.name AS team_leader_name

             FROM {$this->table} hr

             INNER JOIN hackathons h
                ON h.id = hr.hackathon_id
               AND h.organizer_id = :organizer_id

             LEFT JOIN users u
                ON u.id = hr.user_id

             LEFT JOIN teams t
                ON t.id = hr.team_id

             LEFT JOIN users leader
                ON leader.id = t.leader_id

             WHERE hr.hackathon_id = :hackathon_id
               AND hr.status = 'registered'

             ORDER BY
                hr.registration_type ASC,
                hr.registered_at DESC,
                hr.id DESC"
        );

        $statement->execute([
            'hackathon_id' => $hackathonId,
            'organizer_id' => $organizerId,
        ]);

        return $statement->fetchAll();
    }


    /**
     * Get one registration belonging to an organizer-owned hackathon.
     */
    public function findForOrganizer(
        int $registrationId,
        int $organizerId
    ): ?array {
        $statement = $this->db->prepare(
            "SELECT
                hr.id AS registration_id,
                hr.hackathon_id,
                hr.registration_type,
                hr.user_id,
                hr.team_id,
                hr.status,
                hr.registered_at,

                h.title AS hackathon_title,

                u.name AS participant_name,
                u.email AS participant_email,

                t.name AS team_name,
                t.description AS team_description,
                t.leader_id AS team_leader_id,

                leader.name AS team_leader_name

             FROM {$this->table} hr

             INNER JOIN hackathons h
                ON h.id = hr.hackathon_id
               AND h.organizer_id = :organizer_id

             LEFT JOIN users u
                ON u.id = hr.user_id

             LEFT JOIN teams t
                ON t.id = hr.team_id

             LEFT JOIN users leader
                ON leader.id = t.leader_id

             WHERE hr.id = :registration_id
               AND hr.status = 'registered'

             LIMIT 1"
        );

        $statement->execute([
            'registration_id' => $registrationId,
            'organizer_id' => $organizerId,
        ]);

        $result = $statement->fetch();

        return $result ?: null;
    }


    /**
     * Get members of a registered team for an organizer-owned hackathon.
     */
    public function getTeamMembers(
        int $registrationId,
        int $organizerId
    ): array {
        $statement = $this->db->prepare(
            "SELECT
                tm.id AS membership_id,
                tm.user_id,
                tm.role,
                tm.joined_at,

                u.name,
                u.email,

                t.name AS team_name

             FROM {$this->table} hr

             INNER JOIN hackathons h
                ON h.id = hr.hackathon_id
               AND h.organizer_id = :organizer_id

             INNER JOIN teams t
                ON t.id = hr.team_id

             INNER JOIN team_members tm
                ON tm.team_id = t.id

             INNER JOIN users u
                ON u.id = tm.user_id

             WHERE hr.id = :registration_id
               AND hr.registration_type = 'team'
               AND hr.status = 'registered'

             ORDER BY
                CASE
                    WHEN tm.role = 'leader'
                    THEN 0
                    ELSE 1
                END,
                u.name ASC"
        );

        $statement->execute([
            'registration_id' => $registrationId,
            'organizer_id' => $organizerId,
        ]);

        return $statement->fetchAll();
    }
}