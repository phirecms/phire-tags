--
-- Tags Module PostgreSQL Database for Phire CMS 2.0
--

-- --------------------------------------------------------

--
-- Table structure for table "tags"
--

CREATE SEQUENCE tag_id_seq START 7001;

CREATE TABLE IF NOT EXISTS "[{prefix}]tags" (
  "id" integer NOT NULL DEFAULT nextval('tag_id_seq'),
  "title" varchar(255) NOT NULL,
  "slug" varchar(255) NOT NULL,
  PRIMARY KEY ("id")
) ;

ALTER SEQUENCE tag_id_seq OWNED BY "[{prefix}]tags"."id";
CREATE INDEX "tag_title" ON "[{prefix}]tags" ("title");
CREATE INDEX "tag_slug" ON "[{prefix}]tags" ("slug");

-- --------------------------------------------------------

--
-- Table structure for table "tag_items"
--

CREATE TABLE IF NOT EXISTS "[{prefix}]tag_items" (
  "content_id" integer NOT NULL,
  "tag_id" integer NOT NULL,
  UNIQUE ("content_id", "tag_id"),
  CONSTRAINT "fk_tag_content_id" FOREIGN KEY ("content_id") REFERENCES "[{prefix}]content" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT "fk_tag_tag_id" FOREIGN KEY ("tag_id") REFERENCES "[{prefix}]tags" ("id") ON DELETE CASCADE ON UPDATE CASCADE
) ;

CREATE INDEX "tag_content_id" ON "[{prefix}]tag_items" ("content_id");
CREATE INDEX "tag_tag_id" ON "[{prefix}]tag_items" ("tag_id");
