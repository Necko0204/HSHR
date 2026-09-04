ALTER TABLE admin
    ADD COLUMN IF NOT EXISTS sidebarOn TINYINT(1) NOT NULL DEFAULT 1,
    ADD COLUMN IF NOT EXISTS darkmodeOn TINYINT(1) NOT NULL DEFAULT 0;

ALTER TABLE staff_accounts
    MODIFY COLUMN role ENUM('staff', 'intern') NOT NULL DEFAULT 'staff';

ALTER TABLE staff_accounts
    ADD UNIQUE INDEX IF NOT EXISTS staff_accounts_employee_unique (employee_id);

CREATE TABLE IF NOT EXISTS historical_data (
    date DATE NOT NULL,
    total_staff INT UNSIGNED NOT NULL DEFAULT 0,
    active_teachers INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (date)
);

CREATE TABLE IF NOT EXISTS attendance (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    employee_id VARCHAR(50) NOT NULL,
    date DATE NOT NULL,
    time_in TIME NULL,
    time_out TIME NULL,
    break_in TIME NULL,
    break_out TIME NULL,
    break_duration TIME NULL,
    total_hours TIME NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'Pending',
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    image_path VARCHAR(255) NULL,
    manual_clockout_flag TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE KEY attendance_employee_date_unique (employee_id, date)
);

ALTER TABLE leave_requests
    ADD COLUMN IF NOT EXISTS employee_id VARCHAR(50) NULL AFTER leave_id,
    MODIFY COLUMN employee_name VARCHAR(100) NULL;

ALTER TABLE leave_types
    ADD COLUMN IF NOT EXISTS max_days INT UNSIGNED NOT NULL DEFAULT 15;

ALTER TABLE employees
    ADD COLUMN IF NOT EXISTS salary DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT 0.00,
    ADD COLUMN IF NOT EXISTS employment_type VARCHAR(30) NOT NULL DEFAULT 'full_time';

ALTER TABLE employees
    MODIFY COLUMN maritalstatus ENUM('Single','Married','Divorced','Widowed') NOT NULL DEFAULT 'Single',
    MODIFY COLUMN `height(cm)` INT NOT NULL DEFAULT 0,
    MODIFY COLUMN `weight(kg)` INT NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS roles (
    role_id VARCHAR(50) NOT NULL,
    role_name VARCHAR(100) NOT NULL,
    description VARCHAR(500) NOT NULL DEFAULT '',
    status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (role_id),
    UNIQUE KEY roles_name_unique (role_name)
);

INSERT IGNORE INTO roles (role_id, role_name, description)
VALUES
    ('HSHI-ROLE00000001', 'Administrator', 'Full access to human resource administration.'),
    ('HSHI-ROLE00000002', 'Teacher', 'Employee portal access for teaching staff.');

