--
-- Table AnswersComments
--
DROP TABLE IF EXISTS AnswerComments;
CREATE TABLE AnswerComments (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "userId" INTEGER NOT NULL,
    "answerId" INTEGER NOT NULL,
    "text" TEXT NOT NULL,
    "created" DATETIME,
    "updated" DATETIME,
    "deleted" DATETIME,
    "active" DATETIME
);

INSERT INTO AnswerComments ('id', 'userId', 'answerId', 'text', 'created', 'updated') VALUES
    ('1',
    '3',
    '1',
    'I agree. After what he’s been through I would give him time to settle.',
    '2020-01-02 11:37:22',
    '2020-01-02 11:37:22');

--
-- Table AnswerCommentVotes
--
DROP TABLE IF EXISTS AnswerCommentVotes;
CREATE TABLE AnswerCommentVotes (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "userId" INTEGER NOT NULL,
    "acId" INTEGER NOT NULL,
    "vote" INTEGER
);

INSERT INTO AnswerCommentVotes ('id', 'userId', 'acId', 'vote') VALUES
    ('1',
    '1',
    '1',
    '1');

--
-- Table Answers
--
DROP TABLE IF EXISTS Answers;
CREATE TABLE Answers (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "userId" INTEGER NOT NULL,
    "questionId" INTEGER NOT NULL,
    "text" TEXT NOT NULL,
    "accepted" BOOLEAN,
    "created" DATETIME,
    "updated" DATETIME,
    "deleted" DATETIME,
    "active" DATETIME
);

INSERT INTO Answers ('id', 'userId', 'questionId', 'text', 'accepted', 'created', 'updated') VALUES
    ('1',
    '1',
    '1',
    'Given what hes been through, hes probably a bit stressed about being in yet another new environment. Hes unsettled and unsure. Give him a bit of time and build a good bond with him. Remember he barely knows you yet. And he doesnt know where he is or if hes staying there or moving again or what. Have some patience with him and things will get better. He wont behave like a normal puppy yet because of the hard start he had in life. Give you and him a chance to settle down for now.',
    '1',
    '2020-01-01 15:37:33',
    '2020-01-01 15:37:33');


--
-- Table AnswerVotes
--
DROP TABLE IF EXISTS AnswerVotes;
CREATE TABLE AnswerVotes (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "userId" INTEGER NOT NULL,
    "answerId" INTEGER NOT NULL,
    "vote" INTEGER
);

INSERT INTO AnswerVotes ('id', 'userId', 'answerId', 'vote') VALUES
    ('1',
    '3',
    '1',
    '1'),

    ('2',
    '2',
    '1',
    '1');

--
-- Table QuestionComments
--
DROP TABLE IF EXISTS QuestionComments;
CREATE TABLE QuestionComments (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "userId" INTEGER NOT NULL,
    "questionId" INTEGER NOT NULL,
    "text" TEXT NOT NULL,
    "created" DATETIME,
    "updated" DATETIME,
    "deleted" DATETIME,
    "active" DATETIME
);

INSERT INTO QuestionComments ('id', 'userId', 'questionId', 'text', 'created', 'updated') VALUES
    ('1',
    '2',
    '1',
    'Could you pop him in a laundry basket with blankets and toys? You may as well get used to the fact that you will never go to the loo alone again!',
    '2020-01-04 08:14:13',
    '2020-01-04 08:14:13');

--
-- Table QuestionCommentVotes
--
DROP TABLE IF EXISTS QuestionCommentVotes;
CREATE TABLE QuestionCommentVotes (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "userId" INTEGER NOT NULL,
    "qcId" INTEGER NOT NULL,
    "vote" INTEGER
);

INSERT INTO QuestionCommentVotes ('id', 'userId', 'qcId', 'vote') VALUES
    ('1',
    '4',
    '1',
    '-1');

--
-- Table Questions
--
DROP TABLE IF EXISTS Questions;
CREATE TABLE Questions (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "userId" INTEGER NOT NULL,
    "title" TEXT NOT NULL,
    "text" TEXT NOT NULL,
    "created" DATETIME,
    "updated" DATETIME,
    "deleted" DATETIME,
    "active" DATETIME
);

INSERT INTO Questions ('id', 'userId', 'title', 'text', 'created', 'updated') VALUES
    ('1',
    '4',
    'Stop Puppy From Crying',
    'I have a 10 week old puppy called Jasper. Technically we have had him for almost two weeks now but he came to us very sick and spent most of that time either in the out of hours emergency vets or in the Royal Veterinary College as an inpatient. So, there hasn’t been much time for training yet.
    We are fortunate in that someone can pretty much always be with Jasper but on occasion we have had to leave him in his exercise pen for a few minutes (probably about 5 minutes) when everyone is occupied doing something. Jasper hates this. He will start crying immediately and continue to cry even when someone comes back into the room. He will only stop crying when someone either comes into the exercise pen with him or takes him out of it.
    He also cries at night unless he is actually with someone (as in on their bed). He won’t sleep in a crate or in an exercise pen.
    Him not sleeping alone isn’t such a problem but I do worry about his crying when left alone for brief periods during the day since he’s going to have to become accustomed to being alone occasionally (not for long stretches but we can’t take him everywhere!)',
    '2020-01-01 09:44:39',
    '2020-01-01 09:44:39');

--
-- Table QuestionVotes
--
DROP TABLE IF EXISTS QuestionVotes;
CREATE TABLE QuestionVotes (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "userId" INTEGER NOT NULL,
    "questionId" INTEGER NOT NULL,
    "vote" INTEGER
);

INSERT INTO QuestionVotes ('id', 'userId', 'questionId', 'vote') VALUES
    ('1',
    '3',
    '1',
    '1'),

    ('2',
    '2',
    '1',
    '0');

--
-- Table Tags
--
DROP TABLE IF EXISTS Tags;
CREATE TABLE Tags (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "tag" TEXT NOT NULL
);

INSERT INTO Tags ('id', 'tag') VALUES
    ('1',
    'puppy'),

    ('2',
    'dog');

--
-- Table TagsQuestions
--
DROP TABLE IF EXISTS TagsQuestions;
CREATE TABLE TagsQuestions (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "tagId" INTEGER NOT NULL,
    "questionId" INTEGER NOT NULL
);

INSERT INTO TagsQuestions ('id', 'tagId', 'questionId') VALUES
    ('1',
    '1',
    '1'),

    ('2',
    '2',
    '1');

--
-- Table User
--
DROP TABLE IF EXISTS User;
CREATE TABLE User (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "acronym" TEXT UNIQUE NOT NULL,
    "email" TEXT NOT NULL,
    "password" TEXT,
    "created" DATETIME,
    "updated" DATETIME,
    "deleted" DATETIME,
    "active" DATETIME
);

INSERT INTO User ('id', 'acronym', 'email', 'password', 'created', 'updated') VALUES
    ('1',
    'Emelie',
    'theem@live.se',
    'emeliepass',
    '2019-12-01 11:44:53',
    '2019-12-01 11:44:53'),

    ('2',
    'Maximus',
    'max@max.max',
    'maxpass',
    '2019-12-04 23:55:44',
    '2019-12-04 23:55:44'),

    ('3',
    'Bailey',
    'bailey@dog.se',
    'baileypass',
    '2019-12-23 09:31:55',
    '2019-12-23 09:31:55'),

    ('4',
    'Luna',
    'luna@cat.se',
    'lunapass',
    '2020-01-04 16:53:55',
    '2020-01-04 16:53:55');
