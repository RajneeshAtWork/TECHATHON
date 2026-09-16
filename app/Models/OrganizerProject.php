<?php

namespace App\Models;

use App\Core\Model;

class OrganizerProject extends Model
{
    protected string $table = 'projects';

    /**
     * Get all projects belonging to an organizer's hackathon.
     */
    public function getForHackathon(
        int $hackathonId,
        int $organizerId
    ): array {
        $sql = "
            SELECT
                p.id,
                p.hackathon_id,
                p.registration_id,
                p.title,
                p.description,
                p.github_url,
                p.demo_url,
                p.video_url,
                p.created_at,
                p.updated_at,

                hr.registration_type,
                hr.status AS registration_status,
                hr.user_id AS registered_user_id,
                hr.team_id,

                u.name AS participant_name,
                u.email AS participant_email,

                t.name AS team_name,
                leader.name AS team_leader_name,

                (
                    SELECT s.status
                    FROM submissions s
                    WHERE s.project_id = p.id
                    ORDER BY s.version DESC, s.id DESC
                    LIMIT 1
                ) AS latest_submission_status,

                (
                    SELECT s.version
                    FROM submissions s
                    WHERE s.project_id = p.id
                    ORDER BY s.version DESC, s.id DESC
                    LIMIT 1
                ) AS latest_submission_version,

                (
                    SELECT s.submitted_at
                    FROM submissions s
                    WHERE s.project_id = p.id
                      AND s.status IN ('submitted', 'late')
                    ORDER BY s.version DESC, s.id DESC
                    LIMIT 1
                ) AS latest_submitted_at

            FROM projects p

            INNER JOIN hackathons h
                ON h.id = p.hackathon_id

            INNER JOIN organizers o
                ON o.id = h.organizer_id

            INNER JOIN hackathon_registrations hr
                ON hr.id = p.registration_id
               AND hr.hackathon_id = h.id

            LEFT JOIN users u
                ON u.id = hr.user_id

            LEFT JOIN teams t
                ON t.id = hr.team_id

            LEFT JOIN users leader
                ON leader.id = t.leader_id

            WHERE p.hackathon_id = :hackathon_id
              AND o.id = :organizer_id

            ORDER BY p.updated_at DESC, p.id DESC
        ";

        return $this->query($sql, [
            'hackathon_id' => $hackathonId,
            'organizer_id' => $organizerId,
        ]);
    }


    /**
     * Get one project scoped to an organizer's hackathon.
     */
    public function findForHackathon(
        int $hackathonId,
        int $projectId,
        int $organizerId
    ): ?array {
        $sql = "
            SELECT
                p.id,
                p.hackathon_id,
                p.registration_id,
                p.title,
                p.description,
                p.github_url,
                p.demo_url,
                p.video_url,
                p.created_at,
                p.updated_at,

                h.title AS hackathon_title,
                h.participation_type,
                h.submission_deadline,
                h.status AS hackathon_status,

                hr.registration_type,
                hr.status AS registration_status,
                hr.registered_at,

                u.name AS participant_name,
                u.email AS participant_email,

                t.id AS team_id,
                t.name AS team_name,
                t.description AS team_description,
                t.status AS team_status,

                leader.name AS team_leader_name,
                leader.email AS team_leader_email

            FROM projects p

            INNER JOIN hackathons h
                ON h.id = p.hackathon_id

            INNER JOIN organizers o
                ON o.id = h.organizer_id

            INNER JOIN hackathon_registrations hr
                ON hr.id = p.registration_id
               AND hr.hackathon_id = h.id

            LEFT JOIN users u
                ON u.id = hr.user_id

            LEFT JOIN teams t
                ON t.id = hr.team_id

            LEFT JOIN users leader
                ON leader.id = t.leader_id

            WHERE p.hackathon_id = :hackathon_id
              AND p.id = :project_id
              AND o.id = :organizer_id

            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'hackathon_id' => $hackathonId,
            'project_id' => $projectId,
            'organizer_id' => $organizerId,
        ]);

        $result = $statement->fetch();

        return $result ?: null;
    }


    /**
     * Get every submission/version for a project.
     */
    public function getSubmissions(int $projectId): array
    {
        $sql = "
            SELECT
                s.id,
                s.project_id,
                s.version,
                s.submission_notes,
                s.submitted_at,
                s.status
            FROM submissions s
            WHERE s.project_id = :project_id
            ORDER BY s.version DESC, s.id DESC
        ";

        return $this->query($sql, [
            'project_id' => $projectId,
        ]);
    }


    /**
     * Get all members of a project's registered team.
     */
    public function getTeamMembers(
        int $hackathonId,
        int $registrationId,
        int $organizerId
    ): array {
        $sql = "
            SELECT
                tm.id,
                tm.team_id,
                tm.user_id,
                tm.role,
                tm.joined_at,

                u.name,
                u.email,

                t.name AS team_name

            FROM hackathon_registrations hr

            INNER JOIN hackathons h
                ON h.id = hr.hackathon_id

            INNER JOIN organizers o
                ON o.id = h.organizer_id

            INNER JOIN teams t
                ON t.id = hr.team_id

            INNER JOIN team_members tm
                ON tm.team_id = t.id

            INNER JOIN users u
                ON u.id = tm.user_id

            WHERE hr.id = :registration_id
              AND hr.hackathon_id = :hackathon_id
              AND hr.registration_type = 'team'
              AND o.id = :organizer_id

            ORDER BY
                CASE
                    WHEN tm.role = 'leader' THEN 0
                    ELSE 1
                END,
                u.name ASC
        ";

        return $this->query($sql, [
            'registration_id' => $registrationId,
            'hackathon_id' => $hackathonId,
            'organizer_id' => $organizerId,
        ]);
    }
}