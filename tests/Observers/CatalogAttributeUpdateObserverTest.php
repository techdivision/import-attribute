<?php

/**
 * TechDivision\Import\Attribute\Observers\CatalogAttributeUpdateObserverTest
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Observers;

use TechDivision\Import\Attribute\Utils\ColumnKeys;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Utils\EntityStatus;

/**
 * Test class for the catalog attribute update observer implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class CatalogAttributeUpdateObserverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The observer we want to test.
     *
     * @var \TechDivision\Import\Attribute\Observers\CatalogAttributeUpdateObserver
     */
    protected $observer;

    /**
     * The mock bunch processor instance.
     *
     * @var \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface
     */
    protected $mockBunchProcessor;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {

        // mock the attribute bunch processor
        $this->mockBunchProcessor = $this->getMockBuilder('TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface')
                                         ->setMethods(get_class_methods('TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface'))
                                         ->getMock();

        // the observer instance we want to test
        $this->observer = new CatalogAttributeUpdateObserver($this->mockBunchProcessor);
    }

    /**
     * Test's the handle() method successfull.
     *
     * @return void
     */
    public function testHandleWithoutAnyFields()
    {

        // create a dummy CSV file row
        $row = array(
            0  => $attributeCode = 'test_attribute_code'
        );

        // create a mock subject instance
        $mockSubject = $this->getMockBuilder('TechDivision\Import\Attribute\Observers\AttributeSubjectImpl')
                            ->setMethods(get_class_methods('TechDivision\Import\Attribute\Observers\AttributeSubjectImpl'))
                            ->getMock();
        $mockSubject->expects($this->once())
                    ->method('getRow')
                    ->willReturn($row);
        $mockSubject->expects($this->exactly(24))
                    ->method('hasHeader')
                    ->withConsecutive(
                        array(ColumnKeys::ATTRIBUTE_CODE),
                        array(ColumnKeys::FRONTEND_INPUT_RENDERER),
                        array(ColumnKeys::IS_GLOBAL),
                        array(ColumnKeys::IS_VISIBLE),
                        array(ColumnKeys::IS_SEARCHABLE),
                        array(ColumnKeys::IS_FILTERABLE),
                        array(ColumnKeys::IS_COMPARABLE),
                        array(ColumnKeys::IS_VISIBLE_ON_FRONT),
                        array(ColumnKeys::IS_HTML_ALLOWED_ON_FRONT),
                        array(ColumnKeys::IS_USED_FOR_PRICE_RULES),
                        array(ColumnKeys::IS_FILTERABLE_IN_SEARCH),
                        array(ColumnKeys::USED_IN_PRODUCT_LISTING),
                        array(ColumnKeys::USED_FOR_SORT_BY),
                        array(ColumnKeys::APPLY_TO),
                        array(ColumnKeys::IS_VISIBLE_IN_ADVANCED_SEARCH),
                        array(ColumnKeys::POSITION),
                        array(ColumnKeys::IS_WYSIWYG_ENABLED),
                        array(ColumnKeys::IS_USED_FOR_PROMO_RULES),
                        array(ColumnKeys::IS_REQUIRED_IN_ADMIN_STORE),
                        array(ColumnKeys::IS_USED_IN_GRID),
                        array(ColumnKeys::IS_VISIBLE_IN_GRID),
                        array(ColumnKeys::IS_FILTERABLE_IN_GRID),
                        array(ColumnKeys::SEARCH_WEIGHT),
                        array(ColumnKeys::ADDITIONAL_DATA)
                     )
                    ->willReturnOnConsecutiveCalls(
                        true,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false,
                        false
                    );
        $mockSubject->expects($this->once())
                    ->method('getHeader')
                    ->with(ColumnKeys::ATTRIBUTE_CODE)
                    ->willReturn(0);
        $mockSubject->expects($this->once())
                    ->method('hasBeenProcessed')
                    ->with($attributeCode)
                    ->willReturn(false);
        $mockSubject->expects($this->once())
                    ->method('getLastAttributeId')
                    ->willReturn($lastAttributeId = 1001);

        // initialize the existing entity
        $existingEntity = array(
            MemberNames::ATTRIBUTE_ID                  => $lastAttributeId,
            MemberNames::FRONTEND_INPUT_RENDERER       => 'Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Category',
            MemberNames::IS_GLOBAL                     => 0,
            MemberNames::IS_VISIBLE                    => 0,
            MemberNames::IS_SEARCHABLE                 => 1,
            MemberNames::IS_FILTERABLE                 => 1,
            MemberNames::IS_COMPARABLE                 => 1,
            MemberNames::IS_VISIBLE_ON_FRONT           => 1,
            MemberNames::IS_HTML_ALLOWED_ON_FRONT      => 1,
            MemberNames::IS_USED_FOR_PRICE_RULES       => 1,
            MemberNames::IS_FILTERABLE_IN_SEARCH       => 1,
            MemberNames::USED_IN_PRODUCT_LISTING       => 1,
            MemberNames::USED_FOR_SORT_BY              => 1,
            MemberNames::APPLY_TO                      => 'simple,virtual',
            MemberNames::IS_VISIBLE_IN_ADVANCED_SEARCH => 1,
            MemberNames::POSITION                      => 1,
            MemberNames::IS_WYSIWYG_ENABLED            => 1,
            MemberNames::IS_USED_FOR_PROMO_RULES       => 1,
            MemberNames::IS_REQUIRED_IN_ADMIN_STORE    => 1,
            MemberNames::IS_USED_IN_GRID               => 1,
            MemberNames::IS_VISIBLE_IN_GRID            => 1,
            MemberNames::IS_FILTERABLE_IN_GRID         => 1,
            MemberNames::SEARCH_WEIGHT                 => 0,
            MemberNames::ADDITIONAL_DATA               => serialize(array()),
            EntityStatus::MEMBER_NAME                  => EntityStatus::STATUS_UPDATE
        );

        // as NO values has been passed, the expected entity EQUALS the existing entity
        $expectedEntity = $existingEntity;

        // mock the method that loads the existing entity
        $this->mockBunchProcessor->expects($this->once())
                                 ->method('loadCatalogAttribute')
                                 ->with($lastAttributeId)
                                 ->willReturn($existingEntity);
        // mock the method that persists the entity
        $this->mockBunchProcessor->expects($this->once())
                                 ->method('persistCatalogAttribute')
                                 ->with($expectedEntity)
                                 ->willReturn(null);

        // invoke the handle method
        $this->assertSame($row, $this->observer->handle($mockSubject));
    }

    /**
     * Test's the handle() method successfull.
     *
     * @return void
     */
    public function testHandleWithAllFields()
    {

        // create a dummy CSV file row
        $row = array(
            0  => $attributeCode = 'test_attribute_code',
            1  => 'Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Product',
            2  => 1,
            3  => 1,
            4  => 0,
            5  => 0,
            6  => 0,
            7  => 0,
            8  => 0,
            9  => 0,
            10 => 0,
            11 => 0,
            12 => 0,
            13 => 'virtual',
            14 => 0,
            15 => 0,
            16 => 0,
            17 => 0,
            18 => 0,
            19 => 0,
            20 => 0,
            21 => 0,
            22 => 1,
            23 => $additionaData = 'swatch_input_type=visual,update_product_preview_image=0,use_product_image_for_swatch=1'
        );

        // prepare the exploded additional data
        $explodedAdditionalData = array();
        foreach (explode(',', $additionaData) as $data) {
            list ($key, $value) = explode('=', $data);
            $explodedAdditionalData[$key] = $value;
        }

        // create a mock subject instance
        $mockSubject = $this->getMockBuilder('TechDivision\Import\Attribute\Observers\AttributeSubjectImpl')
                            ->setMethods(get_class_methods('TechDivision\Import\Attribute\Observers\AttributeSubjectImpl'))
                            ->getMock();
        $mockSubject->expects($this->once())
                    ->method('getRow')
                    ->willReturn($row);
        $mockSubject->expects($this->exactly(69))
                    ->method('hasHeader')
                    ->willReturn(true);
        $mockSubject->expects($this->exactly(46))
                    ->method('getHeader')
                    ->withConsecutive(
                        array(ColumnKeys::ATTRIBUTE_CODE),
                        array(ColumnKeys::FRONTEND_INPUT_RENDERER),
                        array(ColumnKeys::FRONTEND_INPUT_RENDERER),
                        array(ColumnKeys::IS_GLOBAL),
                        array(ColumnKeys::IS_GLOBAL),
                        array(ColumnKeys::IS_VISIBLE),
                        array(ColumnKeys::IS_VISIBLE),
                        array(ColumnKeys::IS_SEARCHABLE),
                        array(ColumnKeys::IS_SEARCHABLE),
                        array(ColumnKeys::IS_FILTERABLE),
                        array(ColumnKeys::IS_FILTERABLE),
                        array(ColumnKeys::IS_COMPARABLE),
                        array(ColumnKeys::IS_COMPARABLE),
                        array(ColumnKeys::IS_VISIBLE_ON_FRONT),
                        array(ColumnKeys::IS_VISIBLE_ON_FRONT),
                        array(ColumnKeys::IS_HTML_ALLOWED_ON_FRONT),
                        array(ColumnKeys::IS_HTML_ALLOWED_ON_FRONT),
                        array(ColumnKeys::IS_USED_FOR_PRICE_RULES),
                        array(ColumnKeys::IS_USED_FOR_PRICE_RULES),
                        array(ColumnKeys::IS_FILTERABLE_IN_SEARCH),
                        array(ColumnKeys::IS_FILTERABLE_IN_SEARCH),
                        array(ColumnKeys::USED_IN_PRODUCT_LISTING),
                        array(ColumnKeys::USED_IN_PRODUCT_LISTING),
                        array(ColumnKeys::USED_FOR_SORT_BY),
                        array(ColumnKeys::USED_FOR_SORT_BY),
                        array(ColumnKeys::APPLY_TO),
                        array(ColumnKeys::APPLY_TO),
                        array(ColumnKeys::IS_VISIBLE_IN_ADVANCED_SEARCH),
                        array(ColumnKeys::IS_VISIBLE_IN_ADVANCED_SEARCH),
                        array(ColumnKeys::POSITION),
                        array(ColumnKeys::POSITION),
                        array(ColumnKeys::IS_WYSIWYG_ENABLED),
                        array(ColumnKeys::IS_WYSIWYG_ENABLED),
                        array(ColumnKeys::IS_USED_FOR_PROMO_RULES),
                        array(ColumnKeys::IS_USED_FOR_PROMO_RULES),
                        array(ColumnKeys::IS_REQUIRED_IN_ADMIN_STORE),
                        array(ColumnKeys::IS_REQUIRED_IN_ADMIN_STORE),
                        array(ColumnKeys::IS_USED_IN_GRID),
                        array(ColumnKeys::IS_USED_IN_GRID),
                        array(ColumnKeys::IS_VISIBLE_IN_GRID),
                        array(ColumnKeys::IS_VISIBLE_IN_GRID),
                        array(ColumnKeys::IS_FILTERABLE_IN_GRID),
                        array(ColumnKeys::IS_FILTERABLE_IN_GRID),
                        array(ColumnKeys::SEARCH_WEIGHT),
                        array(ColumnKeys::SEARCH_WEIGHT),
                        array(ColumnKeys::ADDITIONAL_DATA),
                        array(ColumnKeys::ADDITIONAL_DATA)
                     )
                    ->willReturnOnConsecutiveCalls(
                        0,
                        1,
                        1,
                        2,
                        2,
                        3,
                        3,
                        4,
                        4,
                        5,
                        5,
                        6,
                        6,
                        7,
                        7,
                        8,
                        8,
                        9,
                        9,
                        10,
                        10,
                        11,
                        11,
                        12,
                        12,
                        13,
                        13,
                        14,
                        14,
                        15,
                        15,
                        16,
                        16,
                        17,
                        17,
                        18,
                        18,
                        19,
                        19,
                        20,
                        20,
                        21,
                        21,
                        22,
                        22,
                        23,
                        23
                     );
        $mockSubject->expects($this->once())
                    ->method('hasBeenProcessed')
                    ->with($attributeCode)
                    ->willReturn(false);
        $mockSubject->expects($this->exactly(4))
                    ->method('explode')
                    ->withConsecutive(
                        array($additionaData),
                        array('swatch_input_type=visual', '='),
                        array('update_product_preview_image=0', '='),
                        array('use_product_image_for_swatch=1', '=')
                    )
                    ->willReturnOnConsecutiveCalls(
                        array('swatch_input_type=visual', 'update_product_preview_image=0', 'use_product_image_for_swatch=1'),
                        array('swatch_input_type', 'visual'),
                        array('update_product_preview_image', '0'),
                        array('use_product_image_for_swatch', '1')
                    );
        $mockSubject->expects($this->once())
                    ->method('getLastAttributeId')
                    ->willReturn($lastAttributeId = 1001);

        // initialize the existing entity
        $existingEntity = array(
            MemberNames::ATTRIBUTE_ID                  => $lastAttributeId,
            MemberNames::FRONTEND_INPUT_RENDERER       => 'Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Category',
            MemberNames::IS_GLOBAL                     => 0,
            MemberNames::IS_VISIBLE                    => 0,
            MemberNames::IS_SEARCHABLE                 => 1,
            MemberNames::IS_FILTERABLE                 => 1,
            MemberNames::IS_COMPARABLE                 => 1,
            MemberNames::IS_VISIBLE_ON_FRONT           => 1,
            MemberNames::IS_HTML_ALLOWED_ON_FRONT      => 1,
            MemberNames::IS_USED_FOR_PRICE_RULES       => 1,
            MemberNames::IS_FILTERABLE_IN_SEARCH       => 1,
            MemberNames::USED_IN_PRODUCT_LISTING       => 1,
            MemberNames::USED_FOR_SORT_BY              => 1,
            MemberNames::APPLY_TO                      => 'simple,virtual',
            MemberNames::IS_VISIBLE_IN_ADVANCED_SEARCH => 1,
            MemberNames::POSITION                      => 1,
            MemberNames::IS_WYSIWYG_ENABLED            => 1,
            MemberNames::IS_USED_FOR_PROMO_RULES       => 1,
            MemberNames::IS_REQUIRED_IN_ADMIN_STORE    => 1,
            MemberNames::IS_USED_IN_GRID               => 1,
            MemberNames::IS_VISIBLE_IN_GRID            => 1,
            MemberNames::IS_FILTERABLE_IN_GRID         => 1,
            MemberNames::SEARCH_WEIGHT                 => 0,
            MemberNames::ADDITIONAL_DATA               => serialize(array()),
            EntityStatus::MEMBER_NAME                  => EntityStatus::STATUS_UPDATE
        );

        // initialize the expected entity
        $expectedEntity = array(
            MemberNames::ATTRIBUTE_ID                  => $lastAttributeId,
            MemberNames::FRONTEND_INPUT_RENDERER       => 'Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Product',
            MemberNames::IS_GLOBAL                     => 1,
            MemberNames::IS_VISIBLE                    => 1,
            MemberNames::IS_SEARCHABLE                 => 0,
            MemberNames::IS_FILTERABLE                 => 0,
            MemberNames::IS_COMPARABLE                 => 0,
            MemberNames::IS_VISIBLE_ON_FRONT           => 0,
            MemberNames::IS_HTML_ALLOWED_ON_FRONT      => 0,
            MemberNames::IS_USED_FOR_PRICE_RULES       => 0,
            MemberNames::IS_FILTERABLE_IN_SEARCH       => 0,
            MemberNames::USED_IN_PRODUCT_LISTING       => 0,
            MemberNames::USED_FOR_SORT_BY              => 0,
            MemberNames::APPLY_TO                      => 'virtual',
            MemberNames::IS_VISIBLE_IN_ADVANCED_SEARCH => 0,
            MemberNames::POSITION                      => 0,
            MemberNames::IS_WYSIWYG_ENABLED            => 0,
            MemberNames::IS_USED_FOR_PROMO_RULES       => 0,
            MemberNames::IS_REQUIRED_IN_ADMIN_STORE    => 0,
            MemberNames::IS_USED_IN_GRID               => 0,
            MemberNames::IS_VISIBLE_IN_GRID            => 0,
            MemberNames::IS_FILTERABLE_IN_GRID         => 0,
            MemberNames::SEARCH_WEIGHT                 => 1,
            MemberNames::ADDITIONAL_DATA               => serialize($explodedAdditionalData),
            EntityStatus::MEMBER_NAME                  => EntityStatus::STATUS_UPDATE
        );

        // mock the method that loads the existing entity
        $this->mockBunchProcessor->expects($this->once())
                                 ->method('loadCatalogAttribute')
                                 ->with($lastAttributeId)
                                 ->willReturn($existingEntity);
        // mock the method that persists the entity
        $this->mockBunchProcessor->expects($this->once())
                                 ->method('persistCatalogAttribute')
                                 ->with($expectedEntity)
                                 ->willReturn(null);

        // invoke the handle method
        $this->assertSame($row, $this->observer->handle($mockSubject));
    }
}
