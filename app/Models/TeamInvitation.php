<?php

namespace App\Models;

use App\Core\Model;

class TeamInvitation extends Model
{
    protected string $table = 'team_invitations';

    /**
     * Get pending invitations for a user.
     *
     * Expired invitations are marked as expired first.
     */
    public function getPendingForUser(
        int $userId
    ): array {
        $this->markExpiredForUser($userId);

        $statement = $this->db->prepare(
            "SELECT
                ti.id,
                ti.team_id,
                ti.invited_user_id,
                ti.invited_by,
                ti.status,
                ti.expires_at,
                ti.created_at,
                ti.updated_at,
                t.name AS team_name,
                t.description AS team_description,
                u.name AS inviter_name,
                u.email AS inviter_email
             FROM team_invitations ti
             INNER JOIN teams t
                ON t.id = ti.team_id
             INNER JOIN users u
                ON u.id = ti.invited_by
             WHERE ti.invited_user_id = :user_id
               AND ti.status = 'pending'
               AND t.status = 'active'
             ORDER BY ti.created_at DESC"
        );

        $statement->execute([
            'user_id' => $userId,
        ]);

        return $statement->fetchAll();
    }

    /**
     * Get pending invitations for a specific team.
     */
    public function getPendingForTeam(
        int $teamId
    ): array {
        $statement = $this->db->prepare(
            "SELECT
                ti.id,
                ti.team_id,
                ti.invited_user_id,
                ti.invited_by,
                ti.status,
                ti.expires_at,
                ti.created_at,
                u.name AS invited_name,
                u.email AS invited_email
             FROM team_invitations ti
             INNER JOIN users u
                ON u.id = ti.invited_user_id
             WHERE ti.team_id = :team_id
               AND ti.status = 'pending'
             ORDER BY ti.created_at DESC"
        );

        $statement->execute([
            'team_id' => $teamId,
        ]);

        return $statement->fetchAll();
    }

    /**
     * Find one invitation.
     */
    public function find(int $id): ?array
    {
        $statement = $this->db->prepare(
            "SELECT
                ti.*,
                t.name AS team_name,
                t.description AS team_description
             FROM team_invitations ti
             INNER JOIN teams t
                ON t.id = ti.team_id
             WHERE ti.id = :id
             LIMIT 1"
        );

        $statement->execute([
            'id' => $id,
        ]);

        $invitation = $statement->fetch();

        return $invitation ?: null;
    }

    /**
     * Check whether a pending invitation already exists.
     */
    public function hasPendingInvitation(
        int $teamId,
        int $userId
    ): bool {
        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM team_invitations
             WHERE team_id = :team_id
               AND invited_user_id = :user_id
               AND status = 'pending'
               AND (
                    expires_at IS NULL
                    OR expires_at > NOW()
               )"
        );

        $statement->execute([
            'team_id' => $teamId,
            'user_id' => $userId,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }

    /**
     * Create a new invitation.
     *
     * Invitation expires after 7 days.
     */
    public function createInvitation(
        int $teamId,
        int $invitedUserId,
        int $invitedBy
    ): int {
        $statement = $this->db->prepare(
            "INSERT INTO team_invitations (
                team_id,
                invited_user_id,
                invited_by,
                status,
                expires_at,
                created_at,
                updated_at
            )
            VALUES (
                :team_id,
                :invited_user_id,
                :invited_by,
                'pending',
                DATE_ADD(NOW(), INTERVAL 7 DAY),
                NOW(),
                NOW()
            )"
        );

        $statement->execute([
            'team_id' => $teamId,
            'invited_user_id' => $invitedUserId,
            'invited_by' => $invitedBy,
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Accept an invitation.
     *
     * This creates the team membership and marks the invitation
     * as accepted in one transaction.
     */
    public function accept(
        int $invitationId,
        int $userId
    ): bool {
        try {

            $this->db->beginTransaction();

            $statement = $this->db->prepare(
                "SELECT
                    id,
                    team_id,
                    invited_user_id,
                    status,
                    expires_at
                 FROM team_invitations
                 WHERE id = :id
                   AND invited_user_id = :user_id
                 LIMIT 1
                 FOR UPDATE"
            );

            $statement->execute([
                'id' => $invitationId,
                'user_id' => $userId,
            ]);

            $invitation = $statement->fetch();

            if (!$invitation) {
                $this->db->rollBack();

                return false;
            }

            if ($invitation['status'] !== 'pending') {
                $this->db->rollBack();

                return false;
            }

            if (
                !empty($invitation['expires_at'])
                && strtotime(
                    $invitation['expires_at']
                ) <= time()
            ) {

                $statement = $this->db->prepare(
                    "UPDATE team_invitations
                     SET
                        status = 'expired',
                        updated_at = NOW()
                     WHERE id = :id"
                );

                $statement->execute([
                    'id' => $invitationId,
                ]);

                $this->db->commit();

                return false;
            }

            /*
             * Do not add the same participant twice.
             */
            $statement = $this->db->prepare(
                "SELECT COUNT(*)
                 FROM team_members
                 WHERE team_id = :team_id
                   AND user_id = :user_id"
            );

            $statement->execute([
                'team_id' => $invitation['team_id'],
                'user_id' => $userId,
            ]);

            if ((int) $statement->fetchColumn() > 0) {

                $statement = $this->db->prepare(
                    "UPDATE team_invitations
                     SET
                        status = 'accepted',
                        updated_at = NOW()
                     WHERE id = :id"
                );

                $statement->execute([
                    'id' => $invitationId,
                ]);

                $this->db->commit();

                return true;
            }

            /*
             * Add participant to team.
             */
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

            $statement->execute([
                'team_id' => $invitation['team_id'],
                'user_id' => $userId,
            ]);

            /*
             * Mark invitation accepted.
             */
            $statement = $this->db->prepare(
                "UPDATE team_invitations
                 SET
                    status = 'accepted',
                    updated_at = NOW()
                 WHERE id = :id"
            );

            $statement->execute([
                'id' => $invitationId,
            ]);

            $this->db->commit();

            return true;

        } catch (\Throwable $exception) {

            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $exception;
        }
    }

    /**
     * Reject an invitation.
     */
    public function reject(
        int $invitationId,
        int $userId
    ): bool {
        $statement = $this->db->prepare(
            "UPDATE team_invitations
             SET
                status = 'rejected',
                updated_at = NOW()
             WHERE id = :id
               AND invited_user_id = :user_id
               AND status = 'pending'"
        );

        $statement->execute([
            'id' => $invitationId,
            'user_id' => $userId,
        ]);

        return $statement->rowCount() > 0;
    }

    /**
     * Mark expired invitations belonging to a user.
     */
    public function markExpiredForUser(
        int $userId
    ): void {
        $statement = $this->db->prepare(
            "UPDATE team_invitations
             SET
                status = 'expired',
                updated_at = NOW()
             WHERE invited_user_id = :user_id
               AND status = 'pending'
               AND expires_at IS NOT NULL
               AND expires_at <= NOW()"
        );

        $statement->execute([
            'user_id' => $userId,
        ]);
    }

    /**
     * Mark expired invitations for a team.
     */
    public function markExpiredForTeam(
        int $teamId
    ): void {
        $statement = $this->db->prepare(
            "UPDATE team_invitations
             SET
                status = 'expired',
                updated_at = NOW()
             WHERE team_id = :team_id
               AND status = 'pending'
               AND expires_at IS NOT NULL
               AND expires_at <= NOW()"
        );

        $statement->execute([
            'team_id' => $teamId,
        ]);
    }
}