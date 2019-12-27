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
