-- ============================================================
-- TECHATHON
-- Migration: 001_create_core_tables
-- Database: MySQL 8+
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- ROLES
-- ============================================================

CREATE TABLE IF NOT EXISTS roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- USERS
-- ============================================================

CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,

    profile_photo VARCHAR(255) NULL,
    phone VARCHAR(30) NULL,
    bio TEXT NULL,
    github_username VARCHAR(100) NULL,

    status ENUM('active', 'inactive', 'suspended')
        NOT NULL DEFAULT 'active',

    email_verified_at TIMESTAMP NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_users_status (status),
    INDEX idx_users_github_username (github_username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- USER ROLES
-- ============================================================

CREATE TABLE IF NOT EXISTS user_roles (
    user_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL,

    PRIMARY KEY (user_id, role_id),

    CONSTRAINT fk_user_roles_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_user_roles_role
        FOREIGN KEY (role_id)
        REFERENCES roles(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- ORGANIZERS
-- ============================================================

CREATE TABLE IF NOT EXISTS organizers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,

    organization_name VARCHAR(150) NULL,
    organization_description TEXT NULL,
    website VARCHAR(255) NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_organizers_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- HACKATHONS
-- ============================================================

CREATE TABLE IF NOT EXISTS hackathons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    organizer_id BIGINT UNSIGNED NOT NULL,

    title VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,

    description TEXT NULL,
    rules TEXT NULL,
    requirements TEXT NULL,

    participation_type ENUM('individual', 'team', 'both')
        NOT NULL DEFAULT 'both',

    min_team_size INT UNSIGNED NULL,
    max_team_size INT UNSIGNED NULL,

    max_teams INT UNSIGNED NULL,
    max_participants INT UNSIGNED NULL,

    registration_start DATETIME NOT NULL,
    registration_end DATETIME NOT NULL,

    hackathon_start DATETIME NOT NULL,
    hackathon_end DATETIME NOT NULL,

    submission_deadline DATETIME NULL,

    status ENUM(
        'draft',
        'pending_approval',
        'approved',
        'registration_open',
        'registration_closed',
        'ongoing',
        'submission_closed',
        'judging',
        'results_published',
        'completed',
        'rejected'
    ) NOT NULL DEFAULT 'draft',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_hackathons_organizer
        FOREIGN KEY (organizer_id)
        REFERENCES users(id)
        ON DELETE RESTRICT,

    INDEX idx_hackathons_organizer (organizer_id),
    INDEX idx_hackathons_status (status),
    INDEX idx_hackathons_registration_dates (
        registration_start,
        registration_end
    )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- TEAMS
-- ============================================================

CREATE TABLE IF NOT EXISTS teams (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(150) NOT NULL,
    description TEXT NULL,

    leader_id BIGINT UNSIGNED NOT NULL,

    status ENUM('active', 'inactive', 'disbanded')
        NOT NULL DEFAULT 'active',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_teams_leader
        FOREIGN KEY (leader_id)
        REFERENCES users(id)
        ON DELETE RESTRICT,

    INDEX idx_teams_leader (leader_id),
    INDEX idx_teams_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- TEAM MEMBERS
-- ============================================================

CREATE TABLE IF NOT EXISTS team_members (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    team_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,

    role ENUM('leader', 'member')
        NOT NULL DEFAULT 'member',

    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_team_member (team_id, user_id),

    CONSTRAINT fk_team_members_team
        FOREIGN KEY (team_id)
        REFERENCES teams(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_team_members_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    INDEX idx_team_members_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- TEAM INVITATIONS
-- ============================================================

CREATE TABLE IF NOT EXISTS team_invitations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    team_id BIGINT UNSIGNED NOT NULL,
    invited_user_id BIGINT UNSIGNED NOT NULL,
    invited_by BIGINT UNSIGNED NOT NULL,

    status ENUM('pending', 'accepted', 'rejected', 'expired')
        NOT NULL DEFAULT 'pending',

    expires_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_team_invitation_team
        FOREIGN KEY (team_id)
        REFERENCES teams(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_team_invitation_user
        FOREIGN KEY (invited_user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_team_invitation_sender
        FOREIGN KEY (invited_by)
        REFERENCES users(id)
        ON DELETE RESTRICT,

    INDEX idx_team_invitation_user (invited_user_id),
    INDEX idx_team_invitation_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- HACKATHON REGISTRATIONS
-- ============================================================

CREATE TABLE IF NOT EXISTS hackathon_registrations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    hackathon_id BIGINT UNSIGNED NOT NULL,

    registration_type ENUM('individual', 'team')
        NOT NULL,

    user_id BIGINT UNSIGNED NULL,
    team_id BIGINT UNSIGNED NULL,

    status ENUM('registered', 'cancelled', 'disqualified')
        NOT NULL DEFAULT 'registered',

    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_registrations_hackathon
        FOREIGN KEY (hackathon_id)
        REFERENCES hackathons(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_registrations_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_registrations_team
        FOREIGN KEY (team_id)
        REFERENCES teams(id)
        ON DELETE CASCADE,

    UNIQUE KEY uq_hackathon_user (
        hackathon_id,
        user_id
    ),

    UNIQUE KEY uq_hackathon_team (
        hackathon_id,
        team_id
    ),

    INDEX idx_registrations_hackathon_type (
        hackathon_id,
        registration_type
    ),
    INDEX idx_registrations_user (user_id),
    INDEX idx_registrations_team (team_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- PROJECTS
-- ============================================================

CREATE TABLE IF NOT EXISTS projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    hackathon_id BIGINT UNSIGNED NOT NULL,
    registration_id BIGINT UNSIGNED NOT NULL,

    title VARCHAR(200) NOT NULL,
    description TEXT NULL,

    github_url VARCHAR(500) NULL,
    demo_url VARCHAR(500) NULL,
    video_url VARCHAR(500) NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_projects_hackathon
        FOREIGN KEY (hackathon_id)
        REFERENCES hackathons(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_projects_registration
        FOREIGN KEY (registration_id)
        REFERENCES hackathon_registrations(id)
        ON DELETE CASCADE,

    UNIQUE KEY uq_project_registration (registration_id),

    INDEX idx_projects_hackathon (hackathon_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- SUBMISSIONS
-- ============================================================

CREATE TABLE IF NOT EXISTS submissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    project_id BIGINT UNSIGNED NOT NULL,

    version INT UNSIGNED NOT NULL DEFAULT 1,

    submission_notes TEXT NULL,

    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    status ENUM('draft', 'submitted', 'late', 'withdrawn')
        NOT NULL DEFAULT 'submitted',

    CONSTRAINT fk_submissions_project
        FOREIGN KEY (project_id)
        REFERENCES projects(id)
        ON DELETE CASCADE,

    INDEX idx_submissions_project (project_id),
    INDEX idx_submissions_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- JUDGES
-- ============================================================

CREATE TABLE IF NOT EXISTS judges (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT UNSIGNED NOT NULL UNIQUE,

    expertise TEXT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_judges_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- HACKATHON JUDGES
-- ============================================================

CREATE TABLE IF NOT EXISTS hackathon_judges (
    hackathon_id BIGINT UNSIGNED NOT NULL,
    judge_id BIGINT UNSIGNED NOT NULL,

    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (hackathon_id, judge_id),

    CONSTRAINT fk_hackathon_judges_hackathon
        FOREIGN KEY (hackathon_id)
        REFERENCES hackathons(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_hackathon_judges_judge
        FOREIGN KEY (judge_id)
        REFERENCES judges(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- EVALUATION CRITERIA
-- ============================================================

CREATE TABLE IF NOT EXISTS evaluation_criteria (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    hackathon_id BIGINT UNSIGNED NOT NULL,

    name VARCHAR(150) NOT NULL,
    description TEXT NULL,

    max_score DECIMAL(8,2) NOT NULL DEFAULT 10.00,
    weight DECIMAL(8,2) NOT NULL DEFAULT 1.00,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_criteria_hackathon
        FOREIGN KEY (hackathon_id)
        REFERENCES hackathons(id)
        ON DELETE CASCADE,

    INDEX idx_criteria_hackathon (hackathon_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- EVALUATIONS
-- ============================================================

CREATE TABLE IF NOT EXISTS evaluations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    submission_id BIGINT UNSIGNED NOT NULL,
    judge_id BIGINT UNSIGNED NOT NULL,
    criteria_id BIGINT UNSIGNED NOT NULL,

    score DECIMAL(8,2) NOT NULL,
    feedback TEXT NULL,

    evaluated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_evaluations_submission
        FOREIGN KEY (submission_id)
        REFERENCES submissions(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_evaluations_judge
        FOREIGN KEY (judge_id)
        REFERENCES judges(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_evaluations_criteria
        FOREIGN KEY (criteria_id)
        REFERENCES evaluation_criteria(id)
        ON DELETE CASCADE,

    UNIQUE KEY uq_evaluation (
        submission_id,
        judge_id,
        criteria_id
    ),

    INDEX idx_evaluations_submission (submission_id),
    INDEX idx_evaluations_judge (judge_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- ANNOUNCEMENTS
-- ============================================================

CREATE TABLE IF NOT EXISTS announcements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    hackathon_id BIGINT UNSIGNED NOT NULL,
    created_by BIGINT UNSIGNED NOT NULL,

    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,

    published_at DATETIME NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_announcements_hackathon
        FOREIGN KEY (hackathon_id)
        REFERENCES hackathons(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_announcements_creator
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE RESTRICT,

    INDEX idx_announcements_hackathon (hackathon_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- NOTIFICATIONS
-- ============================================================

CREATE TABLE IF NOT EXISTS notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT UNSIGNED NOT NULL,

    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,

    type VARCHAR(50) NULL,

    is_read BOOLEAN NOT NULL DEFAULT FALSE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_notifications_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    INDEX idx_notifications_user_read (
        user_id,
        is_read
    )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- COMMENTS
-- ============================================================

CREATE TABLE IF NOT EXISTS comments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT UNSIGNED NOT NULL,
    hackathon_id BIGINT UNSIGNED NOT NULL,

    content TEXT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_comments_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_comments_hackathon
        FOREIGN KEY (hackathon_id)
        REFERENCES hackathons(id)
        ON DELETE CASCADE,

    INDEX idx_comments_hackathon (hackathon_id),
    INDEX idx_comments_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- AUDIT LOGS
-- ============================================================

CREATE TABLE IF NOT EXISTS audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT UNSIGNED NULL,

    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(100) NULL,
    entity_id BIGINT UNSIGNED NULL,

    description TEXT NULL,

    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_audit_logs_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    INDEX idx_audit_logs_user (user_id),
    INDEX idx_audit_logs_entity (
        entity_type,
        entity_id
    ),
    INDEX idx_audit_logs_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- DEFAULT ROLES
-- ============================================================

INSERT IGNORE INTO roles (name, description)
VALUES
    ('admin', 'System administrator'),
    ('organizer', 'Hackathon organizer'),
    ('participant', 'Hackathon participant'),
    ('judge', 'Hackathon judge');


SET FOREIGN_KEY_CHECKS = 1;