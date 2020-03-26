/*
 Navicat Premium Data Transfer

 Source Server         : SQLite
 Source Server Type    : SQLite
 Source Server Version : 3012001
 Source Schema         : main

 Target Server Type    : SQLite
 Target Server Version : 3012001
 File Encoding         : 65001

 Date: 26/03/2020 08:37:55
*/

PRAGMA foreign_keys = false;

-- ----------------------------
-- Table structure for cms_analytics_day
-- ----------------------------
DROP TABLE IF EXISTS "cms_analytics_day";
CREATE TABLE "cms_analytics_day" (
  "date" text NOT NULL,
  "visits" integer(11) NOT NULL,
  "unique_visits" integer(11) NOT NULL,
  PRIMARY KEY ("date")
);

-- ----------------------------
-- Table structure for cms_analytics_metric
-- ----------------------------
DROP TABLE IF EXISTS "cms_analytics_metric";
CREATE TABLE "cms_analytics_metric" (
  "date" text NOT NULL,
  "type" text(255) NOT NULL,
  "value" text(128) NOT NULL,
  "visits" integer(11) NOT NULL,
  PRIMARY KEY ("date", "type", "value")
);

-- ----------------------------
-- Table structure for cms_file
-- ----------------------------
DROP TABLE IF EXISTS "cms_file";
CREATE TABLE "cms_file" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" text(100),
  "extension" text(50),
  "mimetype" text(100),
  "created" text,
  "updated" text,
  "is_folder" integer(10) NOT NULL,
  "folder_id" integer(11),
  "size" integer(11) NOT NULL,
  "user_id" integer(11),
  "key" text(255),
  "hash" text(32),
  CONSTRAINT "cms_file_ibfk_1" FOREIGN KEY ("folder_id") REFERENCES "cms_file" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

-- ----------------------------
-- Table structure for cms_file_permission
-- ----------------------------
DROP TABLE IF EXISTS "cms_file_permission";
CREATE TABLE "cms_file_permission" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "role" text(16),
  "user_id" integer(11),
  "file_id" integer(11),
  "right" integer(4),
  CONSTRAINT "finder_permission_ibfk_1" FOREIGN KEY ("user_id") REFERENCES "cms_user" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT "finder_permission_ibfk_2" FOREIGN KEY ("file_id") REFERENCES "cms_file" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

-- ----------------------------
-- Table structure for cms_language
-- ----------------------------
DROP TABLE IF EXISTS "cms_language";
CREATE TABLE "cms_language" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "code" text(3),
  "name" text(255),
  "active" integer(1) NOT NULL
);

-- ----------------------------
-- Table structure for cms_page
-- ----------------------------
DROP TABLE IF EXISTS "cms_page";
CREATE TABLE "cms_page" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "parent_id" integer(11),
  "alias" integer(11),
  "template" text(16),
  "display_order" integer(11),
  "key" text(32),
  "type" text(255) NOT NULL,
  "level" integer(11),
  "lft" integer(11),
  "rgt" integer(11),
  "link" text(255),
  "menu_max_level" integer(11),
  "created_at" text,
  "updated_at" text,
  CONSTRAINT "cms_page_ibfk_1" FOREIGN KEY ("alias") REFERENCES "cms_page" ("id") ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT "cms_page_ibfk_2" FOREIGN KEY ("parent_id") REFERENCES "cms_page" ("id") ON DELETE RESTRICT ON UPDATE RESTRICT
);

