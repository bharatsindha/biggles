

# Make the transport defalut value
ALTER TABLE `lanes` CHANGE `transport` `transport` tinyint NOT NULL DEFAULT '1' AFTER `capacity`;
