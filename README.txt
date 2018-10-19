
____ ____ ___ _ ____ _  _    ____ ____ ____ _ ____ ___ ____ _   _
|__| |     |  | |  | |\ |    |__/ |___ | __ | [__   |  |__/  \_/
|  | |___  |  | |__| | \|    |  \ |___ |__] | ___]  |  |  \   |
      
                                                                

Installation Instructions

DROP DATABASE actionsregistry;
CREATE DATABASE actionsregistry;
USE actionsregistry;

-- team table
CREATE TABLE teams(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50)
);

-- user table
CREATE TABLE users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    team_id INT,
    user_name VARCHAR(50) UNIQUE,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    FOREIGN KEY(team_id) REFERENCES teams (id)
);

-- status table
CREATE TABLE statuses(
    id INT PRIMARY KEY AUTO_INCREMENT,
    description VARCHAR(50)
);

-- ceremony table
CREATE TABLE ceremonies(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50)
);

-- actions table
CREATE TABLE actions(
    id INT PRIMARY KEY AUTO_INCREMENT,
    owner_id INT,
    status_id INT,
    source_id INT,
    name VARCHAR(100),
    created_date DATETIME,
    updated_date DATETIME,
    FOREIGN KEY(owner_id) REFERENCES users(id),
	FOREIGN KEY(status_id) REFERENCES statuses(id),
	FOREIGN KEY(source_id) REFERENCES ceremonies(id)
);


-- table
CREATE TABLE assignments(
    user_id INT NOT NULL,
    action_id INT NOT NULL,
    FOREIGN KEY(user_id) REFERENCES users(id),
    FOREIGN KEY(action_id) REFERENCES actions(id)
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
    (1, 'jkunz', 'Jeff', 'Kunz'),
    (1, 'thelvick', 'Tom', 'Helvick'),
    (2, 'mtorres', 'Maria', 'Torres'),
    (2, 'dclark', 'Dan', 'Clark'),
    (3, 'pholmes', 'Pete', 'Holmes'),
    (3, 'molivares', 'Marco', 'Olivares');

INSERT INTO statuses(
    description
)
VALUES
    ('Open'),
    ('Assigned'),
    ('In Process'),
    ('Resolved'),
    ('Obsoleted');

INSERT INTO actions(
    name,
    status_id
)
Values
    ('Do Work', 1),
    ('Fix Bug', 1),
    ('Implement Feature', 1),
    ('Create Menu', 1),
    ('Change Value', 1),
    ('Make Things', 1),
    ('Fix Data', 1),
    ('Write Test', 1);