--
-- Table TagsQuestions
--
DROP TABLE IF EXISTS TagsQuestions;
CREATE TABLE TagsQuestions (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "tag_id" INTEGER NOT NULL,
    "question_id" INTEGER NOT NULL
);
