<?php
namespace Carpenstar\ByBitAPI\Core\Interfaces;

interface IEndpointInterface
{
    /**
     * Установка режима вывода результата запроса
     * @param int $resultMode
     * @return $this
     */
    public function setResultMode(int $resultMode): self;
    /**
     * Требуются-ли параметры для запроса
     * @return bool
     */
    public function bindRequestParameters(IParametersInterface $options): self;
    public function execute(): IResponseInterface;
}