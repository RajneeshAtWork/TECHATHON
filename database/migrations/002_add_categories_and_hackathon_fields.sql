USE techathon;

-- ============================================================
-- CATEGORIES
-- ============================================================

CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL UNIQUE,

    description TEXT NULL,

    status ENUM('active', 'inactive')
        NOT NULL DEFAULT 'active',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);


-- ============================================================
-- DEFAULT CATEGORIES
-- ============================================================

INSERT INTO categories (name, description)
VALUES
    ('Web Development', 'Websites and web-based applications'),
    ('Mobile Development', 'Android, iOS and cross-platform applications'),
    ('Artificial Intelligence', 'AI and intelligent application development'),
    ('Machine Learning', 'Machine learning and predictive solutions'),
    ('Blockchain', 'Blockchain, Web3 and decentralized applications'),
    ('Cybersecurity', 'Security, privacy and protection solutions'),
    ('IoT', 'Internet of Things and connected devices'),
    ('Cloud Computing', 'Cloud-based applications and infrastructure');


-- ============================================================
-- HACKATHON CATEGORY
-- ============================================================

ALTER TABLE hackathons
ADD COLUMN category_id BIGINT UNSIGNED NULL
AFTER organizer_id;


ALTER TABLE hackathons
ADD CONSTRAINT fk_hackathons_category
FOREIGN KEY (category_id)
REFERENCES categories(id)
ON DELETE SET NULL
ON UPDATE CASCADE;


-- ============================================================
-- PARTICIPATION SETTINGS
-- ============================================================

ALTER TABLE hackathons
ADD COLUMN participation_type
    ENUM('individual', 'team', 'both')
    NOT NULL DEFAULT 'individual'
AFTER category_id;


ALTER TABLE hackathons
ADD COLUMN max_teams INT UNSIGNED NULL
AFTER participation_type;


ALTER TABLE hackathons
ADD COLUMN max_team_size INT UNSIGNED NULL
AFTER max_teams;