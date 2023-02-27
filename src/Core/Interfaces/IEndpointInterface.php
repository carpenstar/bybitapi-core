<?php
namespace Carpenstar\ByBitAPI\Core\Interfaces;

interface IEndpointInterface
{
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
    public function setParameters(IRequestInterface $parameters): self;
    public function execute(): IResponseInterface;
}