-- ----------------------------
-- Table structure for cms_page_content
-- ----------------------------
DROP TABLE IF EXISTS "cms_page_content";
CREATE TABLE "cms_page_content" (
  "page_id" integer(11) NOT NULL,
  "field" text(16) NOT NULL,
  "value" text,
  PRIMARY KEY ("page_id", "field"),
  CONSTRAINT "cms_page_content_ibfk_1" FOREIGN KEY ("page_id") REFERENCES "cms_page" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

-- ----------------------------
-- Table structure for cms_page_language
-- ----------------------------
DROP TABLE IF EXISTS "cms_page_language";
CREATE TABLE "cms_page_language" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "page_id" integer(11) NOT NULL,
  "language_code" text(3) NOT NULL,
  "active" integer(1),
  "name" text(255),
  "slug" text(255),
  "seo_title" text(255),
  "seo_description" text,
  "seo_keywords" text,
  CONSTRAINT "cms_page_language_ibfk_1" FOREIGN KEY ("language_code") REFERENCES "cms_language" ("code") ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT "cms_page_language_ibfk_2" FOREIGN KEY ("page_id") REFERENCES "cms_page" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

-- ----------------------------
-- Table structure for cms_page_language_content
-- ----------------------------
DROP TABLE IF EXISTS "cms_page_language_content";
CREATE TABLE "cms_page_language_content" (
  "page_id" integer(11) NOT NULL,
  "language_code" text(3) NOT NULL,
  "field" text(16) NOT NULL,
  "value" text,
  PRIMARY KEY ("page_id", "language_code", "field"),
  CONSTRAINT "cms_page_language_content_ibfk_1" FOREIGN KEY ("language_code") REFERENCES "cms_language" ("code") ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT "cms_page_language_content_ibfk_2" FOREIGN KEY ("page_id") REFERENCES "cms_page" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

-- ----------------------------
-- Table structure for cms_translation_key
-- ----------------------------
DROP TABLE IF EXISTS "cms_translation_key";
CREATE TABLE "cms_translation_key" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "key" text(127),
  "db" integer(1) NOT NULL
);

-- ----------------------------
-- Table structure for cms_translation_value
-- ----------------------------
DROP TABLE IF EXISTS "cms_translation_value";
CREATE TABLE "cms_translation_value" (
  "key_id" integer(11) NOT NULL,
  "language_code" text(3) NOT NULL,
  "value" text,
  PRIMARY KEY ("key_id", "language_code"),
  CONSTRAINT "cms_translation_value_ibfk_1" FOREIGN KEY ("language_code") REFERENCES "cms_language" ("code") ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT "cms_translation_value_ibfk_2" FOREIGN KEY ("key_id") REFERENCES "cms_translation_key" ("id") ON DELETE CASCADE ON UPDATE RESTRICT
);

-- ----------------------------
-- Table structure for cms_user
-- ----------------------------
DROP TABLE IF EXISTS "cms_user";
CREATE TABLE "cms_user" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "email" text(255) NOT NULL,
  "password" text(255),
  "blocked" integer(4) NOT NULL,
  "created_at" text NOT NULL,
  "role" text(16) NOT NULL,
  "remember_me" blob,
  "settings" blob
);

-- ----------------------------
-- Table structure for sqlite_sequence
-- ----------------------------

-- ----------------------------
-- Table structure for test_company
-- ----------------------------
DROP TABLE IF EXISTS "test_company";
CREATE TABLE "test_company" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" text(255)
);

-- ----------------------------
-- Table structure for test_datatable_test
-- ----------------------------
DROP TABLE IF EXISTS "test_datatable_test";
CREATE TABLE "test_datatable_test" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "text" text(255),
  "file_id" integer(11),
  "checkbox" integer(1) NOT NULL,
  "date" text,
  "multicheckbox" text,
  "datatableselect" text,
  "textarea" text,
  "select" integer(11),
  "hidden" text(255),
  "autocomplete" text(255),
  "password" text(255),
  "wysiwyg" text
);

-- ----------------------------
-- Table structure for test_datatable_test_child
-- ----------------------------
DROP TABLE IF EXISTS "test_datatable_test_child";
CREATE TABLE "test_datatable_test_child" (
  "id" integer(11) NOT NULL,
  "name" text(255),
  "parent_id" integer(11),
  PRIMARY KEY ("id")
);

-- ----------------------------
-- Table structure for test_interest
-- ----------------------------
DROP TABLE IF EXISTS "test_interest";
CREATE TABLE "test_interest" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" text(255)
);

-- ----------------------------
-- Table structure for test_person
-- ----------------------------
DROP TABLE IF EXISTS "test_person";
CREATE TABLE "test_person" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" text(255),
  "company_id" integer(11),
  "image_id" integer(11),
  "display_order" integer(255),
  CONSTRAINT "test_person_ibfk_1" FOREIGN KEY ("company_id") REFERENCES "test_company" ("id") ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT "test_person_ibfk_2" FOREIGN KEY ("image_id") REFERENCES "cms_file" ("id") ON DELETE SET NULL ON UPDATE CASCADE
);

