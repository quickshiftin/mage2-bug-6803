<?php
namespace Bug\Demo\Console\Command;

use Symfony\Component\Console\Command\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\State;

use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Config as ModelConfig;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Product\Type;

use Magento\Eav\Model\Config;

/**
 * Demonstrate bug #6803
 * https://github.com/magento/magento2/issues/6803#issuecomment-289407131
 */
class ImportProducts extends Command
{
    private
        $_oState,
        $_oProductRepo,
        $_oProductFactory,
        $_oCategoryFactory,
        $_iProductCategoryId,
        $_sMediaPath;

    public function __construct(
        State $oState,
        DirectoryList $oDirectoryList,
        ModelConfig $oModelConfig,
        ProductRepositoryInterface $oProductRepo,
        ProductFactory $oProductFactory,
        CategoryFactory $oCategoryFactory,
        Config $oEavConfig
    ) {
        parent::__construct();

        $this->_sMediaPath       = $oDirectoryList->getPath('pub') . '/media';
        $this->_oState           = $oState;
        $this->_oProductRepo     = $oProductRepo;
        $this->_oProductFactory  = $oProductFactory;
        $this->_oCategoryFactory = $oCategoryFactory;

        $this->_iDefaultAttrSetId
            = $oModelConfig->getAttributeSetId('catalog_product', 'Default');

        $oEavConfig->clear();
    }

    /** 
     * {@inheritdoc}
     */
    protected function configure()
    {   
        $this
            ->setName('bug:demo:6803')
            ->setDescription('Demo Magento 2 bug #6803');

        parent::configure();
    } 

    protected function execute(InputInterface $oInput, OutputInterface $oOutput)
    {
        $this->_oState->setAreaCode('adminhtml');

        $oProduct = $this->_oProductFactory->create();

        $oProduct
            ->setTypeId(Type::TYPE_SIMPLE)
            ->setWebsiteIds([1])
            ->setVisibility(Visibility::VISIBILITY_BOTH)
            ->setStatus(Status::STATUS_ENABLED)
            ->setAttributeSetId($this->_iDefaultAttrSetId)
            ->setCategoryIds([$this->_getProductCategory('Default Category')])
            ->setName('Awesome product')
            ->setSku('a-w-e')
            ->setShortDescription('Awesome product')
            ->setFullDescription('Really awesome product')
            ->setPrice(10.00);

        // @note Magento wants the images in the media path for import...
        $sMediaPath = $this->_sMediaPath . '/fake-product.jpg';
        copy(
            __DIR__ . '/../../../data/images/fake-product.jpg',
            $sMediaPath);

        $oProduct->addImageToMediaGallery(
            $sMediaPath, ['image', 'small_image', 'thumbnail'], true, false);

        $this->_oProductRepo->save($oProduct);

        echo "\n";
    }

    private function _getProductCategory($sCatName)
    {   
        $this->_iProductCategoryId = $this->_oCategoryFactory
            ->create()
            ->getCollection()
            ->addAttributeToFilter('name', $sCatName)
            ->setPageSize(1)
            ->getFirstItem()
            ->getId();
    }
}
