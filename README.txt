
____ ____ ___ _ ____ _  _    ____ ____ ____ _ ____ ___ ____ _   _
|__| |     |  | |  | |\ |    |__/ |___ | __ | [__   |  |__/  \_/
|  | |___  |  | |__| | \|    |  \ |___ |__] | ___]  |  |  \   |
      
                                                                

Installation Instructions

create a directory in your htdocs folder called "registry"
create a new database in my php admin called "actionsregistry"
run the following SQL script

-- deploy and test data
-- DROP DATABASE actionsregistry;
-- CREATE DATABASE actionsregistry;
USE actionsregistry;

-- team table
CREATE TABLE teams(
    team_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50)
);

-- user table
CREATE TABLE users(
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    team_id INT,
    user_name VARCHAR(50) UNIQUE,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    FOREIGN KEY(team_id) REFERENCES teams (team_id)
);

-- status table
CREATE TABLE statuses(
    status_id INT PRIMARY KEY AUTO_INCREMENT,
    description VARCHAR(50)
);

-- ceremony table
CREATE TABLE ceremonies(
    ceremony_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50)
);

-- actions table
CREATE TABLE actions(
    action_id INT PRIMARY KEY AUTO_INCREMENT,
    owner_id INT,
    status_id INT NOT NULL,
    source_id INT,
    name VARCHAR(100),
    created_date DATETIME DEFAULT NOW(),
    updated_date DATETIME DEFAULT NOW(),
    FOREIGN KEY(owner_id) REFERENCES users(user_id),
    FOREIGN KEY(status_id) REFERENCES statuses(status_id),
    FOREIGN KEY(source_id) REFERENCES ceremonies(ceremony_id)
);

-- action archive table
CREATE TABLE actions_archive(
    action_id INT PRIMARY KEY AUTO_INCREMENT,
    owner_id INT,
    status_id INT NOT NULL,
    source_id INT,
    name VARCHAR(100),
    created_date DATETIME DEFAULT NOW(),
    updated_date DATETIME DEFAULT NOW(),
    FOREIGN KEY(owner_id) REFERENCES users(user_id),
    FOREIGN KEY(status_id) REFERENCES statuses(status_id),
    FOREIGN KEY(source_id) REFERENCES ceremonies(ceremony_id)
);


-- table
CREATE TABLE assignments(
    user_id INT NOT NULL,
    action_id INT NOT NULL,
    FOREIGN KEY(user_id) REFERENCES users(user_id),
    FOREIGN KEY(action_id) REFERENCES actions(action_id)
        ON DELETE CASCADE
);


-- population
INSERT INTO teams(
    name
)
VALUES
    ('Red Team'),
    ('Blue Team'),
    ('Green Team');

INSERT INTO users(
    team_id,
    user_name,
    first_name,
    last_name
)
VALUES
    (1, 'pmorrow', 'Portia', 'Morrow'),
    (1, 'kmorin', 'Kristi', 'Morin'),
    (2, 'tman', 'Test', 'Man'),
    (2, 'dclark', 'Dan', 'Clark'),
    (3, 'otruong', 'Owen', 'Truong'),
    (3, 'dlong', 'Darren', 'Long');

INSERT INTO statuses(
    description
)
VALUES
    ('Open'),
    ('Assigned'),
    ('In Process'),
    ('Resolved'),
    ('Obsoleted');


INSERT INTO ceremonies(
    name
)
VALUES
    ('Sprint Planning'),
    ('Backlog Grooming'),
    ('Client Meeting'),
    ('Research Session'),
    ('Sprint Retrospective');


INSERT INTO actions(
    name,
    status_id,
    source_id,
    owner_id
)
Values
    ('Do Work', 1, 1, 4),
    ('Fix Bug', 1, 2, 4),
    ('Implement Feature', 1, 1, 4),
    ('Create Menu', 1, 3, 4),
    ('Change Value', 1, 3, 4),
    ('Make Things', 1, 5, 5),
    ('Fix Data', 1, 2, 5),
    ('Write Test', 1, 2, 5);

INSERT INTO assignments(
    user_id,
    action_id
)
Values
    (1, 2),
    (2, 1),
    (3, 3),
    (4, 4),
    (1, 4),
    (2, 2);



