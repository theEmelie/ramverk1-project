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
