This is a project from the course Ramverk1 at BTH.
=================================

[![Build Status](https://travis-ci.org/theEmelie/ramverk1-project.svg?branch=master)](https://travis-ci.org/theEmelie/ramverk1-project)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/theEmelie/ramverk1-project/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/theEmelie/ramverk1-project/?branch=master)
[![Maintainability](https://api.codeclimate.com/v1/badges/95c3f212d0861aaa7a85/maintainability)](https://codeclimate.com/github/theEmelie/ramverk1-project/maintainability)

How to install
------------------------------------
* Get the code: `git clone https://github.com/theEmelie/ramverk1-project.git`
* Create a database: `sqlite3 data/db.sqlite`
* Insert these tables into the database:
`sqlite3 data/db.sqlite < sql/ddl/user_sqlite.sql`  
`sqlite3 data/db.sqlite < sql/ddl/answers_sqlite.sql`  
`sqlite3 data/db.sqlite < sql/ddl/questions_sqlite.sql`  
`sqlite3 data/db.sqlite < sql/ddl/aComments_sqlite.sql`  
`sqlite3 data/db.sqlite < sql/ddl/qComments_sqlite.sql`  
`sqlite3 data/db.sqlite < sql/ddl/tags_sqlite.sql`  
`sqlite3 data/db.sqlite < sql/ddl/tagQuestions_sqlite.sql`  

License
------------------

This software carries a MIT license. See [LICENSE.txt](LICENSE.txt) for details.

```
 .  
..:  Copyright (c) 2019-2020 Emelie Åslund (emelie-aslund@hotmail.com)
```
