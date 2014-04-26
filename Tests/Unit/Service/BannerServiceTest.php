<?php
namespace DERHANSEN\SfBanners\Test\Unit\Service;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Torben Hansen <derhansen@gmail.com>, Skyfillers GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use DERHANSEN\SfBanners\Service\BannerService;

/**
 * Test cases for the banner service
 */
class BannerServiceTest extends UnitTestCase {
	/**
	 * @var \Tx_Phpunit_Framework
	 */
	protected $testingFramework;

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager
	 */
	protected $objectManager;

	/**
	 * @var \DERHANSEN\SfBanners\Domain\Repository\BannerRepository
	 */
	protected $bannerRepository;

	/**
	 * @var \DERHANSEN\SfBanners\Service\BannerService
	 */
	protected $bannerService;

	/**
	 * @var \DERHANSEN\SfBanners\Domain\Model\BannerDemand
	 */
	protected $demand;

	/**
	 * Set up
	 *
	 * @return void
	 */
	public function setUp() {
		$objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$this->objectManager = clone $objectManager;
		$this->bannerService = new BannerService();
		$this->testingFramework = new \Tx_Phpunit_Framework('tx_sfbanners', array('tx_phpunit'));
		$this->bannerRepository = $this->objectManager->get('DERHANSEN\\SfBanners\\Domain\\Repository\\BannerRepository');
		$this->demand = $this->objectManager->get('DERHANSEN\\SfBanners\Domain\Model\BannerDemand');
		$this->demand->setDisplayMode('all');
	}

	/**
	 * Tear down
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->bannerService);
		$this->testingFramework->cleanUp();
		unset($this->testingFramework, $this->bannerRepository);
	}

	/**
	 * Test if additional css returns an empty string if banner has no margin
	 *
	 * @test
	 * @return void
	 */
	public function getAdditionalCssReturnsEmptyStringIfBannerHasNoMarginsTest() {
		$result = $this->bannerService->getAdditionalCss(array());
		$this->assertEquals('', $result);
	}

