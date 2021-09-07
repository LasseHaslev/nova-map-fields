<?php

namespace Lassehaslev\NovaMapFields;

use Laravel\Nova\Fields\Field;
use Illuminate\Support\Str;

abstract class MapField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-map-fields';

    /**
     * Hide from index field.
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * Create a new field.
     * And set default properties.
     *
     * @param string      $name
     * @param string|null $attribute
     * @param mixed|null  $resolveCallback
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        $this->setHeight('400px');
        $this->setCenter(0, 0);
        $this->setZoom(13);

        $this->withMapMeta('type', $this->getMapFieldType());

        parent::__construct($name, $attribute = null, $resolveCallback);
    }

    /**
     * Set the height for the map.
     *
     * @param mixed $height
     *
     * @return $this
     */
    public function setHeight($height)
    {
        return $this->withMapMeta('height', $height);
    }

    /**
     * Set the zoom for the map.
     *
     * @param mixed $level
     *
     * @return $this
     */
    public function setZoom($level)
    {
        return $this->withMapMeta('zoom', $level);
    }

    /**
     * Set the position for the map.
     *
     * @param mixed $latitude
     * @param mixed $longitude
     *
     * @return $this
     */
    public function setCenter($latitude, $longitude)
    {
        return $this->withMapMeta('center', [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }

    /**
     * Add meta data for the map.
     *
     * @param mixed $key
     * @param mixed $value
     */
    protected function withMapMeta($key, $value)
    {
        $existingMapMeta = $this->meta['map'] ?? [];

        return $this->withMeta([
            'map' => array_merge($existingMapMeta, [
                $key => $value,
            ]),
        ]);
    }

    /**
     * Get component name.
     *
     * @return string
     */
    public function getMapFieldType()
    {
        return Str::kebab(class_basename($this));
    }
}
