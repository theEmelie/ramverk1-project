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
    '2020-01-02 11:37:22'),

    ('2',
    '1',
    '2',
    'I would ask him, & perhaps offer some additional damage deposit, but if the answer is still no then I would leave it at that.',
    '2020-01-05 23:11:55',
    '2020-01-05 23:11:55'),

    ('3',
    '4',
    '2',
    'As a landlord, if someone asked me to keep something in a cage I would consider it. The only problem I could see is if they were let out and nibbled things they shouldnt.',
    '2020-01-06 07:40:35',
    '2020-01-06 07:40:35'),

    ('4',
    '3',
    '4',
    'I’d ask the Landlord but if the answer is no then I’d respect that tbh.',
    '2020-01-09 15:32:22',
    '2020-01-09 15:32:22');

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
    '1'),

    ('2',
    '1',
    '2',
    '1'),

    ('3',
    '1',
    '2',
    '1'),

    ('4',
    '3',
    '4',
    '-1'),

    ('5',
    '2',
    '2',
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
    '2020-01-01 15:37:33'),

    ('2',
    '3',
    '3',
    'I would not jeopardise your tenancy by hiding animals from your landlord, I see far too many adverts for animals being rehomed because the landlord hasnt given permission for them to be there.',
    '1',
    '2020-01-05 09:43:33',
    '2020-01-01 09:43:33'),

    ('3',
    '4',
    '3',
    'A landlord can ask if you have pets before you move in (and obviously make a decision on whether to rent to you based on that) but once you are in, if you ask to have one, the landlord needs a good reason to not let you under the consumer rights act I believe.',
    '0',
    '2020-01-06 15:25:10',
    '2020-01-06 15:25:10'),

    ('4',
    '1',
    '3',
    'I would not be happy at all if my landlord did spot checks without prior arrangement, and certainly not to enter when I wasn’t there...',
    '1',
    '2020-01-07 19:32:12',
    '2020-01-07 19:32:12');


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
    '1'),

    ('3',
    '4',
    '2',
    '1'),

    ('4',
    '1',
    '2',
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
    '2020-01-04 08:14:13'),

    ('2',
    '1',
    '3',
    'If the boot was on the other foot, how would you feel?',
    '2020-01-09 10:14:33',
    '2020-01-09 10:14:33'),

    ('3',
    '4',
    '3',
    'Why not just mention it?',
    '2020-01-09 17:15:32',
    '2020-01-09 17:15:32');

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
    '-1'),

    ('2',
    '1',
    '2',
    '1'),

    ('3',
    '3',
    '2',
    '1'),

    ('4',
    '3',
    '3',
    '1');

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
    '2020-01-01 09:44:39'),

    ('2',
    '1',
    'Bearded Dragons',
    'I have recently got 2 male bearded dragons who had lived together for 7 years. I have separated them and housed them in 2 new 4ft x 2ft x 2ft tanks compete with a slate hide a log large enough to hide and a basking rock. They have a uvb light and ceramic heater controlled by an on off thermostat. The basking area is set to 39° and cool end around 27°. They dont seem happy they hide the whole time one of them isnt eating like he used too and they are both darker than when I got them. I am unsure as to what to do to make them happy and am worried about the tank tempreture as every website I read gives different tempretures. Does anyone have any ideas or advice as to what Im doing wrong or how to make them happy.',
    '2020-01-07 19:53:21',
    '2020-01-07 19:53:21'),

    ('3',
    '2',
    'Opinion on hiding small pets from landlords?',
    'So before I moved I used to have a couple of small pets who I loved and unfortuantly they passed away. I really would like to get another pet but I doubt my landlord will let me have one since my current house I rent.
    Would it be possible to hide a small rodent without him finding out. They do often do spot checks but don’t always enter bedroom and sometimes when we’re out, sometimes does and sometimes doesn’t and if it’s possible what’s your advice and how do you cover up the noise without it causing harm to the animal if there was to be a spot check.
    I don’t really see what damage a pet rat, mice, hamster, rabbit, Guinea pig etc can do especially if there are no cords in the room. Also in my experience rats or mice are probably the quietist rodents and I understand they are some of species that have to be kept in pairs right?
    Has anyone hidden their pet from their landlord and if so did he not find out or what happened if he did find out?',
    '2020-01-05 05:52:06',
    '2020-01-05 05:52:06');

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
    '0'),

    ('3',
    '4',
    '2',
    '-1'),

    ('4',
    '2',
    '2',
    '-1'),

    ('5',
    '3',
    '3',
    '1'),

    ('6',
    '4',
    '3',
    '1'),

    ('7',
    '1',
    '3',
    '1');

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
    'dog'),

    ('3',
    'dragons'),

    ('4',
    'happy'),

    ('5',
    'temperature');
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
    '1'),

    ('3',
    '3',
    '2'),

    ('4',
    '4',
    '2'),

    ('5',
    '5',
    '2'),

    ('6',
    '4',
    '3');

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

--
-- Password for each user is 'test'
--
INSERT INTO User ('id', 'acronym', 'email', 'password', 'created', 'updated') VALUES
    ('1',
    'Emelie',
    'theem@live.se',
    '$2y$10$y17fYx1.BmqgObnapcInhukUTrFEWrSYOUz587YbqzIGGMqvVW0DG',
    '2019-12-01 11:44:53',
    '2019-12-01 11:44:53'),

    ('2',
    'Maximus',
    'max@max.max',
    '$2y$10$sGrb/lCVDtL4nD6iM/fnqeIsg6NDJpQzQoWPzXgK0voiT15kTMnNC',
    '2019-12-04 23:55:44',
    '2019-12-04 23:55:44'),

    ('3',
    'Bailey',
    'bailey@dog.se',
    '$2y$10$.KLtRwHSjKB9RhhNg2Ma5OMMyG9t1aoyitPdw16BucYb6PUqYjItK',
    '2019-12-23 09:31:55',
    '2019-12-23 09:31:55'),

    ('4',
    'Luna',
    'luna@cat.se',
    '$2y$10$0.aDrWRsrCDwU3CFpHc7ee2Rm1Lg65hl6robciuSAES1cGn00JckO',
    '2020-01-04 16:53:55',
    '2020-01-04 16:53:55');