CREATE TABLE IF NOT EXISTS deductions (
    id VARCHAR(50) NOT NULL,
    name VARCHAR(120) NOT NULL,
    description VARCHAR(500) NULL,
    deduction_type ENUM('fixed', 'percentage') NOT NULL,
    amount DECIMAL(12,2) NULL,
    percentage DECIMAL(7,4) NULL,
    max_cap DECIMAL(12,2) NULL,
    is_mandatory TINYINT(1) NOT NULL DEFAULT 0,
    status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS applicants (
    applicant_id VARCHAR(50) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    middlename VARCHAR(100) NULL,
    email VARCHAR(190) NOT NULL,
    gender VARCHAR(30) NOT NULL,
    dateofbirth DATE NOT NULL,
    contact VARCHAR(30) NOT NULL,
    highest_degree VARCHAR(150) NOT NULL,
    university VARCHAR(190) NOT NULL,
    graduation_year SMALLINT UNSIGNED NOT NULL,
    major VARCHAR(150) NULL,
    previous_school VARCHAR(190) NOT NULL,
    years_of_experience SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    subjects_taught TEXT NULL,
    license VARCHAR(150) NOT NULL,
    certifications TEXT NULL,
    resume_path VARCHAR(500) NOT NULL,
    interview_date DATETIME NULL,
    status ENUM('Pending', 'for_interview', 'Accepted', 'Rejected') NOT NULL DEFAULT 'Pending',
    submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (applicant_id),
    KEY applicants_status_submitted (status, submitted_at),
    KEY applicants_email (email)
);

CREATE TABLE IF NOT EXISTS levels (
    level_id VARCHAR(50) NOT NULL,
    level_name VARCHAR(120) NOT NULL,
    status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (level_id),
    UNIQUE KEY levels_name_unique (level_name)
);

CREATE TABLE IF NOT EXISTS incident_reports (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    employee_id VARCHAR(50) NOT NULL,
    incident_date DATE NOT NULL,
    incident_time TIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    incident_type VARCHAR(100) NOT NULL,
    custom_incident_type VARCHAR(150) NULL,
    persons_involved TEXT NULL,
    description TEXT NOT NULL,
    action_taken TEXT NULL,
    cause TEXT NULL,
    impact TEXT NULL,
    severity VARCHAR(30) NULL,
    recommendations TEXT NULL,
    disciplinary_actions TEXT NULL,
    additional_support TEXT NULL,
    follow_up TEXT NULL,
    conclusion TEXT NULL,
    status ENUM('Submitted', 'Under Review', 'Resolved') NOT NULL DEFAULT 'Submitted',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY incident_employee_date (employee_id, incident_date)
);

INSERT IGNORE INTO levels (level_id, level_name)
VALUES
    ('HSHI-LVL20260000001', 'Elementary'),
    ('HSHI-LVL20260000002', 'Junior High School'),
    ('HSHI-LVL20260000003', 'Senior High School');

CREATE TABLE IF NOT EXISTS work_schedules (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    employee_id VARCHAR(50) NOT NULL,
    day_of_week VARCHAR(10) NOT NULL,
    required_hours DECIMAL(5,2) UNSIGNED NOT NULL DEFAULT 0,
    start_time TIME NULL,
    end_time TIME NULL,
    break_start TIME NULL,
    break_end TIME NULL,
    break_minutes SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    preset_name VARCHAR(60) NULL,
    timezone VARCHAR(50) NOT NULL DEFAULT 'Asia/Manila',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY work_schedule_employee_day_unique (employee_id, day_of_week)
);

ALTER TABLE work_schedules
    ADD COLUMN IF NOT EXISTS start_time TIME NULL AFTER required_hours,
    ADD COLUMN IF NOT EXISTS end_time TIME NULL AFTER start_time,
    ADD COLUMN IF NOT EXISTS break_start TIME NULL AFTER end_time,
    ADD COLUMN IF NOT EXISTS break_end TIME NULL AFTER break_start,
    ADD COLUMN IF NOT EXISTS break_minutes SMALLINT UNSIGNED NOT NULL DEFAULT 0 AFTER break_end,
    ADD COLUMN IF NOT EXISTS preset_name VARCHAR(60) NULL AFTER break_minutes,
    ADD COLUMN IF NOT EXISTS timezone VARCHAR(50) NOT NULL DEFAULT 'Asia/Manila' AFTER preset_name,
    ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER timezone,
    ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at;

CREATE TABLE IF NOT EXISTS overtime_undertime_logs (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    employee_id VARCHAR(50) NOT NULL,
    work_schedule_id INT UNSIGNED NULL,
    date DATE NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'on time',
    hours DECIMAL(8,2) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE KEY overtime_employee_date_unique (employee_id, date)
);

CREATE TABLE IF NOT EXISTS clock_in_attempts (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    employee_id VARCHAR(50) NOT NULL,
    attempt_time DATETIME NOT NULL,
    server_time DATETIME NULL,
    client_time DATETIME NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    status VARCHAR(30) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS leave_balances (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    employee_id VARCHAR(50) NOT NULL,
    leave_type_id INT UNSIGNED NOT NULL,
    remaining_days INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE KEY leave_balance_employee_type_unique (employee_id, leave_type_id)
);

CREATE TABLE IF NOT EXISTS staff_background_settings (
    employee_id VARCHAR(50) NOT NULL,
    background_choice ENUM('none', 'particles', 'clouds', 'stars') NOT NULL DEFAULT 'none',
    background_enabled TINYINT(1) NOT NULL DEFAULT 0,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (employee_id)
);

CREATE TABLE IF NOT EXISTS staff_password_resets (
    selector CHAR(32) NOT NULL,
    account_id INT NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (selector),
    KEY staff_password_reset_account_idx (account_id),
    KEY staff_password_reset_expiry_idx (expires_at)
);

CREATE TABLE IF NOT EXISTS sss_deductions (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    salary_range VARCHAR(100) NULL,
    salary_base DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT 0,
    employee_share DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT 0,
    employer_share DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT 0,
    total_contribution DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT 0,
    ec_contribution DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT 0,
    other_contribution DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT 0,
    total_with_others DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (id)
);

-- The legacy dump uses utf8mb4_general_ci while MariaDB 11 defaults new
-- tables to utf8mb4_uca1400_ai_ci. Normalize migration-created tables so
-- joins, UNION queries, and foreign-key-compatible identifiers compare safely.
ALTER DATABASE CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE applicants CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE attendance CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE clock_in_attempts CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE deductions CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE historical_data CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE incident_reports CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE leave_balances CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE levels CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE overtime_undertime_logs CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE roles CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE sss_deductions CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE staff_background_settings CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE staff_password_resets CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE work_schedules CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
