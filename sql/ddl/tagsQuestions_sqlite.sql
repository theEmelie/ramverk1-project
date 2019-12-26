--
-- Table TagsQuestions
--
DROP TABLE IF EXISTS TagsQuestions;
CREATE TABLE TagsQuestions (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "tagId" INTEGER NOT NULL,
    "questionId" INTEGER NOT NULL
);
