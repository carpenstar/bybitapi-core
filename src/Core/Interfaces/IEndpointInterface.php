<?php
namespace Carpenstar\ByBitAPI\Core\Interfaces;

interface IEndpointInterface
{
    /**
     * Получение имени класса параметров запроса для данного эндпоинта
     * @return string
     */
    public function getRequestOptionsDTOClass(): string;
    /**
     * Установка режима вывода результата запроса
     * @param int $outputMode
     * @return $this
     */
    public function setOutputMode(int $outputMode): self;
    /**
     * Требуются-ли параметры для запроса
     * @return bool
     */
    public function bindRequestOptions(IRequestInterface $parameters): self;
    public function execute(): IResponseInterface;
}