<?php

namespace App\Models;

use App\Core\Model;

class OrganizerSubmission extends Model
{
    protected string $table = 'submissions';

    /**
     * Get all submissions for an organizer's hackathon.
     *
     * One project can have multiple submission versions,
     * so every submission row is returned.
     */
    public function getForHackathon(
        int $hackathonId,
        int $organizerId
    ): array {
        $sql = "
            SELECT
                s.id,
                s.project_id,
                s.version,
                s.submission_notes,
                s.submitted_at,
                s.status,

                p.title AS project_title,
                p.github_url,
                p.demo_url,
                p.video_url,

                hr.id AS registration_id,
                hr.registration_type,
                hr.status AS registration_status,

                u.name AS participant_name,
                u.email AS participant_email,

                t.id AS team_id,
                t.name AS team_name,
                leader.name AS team_leader_name

            FROM submissions s

            INNER JOIN projects p
                ON p.id = s.project_id

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

            WHERE h.id = :hackathon_id
              AND o.id = :organizer_id

            ORDER BY
                CASE
                    WHEN s.status IN ('submitted', 'late') THEN 0
                    WHEN s.status = 'draft' THEN 1
                    ELSE 2
                END,
                s.submitted_at DESC,
                s.id DESC
        ";

        return $this->query($sql, [
            'hackathon_id' => $hackathonId,
            'organizer_id' => $organizerId,
        ]);
    }


    /**
     * Find one submission belonging to an organizer's hackathon.
     */
    public function findForHackathon(
        int $hackathonId,
        int $submissionId,
        int $organizerId
    ): ?array {
        $sql = "
            SELECT
                s.id,
                s.project_id,
                s.version,
                s.submission_notes,
                s.submitted_at,
                s.status,

                p.title AS project_title,
                p.description AS project_description,
                p.github_url,
                p.demo_url,
                p.video_url,

                h.id AS hackathon_id,
                h.title AS hackathon_title,
                h.status AS hackathon_status,
                h.submission_deadline,

                hr.id AS registration_id,
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

            FROM submissions s

            INNER JOIN projects p
                ON p.id = s.project_id

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

            WHERE h.id = :hackathon_id
              AND s.id = :submission_id
              AND o.id = :organizer_id

            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'hackathon_id' => $hackathonId,
            'submission_id' => $submissionId,
            'organizer_id' => $organizerId,
        ]);

        $result = $statement->fetch();

        return $result ?: null;
    }


    /**
     * Get all versions belonging to a project.
     */
    public function getProjectSubmissions(
        int $projectId
    ): array {
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

            ORDER BY
                s.version DESC,
                s.id DESC
        ";

        return $this->query($sql, [
            'project_id' => $projectId,
        ]);
    }


    /**
     * Get registered team members for a team submission.
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
                u.email

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