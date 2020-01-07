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
