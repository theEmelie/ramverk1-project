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
