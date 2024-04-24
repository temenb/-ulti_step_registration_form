<?php

namespace Tests\Unit\View\Components;

use App\View\Components\GuestLayout;
use Illuminate\View\View;
use Tests\TestCase;

class GuestLayoutTest extends TestCase
{
    /**
     * Test rendering the guest layout component.
     *
     * @return void
     */
    public function testRenderingGuestLayoutComponent()
    {
        // Создаем экземпляр компонента
        $component = new GuestLayout();

        // Рендерим компонент
        $view = $component->render();

        // Проверяем, что возвращается экземпляр класса View
        $this->assertInstanceOf(View::class, $view);

        // Проверяем, что представление соответствует ожидаемому имени
        $this->assertEquals('layouts.guest', $view->getName());
    }
}
