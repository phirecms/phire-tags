--
-- Tags Module MySQL Database for Phire CMS 2.0
--

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `[{prefix}]tags` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `tag_title` (`title`),
  INDEX `tag_slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7001;

-- --------------------------------------------------------

--
-- Table structure for table "content_to_tags"
--

CREATE TABLE IF NOT EXISTS `[{prefix}]content_to_tags` (
  `content_id` int(16) NOT NULL,
  `tag_id` int(16) NOT NULL,
  INDEX `tag_content_id` (`content_id`),
  INDEX `tag_tag_id` (`tag_id`),
  UNIQUE (`content_id`, `tag_id`),
  CONSTRAINT `fk_tag_content_id` FOREIGN KEY (`content_id`) REFERENCES `[{prefix}]content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tag_tag_id` FOREIGN KEY (`tag_id`) REFERENCES `[{prefix}]tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 1;
