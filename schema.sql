drop table "User" cascade constraints;
drop table Role cascade constraints;
drop table UserSession cascade constraints;
-- Role Table
CREATE TABLE Role(
                     role_id number primary key,
                     role_name varchar(50) not null
);
insert into Role (role_id, role_name) values (1, 'student');
insert into Role (role_id, role_name) values (2, 'administrator');
insert into Role (role_id, role_name) values (3, 'student-administrator');
-- User Table
CREATE TABLE "User" (
                        user_id number generated always as identity primary key,
                        firstname varchar(50) not null,
                        lastname varchar(50) not null,
                        password varchar(50) not null,
                        role_id number(1) not null,
                        foreign key (role_id) references Role(role_id)
);

insert into "User" (firstname, lastname, password, role_id) values ('Elly', 'Strathern', 'c4ca4238a0b923820dcc509a6f75849b', 1);
insert into "User" (firstname, lastname, password, role_id) values ('Willyt', 'Pain', 'c81e728d9d4c2f636f067f89cc14862c', 2);
insert into "User" (firstname, lastname, password, role_id) values ('Carie', 'Felix', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', 3);
insert into "User" (firstname, lastname, password, role_id) values ('Emelia', 'Battisson', '4uZpnf', 3);
insert into "User" (firstname, lastname, password, role_id) values ('Daveta', 'Uphill', 'MnzGNNkK', 2);
insert into "User" (firstname, lastname, password, role_id) values ('Guinna', 'Cullon', 'kStqFIPEw', 1);
insert into "User" (firstname, lastname, password, role_id) values ('Xylina', 'Hamp', 'cd4eax6', 1);
insert into "User" (firstname, lastname, password, role_id) values ('Ashton', 'Ashforth', 'NfkX6Nxm', 3);
insert into "User" (firstname, lastname, password, role_id) values ('Alvy', 'Brockman', 'M5UfdWUWN9', 2);
insert into "User" (firstname, lastname, password, role_id) values ('Fleurette', 'Mohammad', 'ceW1i6', 3);

-- Session Table
CREATE TABLE UserSession(
                            session_id varchar(32) primary key,
                            user_id number,
                            sessiondate date,
                            foreign key (user_id) references "User"(user_id)
                                on delete cascade
);
