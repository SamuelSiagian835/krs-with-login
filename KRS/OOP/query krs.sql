CREATE TABLE course (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_name VARCHAR(255),
  credit_hours INT
);

DROP TABLE courses;

SELECT * FROM course;

INSERT INTO course (course_name, credit_hours) VALUES
('Aljabar & Linear', '3'),
('Jaringan Komputer', '3');

INSERT INTO course (course_name, credit_hours) VALUES
('Pemrograman Prosedural', '3');

SELECT * FROM students;
