--
-- Answers Questions
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
