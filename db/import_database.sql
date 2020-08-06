################### Database structure START ###################

CREATE DATABASE quantox_task DEFAULT CHARSET = utf8;

CREATE TABLE students (
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
) DEFAULT CHARSET = utf8;

CREATE TABLE student_grades (
    student_id INT(11) NOT NULL,
    grade INT(11) NOT NULL,
    KEY student_grades_student_id (student_id)
) DEFAULT CHARSET = utf8;

CREATE TABLE school_boards (
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE, #CSM, CSMB,
    KEY school_boards_name (name)
) DEFAULT CHARSET = utf8;

# NOTE: Pivot table
CREATE TABLE school_board_student (
    school_board_id INT(11) NOT NULL,
    student_id INT(11) NOT NULL,
    PRIMARY KEY (school_board_id, student_id)
) DEFAULT CHARSET = utf8;

################### Database structure END ###################

################### Insert queries START ###################

# Table school_boards queries
INSERT INTO school_boards (id, name) VALUES (1, 'CSM');
INSERT INTO school_boards (id, name) VALUES (2, 'CSMB');

# Table students queries
INSERT INTO students (id, name) VALUES (1, 'Pera Perić');
INSERT INTO students (id, name) VALUES (2, 'Mika Mikić');
INSERT INTO students (id, name) VALUES (3, 'Marko Marković');
INSERT INTO students (id, name) VALUES (4, 'Miloš Milošević');
INSERT INTO students (id, name) VALUES (5, 'Bojan Bojanić');
INSERT INTO students (id, name) VALUES (6, 'Ivan Ivanović');

# Table student_grades queries
# NOTE: Student grade values range: [6 - 10]
INSERT INTO student_grades (student_id, grade) VALUES (1, 6);
INSERT INTO student_grades (student_id, grade) VALUES (1, 7);
INSERT INTO student_grades (student_id, grade) VALUES (1, 8);
INSERT INTO student_grades (student_id, grade) VALUES (1, 9);

INSERT INTO student_grades (student_id, grade) VALUES (2, 8);

INSERT INTO student_grades (student_id, grade) VALUES (3, 6);
INSERT INTO student_grades (student_id, grade) VALUES (3, 6);
INSERT INTO student_grades (student_id, grade) VALUES (3, 9);

INSERT INTO student_grades (student_id, grade) VALUES (4, 6);

INSERT INTO student_grades (student_id, grade) VALUES (5, 10);
INSERT INTO student_grades (student_id, grade) VALUES (5, 6);
INSERT INTO student_grades (student_id, grade) VALUES (5, 8);
INSERT INTO student_grades (student_id, grade) VALUES (5, 8);

INSERT INTO student_grades (student_id, grade) VALUES (6, 6);
INSERT INTO student_grades (student_id, grade) VALUES (6, 6);
INSERT INTO student_grades (student_id, grade) VALUES (6, 6);

# Table school_board_student queries
INSERT INTO school_board_student (school_board_id, student_id) VALUES (1, 1);
INSERT INTO school_board_student (school_board_id, student_id) VALUES (2, 2);
INSERT INTO school_board_student (school_board_id, student_id) VALUES (1, 3);
INSERT INTO school_board_student (school_board_id, student_id) VALUES (1, 4);
INSERT INTO school_board_student (school_board_id, student_id) VALUES (2, 5);
INSERT INTO school_board_student (school_board_id, student_id) VALUES (2, 6);

################### Insert queries END ###################