	/**
	 * Test if additional css returns correct top margin
	 *
	 * @test
	 * @return void
	 */
	public function getAdditionalCssReturnsMarginTopIfBannerHasMarginTopTest() {
		$pid = 110;
		$bannerUid = $this->testingFramework->createRecord('tx_sfbanners_domain_model_banner', array('pid' => $pid,
			'margin_top' => 10));

		/* Get banner from Repository */
		$this->demand->setStartingPoint($pid);
		$banners = $this->bannerRepository->findDemanded($this->demand);

		$expected = '.banner-' . $bannerUid . ' { margin: 10px 0px 0px 0px; }' . chr(10) . chr(13);
		$result = $this->bannerService->getAdditionalCss($banners);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Test if additional css returns correct right margin
	 *
	 * @test
	 * @return void
	 */
	public function getAdditionalCssReturnsMarginRightIfBannerHasMarginRightTest() {
		$pid = 110;
		$bannerUid = $this->testingFramework->createRecord('tx_sfbanners_domain_model_banner', array('pid' => $pid,
			'margin_right' => 10));

		/* Get banner from Repository */
		$this->demand->setStartingPoint($pid);
		$banners = $this->bannerRepository->findDemanded($this->demand);

		$expected = '.banner-' . $bannerUid . ' { margin: 0px 10px 0px 0px; }' . chr(10) . chr(13);
		$result = $this->bannerService->getAdditionalCss($banners);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Test if additional css returns correct bottom margin
	 *
	 * @test
	 * @return void
	 */
	public function getAdditionalCssReturnsMarginBottomIfBannerHasMarginBottomTest() {
		$pid = 110;
		$bannerUid = $this->testingFramework->createRecord('tx_sfbanners_domain_model_banner', array('pid' => $pid,
			'margin_bottom' => 10));

		/* Get banner from Repository */
		$this->demand->setStartingPoint($pid);
		$banners = $this->bannerRepository->findDemanded($this->demand);

		$expected = '.banner-' . $bannerUid . ' { margin: 0px 0px 10px 0px; }' . chr(10) . chr(13);
		$result = $this->bannerService->getAdditionalCss($banners);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Test if additional css returns correct left margin
	 *
	 * @test
	 * @return void
	 */
	public function getAdditionalCssReturnsMarginLeftIfBannerHasMarginLeftTest() {
		$pid = 110;
		$bannerUid = $this->testingFramework->createRecord('tx_sfbanners_domain_model_banner', array('pid' => $pid,
			'margin_left' => 10));

		/* Get banner from Repository */
		$this->demand->setStartingPoint($pid);
		$banners = $this->bannerRepository->findDemanded($this->demand);

		$expected = '.banner-' . $bannerUid . ' { margin: 0px 0px 0px 10px; }' . chr(10) . chr(13);
		$result = $this->bannerService->getAdditionalCss($banners);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Test if additional css returns correct margins for multiple banners
	 *
	 * @test
	 * @return void
	 */
	public function getAdditionalCssReturnsCssForMultipleBannersTest() {
		$pid = 111;
		$bannerUid1 = $this->testingFramework->createRecord('tx_sfbanners_domain_model_banner', array('pid' => $pid,
			'margin_left' => 10, 'margin_right' => 10, 'sorting' => 1));
		$bannerUid2 = $this->testingFramework->createRecord('tx_sfbanners_domain_model_banner', array('pid' => $pid,
			'margin_top' => 10, 'margin_bottom' => 10, 'sorting' => 2));

		/* Get banner from Repository */
		$this->demand->setStartingPoint($pid);
		$banners = $this->bannerRepository->findDemanded($this->demand);

		$expected = '.banner-' . $bannerUid1 . ' { margin: 0px 10px 0px 10px; }' . chr(10) . chr(13);
		$expected .= '.banner-' . $bannerUid2 . ' { margin: 10px 0px 10px 0px; }' . chr(10) . chr(13);
		$result = $this->bannerService->getAdditionalCss($banners);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Test if no CSS file is returned if no banners given
	 *
	 * @test
	 * @return void
	 */
	public function getAdditionalCssFileReturnsEmptyStringIfNoBannersFoundTest() {
		$pid = 112;

		/* Get banner from Repository */
		$this->demand->setStartingPoint($pid);
		$banners = $this->bannerRepository->findDemanded($this->demand);

		$result = $this->bannerService->getAdditionalCssFile($banners);
		$this->assertEmpty($result);
	}

	/**
	 * Test if CSS file is returned
	 *
	 * @test
	 * @return void
	 */
	public function getAdditionalCssFileReturnsFilenameTest() {
		$pid = 113;
		$bannerUid1 = $this->testingFramework->createRecord('tx_sfbanners_domain_model_banner', array('pid' => $pid,
			'margin_left' => 10, 'margin_right' => 10, 'sorting' => 1));
		$bannerUid2 = $this->testingFramework->createRecord('tx_sfbanners_domain_model_banner', array('pid' => $pid,
			'margin_top' => 10, 'margin_bottom' => 10, 'sorting' => 2));

		/* Get banner from Repository */
		$this->demand->setStartingPoint($pid);
		$banners = $this->bannerRepository->findDemanded($this->demand);

		$expected = '/typo3temp\/stylesheet_.*?\.css/';
		$result = $this->bannerService->getAdditionalCssFile($banners);
		$this->assertRegExp($expected, $result);
	}

	/**
	 * Test if no CSS link is returned if no banners given
	 *
	 * @test
	 * @return void
	 */
	public function getAdditionalCssLinkReturnsEmptyStringIfNoBannersFoundTest() {
		$pid = 114;

		/* Get banner from Repository */
		$this->demand->setStartingPoint($pid);
		$banners = $this->bannerRepository->findDemanded($this->demand);

		$result = $this->bannerService->getAdditionalCssLink($banners);
		$this->assertEmpty($result);
	}

	/**
	 * Test if no CSS link is returned if no banners given
	 *
	 * @test
	 * @return void
	 */
	public function getAdditionalCssLinkReturnsLinkTest() {
		$pid = 115;
		$bannerUid1 = $this->testingFramework->createRecord('tx_sfbanners_domain_model_banner', array('pid' => $pid,
			'margin_left' => 10, 'margin_right' => 10, 'sorting' => 1));

		/* Get banner from Repository */
		$this->demand->setStartingPoint($pid);
		$banners = $this->bannerRepository->findDemanded($this->demand);

		$result = $this->bannerService->getAdditionalCssLink($banners);
		$this->assertContains('<link rel="stylesheet" type="text/css" href=', $result);
		$this->assertContains('typo3temp/stylesheet_', $result);
		$this->assertContains('.css', $result);
		$this->assertContains('media="all" />', $result);
	}
}