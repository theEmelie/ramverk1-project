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
