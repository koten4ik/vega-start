<?php

namespace Modules\ShopCatalog\Http\Controllers;

use Modules\ShopCatalog\Commands\ViewCatalogCommand;
use Modules\ShopCatalog\Commands\ViewProductCommand;
use Modules\ZSupport\App\Controllers\VegaController;
use Modules\ZSupport\App\Services\MetaTags;

class CatalogController extends VegaController
{
    public function catalogPage(ViewCatalogCommand $viewCatalogCommand)
    {
        MetaTags::addFromPageModule('catalog');
        $data = $viewCatalogCommand->execute();

        return $this->render($this->getModuleName() . '::catalog', $data);
    }

    public function categoryPage($categorySlug, ViewCatalogCommand $viewCatalogCommand)
    {
        $data = $viewCatalogCommand->execute($categorySlug);

        return $this->render($this->getModuleName() . '::catalog', $data);
    }

    public function productPage($slug, ViewProductCommand $viewProductCommand)
    {
        $data = $viewProductCommand->execute($slug);

        return $this->render($this->getModuleName() . '::product', $data);
    }
}
