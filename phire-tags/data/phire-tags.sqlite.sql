--
-- Tags Module SQLite Database for Phire CMS 2.0
--

--  --------------------------------------------------------

--
-- Set database encoding
--

PRAGMA encoding = "UTF-8";
PRAGMA foreign_keys = ON;

-- --------------------------------------------------------

--
-- Table structure for table "tags"
--

CREATE TABLE IF NOT EXISTS "[{prefix}]tags" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "title" varchar NOT NULL,
  "slug" varchar NOT NULL,
  UNIQUE ("id")
) ;

INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('[{prefix}]tags', 7000);
CREATE INDEX "tag_title" ON "[{prefix}]tags" ("title");
CREATE INDEX "tag_slug" ON "[{prefix}]tags" ("slug");

-- --------------------------------------------------------

--
-- Table structure for table "content_to_tags"
--

CREATE TABLE IF NOT EXISTS "[{prefix}]content_to_tags" (
  "content_id" integer NOT NULL,
  "tag_id" integer NOT NULL,
  UNIQUE ("content_id", "tag_id"),
  CONSTRAINT "fk_tag_content_id" FOREIGN KEY ("content_id") REFERENCES "[{prefix}]content" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT "fk_tag_tag_id" FOREIGN KEY ("tag_id") REFERENCES "[{prefix}]tags" ("id") ON DELETE CASCADE ON UPDATE CASCADE
) ;

CREATE INDEX "tag_content_id" ON "[{prefix}]content_to_tags" ("content_id");
CREATE INDEX "tag_tag_id" ON "[{prefix}]content_to_tags" ("tag_id");

