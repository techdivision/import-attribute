<?php

/**
 * TechDivision\Import\Attribute\Observers\CatalogAttributeUpdateObserverTest
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Observers;

use PHPUnit\Framework\TestCase;
use TechDivision\Import\Utils\EntityStatus;
use TechDivision\Import\Attribute\Utils\ColumnKeys;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Observers\EntityMergers\EntityMergerInterface;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;

/**
 * Test class for the catalog attribute update observer implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class CatalogAttributeUpdateObserverTest extends TestCase
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
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $mockBunchProcessor;

    /**
     * The mock entity merger instance.
     *
     * @var \TechDivision\Import\Observers\EntityMergers\EntityMergerInterface
     */
    protected $mockEntityMerger;

    /**
     * The raw entity instance.
     *
     * @var array
     */
    protected $rawEntity = array(
        MemberNames::FRONTEND_INPUT_RENDERER       => null,
        MemberNames::IS_GLOBAL                     => 0,
        MemberNames::IS_VISIBLE                    => 0,
        MemberNames::IS_SEARCHABLE                 => 0,
        MemberNames::IS_FILTERABLE                 => 0,
        MemberNames::IS_COMPARABLE                 => 0,
        MemberNames::IS_VISIBLE_ON_FRONT           => 0,
        MemberNames::IS_HTML_ALLOWED_ON_FRONT      => 0,
        MemberNames::IS_USED_FOR_PRICE_RULES       => 0,
        MemberNames::IS_FILTERABLE_IN_SEARCH       => 0,
        MemberNames::USED_IN_PRODUCT_LISTING       => 0,
        MemberNames::USED_FOR_SORT_BY              => 0,
        MemberNames::APPLY_TO                      => null,
        MemberNames::IS_VISIBLE_IN_ADVANCED_SEARCH => 0,
        MemberNames::POSITION                      => 0,
        MemberNames::IS_WYSIWYG_ENABLED            => 0,
        MemberNames::IS_USED_FOR_PROMO_RULES       => 0,
        MemberNames::IS_REQUIRED_IN_ADMIN_STORE    => 0,
        MemberNames::IS_USED_IN_GRID               => 0,
        MemberNames::IS_VISIBLE_IN_GRID            => 0,
        MemberNames::IS_FILTERABLE_IN_GRID         => 0,
        MemberNames::SEARCH_WEIGHT                 => 0,
        MemberNames::ADDITIONAL_DATA               => null
    );

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp(): void
    {

        // mock the attribute bunch processor
        $this->mockBunchProcessor = $this->getMockBuilder(AttributeBunchProcessorInterface::class)
                                         ->setMethods(get_class_methods(AttributeBunchProcessorInterface::class))
                                         ->getMock();

        $this->mockEntityMerger = $this->getMockBuilder(EntityMergerInterface::class)
                                       ->setMethods(get_class_methods(EntityMergerInterface::class))
                                       ->getMock();

        // the observer instance we want to test
        $this->observer = new CatalogAttributeUpdateObserver($this->mockBunchProcessor, $this->mockEntityMerger);
    }

    /**
     * Test's the handle() method successfull.
     *
     * @return void
     */
    public function testHandleWithoutAnyFields()
    {

        // create a dummy row for the headers
        $headers = array('attribute_code' => 0);

        // create a dummy CSV file row
        $row = array(0 => $attributeCode = 'test_attribute_code');

        // create a mock subject instance
        $mockSubject = $this->getMockBuilder('TechDivision\Import\Attribute\Observers\AttributeSubjectImpl')
                            ->setMethods(get_class_methods('TechDivision\Import\Attribute\Observers\AttributeSubjectImpl'))
                            ->getMock();
        $mockSubject->expects($this->once())
                    ->method('getRow')
                    ->willReturn($row);
        $mockSubject->expects($this->any())
                    ->method('hasHeader')
                    ->willReturnCallback(function ($key) use ($headers) {
                        return array_key_exists($key, $headers);
                    });
        $mockSubject->expects($this->any())
                    ->method('getHeader')
                    ->willReturnCallback(function ($key) use ($headers) {
                        if (array_key_exists($key, $headers)) {
                            return $headers[$key];
                        }
                        throw new \InvalidArgumentException(sprintf('Header %s is not available', $key));;
                    });
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
            MemberNames::ADDITIONAL_DATA               => json_encode(array()),
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
        // mock the method that loads the raw entity
        $this->mockBunchProcessor->expects($this->once())
                                 ->method('loadRawEntity')
                                 ->willReturn(
                                     array_merge(
                                         array(MemberNames::ATTRIBUTE_ID => $lastAttributeId),
                                         $this->rawEntity
                                     )
                                 );

        // mock the entity merger method
        $this->mockEntityMerger->expects($this->any())
             ->method('merge')
             ->willReturnCallback(function ($observer, $entity, $attr) use ($headers) {
                 foreach (array_keys($attr) as $key) {
                     if (isset($headers[$key])) {
                         continue;
                     }
                     unset($attr[$key]);
                 }
                 return $attr;
             });

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
        $headers = array(
            ColumnKeys::ATTRIBUTE_CODE                => 0,
            ColumnKeys::FRONTEND_INPUT_RENDERER       => 1,
            ColumnKeys::IS_GLOBAL                     => 2,
            ColumnKeys::IS_VISIBLE                    => 3,
            ColumnKeys::IS_SEARCHABLE                 => 4,
            ColumnKeys::IS_FILTERABLE                 => 5,
            ColumnKeys::IS_COMPARABLE                 => 6,
            ColumnKeys::IS_VISIBLE_ON_FRONT           => 7,
            ColumnKeys::IS_HTML_ALLOWED_ON_FRONT      => 8,
            ColumnKeys::IS_USED_FOR_PRICE_RULES       => 9,
            ColumnKeys::IS_FILTERABLE_IN_SEARCH       => 10,
            ColumnKeys::USED_IN_PRODUCT_LISTING       => 11,
            ColumnKeys::USED_FOR_SORT_BY              => 12,
            ColumnKeys::APPLY_TO                      => 13,
            ColumnKeys::IS_VISIBLE_IN_ADVANCED_SEARCH => 14,
            ColumnKeys::POSITION                      => 15,
            ColumnKeys::IS_WYSIWYG_ENABLED            => 16,
            ColumnKeys::IS_USED_FOR_PROMO_RULES       => 17,
            ColumnKeys::IS_REQUIRED_IN_ADMIN_STORE    => 18,
            ColumnKeys::IS_USED_IN_GRID               => 19,
            ColumnKeys::IS_VISIBLE_IN_GRID            => 20,
            ColumnKeys::IS_FILTERABLE_IN_GRID         => 21,
            ColumnKeys::SEARCH_WEIGHT                 => 22,
            ColumnKeys::ADDITIONAL_DATA               => 23
        );

        // create a dummy row for the headers
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
        $mockSubject->expects($this->once())
                    ->method('getMultipleValueDelimiter')
                    ->willReturn('|');
        $mockSubject->expects($this->once())
                    ->method('hasBeenProcessed')
                    ->with($attributeCode)
                    ->willReturn(false);
        $mockSubject->expects($this->exactly(5))
                    ->method('explode')
                    ->withConsecutive(
                        array($additionaData),
                        array('swatch_input_type=visual', '='),
                        array('update_product_preview_image=0', '='),
                        array('use_product_image_for_swatch=1', '='),
                        array(0, '|')
                    )
                    ->willReturnOnConsecutiveCalls(
                        array('swatch_input_type=visual', 'update_product_preview_image=0', 'use_product_image_for_swatch=1'),
                        array('swatch_input_type', 'visual'),
                        array('update_product_preview_image', '0'),
                        array('use_product_image_for_swatch', '1'),
                        array()
                    );
        $mockSubject->expects($this->once())
                    ->method('getLastAttributeId')
                    ->willReturn($lastAttributeId = 1001);
        $mockSubject->expects($this->any())
                    ->method('hasHeader')
                    ->willReturnCallback(function ($key) use ($headers) {
                        return array_key_exists($key, $headers);
                    });
        $mockSubject->expects($this->any())
                    ->method('getHeader')
                    ->willReturnCallback(function ($key) use ($headers) {
                        if (array_key_exists($key, $headers)) {
                            return $headers[$key];
                        }
                        throw new \InvalidArgumentException(sprintf('Header %s is not available', $key));;
                    });

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
            MemberNames::ADDITIONAL_DATA               => json_encode(array()),
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
            MemberNames::ADDITIONAL_DATA               => json_encode($explodedAdditionalData),
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
        // mock the method that loads the raw entity
        $this->mockBunchProcessor->expects($this->once())
                                 ->method('loadRawEntity')
                                 ->willReturn(
                                     array_merge(
                                         array(MemberNames::ATTRIBUTE_ID => $lastAttributeId),
                                         $this->rawEntity
                                     )
                                 );

        // mock the entity merger method
        $this->mockEntityMerger->expects($this->any())
             ->method('merge')
             ->willReturnCallback(function ($observer, $entity, $attr) use ($headers) {
                 foreach (array_keys($attr) as $key) {
                     if (isset($headers[$key])) {
                         continue;
                     }
                     unset($attr[$key]);
                 }
                 return $attr;
             });
        // invoke the handle method
        $this->assertSame($row, $this->observer->handle($mockSubject));
    }
}
