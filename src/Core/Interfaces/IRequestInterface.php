<?php

namespace Carpenstar\ByBitAPI\Core\Interfaces;

interface IRequestInterface
{
    /**
     * Сборка указанных параметров из объекта для передачи в запрос
     * @return array
     */
    public function fetchArray(): array;
}