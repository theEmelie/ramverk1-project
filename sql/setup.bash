#!/usr/bin/env bash

sqlite3 data/db.sqlite < sql/ddl/user_sqlite.sql
sqlite3 data/db.sqlite < sql/ddl/answers_sqlite.sql
sqlite3 data/db.sqlite < sql/ddl/questions_sqlite.sql
sqlite3 data/db.sqlite < sql/ddl/aComments_sqlite.sql
sqlite3 data/db.sqlite < sql/ddl/qComments_sqlite.sql
sqlite3 data/db.sqlite < sql/ddl/tags_sqlite.sql
sqlite3 data/db.sqlite < sql/ddl/tagQuestions_sqlite.sql
