<?php

/**
 *
 * @category   ExtensionsStore
 * @package    ExtensionsStore_BannerUploader
 * @author     ExtensionsStore <Extensions Store <admin@extensions-store.com>>
 */

$installer = $this;
$installer->startSetup();

$installer->run("CREATE TABLE IF NOT EXISTS {$this->getTable('extensions_store_banneruploader_template')} (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `banner_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$installer->endSetup();