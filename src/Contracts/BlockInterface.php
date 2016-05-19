<?php

namespace KodiCMS\Pages\Contracts;

interface BlockInterface
{
    /**
     * @param type string|array
     *
     * @return bool
     */
    public function hasWidgets($name);

    /**
     * @param string $name
     * @param array  $params Дополнительные параметры доступные в виджете
     *
     * @return array
     */
    public function getWidgetsByBlock($name, array $params = []);

    /**
     * Метод служит для разметки выводимых блоков на странице.
     *
     * @param string $name
     * @param array  $params
     */
    public function run($name, array $params = []);

    /**
     * @param string $name
     */
    public function def($name);
}
