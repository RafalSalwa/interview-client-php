<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Category;
use App\Tests\Helpers\ProtectedPropertyHelper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Category::class)]
class CategoryTest extends TestCase
{
    use ProtectedPropertyHelper;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = new Category();
    }

    public function testGettersAndSetters(): void
    {
        $this->category->setName('Test Category');
        $this->assertEquals('Test Category', $this->category->getName());

        $this->category->setDescription('Test Description');
        $this->assertEquals('Test Description', $this->category->getDescription());

        $this->category->setSlug('test-category');
        $this->assertEquals('test-category', $this->category->getSlug());

        // Test nullable properties
        $this->assertNull($this->category->getId());
    }

    public function testIdGetterAndSetter(): void
    {
        $this->setProtectedProperty($this->category, 'id', 5);
        $this->assertEquals(5, $this->category->getId());
    }
}
