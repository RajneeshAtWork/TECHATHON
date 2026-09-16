<?php

namespace App\Models;

use App\Core\Model;

class Team extends Model
{
    protected string $table = 'teams';

    /**
     * Get all active teams where the user is a member.
     */
    public function getTeamsForUser(int $userId): array
    {
        $statement = $this->db->prepare(
            "SELECT
                t.id,
                t.name,
                t.description,
                t.leader_id,
                t.status,
                t.created_at,
                t.updated_at
             FROM teams t
             INNER JOIN team_members tm
                ON tm.team_id = t.id
             WHERE tm.user_id = :user_id
               AND t.status = 'active'
             ORDER BY t.created_at DESC"
        );

        $statement->execute([
            'user_id' => $userId,
        ]);

        return $statement->fetchAll();
    }

    /**
     * Find a team that the user belongs to.
     */
    public function findForUser(
        int $teamId,
        int $userId
    ): ?array {
        $statement = $this->db->prepare(
            "SELECT
                t.id,
                t.name,
                t.description,
                t.leader_id,
                t.status,
                t.created_at,
                t.updated_at
             FROM teams t
             INNER JOIN team_members tm
                ON tm.team_id = t.id
             WHERE t.id = :team_id
               AND tm.user_id = :user_id
               AND t.status = 'active'
             LIMIT 1"
        );

        $statement->execute([
            'team_id' => $teamId,
            'user_id' => $userId,
        ]);

        $team = $statement->fetch();

        return $team ?: null;
    }

    /**
     * Get all team members.
     */
    public function getMembers(int $teamId): array
    {
        $statement = $this->db->prepare(
            "SELECT
                tm.id AS membership_id,
                tm.user_id,
                tm.role,
                tm.joined_at,
                u.name,
                u.email
             FROM team_members tm
             INNER JOIN users u
                ON u.id = tm.user_id
             WHERE tm.team_id = :team_id
             ORDER BY
                CASE
                    WHEN tm.role = 'leader'
                    THEN 0
                    ELSE 1
                END,
                tm.joined_at ASC"
        );

        $statement->execute([
            'team_id' => $teamId,
        ]);

        return $statement->fetchAll();
    }

    /**
     * Check whether a user belongs to a team.
     */
    public function isMember(
        int $teamId,
        int $userId
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
     * Check whether a user is the team leader.
     */
    public function isLeader(
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
     * Get team member count.
     */
    public function getMemberCount(
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
     * Create a team.
     *
     * The creator becomes the team leader in BOTH:
     *
     * teams.leader_id
     * team_members.role = leader
     */
    public function createTeam(
        int $userId,
        string $name,
        ?string $description = null
    ): int {
        try {

            $this->db->beginTransaction();

            $statement = $this->db->prepare(
                "INSERT INTO teams (
                    name,
                    description,
                    leader_id,
                    status
                )
                VALUES (
                    :name,
                    :description,
                    :leader_id,
                    'active'
                )"
            );

            $statement->execute([
                'name' => $name,
                'description' => $description,
                'leader_id' => $userId,
            ]);

            $teamId =
                (int) $this->db->lastInsertId();

            $statement = $this->db->prepare(
                "INSERT INTO team_members (
                    team_id,
                    user_id,
                    role,
                    joined_at
                )
                VALUES (
                    :team_id,
                    :user_id,
                    'leader',
                    NOW()
                )"
            );

            $statement->execute([
                'team_id' => $teamId,
                'user_id' => $userId,
            ]);

            $this->db->commit();

            return $teamId;

        } catch (\Throwable $exception) {

            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $exception;
        }
    }

    /**
     * Find a registered user by email.
     */
    public function findUserByEmail(
        string $email
    ): ?array {
        $statement = $this->db->prepare(
            "SELECT
                id,
                name,
                email,
                status
             FROM users
             WHERE email = :email
             LIMIT 1"
        );

        $statement->execute([
            'email' => $email,
        ]);

        $user = $statement->fetch();

        return $user ?: null;
    }

    /**
     * Add a member directly to the team.
     *
     * Invitation workflow will be connected after
     * we confirm team_invitations schema.
     */
    public function addMember(
        int $teamId,
        int $userId
    ): bool {
        if (
            $this->isMember(
                $teamId,
                $userId
            )
        ) {
            return false;
        }

        $statement = $this->db->prepare(
            "INSERT INTO team_members (
                team_id,
                user_id,
                role,
                joined_at
            )
            VALUES (
                :team_id,
                :user_id,
                'member',
                NOW()
            )"
        );

        return $statement->execute([
            'team_id' => $teamId,
            'user_id' => $userId,
        ]);
    }

    /**
     * Remove a member.
     *
     * A leader cannot be removed through this action.
     */
    public function removeMember(
        int $teamId,
        int $userId
    ): bool {
        $statement = $this->db->prepare(
            "DELETE FROM team_members
             WHERE team_id = :team_id
               AND user_id = :user_id
               AND role = 'member'"
        );

        $statement->execute([
            'team_id' => $teamId,
            'user_id' => $userId,
        ]);

        return $statement->rowCount() > 0;
    }
}