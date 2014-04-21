<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:plugin_title'
);

/* Add Flexform */
$extensionName = t3lib_div::underscoredToUpperCamelCase($_EXTKEY);
$pluginSignature = strtolower($extensionName) . '_pi1';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/Flexforms/Flexform_plugin.xml');

/* Remove unused fields */
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] ='layout,recursive,select_key';

/* Add default Typoscript */
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Banner Management');

if (TYPO3_MODE == 'BE') {
	/* Add Plugin to wizzard */
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses'][$pluginSignature . '_wizicon'] =
		t3lib_extMgm::extPath($_EXTKEY) . 'Resources/Private/Php/class.' . $_EXTKEY . '_wizicon.php';

	/* register the cache in BE so it will be cleared with "clear all caches" */
	if (t3lib_utility_VersionNumber::convertVersionNumberToInteger(TYPO3_version) < '4006000') {
		t3lib_cache::initializeCachingFramework();
		$GLOBALS['typo3CacheFactory']->create(
			'sfbanners_cache',
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['sfbanners_cache']['frontend'],
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['sfbanners_cache']['backend'],
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['sfbanners_cache']['options']
		);
	}
}

t3lib_extMgm::addLLrefForTCAdescr('tx_sfbanners_domain_model_category', 'EXT:sf_banners/Resources/Private/Language/locallang_csh_tx_sfbanners_domain_model_category.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_sfbanners_domain_model_category');
$TCA['tx_sfbanners_domain_model_category'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:sf_banners/Resources/Private/Language/locallang_db.xml:tx_sfbanners_domain_model_category',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,parent,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Category.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_sfbanners_domain_model_category.gif'
	),
);

t3lib_extMgm::addLLrefForTCAdescr('tx_sfbanners_domain_model_banner', 'EXT:sf_banners/Resources/Private/Language/locallang_csh_tx_sfbanners_domain_model_banner.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_sfbanners_domain_model_banner');
$TCA['tx_sfbanners_domain_model_banner'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:sf_banners/Resources/Private/Language/locallang_db.xml:tx_sfbanners_domain_model_banner',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'type' => 'type',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,description,type,category,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Banner.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_sfbanners_domain_model_banner.gif'
	),
);
