CREATE TABLE student_course (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id VARCHAR(20),
  course_id INT
);

DROP TABLE student_course;

SELECT * FROM course;

INSERT INTO course (course_name, credit_hours)
VALUES ('Pancasila dan Kewarganegaraan', '2'),
 ('Kecerdasan Buatan', '4'),
 ('Basis Data Lanjut', '3'),
 ('Manajemen Proyek Sistem Informasi', '3'),
 ('Pemrograman dan Pengujian Aplikasi Web', '4'),
 ('Keteknowiraan', '3');
 
  

INSERT INTO students (name, student_id)
VALUES ('Walker', '12S21012'),
 ('Jaden', '12S21044');
