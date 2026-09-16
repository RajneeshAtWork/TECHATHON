<?php

namespace App\Models;

use App\Core\Model;

class OrganizerTeam extends Model
{
    protected string $table = 'teams';


    /*
    |--------------------------------------------------------------------------
    | Get teams registered for an organizer's hackathon
    |--------------------------------------------------------------------------
    */

    public function getForHackathon(
        int $hackathonId,
        int $organizerId
    ): array {

        $statement = $this->db->prepare(
            "SELECT
                t.id,
                t.name,
                t.description,
                t.status,
                t.leader_id,
                leader.name AS leader_name,
                leader.email AS leader_email,
                COUNT(DISTINCT tm.id) AS member_count,
                hr.registered_at

             FROM teams t

             INNER JOIN hackathon_registrations hr
                ON hr.team_id = t.id
               AND hr.registration_type = 'team'
               AND hr.status = 'registered'

             INNER JOIN hackathons h
                ON h.id = hr.hackathon_id

             INNER JOIN organizers o
                ON o.id = h.organizer_id

             LEFT JOIN users leader
                ON leader.id = t.leader_id

             LEFT JOIN team_members tm
                ON tm.team_id = t.id

             WHERE h.id = :hackathon_id
               AND o.id = :organizer_id

             GROUP BY
                t.id,
                t.name,
                t.description,
                t.status,
                t.leader_id,
                leader.name,
                leader.email,
                hr.registered_at

             ORDER BY t.name ASC"
        );


        $statement->execute([
            'hackathon_id' => $hackathonId,
            'organizer_id' => $organizerId,
        ]);


        return $statement->fetchAll();
    }


    /*
    |--------------------------------------------------------------------------
    | Find one registered team for an organizer's hackathon
    |--------------------------------------------------------------------------
    */

    public function findForHackathon(
        int $hackathonId,
        int $teamId,
        int $organizerId
    ): ?array {

        $statement = $this->db->prepare(
            "SELECT
                t.*,
                leader.name AS leader_name,
                leader.email AS leader_email,
                h.title AS hackathon_title,
                h.participation_type,
                hr.registered_at

             FROM teams t

             INNER JOIN hackathon_registrations hr
                ON hr.team_id = t.id
               AND hr.registration_type = 'team'
               AND hr.status = 'registered'

             INNER JOIN hackathons h
                ON h.id = hr.hackathon_id

             INNER JOIN organizers o
                ON o.id = h.organizer_id

             LEFT JOIN users leader
                ON leader.id = t.leader_id

             WHERE h.id = :hackathon_id
               AND t.id = :team_id
               AND o.id = :organizer_id

             LIMIT 1"
        );


        $statement->execute([
            'hackathon_id' => $hackathonId,
            'team_id' => $teamId,
            'organizer_id' => $organizerId,
        ]);


        $team = $statement->fetch();


        return $team ?: null;
    }


    /*
    |--------------------------------------------------------------------------
    | Get members of one registered team
    |--------------------------------------------------------------------------
    */

    public function getMembers(
        int $hackathonId,
        int $teamId,
        int $organizerId
    ): array {

        $statement = $this->db->prepare(
            "SELECT
                tm.id,
                tm.team_id,
                tm.user_id,
                tm.role,
                tm.joined_at,
                u.name,
                u.email

             FROM team_members tm

             INNER JOIN users u
                ON u.id = tm.user_id

             INNER JOIN teams t
                ON t.id = tm.team_id

             INNER JOIN hackathon_registrations hr
                ON hr.team_id = t.id
               AND hr.registration_type = 'team'
               AND hr.status = 'registered'

             INNER JOIN hackathons h
                ON h.id = hr.hackathon_id

             INNER JOIN organizers o
                ON o.id = h.organizer_id

             WHERE h.id = :hackathon_id
               AND t.id = :team_id
               AND o.id = :organizer_id

             ORDER BY
                CASE
                    WHEN tm.role = 'leader' THEN 0
                    ELSE 1
                END,
                u.name ASC"
        );


        $statement->execute([
            'hackathon_id' => $hackathonId,
            'team_id' => $teamId,
            'organizer_id' => $organizerId,
        ]);


        return $statement->fetchAll();
    }
}