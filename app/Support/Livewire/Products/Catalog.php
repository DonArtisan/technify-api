<?php

namespace App\Support\Livewire\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;

trait Catalog
{
    public string $catalogName = '';

    public string $catalogType = '';

    public function addCatalog(string $type): void
    {
        if (! in_array($type, ['category', 'brand', 'color'])) {
            return;
        }

        $this->catalogType = $type;
    }

    public function resetCatalog(): void
    {
        $this->reset(['catalogType', 'catalogName']);
    }

    public function saveCategory(): void
    {
        Category::query()->create(['name' => $this->catalogName]);

        $this->resetCatalog();
    }

    public function saveBrand(): void
    {
        Brand::query()->create(['name' => $this->catalogName]);

        $this->resetCatalog();
    }

    public function saveColor(): void
    {
        Color::query()->create(['name' => $this->catalogName]);

        $this->resetCatalog();
    }
}
