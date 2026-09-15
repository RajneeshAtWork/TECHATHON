<?php

namespace App\Models;

use App\Core\Model;

class AdminDashboard extends Model
{
    public function getStatistics(): array
    {
        $statistics = [];

        $statistics['total_users'] = (int) $this->db
            ->query("SELECT COUNT(*) FROM users")
            ->fetchColumn();

        $statistics['participants'] = (int) $this->db
            ->query("
                SELECT COUNT(DISTINCT ur.user_id)
                FROM user_roles ur
                INNER JOIN roles r
                    ON r.id = ur.role_id
                WHERE r.name = 'participant'
            ")
            ->fetchColumn();

        $statistics['organizers'] = (int) $this->db
            ->query("
                SELECT COUNT(DISTINCT ur.user_id)
                FROM user_roles ur
                INNER JOIN roles r
                    ON r.id = ur.role_id
                WHERE r.name = 'organizer'
            ")
            ->fetchColumn();

        $statistics['judges'] = (int) $this->db
            ->query("
                SELECT COUNT(DISTINCT ur.user_id)
                FROM user_roles ur
                INNER JOIN roles r
                    ON r.id = ur.role_id
                WHERE r.name = 'judge'
            ")
            ->fetchColumn();

        $statistics['total_hackathons'] = (int) $this->db
            ->query("SELECT COUNT(*) FROM hackathons")
            ->fetchColumn();

        $statistics['pending_hackathons'] = (int) $this->db
            ->query("
                SELECT COUNT(*)
                FROM hackathons
                WHERE status = 'pending_approval'
            ")
            ->fetchColumn();

        return $statistics;
    }
}