
____ ____ ___ _ ____ _  _    ____ ____ ____ _ ____ ___ ____ _   _
|__| |     |  | |  | |\ |    |__/ |___ | __ | [__   |  |__/  \_/
|  | |___  |  | |__| | \|    |  \ |___ |__] | ___]  |  |  \   |
      
                                                                

Installation Instructions



DROP DATABASE actionsregistry;
CREATE DATABASE actionsregistry;
USE actionsregistry;

-- assignee table
CREATE TABLE assignees(
    id INT PRIMARY KEY
);

-- team table
CREATE TABLE teams(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50),
	FOREIGN KEY(id) REFERENCES assignees(id)
);

-- user table
CREATE TABLE users(
    id INT PRIMARY KEY,
    team_id INT,
    user_name VARCHAR(50),
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    FOREIGN KEY(id) REFERENCES assignees(id),
    FOREIGN KEY(team_id) REFERENCES teams (id)
);

-- status table
CREATE TABLE statuses(
    id INT PRIMARY KEY AUTO_INCREMENT,
    description VARCHAR(50)
);

INSERT INTO statuses(description)
VALUES('Open'), ('Assigned'), ('In Process'), ('Resolved'), ('Obsoleted');

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
    assignee_id INT,
    name VARCHAR(100),
    created_date DATETIME,
    updated_date DATETIME,
    FOREIGN KEY(owner_id) REFERENCES users(id),
	FOREIGN KEY(status_id) REFERENCES statuses(id),
	FOREIGN KEY(source_id) REFERENCES ceremonies(id),
	FOREIGN KEY(assignee_id) REFERENCES assignees(id)
);



-- table
CREATE TABLE assignments(
    user_id INT NOT NULL,
    assignee_id INT NOT NULL,
    FOREIGN KEY(user_id) REFERENCES users(id),
    FOREIGN KEY(assignee_id) REFERENCES assignees(id)
);