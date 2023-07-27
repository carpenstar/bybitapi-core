<?php

namespace Carpenstar\ByBitAPI\Core\Interfaces;

interface IParametersInterface
{
    /**
     * Сборка указанных параметров из объекта для передачи в запрос
     * @return array
     */
    public function array(): array;
}