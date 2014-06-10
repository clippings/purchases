DROP TABLE IF EXISTS `Address`;
CREATE TABLE `Address` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `firstName` varchar(255),
  `lastName` varchar(255),
  `email` varchar(255),
  `phone` varchar(255),
  `postCode` varchar(255),
  `line1` varchar(255),
  `line2` varchar(255),
  `countryId` int(11) UNSIGNED NULL,
  `cityId` int(11) UNSIGNED NULL,
  `deletedAt` TIMESTAMP NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Basket`;
CREATE TABLE `Basket` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `omnipay` TEXT NULL,
  `billingId` int(11) UNSIGNED NULL,
  `currency` varchar(3) NULL,
  `status` TINYINT(1) UNSIGNED NULL,
  `deletedAt` TIMESTAMP NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `BasketItem`;
CREATE TABLE `BasketItem` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `class` varchar(255),
  `basketId` int(11) UNSIGNED NULL,
  `purchaseId` int(11) UNSIGNED NULL,
  `refundId` int(11) UNSIGNED NULL,
  `refId` int(11) UNSIGNED NULL,
  `quantity` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `price` int(11) NULL,
  `isFrozen` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `deletedAt` TIMESTAMP NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Product`;
CREATE TABLE `Product` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `storeId` int(11) UNSIGNED NULL,
  `name` varchar(255),
  `price` int(11) NULL,
  `currency` varchar(3) NULL,
  `deletedAt` TIMESTAMP NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Purchase`;
CREATE TABLE `Purchase` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` TINYINT(1) UNSIGNED NULL,
  `basketId` int(11) UNSIGNED NULL,
  `storeId` int(11) UNSIGNED NULL,
  `deletedAt` TIMESTAMP NULL,
  `createdAt` TIMESTAMP NULL,
  `updatedAt` TIMESTAMP NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Refund`;
CREATE TABLE `Refund` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `state` TINYINT(1) UNSIGNED NULL,
  `basketId` int(11) UNSIGNED NULL,
  `deletedAt` TIMESTAMP NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Store`;
CREATE TABLE `Store` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255),
  `deletedAt` TIMESTAMP NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Location`;
CREATE TABLE `Location` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NULL,
  `class` varchar(255) NULL,
  `parentId` int(11) UNSIGNED NULL,
  `path` varchar(255) NULL,
  `code` varchar(255) NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `Location` (`id`, `name`, `class`, `code`, `parentId`, `path`)
VALUES
  (1, 'Bulgaria', 'Harp\\Locations\\Model\\Country', 'BG', 0, ''),
  (2, 'Sofia', 'Harp\\Locations\\Model\\City', NULL, 1, '1');

INSERT INTO `Address`
(`id`, `firstName`, `lastName`, `email`, `phone`, `postCode`, `line1`, `line2`, `cityId`, `countryId`)
VALUES
  (1, 'John', 'Doe', 'john@example.com', '123123', '1000', 'Moskovska', '132', 2, 1);

INSERT INTO `Basket`
(`id`, `omnipay`, `currency`, `status`, `billingId`, `deletedAt`)
VALUES
  (1, NULL, 'GBP', 1, 1, NULL),
  (2, NULL, 'GBP', 2, 0, NULL);

INSERT INTO `BasketItem`
(`id`, `class`, `basketId`, `purchaseId`, `refundId`, `refId`, `price`, `isFrozen`, `deletedAt`)
VALUES
  (1, 'CL\\Purchases\\Model\\ProductItem', 1, 1, NULL, 1, 1000, 1, NULL),
  (2, 'CL\\Purchases\\Model\\ProductItem', 1, 1, NULL, 2, 2000, 1, NULL),
  (3, 'CL\\Purchases\\Model\\ProductItem', 1, 1, NULL, 3, 3000, 1, NULL),
  (4, 'CL\\Purchases\\Model\\ProductItem', 1, 2, NULL, 4, 4000, 1, NULL),
  (5, 'CL\\Purchases\\Model\\RefundItem',  1, 2, 1,    4, -4000, 1, NULL),
  (6, 'CL\\Purchases\\Model\\ProductItem', 1, 2, NULL, 5, 5000, 1, '2014-02-03 00:00:00');

INSERT INTO `Purchase`
(`id`, `status`, `basketId`, `storeId`, `createdAt`, `updatedAt`, `deletedAt`)
VALUES
  (1, 2, 1, 1, '2014-01-03 10:00:00', '2014-03-03 12:00:00', NULL),
  (2, 2, 1, 1, '2014-01-03 10:00:00', '2014-06-03 12:00:00', NULL);

INSERT INTO `Product`
(`id`, `name`, `storeId`, `price`, `currency`, `deletedAt`)
VALUES
  (1, 'Product 1', 1, 1000, 'GBP', NULL),
  (2, 'Product 1', 1, 2000, 'GBP', NULL),
  (3, 'Product 1', 1, 3000, 'GBP', NULL),
  (4, 'Product 1', 1, 4000, 'GBP', NULL),
  (5, 'Product 1', 1, 5000, 'GBP', NULL);

INSERT INTO `Refund`
(`id`, `state`, `basketId`, `deletedAt`)
VALUES
(1, 1, 1, NULL);

INSERT INTO `Store`
(`id`, `name`, `deletedAt`)
VALUES
(1, 'Store 1', NULL),
(2, 'Store 2', NULL);