-- ----------------------------
-- Table structure for test_person_interest
-- ----------------------------
DROP TABLE IF EXISTS "test_person_interest";
CREATE TABLE "test_person_interest" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "person_id" integer(11),
  "interest_id" integer(11),
  "grade" integer(11),
  CONSTRAINT "test_person_interest_ibfk_1" FOREIGN KEY ("person_id") REFERENCES "test_person" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT "test_person_interest_ibfk_2" FOREIGN KEY ("interest_id") REFERENCES "test_interest" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

-- ----------------------------
-- Table structure for test_simple_object
-- ----------------------------
DROP TABLE IF EXISTS "test_simple_object";
CREATE TABLE "test_simple_object" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" text(255)
);

-- ----------------------------
-- Indexes structure for table cms_analytics_metric
-- ----------------------------
CREATE INDEX "main"."date"
ON "cms_analytics_metric" (
  "date" ASC
);
CREATE INDEX "main"."type"
ON "cms_analytics_metric" (
  "type" ASC
);
CREATE INDEX "main"."value"
ON "cms_analytics_metric" (
  "value" ASC
);
CREATE INDEX "main"."visits"
ON "cms_analytics_metric" (
  "visits" ASC
);

-- ----------------------------
-- Indexes structure for table cms_file
-- ----------------------------
CREATE INDEX "main"."cms_file_ibfk_1"
ON "cms_file" (
  "folder_id" ASC
);

-- ----------------------------
-- Indexes structure for table cms_file_permission
-- ----------------------------
CREATE INDEX "main"."file_id"
ON "cms_file_permission" (
  "file_id" ASC
);
CREATE INDEX "main"."role"
ON "cms_file_permission" (
  "role" ASC,
  "file_id" ASC
);
CREATE INDEX "main"."role_2"
ON "cms_file_permission" (
  "role" ASC
);
CREATE INDEX "main"."user_id"
ON "cms_file_permission" (
  "user_id" ASC,
  "file_id" ASC
);

-- ----------------------------
-- Auto increment value for cms_language
-- ----------------------------
UPDATE "main"."sqlite_sequence" SET seq = 2 WHERE name = 'cms_language';

-- ----------------------------
-- Indexes structure for table cms_language
-- ----------------------------
CREATE INDEX "main"."code"
ON "cms_language" (
  "code" ASC
);

-- ----------------------------
-- Auto increment value for cms_page
-- ----------------------------
UPDATE "main"."sqlite_sequence" SET seq = 6 WHERE name = 'cms_page';

-- ----------------------------
-- Indexes structure for table cms_page
-- ----------------------------
CREATE INDEX "main"."alias"
ON "cms_page" (
  "alias" ASC
);
CREATE INDEX "main"."display_order"
ON "cms_page" (
  "display_order" ASC,
  "parent_id" ASC
);
CREATE INDEX "main"."key"
ON "cms_page" (
  "key" ASC
);
CREATE INDEX "main"."parent_id"
ON "cms_page" (
  "parent_id" ASC
);
CREATE INDEX "main"."template_id"
ON "cms_page" (
  "template" ASC
);

-- ----------------------------
-- Indexes structure for table cms_page_content
-- ----------------------------
CREATE INDEX "main"."field"
ON "cms_page_content" (
  "field" ASC
);

-- ----------------------------
-- Auto increment value for cms_page_language
-- ----------------------------
UPDATE "main"."sqlite_sequence" SET seq = 8 WHERE name = 'cms_page_language';

-- ----------------------------
-- Indexes structure for table cms_page_language
-- ----------------------------
CREATE INDEX "main"."language_code"
ON "cms_page_language" (
  "language_code" ASC
);
CREATE INDEX "main"."language_code_2"
ON "cms_page_language" (
  "language_code" ASC
);
CREATE INDEX "main"."page_id"
ON "cms_page_language" (
  "page_id" ASC,
  "language_code" ASC
);

-- ----------------------------
-- Auto increment value for cms_translation_key
-- ----------------------------
UPDATE "main"."sqlite_sequence" SET seq = 1 WHERE name = 'cms_translation_key';

PRAGMA foreign_keys = true;
