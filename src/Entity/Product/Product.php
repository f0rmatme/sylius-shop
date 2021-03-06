<?php

declare(strict_types=1);

namespace App\Entity\Product;

use BitBag\SyliusProductBundlePlugin\Entity\ProductBundlesAwareInterface;
use BitBag\SyliusProductBundlePlugin\Entity\ProductBundlesAwareTrait;
use Doctrine\ORM\Mapping as ORM;
use MonsieurBiz\SyliusSearchPlugin\Model\Document\Result;
use MonsieurBiz\SyliusSearchPlugin\Model\Documentable\DocumentableInterface;
use MonsieurBiz\SyliusSearchPlugin\Model\Documentable\DocumentableProductTrait;
use Sylius\Component\Core\Model\Product as BaseProduct;
use Sylius\Component\Product\Model\ProductTranslationInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_product")
 */
class Product extends BaseProduct implements ProductBundlesAwareInterface, DocumentableInterface
{
    use ProductBundlesAwareTrait;
    use DocumentableProductTrait;
    use DocumentableProductTrait {
        convertToDocument as protected traitConvertToDocument;
    }

    /**
     * @ORM\OneToOne(targetEntity="BitBag\SyliusProductBundlePlugin\Entity\ProductBundleInterface", mappedBy="product", cascade={"persist"})
     *
     * @var ProductBundleInterface
     */
    protected $productBundle;

    protected function createTranslation(): ProductTranslationInterface
    {
        return new ProductTranslation();
    }

    public function convertToDocument(string $locale): Result {
        $document = $this->traitConvertToDocument($locale);

        $document->addAttribute('meta_keywords', 'Meta keywords', [$this->getTranslation($locale)->getMetaKeywords()], $locale, 15);

        return $document;
    }
}
